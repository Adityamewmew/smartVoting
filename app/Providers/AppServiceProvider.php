<?php

namespace App\Providers;

use App\Usecase\Admin\SidebarMenuUsecase;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Blaze\Blaze;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blaze::optimize()->in(
            resource_path('views/components')
        );

        $this->configureRateLimiting();

        View::composer('_admin._layout.sidebar.*', function ($view) {
            if (! Auth::check()) {
                return;
            }

            $usecase = app(SidebarMenuUsecase::class);

            $sidebarMenus = [
                'utama' => $usecase->getMenusForSidebar((int) Auth::user()->access_type, 'utama')['data'] ?? [],
                'pengaturan' => $usecase->getMenusForSidebar((int) Auth::user()->access_type, 'pengaturan')['data'] ?? [],
            ];

            $view->with('sidebarMenus', $sidebarMenus);
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
        });

        RateLimiter::for('google-auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('onboarding', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('voting', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('operator-kiosk', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('polling', function (Request $request) {
            return Limit::perMinute(120)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-mutations', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('global-web', function (Request $request) {
            return Limit::perMinute(180)->by($request->ip());
        });
    }
}
