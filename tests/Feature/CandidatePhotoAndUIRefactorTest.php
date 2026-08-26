<?php

use App\Constants\UserConst;
use App\Models\User;
use Database\Seeders\SidebarMenuSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    if (! Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('secret');
            $table->unsignedBigInteger('access_type')->default(1);
            $table->boolean('is_active')->default(1);
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

    if (! Schema::hasTable('elections')) {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
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
            $table->string('vice_chairman_name')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('vice_chairman_photo_path')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    } elseif (! Schema::hasColumn('candidates', 'vice_chairman_photo_path')) {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('vice_chairman_photo_path')->nullable()->after('photo_path');
        });
    }

    if (! Schema::hasTable('votes')) {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->string('vote_hash')->nullable();
            $table->timestamps();
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

    $this->seed(SidebarMenuSeeder::class);
    Storage::fake('public');
});

test('candidate photo upload rejects landscape images', function () {
    $admin = User::forceCreate([
        'name' => 'Admin Test',
        'email' => 'admin_landscape@example.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
        'is_active' => 1,
    ]);

    $electionId = DB::table('elections')->insertGetId([
        'name' => 'Pemilihan Ketua OSIS',
        'slug' => 'pemilihan-ketua-osis-landscape',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Landscape image (width 500, height 300)
    $landscapeImage = UploadedFile::fake()->image('chairman_landscape.jpg', 500, 300);

    $response = $this->actingAs($admin)
        ->from(route('admin.elections.detail', $electionId))
        ->post(route('admin.candidates.create'), [
            'election_id' => $electionId,
            'order_number' => 1,
            'chairman_name' => 'Calon Ketua 1',
            'vice_chairman_name' => 'Calon Wakil 1',
            'photo' => $landscapeImage,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    expect(DB::table('candidates')->where('election_id', $electionId)->count())->toBe(0);
});

test('candidate photo upload rejects dimensions exceeding 700px', function () {
    $admin = User::forceCreate([
        'name' => 'Admin Test',
        'email' => 'admin_large@example.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
        'is_active' => 1,
    ]);

    $electionId = DB::table('elections')->insertGetId([
        'name' => 'Pemilihan Ketua OSIS',
        'slug' => 'pemilihan-ketua-osis-large',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Portrait image exceeding 700px (width 600, height 800)
    $largeImage = UploadedFile::fake()->image('chairman_large.jpg', 600, 800);

    $response = $this->actingAs($admin)
        ->from(route('admin.elections.detail', $electionId))
        ->post(route('admin.candidates.create'), [
            'election_id' => $electionId,
            'order_number' => 1,
            'chairman_name' => 'Calon Ketua 1',
            'vice_chairman_name' => 'Calon Wakil 1',
            'photo' => $largeImage,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    expect(DB::table('candidates')->where('election_id', $electionId)->count())->toBe(0);
});

test('candidate photo upload accepts valid portrait image and converts to 354x472 px', function () {
    $admin = User::forceCreate([
        'name' => 'Admin Test',
        'email' => 'admin_valid_photo@example.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
        'is_active' => 1,
    ]);

    $electionId = DB::table('elections')->insertGetId([
        'name' => 'Pemilihan Ketua OSIS',
        'slug' => 'pemilihan-ketua-osis-valid',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Valid portrait images (width <= height and <= 700px)
    $chairmanImage = UploadedFile::fake()->image('chairman.jpg', 400, 500);
    $viceImage = UploadedFile::fake()->image('vice_chairman.png', 450, 600);

    $response = $this->actingAs($admin)
        ->post(route('admin.candidates.create'), [
            'election_id' => $electionId,
            'order_number' => 1,
            'chairman_name' => 'Budi Santoso',
            'vice_chairman_name' => 'Dewi Sartika',
            'photo' => $chairmanImage,
            'vice_chairman_photo' => $viceImage,
            'vision' => 'Visi Unggul',
            'mission' => 'Misi Maju',
        ]);

    $response->assertRedirect(route('admin.elections.detail', ['id' => $electionId, 'tab' => 'paslon']));
    $response->assertSessionHas('success');

    $candidate = DB::table('candidates')->where('election_id', $electionId)->first();
    expect($candidate)->not->toBeNull();
    expect($candidate->chairman_name)->toBe('Budi Santoso');
    expect($candidate->vice_chairman_name)->toBe('Dewi Sartika');
    expect($candidate->photo_path)->not->toBeNull();
    expect($candidate->vice_chairman_photo_path)->not->toBeNull();

    // Verify converted file in fake storage
    Storage::disk('public')->assertExists($candidate->photo_path);
    Storage::disk('public')->assertExists($candidate->vice_chairman_photo_path);

    // Verify dimensions of converted image
    $photoContents = Storage::disk('public')->get($candidate->photo_path);
    $img = imagecreatefromstring($photoContents);
    expect(imagesx($img))->toBe(354);
    expect(imagesy($img))->toBe(472);
    imagedestroy($img);
});

test('public landing page renders candidate cards with both photos and visi misi modal', function () {
    $electionId = DB::table('elections')->insertGetId([
        'name' => 'Pemilihan Raya Mahasiswa 2026',
        'slug' => 'pemira-2026',
        'description' => 'Gunakan hak pilih Anda.',
        'date' => '2026-10-15',
        'start_time' => '08:00',
        'end_time' => '14:00',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('candidates')->insert([
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Ahmad Dahlan',
        'vice_chairman_name' => 'Siti Walidah',
        'vision' => 'Menjadikan kampus yang mandiri dan berintegritas.',
        'mission' => "1. Meningkatkan riset\n2. Pemberdayaan mahasiswa",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('landing.election', 'pemira-2026'));
    $response->assertOk();
    $response->assertSee('Pemilihan Raya Mahasiswa 2026');
    $response->assertSee('Ahmad Dahlan');
    $response->assertSee('Siti Walidah');
    $response->assertSee('Lihat Visi &amp; Misi', false);
    $response->assertSee('Menjadikan kampus yang mandiri dan berintegritas.');
});

test('admin election detail paslon tab displays both candidate photos', function () {
    $admin = User::forceCreate([
        'name' => 'Admin Test',
        'email' => 'admin_detail_tab@example.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
        'is_active' => 1,
    ]);

    $electionId = DB::table('elections')->insertGetId([
        'name' => 'Pemilihan Ketua BEM',
        'slug' => 'pemilihan-ketua-bem',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('candidates')->insert([
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Bambang',
        'vice_chairman_name' => 'Siti',
        'photo_path' => 'candidates/chairman.webp',
        'vice_chairman_photo_path' => 'candidates/vice.webp',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.elections.detail', ['id' => $electionId, 'tab' => 'paslon']));
    $response->assertOk();
    $response->assertSee('Bambang');
    $response->assertSee('Siti');
    $response->assertSee('Foto Ketua: Bambang');
    $response->assertSee('Foto Wakil: Siti');
});
