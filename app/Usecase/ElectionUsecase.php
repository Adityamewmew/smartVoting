<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ElectionUsecase extends Usecase
{
    public function syncExpiredElections(): void
    {
        try {
            DB::table(DatabaseConst::ELECTIONS())
                ->where('status', 'active')
                ->where('end_time', '<=', now())
                ->whereNull('deleted_at')
                ->update([
                    'status' => 'inactive',
                    'updated_at' => now(),
                ]);
        } catch (Exception $e) {
            Log::warning('Failed syncing expired elections: '.$e->getMessage());
        }
    }

    public function getAll(array $filterData = []): array
    {
        try {
            $this->syncExpiredElections();

            $query = DB::table(DatabaseConst::ELECTIONS())
                ->whereNull('deleted_at')
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where('name', 'like', '%'.$keywords.'%');
                })
                ->orderBy('created_at', 'desc');

            if (! empty($filterData['no_pagination'])) {
                $data = $query->get();
            } else {
                $data = $query->paginate(20);
                if (! empty($filterData)) {
                    $data->appends($filterData);
                }
            }

            return Response::buildSuccess(
                ['list' => $data],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByID(int $id): array
    {
        try {
            $this->syncExpiredElections();

            $data = DB::table(DatabaseConst::ELECTIONS())
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->first();

            return Response::buildSuccess(data: collect($data)->toArray());
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
            'slug' => 'nullable|string|max:255|alpha_dash|not_in:login,admin,api,pemilihan',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:draft,active',
        ], [
            'slug.alpha_dash' => 'Slug hanya boleh berisi huruf, angka, strip (-), dan underscore (_). Tidak boleh ada spasi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validator->validate();

        $dateStr = Carbon::parse($data['date'])->format('Y-m-d');
        $startDateTime = Carbon::parse($dateStr.' '.$data['start_time'])->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($dateStr.' '.$data['end_time'])->format('Y-m-d H:i:s');

        if ($data['status'] === 'active' && Carbon::parse($endDateTime)->isPast()) {
            throw ValidationException::withMessages([
                'status' => 'Pemilihan tidak dapat diaktifkan karena jadwal waktu selesai telah berakhir.',
            ]);
        }

        DB::beginTransaction();
        try {
            $baseSlug = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (DB::table(DatabaseConst::ELECTIONS())->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }

            DB::table(DatabaseConst::ELECTIONS())->insert([
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'date' => $dateStr,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'status' => $data['status'],
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated();
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|string|max:255|alpha_dash|not_in:login,admin,api,pemilihan',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:draft,active',
        ], [
            'slug.alpha_dash' => 'Slug hanya boleh berisi huruf, angka, strip (-), dan underscore (_). Tidak boleh ada spasi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validator->validate();

        $dateStr = Carbon::parse($data['date'])->format('Y-m-d');
        $startDateTime = Carbon::parse($dateStr.' '.$data['start_time'])->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($dateStr.' '.$data['end_time'])->format('Y-m-d H:i:s');

        if ($data['status'] === 'active' && Carbon::parse($endDateTime)->isPast()) {
            throw ValidationException::withMessages([
                'status' => 'Pemilihan tidak dapat diaktifkan karena jadwal waktu selesai telah berakhir.',
            ]);
        }

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'date' => $dateStr,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'status' => $data['status'],
            ];

            $baseSlug = Str::slug($data['slug']);
            $slug = $baseSlug;
            $counter = 1;
            while (DB::table(DatabaseConst::ELECTIONS())->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
            $payload['slug'] = $slug;

            $payload['updated_by'] = Auth::user()?->id;
            $payload['updated_at'] = now();

            DB::table(DatabaseConst::ELECTIONS())->where('id', $id)->update($payload);
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
            $delete = DB::table(DatabaseConst::ELECTIONS())->where('id', $id)->update([
                'deleted_by' => Auth::user()?->id,
                'deleted_at' => now(),
            ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('FAILED DELETE DATA');
            }

            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_DELETED);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
