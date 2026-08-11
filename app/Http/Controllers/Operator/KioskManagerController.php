<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Usecase\LivePollingUsecase;
use App\Usecase\VotingSessionUsecase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KioskManagerController extends Controller
{
    protected array $page = [
        'route' => 'kiosk_manager',
        'title' => 'Manajemen Bilik Suara',
    ];

    public function __construct(
        protected LivePollingUsecase $livePollingUsecase,
        protected VotingSessionUsecase $votingSessionUsecase
    ) {}

    public function index(Request $request): View
    {
        $process = $this->livePollingUsecase->getActiveElectionsWithStats();
        $activeElections = $process['data']['list'] ?? [];

        return view('_operator.kiosk.index', [
            'data' => $activeElections,
            'page' => $this->page,
        ]);
    }

    public function generate(int $electionId): RedirectResponse
    {
        return redirect()->route('kiosk.start', ['electionId' => $electionId]);
    }

    public function candidates(int $electionId): JsonResponse
    {
        $process = $this->livePollingUsecase->getCandidatesByElection($electionId);

        return response()->json($process);
    }
}
