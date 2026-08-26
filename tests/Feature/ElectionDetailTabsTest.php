<?php

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use App\Models\User;
use Database\Seeders\SidebarMenuSeeder;
use Illuminate\Database\Schema\Blueprint;
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
});

test('election detail renders both paslon and results tabs', function () {
    $user = User::create([
        'name' => 'Admin Detail Test',
        'email' => 'admin_detail_'.uniqid().'@test.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
    ]);
    $this->actingAs($user);

    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Tab Test',
        'slug' => 'pemilihan-tab-test-'.uniqid(),
        'date' => '2026-10-10',
        'start_time' => '2026-10-10 08:00:00',
        'end_time' => '2026-10-10 16:00:00',
        'status' => 'draft',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table(DatabaseConst::CANDIDATES())->insert([
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Calon Ketua 1',
        'vice_chairman_name' => 'Calon Wakil 1',
        'vision' => 'Visi Tab Test',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('admin.elections.detail', $electionId));
    $response->assertStatus(200)
        ->assertSee('Pasangan Calon (Paslon)')
        ->assertSee('Hasil &amp; Detail Pemilihan', false)
        ->assertSee('Calon Ketua 1')
        ->assertSee('Tambah Paslon');

    // Clean up
    DB::table(DatabaseConst::CANDIDATES())->where('election_id', $electionId)->delete();
    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});

test('candidate crud redirects back to election detail tab paslon', function () {
    Storage::fake('public');
    $user = User::create([
        'name' => 'Admin Candidate CRUD Test',
        'email' => 'admin_crud_'.uniqid().'@test.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
    ]);
    $this->actingAs($user);

    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan CRUD Redirect',
        'slug' => 'pemilihan-crud-redirect-'.uniqid(),
        'date' => '2026-10-10',
        'start_time' => '2026-10-10 08:00:00',
        'end_time' => '2026-10-10 16:00:00',
        'status' => 'draft',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Test Create
    $response = $this->post(route('admin.candidates.create'), [
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Ketua Baru',
        'vice_chairman_name' => 'Wakil Baru',
        'vision' => 'Visi Baru',
        'mission' => 'Misi Baru',
    ]);

    $response->assertRedirect(route('admin.elections.detail', ['id' => $electionId, 'tab' => 'paslon']));

    $candidate = DB::table(DatabaseConst::CANDIDATES())
        ->where('election_id', $electionId)
        ->first();

    expect($candidate)->not->toBeNull()
        ->and($candidate->chairman_name)->toBe('Ketua Baru');

    // Test Update
    $updateResponse = $this->post(route('admin.candidates.doUpdate', $candidate->id), [
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Ketua Diedit',
        'vice_chairman_name' => 'Wakil Diedit',
        'vision' => 'Visi Diedit',
        'mission' => 'Misi Diedit',
    ]);

    $updateResponse->assertRedirect(route('admin.elections.detail', ['id' => $electionId, 'tab' => 'paslon']));

    // Test Delete (HTTP DELETE)
    $deleteResponse = $this->delete(route('admin.candidates.delete', $candidate->id));
    $deleteResponse->assertRedirect(route('admin.elections.detail', ['id' => $electionId, 'tab' => 'paslon']));

    // Clean up
    DB::table(DatabaseConst::CANDIDATES())->where('election_id', $electionId)->delete();
    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});
