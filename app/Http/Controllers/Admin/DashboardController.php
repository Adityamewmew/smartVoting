<?php

namespace App\Http\Controllers\Admin;

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use App\Http\Controllers\Controller;
use App\Usecase\Admin\SidebarMenuUsecase;
use App\Usecase\LivePollingUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        protected SidebarMenuUsecase $sidebarMenuUsecase,
        protected LivePollingUsecase $livePollingUsecase
    ) {}

    public function index(Request $request): View|RedirectResponse|Response
    {
        if (Auth::user()->access_type == UserConst::PLATFORM_SUPERADMIN) {
            return redirect()->route('admin.institutions.index');
        }

        if (Auth::user()->access_type == UserConst::OPERATOR) {
            return redirect()->route('operator.kiosk.index');
        }
        $tenantId = Auth::user()->institution_id;

        // Cari pemilihan aktif yang sedang berlangsung (Hari-H & status == 'active')
        $activeElection = DB::table(DatabaseConst::ELECTIONS())
            ->where('institution_id', $tenantId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        $isLive = (bool) $activeElection;
        $selectedElection = $activeElection;

        // Jika tidak ada pemilihan live hari ini, ambil event terdekat / terbaru milik tenant
        if (! $selectedElection) {
            $selectedElection = DB::table(DatabaseConst::ELECTIONS())
                ->where('institution_id', $tenantId)
                ->whereNull('deleted_at')
                ->orderByRaw("CASE WHEN status = 'active' THEN 1 WHEN status = 'draft' THEN 2 ELSE 3 END")
                ->orderBy('start_time', 'desc')
                ->first();
        }

        $recentSessions = [];
        $candidates = collect();
        $totalVotes = 0;
        $chartLabels = [];
        $chartData = [];

        if ($isLive && $selectedElection) {
            $processSessions = $this->livePollingUsecase->getRecentSessions(10, $selectedElection->id);
            $recentSessions = $processSessions['data']['sessions'] ?? [];

            $liveResults = $this->livePollingUsecase->getLiveResults($selectedElection->id);
            if ($liveResults['success']) {
                $totalVotes = $liveResults['data']['total_votes'] ?? 0;
                $candidates = collect($liveResults['data']['candidates'] ?? []);
                $chartLabels = $candidates->map(fn ($c) => 'Paslon '.$c->order_number)->toArray();
                $chartData = $candidates->pluck('vote_count')->toArray();
            }
        }

        $onboardingRes = $this->livePollingUsecase->getOnboardingProgress($tenantId, $selectedElection);
        $onboarding = $onboardingRes['data'] ?? [
            'steps' => [],
            'completed_count' => 0,
            'total_steps' => 2,
            'progress_percentage' => 0,
            'all_completed' => false,
        ];
        $showOnboarding = ! ($onboarding['all_completed'] ?? false);

        return view('_admin.dashboard', [
            'selectedElection' => $selectedElection,
            'isLive' => $isLive,
            'recentSessions' => $recentSessions,
            'totalVotes' => $totalVotes,
            'candidates' => $candidates,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'onboarding' => $onboarding,
            'showOnboarding' => $showOnboarding,
        ]);
    }

    public function data(Request $request)
    {
        $electionId = $request->query('election_id');

        if (! $electionId) {
            return response()->json(['success' => false, 'message' => 'Election ID is required']);
        }

        $process = $this->livePollingUsecase->getLiveResults($electionId);

        if (! $process['success']) {
            return response()->json(['success' => false, 'message' => $process['message']]);
        }

        return response()->json([
            'success' => true,
            'data' => $process['data'],
        ]);
    }

    public function print(int $electionId): View|RedirectResponse
    {
        $election = DB::table(DatabaseConst::ELECTIONS())
            ->where('id', $electionId)
            ->whereNull('deleted_at')
            ->first();

        if (! $election) {
            return redirect()->route('admin.dashboard')->with('error', 'Election not found');
        }

        $process = $this->livePollingUsecase->getLiveResults($electionId);

        if (! $process['success']) {
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat data laporan.');
        }

        return view('_admin.print', [
            'election' => $election,
            'totalVotes' => $process['data']['total_votes'],
            'candidates' => $process['data']['candidates'],
        ]);
    }
}
