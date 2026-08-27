<?php

namespace Database\Seeders;

use App\Constants\DatabaseConst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DefaultInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('institutions')) {
            DB::table(DatabaseConst::INSTITUTION())->updateOrInsert(
                ['id' => 1],
                [
                    'name' => 'SMK Negeri 1 Demo',
                    'type' => 'school',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'institution_id')) {
            DB::table(DatabaseConst::USER())->whereNull('institution_id')->where('access_type', '!=', 0)->update(['institution_id' => 1]);
        }
        if (Schema::hasTable('elections') && Schema::hasColumn('elections', 'institution_id')) {
            DB::table(DatabaseConst::ELECTIONS())->where('institution_id', 0)->update(['institution_id' => 1]);
        }
        if (Schema::hasTable('candidates') && Schema::hasColumn('candidates', 'institution_id')) {
            DB::table(DatabaseConst::CANDIDATES())->where('institution_id', 0)->update(['institution_id' => 1]);
        }
        if (Schema::hasTable('voting_sessions') && Schema::hasColumn('voting_sessions', 'institution_id')) {
            DB::table(DatabaseConst::VOTING_SESSIONS())->where('institution_id', 0)->update(['institution_id' => 1]);
        }
        if (Schema::hasTable('votes') && Schema::hasColumn('votes', 'institution_id')) {
            DB::table(DatabaseConst::VOTES())->where('institution_id', 0)->update(['institution_id' => 1]);
        }
    }
}
