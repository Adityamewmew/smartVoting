<?php

namespace Database\Seeders;

use App\Constants\UserConst;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Platform Superadmin Only
        User::updateOrCreate(
            ['email' => 'superadmin@smartvoting.id'],
            [
                'name' => 'Platform Superadmin',
                'password' => Hash::make('password'),
                'access_type' => UserConst::PLATFORM_SUPERADMIN, // 0
                'institution_id' => null,
                'is_active' => 1,
            ]
        );
    }
}
