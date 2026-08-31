<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Constants\UserConst;
use App\Http\Presenter\Response;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserUsecase extends Usecase
{
    public function __construct() {}

    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::USER())
                ->where('institution_id', $this->tenantId())
                ->whereNull('deleted_at')
                ->whereIn('access_type', array_keys(UserConst::getAppAccessTypes()))
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where(function ($q) use ($keywords) {
                        $q->where('name', 'like', '%'.$keywords.'%')
                            ->orWhere('email', 'like', '%'.$keywords.'%');
                    });
                })
                ->when($filterData['access_type'] ?? false, function ($query, $accessType) {
                    if ($accessType !== 'all') {
                        return $query->where('access_type', $accessType);
                    }
                })
                ->orderBy('created_at', 'desc');

            $data = empty($filterData['no_pagination'])
                ? $query->paginate(20)
                : $query->get();

            if (! empty($filterData) && method_exists($data, 'appends')) {
                $data->appends($filterData);
            }

            return Response::buildSuccess(
                [
                    'list' => $data,
                ],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByID(int $id): array
    {
        try {
            $data = DB::table(DatabaseConst::USER())
                ->where('institution_id', $this->tenantId())
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->first();

            return Response::buildSuccess(
                data: collect($data)->toArray()
            );
        } catch (Exception $e) {
            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'access_type' => 'required|in:'.implode(',', array_keys(UserConst::getAppAccessTypes())),
            'password' => ['nullable', 'string', 'confirmed', Password::min(6)],
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $password = ! empty($data['password'])
                ? Hash::make($data['password'])
                : UserConst::DEFAULT_PASSWORD;

            DB::table(DatabaseConst::USER())
                ->insert([
                    'institution_id' => $this->tenantId(),
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'access_type' => $data['access_type'],
                    'password' => $password,
                    'is_active' => 1,
                    'created_by' => Auth::user()?->id,
                    'created_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccessCreated();
        } catch (Exception $e) {
            DB::rollback();

            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array|Exception
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users,email,'.$id,
            'access_type' => 'required|in:'.implode(',', array_keys(UserConst::getAppAccessTypes())),
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $validator->validate();

        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
            'access_type' => $data['access_type'],
            'updated_by' => Auth::user()?->id,
            'updated_at' => now(),
        ];

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        DB::beginTransaction();

        try {
            DB::table(DatabaseConst::USER())
                ->where('institution_id', $this->tenantId())
                ->where('id', $id)
                ->update($update);

            DB::commit();

            return Response::buildSuccess(
                message: ResponseConst::SUCCESS_MESSAGE_UPDATED
            );
        } catch (Exception $e) {
            DB::rollback();

            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        DB::beginTransaction();

        try {
            $delete = DB::table(DatabaseConst::USER())
                ->where('institution_id', $this->tenantId())
                ->where('id', $id)
                ->update([
                    'deleted_by' => Auth::user()?->id,
                    'deleted_at' => now(),
                ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('FAILED DELETE DATA');
            }

            DB::commit();

            return Response::buildSuccess(
                message: ResponseConst::SUCCESS_MESSAGE_DELETED
            );
        } catch (Exception $e) {
            DB::rollback();

            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function changePassword(array $data): array
    {
        $userID = Auth::user()?->id;

        $validator = Validator::make($data, [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(6)],
        ]);

        $customAttributes = [
            'current_password' => 'Password Lama',
            'password' => 'Password Baru',
        ];
        $validator->setAttributeNames($customAttributes);
        $validator->validate();

        DB::beginTransaction();

        try {
            $locked = DB::table(DatabaseConst::USER())
                ->where('id', $userID)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->first(['id']);

            if (! $locked) {
                DB::rollback();

                throw new Exception('FAILED LOCKED DATA');
            }

            DB::table(DatabaseConst::USER())
                ->where('id', $userID)
                ->update([
                    'password' => Hash::make($data['password']),
                    'updated_by' => $userID,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(
                message: ResponseConst::SUCCESS_MESSAGE_UPDATED
            );
        } catch (Exception $e) {
            DB::rollback();

            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function resetPassword(int $id): array
    {
        $defaultPassword = UserConst::DEFAULT_PASSWORD;

        DB::beginTransaction();

        try {
            DB::table(DatabaseConst::USER())
                ->where('id', $id)
                ->update([
                    'password' => Hash::make($defaultPassword),
                    'updated_by' => Auth::user()?->id,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(
                message: 'Password berhasil direset ke '.UserConst::DEFAULT_PASSWORD
            );
        } catch (Exception $e) {
            DB::rollback();

            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function handleGoogleUser(object $googleUser): array
    {
        $googleId = (string) $googleUser->getId();
        $email = strtolower(trim((string) $googleUser->getEmail()));
        $rawVerified = $googleUser->user['email_verified'] ?? null;
        $isEmailVerified = filter_var($rawVerified, FILTER_VALIDATE_BOOLEAN);

        if (empty($googleId) || empty($email) || ! $isEmailVerified) {
            return Response::buildError(403, 'Autentikasi Google gagal: Akun tidak valid atau email belum diverifikasi.');
        }

        try {
            $user = DB::transaction(function () use ($googleUser, $googleId, $email) {
                // 1. Strict lookup by google_id
                $existingUser = DB::table(DatabaseConst::USER())
                    ->where('google_id', $googleId)
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->first();

                if ($existingUser) {
                    return $existingUser;
                }

                // 2. Lookup by verified email for account linking
                $emailUser = DB::table(DatabaseConst::USER())
                    ->where('email', $email)
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->first();

                if ($emailUser) {
                    DB::table(DatabaseConst::USER())
                        ->where('id', $emailUser->id)
                        ->update([
                            'google_id' => $googleId,
                            'avatar' => $emailUser->avatar ?? $googleUser->getAvatar(),
                            'updated_at' => now(),
                        ]);

                    return DB::table(DatabaseConst::USER())->where('id', $emailUser->id)->first();
                }

                // 3. Auto-Provisioning: create institution + admin user
                $orgName = ($googleUser->getName() ?: 'User').' Organization';

                $institutionId = DB::table(DatabaseConst::INSTITUTION())->insertGetId([
                    'name' => $orgName,
                    'type' => 'organization',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $userId = DB::table(DatabaseConst::USER())->insertGetId([
                    'institution_id' => $institutionId,
                    'name' => $googleUser->getName() ?: 'Admin',
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'google_id' => $googleId,
                    'avatar' => $googleUser->getAvatar(),
                    'access_type' => UserConst::SUPERADMIN,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return DB::table(DatabaseConst::USER())->where('id', $userId)->first();
            });
        } catch (QueryException $e) {
            Log::warning('Google OAuth collision, retrying lookup', ['email' => $email, 'error' => $e->getMessage()]);
            $user = DB::table(DatabaseConst::USER())
                ->where('google_id', $googleId)
                ->orWhere('email', $email)
                ->whereNull('deleted_at')
                ->first();

            if (! $user) {
                return Response::buildErrorService('Terjadi kendala saat memproses login. Silakan coba kembali.');
            }
        } catch (Exception $e) {
            Log::error('Google OAuth failed', ['error' => $e->getMessage(), 'method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }

        // Status checks
        if (isset($user->is_active) && ! $user->is_active) {
            return Response::buildError(403, 'Akun Anda sedang dinonaktifkan.');
        }

        if (! empty($user->institution_id)) {
            $institution = DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $user->institution_id)
                ->whereNull('deleted_at')
                ->first();

            if (! $institution || $institution->status !== 'active') {
                return Response::buildError(403, 'Institusi atau organisasi Anda sedang tidak aktif atau ditangguhkan.');
            }
        }

        $userModel = User::find($user->id);
        Auth::login($userModel, remember: true);

        return Response::buildSuccess(['user' => $userModel]);
    }
}
