<?php

namespace App\Http\Controllers\Admin;

use App\Constants\DatabaseConst;
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
        if (Auth::user()->access_type == 2) {
            return redirect()->route('operator.kiosk.index');
        }
        $modules = $this->sidebarMenuUsecase->getDashboardModules(
            accessType: (int) Auth::user()->access_type
        );

        $allowedRoutes = [
            'admin.users.index',
            'admin.sidebar_menu.index',
        ];

        $modules = collect($modules['data'] ?? [])
            ->filter(fn ($menu) => in_array($menu->route_name, $allowedRoutes, true))
            ->values()
            ->all();

        $electionId = $request->get('election_id');
        $keywords = $request->get('keywords');

        // Fetch Elections for Admin Dashboard (both currently active or closed/past)
        $process = $this->livePollingUsecase->getDashboardElections($keywords);
        $electionsList = collect($process['data']['list'] ?? []);

        if ($electionId) {
            $selectedElection = $electionsList->firstWhere('id', $electionId);
        } else {
            // Default to the first active election, or the first election
            $selectedElection = $electionsList->firstWhere('status', 'active') ?? $electionsList->first();
        }

        // Fetch recent voting sessions (T-07)
        $processSessions = $this->livePollingUsecase->getRecentSessions();
        $recentSessions = $processSessions['data']['sessions'] ?? [];

        return view('_admin.dashboard', [
            'electionsList' => $electionsList,
            'selectedElection' => $selectedElection,
            'recentSessions' => $recentSessions,
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
