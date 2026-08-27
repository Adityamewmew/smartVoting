<?php

namespace App\Http\Controllers\Operator;

use App\Constants\DatabaseConst;
use App\Http\Controllers\Controller;
use App\Usecase\VotingSessionUsecase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KioskManagerController extends Controller
{
    public function __construct(
        protected VotingSessionUsecase $votingSessionUsecase
    ) {}

    /**
     * Tampilkan daftar event pemilihan aktif untuk panel operator
     */
    public function index(): View
    {
        $user = Auth::user();
        $institutionId = $user?->institution_id;

        $elections = DB::table(DatabaseConst::ELECTIONS())
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->when($institutionId, function ($query, $institutionId) {
                return $query->where('institution_id', $institutionId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $elections->map(function ($election) {
            $totalVotes = DB::table(DatabaseConst::VOTES())
                ->where('election_id', $election->id)
                ->whereNull('deleted_at')
                ->count();

            $activeSessions = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('election_id', $election->id)
                ->where('status', 'open')
                ->count();

            $election->total_votes = $totalVotes;
            $election->active_sessions = $activeSessions;

            return $election;
        });

        return view('_operator.kiosk.index', [
            'data' => $data,
        ]);
    }

    /**
     * Buka bilik suara & buat token sesi pemilihan baru
     */
    public function generate(Request $request, int $electionId): RedirectResponse
    {
        $operatorId = Auth::id();

        if (! $operatorId) {
            return redirect()->back()->with('error', 'Sesi operator tidak valid. Silakan login kembali.');
        }

        $election = DB::table(DatabaseConst::ELECTIONS())
            ->where('id', $electionId)
            ->whereNull('deleted_at')
            ->first();

        if (! $election || $election->status !== 'active') {
            return redirect()->back()->with('error', 'Pemilihan ini sedang tidak aktif.');
        }

        $process = $this->votingSessionUsecase->generateSession($electionId, $operatorId);

        if ($process['success']) {
            return redirect()->route('kiosk.vote', ['token' => $process['data']['token']]);
        }

        return redirect()->back()->with('error', $process['message'] ?? 'Gagal membuat sesi bilik suara.');
    }

    /**
     * Ambil data kandidat paslon untuk preview modal operator
     */
    public function candidates(int $electionId): JsonResponse
    {
        $candidates = DB::table(DatabaseConst::CANDIDATES())
            ->where('election_id', $electionId)
            ->whereNull('deleted_at')
            ->orderBy('order_number', 'asc')
            ->get();

        return response()->json($candidates);
    }
}
