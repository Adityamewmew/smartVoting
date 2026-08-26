<?php

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

    if (! Schema::hasTable('elections')) {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    if (! Schema::hasTable('voting_sessions')) {
        Schema::create('voting_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->string('session_token')->nullable();
            $table->string('status')->default('open');
            $table->dateTime('open_time')->nullable();
            $table->dateTime('close_time')->nullable();
            $table->timestamps();
        });
    }
});

test('admin dashboard renders empty state without sessions table when no active election exists', function () {
    DB::table(DatabaseConst::ELECTIONS())->delete();

    $user = User::factory()->make(['id' => 1, 'access_type' => UserConst::SUPERADMIN]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Dashboard')
        ->assertSee('Belum ada event pemilihan yang berstatus aktif saat ini.')
        ->assertDontSee('Sesi Bilik Suara Terkini');
});

test('admin dashboard renders live polling and sessions table when active election exists', function () {
    DB::table(DatabaseConst::ELECTIONS())->delete();

    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Aktif Test',
        'slug' => 'pemilihan-aktif-test',
        'status' => 'active',
        'date' => '2026-10-02',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->make(['id' => 1, 'access_type' => UserConst::SUPERADMIN]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Dashboard')
        ->assertSee('Sesi Bilik Suara Terkini')
        ->assertSee('Pemilihan Aktif Test');

    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});
