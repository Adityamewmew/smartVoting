<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VotingSessionUsecase extends Usecase
{
    public function __construct() {}

    /**
     * Generate a new voting session token.
     */
    public function generateSession(int $electionId, int $operatorId): array
    {
        try {
            $tenantId = $this->tenantId();
            // First check if the election is active
            $election = DB::table(DatabaseConst::ELECTIONS())
                ->where('institution_id', $tenantId)
                ->where('id', $electionId)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->first();

            if (! $election) {
                return Response::buildErrorService('Event pemilihan tidak ditemukan atau belum aktif.');
            }

            if (! empty($election->start_time) && now()->lt(Carbon::parse($election->start_time))) {
                return Response::buildErrorService('Waktu pemungutan suara belum dimulai.');
            }

            if (! empty($election->end_time) && now()->gt(Carbon::parse($election->end_time))) {
                return Response::buildErrorService('Waktu pemungutan suara telah berakhir.');
            }

            $token = Str::uuid()->toString();

            DB::table(DatabaseConst::VOTING_SESSIONS())->insert([
                'institution_id' => $tenantId,
                'election_id' => $electionId,
                'operator_id' => $operatorId,
                'session_token' => $token,
                'status' => 'open',
                'created_at' => now(),
            ]);

            return Response::buildSuccess(
                ['token' => $token],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Verify if a token is valid and still open.
     */
    public function verifySession(string $token): array
    {
        try {
            $query = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('session_token', $token);

            if (app()->bound('current_tenant')) {
                $query->where('institution_id', $this->tenantId());
            }

            $session = $query->first();

            if (! $session) {
                return Response::buildErrorService('Sesi tidak valid atau tidak ditemukan.');
            }

            if ($session->status === 'expired') {
                return Response::buildErrorService('Sesi ini sudah kedaluwarsa.');
            }

            if ($session->status === 'submitted') {
                return Response::buildErrorService('Sesi ini sudah digunakan untuk memilih.');
            }

            // Valid open session
            return Response::buildSuccess(data: collect($session)->toArray());
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Submit a vote and mark the session as submitted.
     */
    public function submitVote(string $token, int $candidateId): array
    {
        DB::beginTransaction();
        try {
            // Re-verify and lock the session row to prevent race conditions
            $session = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('session_token', $token)
                ->lockForUpdate()
                ->first();

            if (! $session) {
                throw new Exception('Sesi tidak ditemukan.');
            }

            if ($session->status !== 'open') {
                throw new Exception('Sesi tidak valid untuk digunakan.');
            }

            // Verify election status & time window
            $election = DB::table(DatabaseConst::ELECTIONS())
                ->where('id', $session->election_id)
                ->where('institution_id', $session->institution_id)
                ->whereNull('deleted_at')
                ->first();

            if (! $election || $election->status !== 'active') {
                throw new Exception('Event pemilihan tidak aktif atau tidak ditemukan.');
            }

            if (! empty($election->start_time) && now()->lt(Carbon::parse($election->start_time))) {
                throw new Exception('Waktu pemungutan suara belum dimulai.');
            }

            if (! empty($election->end_time) && now()->gt(Carbon::parse($election->end_time))) {
                throw new Exception('Waktu pemungutan suara telah berakhir.');
            }

            // Verify candidate
            $candidate = DB::table(DatabaseConst::CANDIDATES())
                ->where('id', $candidateId)
                ->where('election_id', $session->election_id)
                ->where('institution_id', $session->institution_id)
                ->whereNull('deleted_at')
                ->first();

            if (! $candidate) {
                throw new Exception('Kandidat tidak valid.');
            }

            // Insert the vote
            DB::table(DatabaseConst::VOTES())->insert([
                'institution_id' => $session->institution_id,
                'election_id' => $session->election_id,
                'candidate_id' => $candidateId,
                'created_at' => now(),
            ]);

            // Mark session as submitted
            DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('id', $session->id)
                ->update([
                    'status' => 'submitted',
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(
                ['message' => 'Suara berhasil disimpan!'],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Expire a session (timeout).
     */
    public function expireSession(string $token): array
    {
        DB::beginTransaction();
        try {
            $session = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('session_token', $token)
                ->lockForUpdate()
                ->first();

            if (! $session || $session->status !== 'open') {
                // Ignore if already submitted or expired
                DB::rollBack();

                return Response::buildSuccess();
            }

            DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('id', $session->id)
                ->update([
                    'status' => 'expired',
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(
                ['message' => 'Sesi berhasil diakhiri.'],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            DB::rollback();
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
