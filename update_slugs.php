<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

$elections = DB::table('elections')->get();
foreach ($elections as $e) {
    DB::table('elections')->where('id', $e->id)->update(['slug' => Str::slug($e->name).'-'.$e->id]);
}
echo 'Done';
