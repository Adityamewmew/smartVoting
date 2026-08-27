<?php

namespace App\Http\Middleware;

use App\Constants\DatabaseConst;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if (! empty($user->institution_id) && Schema::hasTable('institutions')) {
                $tenant = DB::table(DatabaseConst::INSTITUTION())
                    ->where('id', $user->institution_id)
                    ->whereNull('deleted_at')
                    ->first();

                if (! $tenant || $tenant->status !== 'active') {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $message = match ($tenant?->status) {
                        'suspended' => 'Institusi atau sekolah Anda sedang ditangguhkan.',
                        'pending' => 'Institusi Anda masih menunggu aktivasi / pembayaran tagihan.',
                        'inactive' => 'Institusi atau sekolah Anda sedang dinonaktifkan.',
                        default => 'Institusi atau sekolah Anda tidak aktif atau tidak ditemukan.',
                    };

                    return redirect()->route('login')->withErrors([
                        'login_error' => $message,
                    ]);
                }

                app()->instance('current_tenant', $tenant);
                View::share('current_tenant', $tenant);
            }
        }

        return $next($request);
    }
}
