<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$menus = DB::table('sidebar_menus')->get();
echo "MENUS:\n";
foreach ($menus as $m) {
    echo "{$m->id} - {$m->label} - group:{$m->group} - is_active:{$m->is_active}\n";
}

$access = DB::table('sidebar_menu_accesses')->get();
echo "\nACCESS:\n";
foreach ($access as $a) {
    echo "id:{$a->id} - menu_id:{$a->sidebar_menu_id} - type:{$a->access_type}\n";
}
