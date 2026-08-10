<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Usecase\Admin\SidebarMenuUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected SidebarMenuUsecase $sidebarMenuUsecase,
        protected \App\Usecase\ElectionUsecase $electionUsecase
    ) {}

    public function index(): View|\Illuminate\Http\RedirectResponse|Response
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

        // Fetch Elections for Admin Dashboard (both currently active or closed/past)
        // so that Admin can still print the reports.
        $electionsResponse = $this->electionUsecase->getDashboardElections();
        $activeElections = $electionsResponse['data'] ?? [];

        return view('_admin.dashboard', [
            'modules' => $modules,
            'activeElections' => $activeElections,
        ]);
    }

    public function data(\Illuminate\Http\Request $request)
    {
        $electionId = $request->query('election_id');

        if (!$electionId) {
            return response()->json(['success' => false, 'message' => 'Election ID is required']);
        }

        $resultsResponse = $this->electionUsecase->getElectionResults($electionId);
        if (!$resultsResponse['success']) {
            return response()->json(['success' => false, 'message' => $resultsResponse['message']], 500);
        }

        $totalVotes = $resultsResponse['data']['total_votes'] ?? 0;
        $candidates = $resultsResponse['data']['candidates'] ?? [];

        return response()->json([
            'success' => true,
            'data' => [
                'total_votes' => $totalVotes,
                'candidates' => $candidates
            ]
        ]);
    }

    public function print(int $electionId): View|\Illuminate\Http\RedirectResponse
    {
        $election = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::ELECTIONS())
            ->where('id', $electionId)
            ->whereNull('deleted_at')
            ->first();

        if (!$election) {
            return redirect()->route('admin.dashboard')->with('error', 'Election not found');
        }

        $resultsResponse = $this->electionUsecase->getElectionResults($electionId);
        $totalVotes = $resultsResponse['data']['total_votes'] ?? 0;
        $candidates = $resultsResponse['data']['candidates'] ?? [];

        return view('_admin.print', [
            'election' => $election,
            'totalVotes' => $totalVotes,
            'candidates' => $candidates
        ]);
    }
}
