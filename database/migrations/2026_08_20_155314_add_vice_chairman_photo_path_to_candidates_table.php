<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (! Schema::hasColumn('candidates', 'vice_chairman_photo_path')) {
                $table->string('vice_chairman_photo_path')->nullable()->after('photo_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (Schema::hasColumn('candidates', 'vice_chairman_photo_path')) {
                $table->dropColumn('vice_chairman_photo_path');
            }
        });
    }
};
