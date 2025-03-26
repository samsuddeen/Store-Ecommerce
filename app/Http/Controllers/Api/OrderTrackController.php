<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrackOrderApiRequest;
use App\Actions\TrackOrder\TrackOrderAction;

class OrderTrackController extends Controller
{
    public function getOrderTrackDetail(TrackOrderApiRequest $request)
    {
        $order_status=(new TrackOrderAction($request))->getOrderTrack();
        return response()->json($order_status, 200);
    }
}
