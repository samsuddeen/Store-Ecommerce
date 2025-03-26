<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionTableSeeder extends Seeder
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
        
        DB::table('role_has_permissions')->truncate();
        $permissions = Permission::orderBy('id')->get();
        $seller_permissions = Permission::orderBy('id')->where('guard_name', 'seller')->get();
        DB::beginTransaction();
        try {
            foreach($permissions as $permission){
                $data1 = [
                    'permission_id'=>$permission->id,
                    'role_id'=>2
                ];
                DB::table('role_has_permissions')->insert($data1);
               
            }
            foreach($seller_permissions as $permission){
                $data2 = [
                    'permission_id'=>$permission->id,
                    'role_id'=>3
                ];
                DB::table('role_has_permissions')->insert($data2);
            }

            DB::commit();
        } catch (\Throwable $th) {
           DB::rollBack();
        }
        Schema::enableForeignKeyConstraints();
    }
}
