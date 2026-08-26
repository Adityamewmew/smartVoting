<?php

use App\Constants\DatabaseConst;
use App\Models\User;
use App\Usecase\ElectionUsecase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

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

    if (! Schema::hasTable('candidates')) {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->integer('order_number');
            $table->string('chairman_name');
            $table->string('vice_chairman_name');
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('vice_chairman_photo_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
});

test('election can be created with logo upload', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $logo = UploadedFile::fake()->image('logo.png', 500, 500);

    $usecase = app(ElectionUsecase::class);
    $request = new Request(
        query: [
            'name' => 'Pemilihan dengan Logo',
            'slug' => 'pemilihan-logo-test',
            'date' => '2026-09-20',
            'start_time' => '08:00',
            'end_time' => '16:00',
            'status' => 'active',
        ],
        files: [
            'logo' => $logo,
        ]
    );

    $result = $usecase->create($request);
    expect($result['success'])->toBeTrue();

    $election = DB::table(DatabaseConst::ELECTIONS())
        ->where('slug', 'pemilihan-logo-test')
        ->first();

    expect($election)->not->toBeNull()
        ->and($election->logo_path)->not->toBeNull();

    Storage::disk('public')->assertExists($election->logo_path);
});

test('election update replaces existing logo', function () {
    $user = User::first() ?? User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $oldLogo = UploadedFile::fake()->image('old_logo.png', 500, 500);
    $oldPath = 'elections/logos/old_logo.png';
    Storage::disk('public')->put($oldPath, 'content');

    $id = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Logo Edit',
        'slug' => 'pemilihan-logo-edit',
        'logo_path' => $oldPath,
        'date' => '2026-09-20',
        'start_time' => '2026-09-20 08:00:00',
        'end_time' => '2026-09-20 16:00:00',
        'status' => 'draft',
        'created_at' => now(),
    ]);

    $newLogo = UploadedFile::fake()->image('new_logo.png', 500, 500);
    $usecase = app(ElectionUsecase::class);
    $request = new Request(
        query: [
            'name' => 'Pemilihan Logo Edit Updated',
            'slug' => 'pemilihan-logo-edit',
            'date' => '2026-09-20',
            'start_time' => '08:00',
            'end_time' => '16:00',
            'status' => 'draft',
        ],
        files: [
            'logo' => $newLogo,
        ]
    );

    $result = $usecase->update($request, $id);
    expect($result['success'])->toBeTrue();

    $updated = DB::table(DatabaseConst::ELECTIONS())->where('id', $id)->first();
    expect($updated->logo_path)->not->toBe($oldPath);
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($updated->logo_path);
});

test('landing slug displays custom logo if present and default if null', function () {
    $logoPath = 'elections/logos/custom_logo.webp';
    Storage::disk('public')->put($logoPath, 'custom logo content');

    $id1 = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Custom Logo',
        'slug' => 'slug-custom-logo',
        'logo_path' => $logoPath,
        'date' => '2026-09-20',
        'start_time' => '2026-09-20 08:00:00',
        'end_time' => '2026-09-20 16:00:00',
        'status' => 'active',
        'created_at' => now(),
    ]);

    $response1 = $this->get('/slug-custom-logo');
    $response1->assertOk();
    $response1->assertSee(Storage::url($logoPath));

    $id2 = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Default Logo',
        'slug' => 'slug-default-logo',
        'logo_path' => null,
        'date' => '2026-09-20',
        'start_time' => '2026-09-20 08:00:00',
        'end_time' => '2026-09-20 16:00:00',
        'status' => 'active',
        'created_at' => now(),
    ]);

    $response2 = $this->get('/slug-default-logo');
    $response2->assertOk();
    $response2->assertSee('images/logo-light.png');
});
