<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Usecase\ElectionUsecase;
use App\Usecase\VotingSessionUsecase;
use App\Constants\ResponseConst;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KioskManagerController extends Controller
{
    protected array $page = [
        'route' => 'kiosk_manager',
        'title' => 'Manajemen Bilik Suara',
    ];

    public function __construct(
        protected ElectionUsecase $electionUsecase,
        protected VotingSessionUsecase $votingSessionUsecase
    ) {}

    public function index(Request $request): View
    {
        // Get all active elections
        // But for MVP, just get all or filter by 'active'
        $elections = $this->electionUsecase->getAll(['no_pagination' => true]);
        $elections = $elections['data']['list'] ?? [];

        // Filter active in collection (only rely on manual status from admin)
        $activeElections = collect($elections)->filter(function ($election) {
            return $election->status === 'active';
        })->map(function ($election) {
            $election->total_votes = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::VOTES())
                ->where('election_id', $election->id)
                ->count();
            
            $election->active_sessions = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::VOTING_SESSIONS())
                ->where('election_id', $election->id)
                ->where('status', 'open')
                ->count();

            return $election;
        })->values();

        return view('_operator.kiosk.index', [
            'data' => $activeElections,
            'page' => $this->page,
        ]);
    }

    public function generate(int $electionId): RedirectResponse
    {
        return redirect()->route('kiosk.start', ['electionId' => $electionId]);
    }
}
