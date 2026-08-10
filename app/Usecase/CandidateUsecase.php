<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidateUsecase extends Usecase
{
    public function __construct()
    {
        $this->className = CandidateUsecase::class;
    }

    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::CANDIDATES().' as c')
                ->leftJoin(DatabaseConst::ELECTIONS().' as e', 'c.election_id', '=', 'e.id')
                ->select('c.*', 'e.name as election_name')
                ->whereNull('c.deleted_at')
                ->when(! empty($filterData['election_id']), function ($query) use ($filterData) {
                    return $query->where('c.election_id', $filterData['election_id']);
                })
                ->when(! empty($filterData['keywords']), function ($query) use ($filterData) {
                    return $query->where(function ($q) use ($filterData) {
                        $q->where('c.chairman_name', 'like', '%'.$filterData['keywords'].'%')
                            ->orWhere('c.vice_chairman_name', 'like', '%'.$filterData['keywords'].'%');
                    });
                })
                ->orderBy('c.order_number', 'asc');

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
            $data = DB::table(DatabaseConst::CANDIDATES())
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->first();

            if (! $data) {
                return Response::buildErrorNotFound();
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
            'election_id' => 'required|integer',
            'order_number' => 'required|integer|min:1',
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($data->hasFile('photo')) {
                $photoPath = $data->file('photo')->store('candidates', 'public');
            }

            DB::table(DatabaseConst::CANDIDATES())->insert([
                'election_id' => $data->input('election_id'),
                'order_number' => $data->input('order_number'),
                'chairman_name' => $data->input('chairman_name'),
                'vice_chairman_name' => $data->input('vice_chairman_name'),
                'vision' => $data->input('vision'),
                'mission' => $data->input('mission'),
                'photo_path' => $photoPath,
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
            'election_id' => 'required|integer',
            'order_number' => 'required|integer|min:1',
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $candidate = DB::table(DatabaseConst::CANDIDATES())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (! $candidate) {
                DB::rollback();

                return Response::buildErrorNotFound();
            }

            $payload = [
                'election_id' => $data->input('election_id'),
                'order_number' => $data->input('order_number'),
                'chairman_name' => $data->input('chairman_name'),
                'vice_chairman_name' => $data->input('vice_chairman_name'),
                'vision' => $data->input('vision'),
                'mission' => $data->input('mission'),
                'updated_by' => Auth::user()?->id,
                'updated_at' => now(),
            ];

            if ($data->hasFile('photo')) {
                // Delete old photo if exists
                if ($candidate->photo_path && Storage::disk('public')->exists($candidate->photo_path)) {
                    Storage::disk('public')->delete($candidate->photo_path);
                }

                $payload['photo_path'] = $data->file('photo')->store('candidates', 'public');
            }

            DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->update($payload);
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
            $candidate = DB::table(DatabaseConst::CANDIDATES())
                ->where('id', $id)
                ->first();

            $delete = DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->update([
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
