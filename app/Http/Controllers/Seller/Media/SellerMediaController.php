<?php

namespace App\Http\Controllers\Seller\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerMediaController extends Controller
{
    //
    public function index()
    {
        return view('seller.media.index');
    }
}
