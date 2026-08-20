<?php

use App\Constants\UserConst;
use App\Usecase\Admin\SidebarMenuUsecase;
use Database\Seeders\SidebarMenuSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    if (! Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('secret');
            $table->unsignedBigInteger('access_type')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    if (! Schema::hasTable('sidebar_menu_groups')) {
        Schema::create('sidebar_menu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('sidebar_menus')) {
        Schema::create('sidebar_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label');
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->string('group')->default('utama');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    if (! Schema::hasTable('sidebar_menu_accesses')) {
        Schema::create('sidebar_menu_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sidebar_menu_id');
            $table->unsignedBigInteger('access_type');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }
});

test('sidebar has 2 groups: utama (dasbor, pemilihan) and pengaturan (pengguna)', function () {
    $this->seed(SidebarMenuSeeder::class);

    $usecase = app(SidebarMenuUsecase::class);
    $usecase->flushSidebarCache();

    $utama = $usecase->getMenusForSidebar(UserConst::SUPERADMIN, 'utama');
    $pengaturan = $usecase->getMenusForSidebar(UserConst::SUPERADMIN, 'pengaturan');

    expect($utama['success'])->toBeTrue();
    $utamaLabels = collect($utama['data'])->pluck('label')->all();
    expect($utamaLabels)->toContain('Dasbor')
        ->and($utamaLabels)->toContain('Pemilihan')
        ->and($utamaLabels)->not->toContain('Paslon');

    expect($pengaturan['success'])->toBeTrue();
    $pengaturanLabels = collect($pengaturan['data'])->pluck('label')->all();
    expect($pengaturanLabels)->toContain('Pengguna');
});
