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
        protected SidebarMenuUsecase $sidebarMenuUsecase
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
        $activeElections = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::ELECTIONS())
            ->whereIn('status', ['active', 'closed'])
            ->whereNull('deleted_at')
            ->get();

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

        // Get total votes
        $totalVotes = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::VOTES())
            ->where('election_id', $electionId)
            ->count();

        // Get vote count per candidate
        $candidates = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::CANDIDATES() . ' as c')
            ->leftJoin(\App\Constants\DatabaseConst::VOTES() . ' as v', 'c.id', '=', 'v.candidate_id')
            ->where('c.election_id', $electionId)
            ->whereNull('c.deleted_at')
            ->select(
                'c.id',
                'c.order_number',
                'c.chairman_name',
                'c.vice_chairman_name',
                \Illuminate\Support\Facades\DB::raw('COUNT(v.id) as vote_count')
            )
            ->groupBy('c.id', 'c.order_number', 'c.chairman_name', 'c.vice_chairman_name')
            ->orderBy('c.order_number', 'asc')
            ->get();

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

        $totalVotes = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::VOTES())
            ->where('election_id', $electionId)
            ->count();

        $candidates = \Illuminate\Support\Facades\DB::table(\App\Constants\DatabaseConst::CANDIDATES() . ' as c')
            ->leftJoin(\App\Constants\DatabaseConst::VOTES() . ' as v', 'c.id', '=', 'v.candidate_id')
            ->where('c.election_id', $electionId)
            ->whereNull('c.deleted_at')
            ->select(
                'c.id',
                'c.order_number',
                'c.chairman_name',
                'c.vice_chairman_name',
                \Illuminate\Support\Facades\DB::raw('COUNT(v.id) as vote_count')
            )
            ->groupBy('c.id', 'c.order_number', 'c.chairman_name', 'c.vice_chairman_name')
            ->orderBy('c.order_number', 'asc')
            ->get();

        return view('_admin.print', [
            'election' => $election,
            'totalVotes' => $totalVotes,
            'candidates' => $candidates
        ]);
    }
}
