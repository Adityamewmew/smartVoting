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
    public function getActiveElectionsWithStats(): array
    {
        try {
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

    public function getDashboardElections(): array
    {
        try {
            $elections = DB::table(DatabaseConst::ELECTIONS())
                ->whereIn('status', ['active', 'closed'])
                ->whereNull('deleted_at')
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
                ->select('order_number', 'chairman_name', 'vice_chairman_name')
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
                    DB::raw('COUNT(v.id) as vote_count')
                )
                ->groupBy('c.id', 'c.order_number', 'c.chairman_name', 'c.vice_chairman_name')
                ->orderBy('c.order_number', 'asc')
                ->get();

            return Response::buildSuccess([
                'total_votes' => $totalVotes,
                'candidates' => $candidates,
            ], ResponseConst::HTTP_SUCCESS);
        } catch (Exception $e) {
            Log::error(message: $e->getMessage(), context: ['method' => __METHOD__]);

            return Response::buildErrorService($e->getMessage());
        }
    }
}
