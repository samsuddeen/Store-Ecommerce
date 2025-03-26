<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('admin.media.index');
    }
}
