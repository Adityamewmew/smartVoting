<?php

use App\Constants\UserConst;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SidebarMenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElectionLandingController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\Operator\KioskManagerController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->access_type == UserConst::PLATFORM_SUPERADMIN) {
            return redirect()->route('admin.institutions.index');
        }

        if (auth()->user()->access_type == UserConst::OPERATOR) {
            return redirect()->route('operator.kiosk.index');
        }

        return redirect()->route('admin.dashboard');
    }

    return view('landing.main');
})->name('landing.home');

// Subscription & Self-Service Onboarding Routes
Route::get('/subscribe', [SubscriptionController::class, 'showForm'])->name('subscribe');
Route::post('/subscribe', [SubscriptionController::class, 'doSubscribe'])->name('subscribe.post');
Route::get('/payment/{invoice_number}', [SubscriptionController::class, 'showPayment'])->name('payment.invoice');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Users Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminDashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/dashboard/print/{electionId}', [AdminDashboardController::class, 'print'])->name('dashboard.print');

    // Superadmin Platform - Multi Tenant Institutions Management
    Route::middleware('access_type:0')->prefix('institutions')->name('institutions.')->group(function () {
        Route::get('/', [InstitutionController::class, 'index'])->name('index');
        Route::get('/add', [InstitutionController::class, 'add'])->name('add');
        Route::post('/create', [InstitutionController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [InstitutionController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [InstitutionController::class, 'update'])->name('update');
        Route::post('/update/{id}', [InstitutionController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [InstitutionController::class, 'delete'])->name('delete');
    });

    // Superadmin Platform - Payments & Billing Management
    Route::middleware('access_type:0')->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/add', [PaymentController::class, 'add'])->name('add');
        Route::post('/create', [PaymentController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [PaymentController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [PaymentController::class, 'update'])->name('update');
        Route::post('/update/{id}', [PaymentController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [PaymentController::class, 'delete'])->name('delete');
        Route::post('/{id}/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm');
    });

    Route::middleware('access_type:1')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/add', [UserController::class, 'add'])->name('add');
        Route::post('/create', [UserController::class, 'doCreate'])->name('create');
        Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/update/{id}', [UserController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::post('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('resetPassword');
    });

    Route::middleware('access_type:1,2')->prefix('elections')->name('elections.')->group(function () {
        Route::get('/', [ElectionController::class, 'index'])->name('index');
        Route::get('/add', [ElectionController::class, 'add'])->name('add');
        Route::post('/create', [ElectionController::class, 'doCreate'])->name('create');
        Route::get('/{id}/detail', [ElectionController::class, 'detail'])->name('detail');
        Route::get('/update/{id}', [ElectionController::class, 'update'])->name('update');
        Route::post('/update/{id}', [ElectionController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [ElectionController::class, 'delete'])->name('delete');
    });

    Route::middleware('access_type:1,2')->prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/add', [CandidateController::class, 'add'])->name('add');
        Route::post('/create', [CandidateController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [CandidateController::class, 'update'])->name('update');
        Route::post('/update/{id}', [CandidateController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [CandidateController::class, 'delete'])->name('delete');
    });

    Route::middleware('access_type:1')->prefix('sidebar-menu')->name('sidebar_menu.')->group(function () {
        Route::get('/', [SidebarMenuController::class, 'index'])->name('index');
        Route::get('/refresh-cache', [SidebarMenuController::class, 'refreshCache'])->name('refresh_cache');
        Route::get('/add', [SidebarMenuController::class, 'add'])->name('add');
        Route::post('/create', [SidebarMenuController::class, 'doCreate'])->name('create');
        Route::get('/update/{id}', [SidebarMenuController::class, 'update'])->name('update');
        Route::post('/update/{id}', [SidebarMenuController::class, 'doUpdate'])->name('doUpdate');
        Route::delete('/delete/{id}', [SidebarMenuController::class, 'delete'])->name('delete');
        Route::get('/{id}/access', [SidebarMenuController::class, 'access'])->name('access');
        Route::post('/{id}/access', [SidebarMenuController::class, 'doAccess'])->name('doAccess');
        Route::get('/role-access/{accessType}', [SidebarMenuController::class, 'roleAccess'])->name('role_access');
        Route::post('/role-access/{accessType}', [SidebarMenuController::class, 'doRoleAccess'])->name('doRoleAccess');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/change-password', [UserController::class, 'changePassword'])->name('change_password');
        Route::post('/change-password', [UserController::class, 'doChangePassword'])->name('do_change_password');
    });

});

// Operator Routes
Route::middleware(['auth', 'access_type:2'])->prefix('operator')->name('operator.')->group(function () {
    Route::prefix('kiosk')->name('kiosk.')->group(function () {
        Route::get('/', [KioskManagerController::class, 'index'])->name('index');
        Route::post('/generate/{electionId}', [KioskManagerController::class, 'generate'])->name('generate');
        Route::get('/{electionId}/candidates', [KioskManagerController::class, 'candidates'])->name('candidates');
    });
});

// Public Kiosk Routes
Route::prefix('bilik')->name('kiosk.')->group(function () {
    Route::get('/start/{electionId}', [KioskController::class, 'start'])->name('start');
    Route::post('/start/{electionId}', [KioskController::class, 'generate'])->name('generate');
    Route::get('/{token}/vote', [KioskController::class, 'vote'])->name('vote');
    Route::post('/{token}/submit', [KioskController::class, 'submit'])->name('submit');
    Route::post('/{token}/expire', [KioskController::class, 'expire'])->name('expire');
});

// Landing Page Publik (T-08) - Ditempatkan di paling bawah dengan regex guard
Route::get('/{slug}', [ElectionLandingController::class, 'show'])
    ->where('slug', '^(?!login|subscribe|payment|admin|operator|bilik|up|storage)[a-zA-Z0-9_-]+$')
    ->name('landing.election');
