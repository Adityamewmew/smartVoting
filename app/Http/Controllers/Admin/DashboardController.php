<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Usecase\Admin\SidebarMenuUsecase;
use App\Usecase\LivePollingUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected SidebarMenuUsecase $sidebarMenuUsecase,
        protected LivePollingUsecase $livePollingUsecase
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
        $process = $this->livePollingUsecase->getDashboardElections();
        $activeElections = collect($process['data']['list'] ?? []);

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

        $process = $this->livePollingUsecase->getLiveResults($electionId);
        
        if (!$process['success']) {
            return response()->json(['success' => false, 'message' => $process['message']]);
        }

        return response()->json([
            'success' => true,
            'data' => $process['data']
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

        $process = $this->livePollingUsecase->getLiveResults($electionId);

        if (!$process['success']) {
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat data laporan.');
        }

        return view('_admin.print', [
            'election' => $election,
            'totalVotes' => $process['data']['total_votes'],
            'candidates' => $process['data']['candidates']
        ]);
    }
}
