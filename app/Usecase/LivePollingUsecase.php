<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LivePollingUsecase extends Usecase
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

    public function getActiveElectionsWithStats(): array
    {
        try {
            $this->syncExpiredElections();

            $elections = DB::table(DatabaseConst::ELECTIONS())
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->get();

            $data = collect($elections)->map(function ($election) {
                $election->total_votes = DB::table(DatabaseConst::VOTES())
                    ->where('election_id', $election->id)
                    ->count();

                $election->active_sessions = DB::table(DatabaseConst::VOTING_SESSIONS())
                    ->where('election_id', $election->id)
                    ->where('status', 'open')
                    ->count();

                return $election;
            })->values();

            return Response::buildSuccess(['list' => $data], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getDashboardElections(?string $keywords = null): array
    {
        try {
            $this->syncExpiredElections();

            $elections = DB::table(DatabaseConst::ELECTIONS())
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->when($keywords, function ($query, $keywords) {
                    return $query->where('name', 'like', '%'.$keywords.'%');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return Response::buildSuccess(['list' => $elections], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getCandidatesByElection(int $electionId): array
    {
        try {
            $candidates = DB::table(DatabaseConst::CANDIDATES())
                ->where('election_id', $electionId)
                ->whereNull('deleted_at')
                ->select('id', 'order_number', 'chairman_name', 'vice_chairman_name', 'photo_path')
                ->orderBy('order_number', 'asc')
                ->get();

            return Response::buildSuccess(['candidates' => $candidates], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getLiveResults(int $electionId): array
    {
        try {
            // Get total votes
            $totalVotes = DB::table(DatabaseConst::VOTES())
                ->where('election_id', $electionId)
                ->count();

            $activeSessions = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('election_id', $electionId)
                ->where('status', 'open')
                ->count();

            $totalSessions = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('election_id', $electionId)
                ->count();

            // Get vote count per candidate
            $candidates = DB::table(DatabaseConst::CANDIDATES().' as c')
                ->leftJoin(DatabaseConst::VOTES().' as v', 'c.id', '=', 'v.candidate_id')
                ->where('c.election_id', $electionId)
                ->whereNull('c.deleted_at')
                ->select(
                    'c.id',
                    'c.order_number',
                    'c.chairman_name',
                    'c.vice_chairman_name',
                    'c.photo_path',
                    'c.vice_chairman_photo_path',
                    DB::raw('COUNT(v.id) as vote_count')
                )
                ->groupBy('c.id', 'c.order_number', 'c.chairman_name', 'c.vice_chairman_name', 'c.photo_path', 'c.vice_chairman_photo_path')
                ->orderBy('c.order_number', 'asc')
                ->get();

            return Response::buildSuccess([
                'total_votes' => $totalVotes,
                'active_sessions' => $activeSessions,
                'total_sessions' => $totalSessions,
                'candidates' => $candidates,
            ], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getRecentSessions(int $limit = 20, ?int $electionId = null): array
    {
        try {
            $query = DB::table(DatabaseConst::VOTING_SESSIONS().' as vs')
                ->join(DatabaseConst::ELECTIONS().' as e', 'vs.election_id', '=', 'e.id')
                ->join(DatabaseConst::USER().' as u', 'vs.operator_id', '=', 'u.id')
                ->select(
                    'vs.id',
                    'vs.status',
                    'vs.created_at as open_time',
                    'vs.updated_at as close_time',
                    'e.name as election_name',
                    'u.name as operator_name'
                );

            if ($electionId) {
                $query->where('vs.election_id', $electionId);
            }

            $sessions = $query->orderBy('vs.created_at', 'desc')
                ->paginate($limit);

            // Append filter parameters if electionId exists
            if ($electionId) {
                $sessions->appends(['election_id' => $electionId]);
            }

            return Response::buildSuccess(['sessions' => $sessions], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
