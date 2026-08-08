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
    public string $className = 'CandidateUsecase';

    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::CANDIDATES().' as c')
                ->leftJoin(DatabaseConst::ELECTIONS().' as e', 'c.election_id', '=', 'e.id')
                ->select('c.*', 'e.title as election_title')
                ->whereNull('c.deleted_at')
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where(function ($q) use ($keywords) {
                        $q->where('c.nama_ketua', 'like', '%'.$keywords.'%')
                            ->orWhere('c.nama_wakil', 'like', '%'.$keywords.'%');
                    });
                })
                ->orderBy('c.created_at', 'desc');

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
            'nomor_urut' => 'required|integer',
            'nama_ketua' => 'required|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $fotoPath = null;
            if ($data->hasFile('foto')) {
                $fotoPath = $data->file('foto')->store('candidates', 'public');
            }

            DB::table(DatabaseConst::CANDIDATES())->insert([
                'election_id' => $data['election_id'],
                'nomor_urut' => $data['nomor_urut'],
                'nama_ketua' => $data['nama_ketua'],
                'nama_wakil' => $data['nama_wakil'],
                'visi' => $data['visi'],
                'misi' => $data['misi'],
                'foto_path' => $fotoPath,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(message: ResponseConst::SUCCESS_MESSAGE_CREATED);
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
            'nomor_urut' => 'required|integer',
            'nama_ketua' => 'required|string|max:255',
            'nama_wakil' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return Response::buildError(ResponseConst::HTTP_BAD_REQUEST, $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $payload = $data->only(['election_id', 'nomor_urut', 'nama_ketua', 'nama_wakil', 'visi', 'misi']);
            $payload['updated_by'] = Auth::user()?->id;
            $payload['updated_at'] = now();

            if ($data->hasFile('foto')) {
                $candidate = DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->first();
                if ($candidate && $candidate->foto_path) {
                    Storage::disk('public')->delete($candidate->foto_path);
                }
                $payload['foto_path'] = $data->file('foto')->store('candidates', 'public');
            }

            DB::table(DatabaseConst::CANDIDATES())->where('id', $id)->update($payload);
            DB::commit();

            return Response::buildSuccess(message: ResponseConst::SUCCESS_MESSAGE_UPDATED);
        } catch (Exception $e) {
            DB::rollback();
            if (isset($payload['foto_path'])) {
                Storage::disk('public')->delete($payload['foto_path']);
            }
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
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
