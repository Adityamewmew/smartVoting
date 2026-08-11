<?php

namespace App\Http\Controllers;

use App\Constants\DatabaseConst;
use App\Usecase\VotingSessionUsecase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KioskController extends Controller
{
    public function __construct(
        protected VotingSessionUsecase $votingSessionUsecase
    ) {}

    /**
     * Layar standby (Continuous Kiosk Mode)
     */
    public function start(int $electionId)
    {
        $election = DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->first();
        if (! $election) {
            return abort(404);
        }

        if ($election->status !== 'active') {
            return redirect()->route('operator.kiosk.index')->with('error', 'Pemilihan ini sedang tidak aktif.');
        }

        return view('kiosk.welcome', ['election' => $election]);
    }

    /**
     * Generate session dan mulai voting
     */
    public function generate(Request $request, int $electionId)
    {
        // Because the tablet is opened by the operator, they must be logged in.
        // For MVP, if not logged in, we can fallback to operator 1 or just show error.
        $operatorId = Auth::user()?->id;

        if (! $operatorId) {
            return redirect()->back()->with('error', 'Sesi operator telah habis. Silakan login kembali di tab baru.');
        }

        $election = DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->first();
        if (! $election) {
            return redirect()->back()->with('error', 'Pemilihan tidak ditemukan.');
        }

        if ($election->status !== 'active') {
            return redirect()->route('operator.kiosk.index')->with('error', 'Pemilihan ini sedang tidak aktif.');
        }

        $process = $this->votingSessionUsecase->generateSession($electionId, $operatorId);

        if ($process['success']) {
            return redirect()->route('kiosk.vote', ['token' => $process['data']['token']]);
        }

        return redirect()->back()->with('error', $process['message']);
    }

    /**
     * Layar daftar paslon dan timer 1 menit
     */
    public function vote(string $token)
    {
        $process = $this->votingSessionUsecase->verifySession($token);

        if (! $process['success']) {
            // If token was already used or expired, redirect back to the start screen
            $session = DB::table(DatabaseConst::VOTING_SESSIONS())
                ->where('session_token', $token)
                ->select('election_id', 'status')
                ->first();

            if ($session && in_array($session->status, ['submitted', 'expired'])) {
                return redirect()->route('kiosk.start', $session->election_id);
            }

            return view('kiosk.error', [
                'message' => $process['message'],
                'electionId' => $session?->election_id,
            ]);
        }

        $session = $process['data'];

        // Get candidates for this election
        $candidates = DB::table(DatabaseConst::CANDIDATES())
            ->where('election_id', $session['election_id'])
            ->whereNull('deleted_at')
            ->orderBy('order_number', 'asc')
            ->get();

        $election = DB::table(DatabaseConst::ELECTIONS())
            ->where('id', $session['election_id'])
            ->first();

        return view('kiosk.vote', [
            'token' => $token,
            'session' => $session,
            'candidates' => $candidates,
            'election' => $election,
        ]);
    }

    /**
     * Submit API endpoint
     */
    public function submit(Request $request, string $token): JsonResponse
    {
        $request->validate([
            'candidate_id' => 'required|integer',
        ]);

        $process = $this->votingSessionUsecase->submitVote($token, $request->input('candidate_id'));

        if ($process['success']) {
            return response()->json(['success' => true, 'message' => $process['message']]);
        }

        return response()->json(['success' => false, 'message' => $process['message']], 400);
    }

    /**
     * Expire API endpoint
     */
    public function expire(string $token): JsonResponse
    {
        $process = $this->votingSessionUsecase->expireSession($token);

        return response()->json(['success' => $process['success']]);
    }
}
