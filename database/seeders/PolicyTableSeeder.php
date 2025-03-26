<?php

namespace Database\Seeders;

use App\Data\Policy\ArrayPolicy\PolicyData;
use App\Models\Policy\AppPolicy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PolicyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::table('app_policies')->truncate();
        DB::table('app_policy_assists')->truncate();
        DB::beginTransaction();
            try {

                $policy = AppPolicy::create([
                    'created_by'=>1,
                    'year'=>Carbon::now()->format('Y'),
                ]);
                $this->storeAssist($policy);
                DB::commit();
            } catch (\Throwable $th) {
                info($th->getMessage());
            }
        Schema::enableForeignKeyConstraints();
    }
    private function storeAssist($policy)
    {
        $policies =(new PolicyData)->getData();
        foreach($policies as $policy_enum){
            $policy->assist()->create([
                'title'=>$policy_enum['title'],
                'policy'=>'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque exercitationem impedit cum repellendus debitis! Quod, nihil.',
            ]);
        }
    }
}

