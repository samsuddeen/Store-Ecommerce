<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdvertisementPosition extends Model
{
    use HasFactory;

    public function ads()
    {
        return $this->hasOne(Advertisement::class,'id','advertisement_id')
                    ->select(['id','title','url','image','mobile_image'])
                    ->where('status','active')
                    ->where('ad_type','General')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                              ->from('advertisement_positions')
                              ->whereColumn('advertisements.id', 'advertisement_positions.advertisement_id');
                    });
    }
}

