<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Data\Date\DateData;
use App\Models\Payment\PaymentHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view("admin.payment-histories.index", $data);
    }
}
