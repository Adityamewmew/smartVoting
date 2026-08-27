<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentUsecase extends Usecase
{
    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::PAYMENTS().' as p')
                ->join(DatabaseConst::INSTITUTION().' as i', 'p.institution_id', '=', 'i.id')
                ->select([
                    'p.*',
                    'i.name as institution_name',
                ])
                ->whereNull('p.deleted_at')
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where(function ($q) use ($keywords) {
                        $q->where('p.invoice_number', 'like', '%'.$keywords.'%')
                            ->orWhere('p.package_name', 'like', '%'.$keywords.'%')
                            ->orWhere('i.name', 'like', '%'.$keywords.'%');
                    });
                })
                ->when(isset($filterData['status']) && $filterData['status'] !== 'all' && $filterData['status'] !== '', function ($query) use ($filterData) {
                    return $query->where('p.status', $filterData['status']);
                })
                ->when(isset($filterData['institution_id']) && $filterData['institution_id'] !== 'all' && $filterData['institution_id'] !== '', function ($query) use ($filterData) {
                    return $query->where('p.institution_id', $filterData['institution_id']);
                })
                ->orderBy('p.created_at', 'desc');

            if (! empty($filterData['no_pagination'])) {
                $data = $query->get();
            } else {
                $data = $query->paginate(20);
                if (! empty($filterData)) {
                    $data->appends($filterData);
                }
            }

            return Response::buildSuccess(['list' => $data], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByID(int $id): array
    {
        try {
            $data = DB::table(DatabaseConst::PAYMENTS().' as p')
                ->join(DatabaseConst::INSTITUTION().' as i', 'p.institution_id', '=', 'i.id')
                ->select([
                    'p.*',
                    'i.name as institution_name',
                ])
                ->where('p.id', $id)
                ->whereNull('p.deleted_at')
                ->first();

            if (! $data) {
                return Response::buildErrorNotFound('Data pembayaran tidak ditemukan.');
            }

            return Response::buildSuccess(data: collect($data)->toArray());
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'institution_id' => 'required|integer',
            'package_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4));

            // Optional: Generate Mayar Payment Link if API Key is configured
            $mayarData = $this->createMayarInvoice([
                'invoice_number' => $invoiceNumber,
                'package_name' => $data['package_name'],
                'amount' => (int) $data['amount'],
                'customer_name' => $data['customer_name'] ?? 'Pelanggan SmartVoting',
                'customer_email' => $data['customer_email'] ?? 'admin@smartvoting.id',
                'customer_phone' => $data['customer_phone'] ?? '08123456789',
            ]);

            $paymentId = DB::table(DatabaseConst::PAYMENTS())->insertGetId([
                'institution_id' => $data['institution_id'],
                'invoice_number' => $invoiceNumber,
                'package_name' => $data['package_name'],
                'amount' => $data['amount'],
                'payment_method' => 'mayar',
                'mayar_payment_id' => $mayarData['id'] ?? null,
                'payment_url' => $mayarData['link'] ?? null,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(['id' => $paymentId, 'invoice_number' => $invoiceNumber]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'package_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,failed,expired',
            'notes' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $payload = [
                'package_name' => $data['package_name'],
                'amount' => $data['amount'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'updated_by' => Auth::user()?->id,
                'updated_at' => now(),
            ];

            if ($data['status'] === 'paid') {
                $payload['paid_at'] = now();
            }

            DB::table(DatabaseConst::PAYMENTS())->where('id', $id)->update($payload);
            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_UPDATED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function confirmPayment(int $id): array
    {
        DB::beginTransaction();
        try {
            $payment = DB::table(DatabaseConst::PAYMENTS())->where('id', $id)->first();

            if (! $payment) {
                DB::rollback();

                return Response::buildErrorNotFound('Data pembayaran tidak ditemukan.');
            }

            DB::table(DatabaseConst::PAYMENTS())
                ->where('id', $id)
                ->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'updated_by' => Auth::user()?->id,
                    'updated_at' => now(),
                ]);

            // Automatically activate the associated institution and its administrator
            DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $payment->institution_id)
                ->update([
                    'status' => 'active',
                    'updated_at' => now(),
                ]);

            DB::table(DatabaseConst::USER())
                ->where('institution_id', $payment->institution_id)
                ->update([
                    'is_active' => 1,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(message: 'Pembayaran berhasil dikonfirmasi lunas dan institusi telah diaktifkan.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseConst::PAYMENTS())->where('id', $id)->update([
                'deleted_by' => Auth::user()?->id,
                'deleted_at' => now(),
            ]);

            if (! $delete) {
                DB::rollback();

                return Response::buildErrorNotFound('Data pembayaran tidak ditemukan.');
            }

            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_DELETED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Helper to prepare Mayar Invoice creation (Ready for production Mayar integration).
     */
    public function createMayarInvoice(array $params): array
    {
        $apiKey = config('services.mayar.api_key', env('MAYAR_API_KEY'));
        $baseUrl = config('services.mayar.base_url', env('MAYAR_BASE_URL', 'https://api.mayar.id/hl/v1'));

        if (empty($apiKey)) {
            // Simulated Mayar link when API key is not configured in local/dev
            return [
                'id' => 'mayar_'.Str::random(12),
                'link' => 'https://mayar.link/'.strtolower($params['invoice_number']),
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->post($baseUrl.'/payment/create', [
                'name' => $params['customer_name'] ?? 'Pelanggan SmartVoting',
                'email' => $params['customer_email'] ?? 'admin@smartvoting.id',
                'mobile' => $params['customer_phone'] ?? '08123456789',
                'amount' => $params['amount'],
                'description' => 'Pembayaran '.$params['package_name'].' ('.$params['invoice_number'].')',
                'redirectUrl' => route('admin.payments.index'),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'id' => $data['data']['id'] ?? null,
                    'link' => $data['data']['link'] ?? null,
                ];
            }

            Log::warning('Mayar API Error: '.$response->body());
        } catch (Exception $e) {
            Log::error('Mayar Integration Exception: '.$e->getMessage());
        }

        return [
            'id' => null,
            'link' => null,
        ];
    }
}
