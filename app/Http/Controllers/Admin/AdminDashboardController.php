<?php

namespace App\Http\Controllers\Admin;

use App\Data\Dashboard\DashboardData;
use App\Http\Controllers\Controller;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Models\Task\Task;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('seller')->user() != null) {
            return redirect()->route('sellerDashboard');
        } else {
            $filters = (new FilterData($request))->getData();
            $data['filters'] =  $filters;
            $type = $request->query('type');
            $data = (new DashboardData)->getData($type);
            // dd($data);
            return view('welcome', $data);
        }
    }

    public function updateModeColor(Request $request)
    {
      if($request->darkCheck==='true')
      {
        $darkModeArray=[
            'htmlValue'=>'dark-layout',
            'navValue'=>'navbar-dark',
            'mainValue'=>'menu-dark',
            'darkClassValue'=>'darkModeClass'
        ];

      }
      else
      {
        $darkModeArray=[
            'htmlValue'=>'',
            'navValue'=>'',
            'mainValue'=>'',
            'darkClassValue'
        ];
      }
      $request->session()->put('DarkModeValue',$darkModeArray);
      return response()->json( $request->session()->get('DarkModeValue'),200);

        
    }
}
