<?php

use App\Constants\DatabaseConst;
use App\Models\User;
use App\Usecase\ElectionUsecase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

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
});

test('election creation separates date and time properly', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $usecase = app(ElectionUsecase::class);
    $request = new Request([
        'name' => 'Pemilihan Jadwal Test',
        'slug' => 'pemilihan-jadwal-test',
        'description' => 'Uji coba pisah tanggal dan jam',
        'date' => '2026-09-15',
        'start_time' => '08:00',
        'end_time' => '15:30',
        'status' => 'active',
    ]);

    $result = $usecase->create($request);
    expect($result['success'])->toBeTrue();

    $election = DB::table(DatabaseConst::ELECTIONS())
        ->where('slug', 'pemilihan-jadwal-test')
        ->first();

    expect($election)->not->toBeNull()
        ->and($election->date)->toBe('2026-09-15')
        ->and($election->start_time)->toBe('2026-09-15 08:00:00')
        ->and($election->end_time)->toBe('2026-09-15 15:30:00');

    // Clean up
    DB::table(DatabaseConst::ELECTIONS())->where('id', $election->id)->delete();
});

test('election creation fails if end time is before or equal to start time', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test 2',
        'email' => 'admin2@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $usecase = app(ElectionUsecase::class);
    $request = new Request([
        'name' => 'Pemilihan Waktu Salah',
        'slug' => 'pemilihan-waktu-salah',
        'date' => '2026-09-15',
        'start_time' => '14:00',
        'end_time' => '10:00',
        'status' => 'draft',
    ]);

    expect(fn () => $usecase->create($request))->toThrow(ValidationException::class);
});

test('election status only accepts draft and active', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test 3',
        'email' => 'admin3@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $usecase = app(ElectionUsecase::class);
    $request = new Request([
        'name' => 'Pemilihan Status Lama',
        'slug' => 'pemilihan-status-lama',
        'date' => '2026-09-15',
        'start_time' => '08:00',
        'end_time' => '16:00',
        'status' => 'scheduled',
    ]);

    expect(fn () => $usecase->create($request))->toThrow(ValidationException::class);
});

test('election cannot be activated if schedule end time is already in the past', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test 4',
        'email' => 'admin4@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $usecase = app(ElectionUsecase::class);
    $request = new Request([
        'name' => 'Pemilihan Kadaluarsa',
        'slug' => 'pemilihan-kadaluarsa',
        'date' => '2020-01-01',
        'start_time' => '08:00',
        'end_time' => '10:00',
        'status' => 'active',
    ]);

    expect(fn () => $usecase->create($request))->toThrow(ValidationException::class);
});

test('expired active elections are automatically synced to inactive status', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test 5',
        'email' => 'admin5@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    DB::table(DatabaseConst::ELECTIONS())->insert([
        'name' => 'Pemilihan Expired Test',
        'slug' => 'pemilihan-expired-test',
        'date' => '2020-01-01',
        'start_time' => '2020-01-01 08:00:00',
        'end_time' => '2020-01-01 10:00:00',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $usecase = app(ElectionUsecase::class);
    $usecase->syncExpiredElections();

    $election = DB::table(DatabaseConst::ELECTIONS())
        ->where('slug', 'pemilihan-expired-test')
        ->first();

    expect($election->status)->toBe('inactive');

    DB::table(DatabaseConst::ELECTIONS())->where('id', $election->id)->delete();
});
