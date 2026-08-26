<?php

namespace Database\Seeders;

use App\Constants\DatabaseConst;
use App\Constants\UserConst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidebarMenuSeeder extends Seeder
{
    /**
     * Seed sidebar_menu_groups, sidebar_menus, and sidebar_menu_accesses tables.
     */
    public function run(): void
    {
        DB::table(DatabaseConst::SIDEBAR_MENU_GROUP())->truncate();
        DB::table(DatabaseConst::SIDEBAR_MENU())->truncate();
        DB::table(DatabaseConst::SIDEBAR_MENU_ACCESS())->truncate();

        $now = now();

        // =====================================================================
        // GROUPS
        // =====================================================================
        DB::table(DatabaseConst::SIDEBAR_MENU_GROUP())->insert([
            ['key' => 'utama',      'label' => 'Menu Utama', 'color' => 'blue', 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pengaturan', 'label' => 'Pengaturan', 'color' => 'gray', 'sort_order' => 20, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $superadmin = UserConst::SUPERADMIN;
        $operator = UserConst::OPERATOR;
        $superadminOnly = [$superadmin];
        $allRoles = [$superadmin, $operator];

        // =====================================================================
        // GROUP: utama (Dashboard & Pemilihan)
        // =====================================================================
        $dashboardId = DB::table(DatabaseConst::SIDEBAR_MENU())->insertGetId([
            'label' => 'Dashboard',
            'route_name' => 'admin.dashboard',
            'icon' => '_admin._layout.icons.sidebar.dashboard',
            'group' => 'utama',
            'sort_order' => 10,
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->assignAccess($dashboardId, $allRoles, $now);

        $electionsId = DB::table(DatabaseConst::SIDEBAR_MENU())->insertGetId([
            'label' => 'Pemilihan',
            'route_name' => 'admin.elections.index',
            'icon' => '_admin._layout.icons.sidebar.data_master',
            'group' => 'utama',
            'sort_order' => 20,
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->assignAccess($electionsId, $allRoles, $now);

        // =====================================================================
        // GROUP: pengaturan (Pengguna)
        // =====================================================================
        $penggunaId = DB::table(DatabaseConst::SIDEBAR_MENU())->insertGetId([
            'label' => 'Pengguna',
            'route_name' => 'admin.users.index',
            'icon' => '_admin._layout.icons.sidebar.user',
            'group' => 'pengaturan',
            'sort_order' => 10,
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->assignAccess($penggunaId, $superadminOnly, $now);

    }

    /**
     * Insert access records for a given sidebar menu item.
     *
     * @param  array<int>  $accessTypes
     */
    private function assignAccess(int $sidebarMenuId, array $accessTypes, mixed $now): void
    {
        $inserts = array_map(fn ($type) => [
            'sidebar_menu_id' => $sidebarMenuId,
            'access_type' => $type,
            'created_at' => $now,
        ], $accessTypes);

        DB::table(DatabaseConst::SIDEBAR_MENU_ACCESS())->insert($inserts);
    }
}
