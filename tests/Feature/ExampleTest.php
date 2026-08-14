<?php

use App\Constants\UserConst;
use App\Models\User;

test('the application redirects unauthenticated users to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

test('the application redirects authenticated superadmin to admin dashboard', function () {
    $user = User::factory()->make(['id' => 1, 'access_type' => UserConst::SUPERADMIN]);

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirect(route('admin.dashboard'));
});
