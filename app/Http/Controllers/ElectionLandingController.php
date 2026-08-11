<?php

namespace App\Http\Controllers;

use App\Constants\DatabaseConst;
use Illuminate\Support\Facades\DB;

class ElectionLandingController extends Controller
{
    public function show(string $slug)
    {
        $election = DB::table(DatabaseConst::ELECTIONS())
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->first();

        if (! $election) {
            abort(404, 'Event pemilihan tidak ditemukan.');
        }

        $candidates = [];
        if ($election->status !== 'draft') {
            $candidates = DB::table(DatabaseConst::CANDIDATES())
                ->where('election_id', $election->id)
                ->whereNull('deleted_at')
                ->orderBy('order_number', 'asc')
                ->get();
        }

        return view('landing.election', [
            'election' => $election,
            'candidates' => $candidates,
        ]);
    }
}
