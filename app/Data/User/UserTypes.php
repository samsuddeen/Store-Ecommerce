<?php
namespace App\Data\User;

class UserTypes
{
    public function getData()
    {
        $guards = config('auth.guards');
        $final_guards = [];
        foreach($guards as $index=>$guard){
            if($guard['provider'] !== null){
                $final_guards[$guard['provider']] = [
                    "guard" => $index,
                    "driver" => $guard['driver'],
                ];
            }
        }
        $providers = config('auth.providers');
        $final_data = collect($providers)->map(function($row, $index) use($final_guards){
            return [
                'guard'=>$final_guards[$index]['guard'],
                'name'=>($final_guards[$index]['guard'] !== 'web') ? ucfirst($final_guards[$index]['guard']) : 'Super/Admin/Other',
                'model'=>$row['model'],
                'driver'=>$final_guards[$index]['driver'],
            ];
        })->toArray();
        return $final_data;
    }
}