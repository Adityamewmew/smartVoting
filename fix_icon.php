<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$menus = DB::table('sidebar_menus')->get();
foreach ($menus as $m) {
    echo "{$m->id} - {$m->label} - icon:{$m->icon}\n";
}

// Fix Bilik Suara icon
DB::table('sidebar_menus')->where('label', 'Bilik Suara')->update([
    'icon' => '_admin._layout.icons.sidebar.dashboard',
]);

cache()->flush();
echo "Fixed icon for Bilik Suara.\n";
