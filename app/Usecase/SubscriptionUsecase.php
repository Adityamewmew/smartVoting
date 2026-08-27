<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionUsecase extends Usecase
{
    public function subscribe(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'institution_name' => 'required|string|max:150',
            'type' => 'nullable|string|in:school,campus,organization',
            'admin_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|string|min:8|confirmed',
            'package' => 'nullable|string|in:starter,pro,enterprise',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $packageKey = $data['package'] ?? 'pro';
            $packageMap = [
                'starter' => ['name' => 'Paket Uji Coba / Trial', 'amount' => 0],
                'pro' => ['name' => 'Paket Sekolah & OSIS', 'amount' => 1500000],
                'enterprise' => ['name' => 'Paket Kampus & Organisasi', 'amount' => 3500000],
            ];

            $selectedPackage = $packageMap[$packageKey] ?? $packageMap['pro'];
            $isFreeTrial = ($selectedPackage['amount'] === 0);

            // 1. Insert Institution
            $institutionId = DB::table(DatabaseConst::INSTITUTION())->insertGetId([
                'name' => $data['institution_name'],
                'type' => $data['type'] ?? 'school',
                'status' => $isFreeTrial ? 'active' : 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Insert School Admin User
            $userId = DB::table(DatabaseConst::USER())->insertGetId([
                'institution_id' => $institutionId,
                'name' => $data['admin_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'access_type' => UserConst::SUPERADMIN, // School Admin (access_type = 1)
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Generate Payment Invoice
            $invoiceNumber = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4));

            $paymentUsecase = app(PaymentUsecase::class);
            $mayarData = $paymentUsecase->createMayarInvoice([
                'invoice_number' => $invoiceNumber,
                'package_name' => $selectedPackage['name'],
                'amount' => $selectedPackage['amount'],
                'customer_name' => $data['admin_name'],
                'customer_email' => $data['email'],
                'customer_phone' => $data['phone'] ?? '081234567890',
            ]);

            $paymentId = DB::table(DatabaseConst::PAYMENTS())->insertGetId([
                'institution_id' => $institutionId,
                'invoice_number' => $invoiceNumber,
                'package_name' => $selectedPackage['name'],
                'amount' => $selectedPackage['amount'],
                'payment_method' => 'mayar',
                'mayar_payment_id' => $mayarData['id'] ?? null,
                'payment_url' => $mayarData['link'] ?? null,
                'status' => $isFreeTrial ? 'paid' : 'pending',
                'paid_at' => $isFreeTrial ? now() : null,
                'notes' => 'Pendaftaran paket '.$selectedPackage['name'].' oleh '.$data['admin_name'].' ('.($data['phone'] ?? '-').')',
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated([
                'institution_id' => $institutionId,
                'user_id' => $userId,
                'payment_id' => $paymentId,
                'invoice_number' => $invoiceNumber,
                'is_free' => $isFreeTrial,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getInvoice(string $invoiceNumber): array
    {
        try {
            $data = DB::table(DatabaseConst::PAYMENTS().' as p')
                ->join(DatabaseConst::INSTITUTION().' as i', 'p.institution_id', '=', 'i.id')
                ->select([
                    'p.*',
                    'i.name as institution_name',
                    'i.status as institution_status',
                ])
                ->where('p.invoice_number', $invoiceNumber)
                ->whereNull('p.deleted_at')
                ->first();

            if (! $data) {
                return Response::buildErrorNotFound('Tagihan pembayaran tidak ditemukan.');
            }

            return Response::buildSuccess(data: collect($data)->toArray());
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
