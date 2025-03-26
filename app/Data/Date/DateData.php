<?php
namespace App\Data\Date;

use App\Models\Transaction\Transaction;
use DateTime;
use Carbon\Carbon;
use App\Models\User;

class DateData
{
    function __construct($filters=null)
    {
        
    }
    public function getYears()
    {
        $last_date = User::orderBy('created_at')->first()->created_at ?? Carbon::now();
        $first_year = $last_date->format('Y');
        $years =[];
        for($i=$first_year; $i<=Carbon::now()->format('Y'); $i++){
            $years[] = $i;
        }
        return $years;
    }
    public function getMonths()
    {
        $mopnths = [];
        for($i=1; $i<=12; $i++){
            $dateObj   = DateTime::createFromFormat('!m', $i);
            $monthName = $dateObj->format('F');
            $months[] = [
                'title'=>$monthName,
                'value'=>$i,
            ];
        }
        return $months;
    }

//     public function getYearsTable()
//     {
//         $array = [];
//         $sales = Transaction::get();
//         foreach($sales as $payments)
//         {
//             array_push($array , $payments->created_at->format('Y'));

//         }
//   return array_unique($array);
//     }

}