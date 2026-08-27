<?php

namespace App\Http\Controllers;

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    public function login()
    {
        $currentTenant = app()->bound('current_tenant') ? app('current_tenant') : null;

        return view('_admin.auth.login', [
            'currentTenant' => $currentTenant,
        ]);
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user account is active
            if (isset($user->is_active) && ! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'login_error' => 'Akun Anda sedang dinonaktifkan.',
                ])->onlyInput('email');
            }

            // Check institution status if user belongs to a tenant
            if (! empty($user->institution_id)) {
                $institution = DB::table(DatabaseConst::INSTITUTION())
                    ->where('id', $user->institution_id)
                    ->whereNull('deleted_at')
                    ->first();

                if (! $institution || $institution->status !== 'active') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $message = match ($institution?->status) {
                        'suspended' => 'Institusi atau sekolah Anda sedang ditangguhkan.',
                        'pending' => 'Institusi Anda masih menunggu aktivasi / pembayaran tagihan.',
                        'inactive' => 'Institusi atau sekolah Anda sedang dinonaktifkan.',
                        default => 'Institusi atau sekolah Anda tidak aktif atau tidak ditemukan.',
                    };

                    return back()->withErrors([
                        'login_error' => $message,
                    ])->onlyInput('email');
                }

                // Bind current tenant context
                app()->instance('current_tenant', $institution);
                View::share('current_tenant', $institution);
            }

            $request->session()->regenerate();

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'login_error' => 'Email atau Password tidak sesuai, periksa kembali',
        ])->onlyInput('email');
    }

    private function redirectByRole($user)
    {
        switch ($user->access_type) {
            case UserConst::PLATFORM_SUPERADMIN:
                return redirect()->route('admin.institutions.index');
            case UserConst::SUPERADMIN:
                return redirect()->route('admin.dashboard');
            case UserConst::OPERATOR:
                return redirect()->route('operator.kiosk.index');
            default:
                return redirect()->intended(route('admin.dashboard'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
