<?php
namespace App\Http\Controllers;

use View;
use App\Models\User;
use App\Models\footer;
use App\Models\header;
use App\Models\Setting;
use App\Models\Category;
use App\Models\footertitle;
use App\Models\Advertisement;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function __construct()
  {
    //its just a dummy data object.
    $user = User::all();
    $advertisements = Advertisement::get();
    $categories = Category::where('parent_id','=',NULL)->withCount(['ancestors', 'descendants'])->take(16)->with('children')->get();
    $settings = Setting::get();


    // dd($footers);

    // Sharing is caring
    View::share(['user'=> $user,
                 'settings'=>$settings,
                 'categories'=>$categories,
                 'advertisements'=>$advertisements,
                ]);
  }

  
}
