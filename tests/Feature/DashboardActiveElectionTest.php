<?php

use App\Constants\DatabaseConst;
use App\Usecase\LivePollingUsecase;
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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_token')->nullable();
            $table->string('status')->default('open');
            $table->dateTime('open_time')->nullable();
            $table->dateTime('close_time')->nullable();
            $table->timestamps();
        });
    }
});

test('dashboard only retrieves active elections', function () {
    // Clear any previous records
    DB::table(DatabaseConst::ELECTIONS())->delete();

    // Insert 1 draft election and 1 active election
    DB::table(DatabaseConst::ELECTIONS())->insert([
        [
            'name' => 'Pemilihan Draft',
            'slug' => 'pemilihan-draft',
            'status' => 'draft',
            'date' => '2026-10-01',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Pemilihan Aktif',
            'slug' => 'pemilihan-aktif',
            'status' => 'active',
            'date' => '2026-10-02',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $usecase = app(LivePollingUsecase::class);
    $result = $usecase->getDashboardElections();

    expect($result['success'])->toBeTrue();
    $list = collect($result['data']['list']);

    expect($list)->toHaveCount(1)
        ->and($list->first()->name)->toBe('Pemilihan Aktif')
        ->and($list->first()->status)->toBe('active');

    // Clean up
    DB::table(DatabaseConst::ELECTIONS())->delete();
});
