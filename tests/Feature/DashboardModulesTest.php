<?php

use App\Constants\UserConst;
use App\Models\User;

test('admin dashboard renders successfully for superadmin', function () {
    $user = User::factory()->make(['id' => 1, 'access_type' => UserConst::SUPERADMIN]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Dasbor Pemantauan')
        ->assertSee('Sesi Bilik Suara Terkini');
});
