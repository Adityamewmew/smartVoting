<?php

namespace App\Providers;

use App\Usecase\Admin\SidebarMenuUsecase;
use Illuminate\Support\Facades\Auth;
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
}
