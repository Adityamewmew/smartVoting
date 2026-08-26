<?php

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use App\Models\User;
use Database\Seeders\SidebarMenuSeeder;
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

test('election forms render markdown editor for description', function () {
    $user = User::create([
        'name' => 'Admin Markdown Test',
        'email' => 'admin_mde_'.uniqid().'@test.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
    ]);
    $this->actingAs($user);

    // Test Add view
    $responseAdd = $this->get(route('admin.elections.add'));
    $responseAdd->assertStatus(200)
        ->assertSee('Deskripsi Pemilihan')
        ->assertSee('markdown-editor-wrapper');

    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan MDE Test',
        'slug' => 'pemilihan-mde-test-'.uniqid(),
        'description' => '**Deskripsi Tebal** dan *Miring*',
        'date' => '2026-10-10',
        'start_time' => '2026-10-10 08:00:00',
        'end_time' => '2026-10-10 16:00:00',
        'status' => 'draft',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Test Update view
    $responseUpdate = $this->get(route('admin.elections.update', $electionId));
    $responseUpdate->assertStatus(200)
        ->assertSee('Deskripsi Pemilihan')
        ->assertSee('markdown-editor-wrapper')
        ->assertSee('**Deskripsi Tebal** dan *Miring*');

    // Clean up
    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});

test('candidate forms render markdown editor for vision and mission', function () {
    $user = User::create([
        'name' => 'Admin Candidate MDE',
        'email' => 'admin_cand_mde_'.uniqid().'@test.com',
        'password' => bcrypt('password'),
        'access_type' => UserConst::SUPERADMIN,
    ]);
    $this->actingAs($user);

    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Paslon MDE',
        'slug' => 'pemilihan-paslon-mde-'.uniqid(),
        'date' => '2026-10-10',
        'start_time' => '2026-10-10 08:00:00',
        'end_time' => '2026-10-10 16:00:00',
        'status' => 'draft',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $responseAdd = $this->get(route('admin.candidates.add', ['election_id' => $electionId]));
    $responseAdd->assertStatus(200)
        ->assertSee('Visi Paslon')
        ->assertSee('Misi Paslon')
        ->assertSee('markdown-editor-wrapper');

    $candidateId = DB::table(DatabaseConst::CANDIDATES())->insertGetId([
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Ketua MDE',
        'vision' => 'Visi **Unggul**',
        'mission' => "- Poin 1\n- Poin 2",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $responseUpdate = $this->get(route('admin.candidates.update', $candidateId));
    $responseUpdate->assertStatus(200)
        ->assertSee('Visi Paslon')
        ->assertSee('Misi Paslon')
        ->assertSee('Visi **Unggul**')
        ->assertSee("- Poin 1\n- Poin 2");

    // Clean up
    DB::table(DatabaseConst::CANDIDATES())->where('id', $candidateId)->delete();
    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});

test('public landing page and visi-misi modal render markdown HTML correctly', function () {
    $electionId = DB::table(DatabaseConst::ELECTIONS())->insertGetId([
        'name' => 'Pemilihan Publik MDE',
        'slug' => 'pemilihan-publik-mde-'.uniqid(),
        'description' => 'Ini **deskripsi tebal** pemilihan.',
        'date' => '2026-10-10',
        'start_time' => '2026-10-10 08:00:00',
        'end_time' => '2026-10-10 16:00:00',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $candidateId = DB::table(DatabaseConst::CANDIDATES())->insertGetId([
        'election_id' => $electionId,
        'order_number' => 1,
        'chairman_name' => 'Ketua Publik',
        'vice_chairman_name' => 'Wakil Publik',
        'vision' => 'Menjadikan kampus **unggul** dan *berkualitas*.',
        'mission' => "* Misi satu\n* Misi dua",
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $slug = DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->value('slug');

    $response = $this->get('/'.$slug);
    $response->assertStatus(200)
        ->assertSee('<strong>deskripsi tebal</strong>', false)
        ->assertSee('<strong>unggul</strong>', false)
        ->assertSee('<em>berkualitas</em>', false)
        ->assertSee('<li>Misi satu</li>', false);

    // Clean up
    DB::table(DatabaseConst::CANDIDATES())->where('id', $candidateId)->delete();
    DB::table(DatabaseConst::ELECTIONS())->where('id', $electionId)->delete();
});
