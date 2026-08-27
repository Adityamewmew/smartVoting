<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Constants\UserConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstitutionUsecase extends Usecase
{
    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::INSTITUTION().' as i')
                ->whereNull('i.deleted_at')
                ->select([
                    'i.*',
                    DB::raw('(SELECT COUNT(*) FROM elections WHERE elections.institution_id = i.id AND elections.deleted_at IS NULL) as elections_count'),
                    DB::raw('(SELECT COUNT(*) FROM users WHERE users.institution_id = i.id AND users.deleted_at IS NULL) as users_count'),
                ])
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where('i.name', 'like', '%'.$keywords.'%');
                })
                ->when(isset($filterData['status']) && $filterData['status'] !== 'all' && ! empty($filterData['status']), function ($query) use ($filterData) {
                    return $query->where('i.status', $filterData['status']);
                })
                ->when(isset($filterData['type']) && $filterData['type'] !== 'all' && ! empty($filterData['type']), function ($query) use ($filterData) {
                    return $query->where('i.type', $filterData['type']);
                })
                ->orderBy('i.created_at', 'desc');

            if (! empty($filterData['no_pagination'])) {
                $data = $query->get();
            } else {
                $data = $query->paginate(15);
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
            $data = DB::table(DatabaseConst::INSTITUTION().' as i')
                ->whereNull('i.deleted_at')
                ->where('i.id', $id)
                ->select([
                    'i.*',
                    DB::raw('(SELECT COUNT(*) FROM elections WHERE elections.institution_id = i.id AND elections.deleted_at IS NULL) as elections_count'),
                    DB::raw('(SELECT COUNT(*) FROM users WHERE users.institution_id = i.id AND users.deleted_at IS NULL) as users_count'),
                ])
                ->first();

            if (! $data) {
                return Response::buildErrorNotFound('Data institusi tidak ditemukan.');
            }

            $users = DB::table(DatabaseConst::USER())
                ->where('institution_id', $id)
                ->whereNull('deleted_at')
                ->get();

            $result = collect($data)->toArray();
            $result['users'] = $users;

            return Response::buildSuccess(data: $result);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|in:school,campus,organization',
            'status' => 'nullable|string|in:active,suspended,pending',
            'admin_name' => 'required|string|max:255',
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'admin_password' => 'required|string|min:6',
        ], [
            'admin_email.unique' => 'Email admin ini sudah digunakan oleh akun lain.',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $institutionId = DB::table(DatabaseConst::INSTITUTION())->insertGetId([
                'name' => $data['name'],
                'type' => $data['type'] ?? 'school',
                'status' => $data['status'] ?? 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table(DatabaseConst::USER())->insert([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'access_type' => UserConst::SUPERADMIN,
                'institution_id' => $institutionId,
                'is_active' => 1,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(['id' => $institutionId]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|in:school,campus,organization',
            'status' => 'required|string|in:active,suspended,pending,inactive',
        ]);

        $validator->validate();

        $current = DB::table(DatabaseConst::INSTITUTION())
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (! $current) {
            return Response::buildErrorNotFound('Data institusi tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $data['name'],
                'type' => $data['type'] ?? $current->type,
                'status' => $data['status'] ?? $current->status,
                'updated_at' => now(),
            ];

            DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->update($payload);

            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_UPDATED);
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
            $delete = DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => now(),
                ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('Gagal menghapus data institusi.');
            }

            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_DELETED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function toggleStatus(int $id): array
    {
        DB::beginTransaction();
        try {
            $institution = DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (! $institution) {
                DB::rollback();

                return Response::buildErrorNotFound('Data institusi tidak ditemukan.');
            }

            $newStatus = $institution->status === 'active' ? 'suspended' : 'active';

            DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->update([
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);

            DB::commit();

            $statusLabel = $newStatus === 'active' ? 'diaktifkan' : 'ditangguhkan (suspended)';

            return Response::buildSuccess(message: "Status institusi berhasil {$statusLabel}.");
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
