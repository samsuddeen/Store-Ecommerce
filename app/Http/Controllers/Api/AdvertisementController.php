<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Advertisement,
    AdvertisementPosition
};

class AdvertisementController extends Controller
{
    protected $advertisementpostion = null;

    public function __construct(AdvertisementPosition $advertisementpostion)
    {
        $this->advertisementpostion = $advertisementpostion;
    }


    public function ads()
    {   
        $this->advertisementpostion = AdvertisementPosition::orderBy('position_id', 'ASC')
        ->with(['ads' => function ($query) {
            $query->select(['id', 'title', 'url', 'image', 'mobile_image'])
                ->where('status', 'active')
                ->where('ad_type', 'General');
        }])
        ->whereHas('ads')
        ->take(4)
        ->get();
                
        $this->advertisementpostion->makeHidden([
            "created_at",
            "updated_at",
        ]);

        $response = [
            'error' => false,
            'data' => $this->advertisementpostion,
            'msg' => 'Ads List'
        ];

        return response()->json($response, 200);
    }

    public function skipAd()
    {
        $skipAd = Advertisement::select('mobile_image')->where('status','active')->where('ad_type','Skip Ad')->latest()->get();
        $response = [
            'error' => false,
            'data' => $skipAd,
            'msg' => 'Skip Ad'
        ];
        return response()->json($response, 200);
        
    }
}
