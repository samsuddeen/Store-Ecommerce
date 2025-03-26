<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuFilterController extends Controller
{
    public function filter(Request $request)
    {
        // dd('ok');
        $menus = Menu::get()->toTree();
        return view('admin.menu.menuFilter', compact('menus'));
    }

    public function categoryFilter(Request $request)
    {
        parse_str($request->sort, $arr);
        foreach ($arr['test'] as $key => $value) {
            Menu::where('id', $key)
                ->update(['parent_id' => (int) $value]);
        }
        Menu::fixTree();
        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Meneu Updated'
        ], 200);
    }


    public function updateOrder(Request $request)
    {
        // dd($request->all());
        $order = $request->input('order');
        foreach ($order as $key => $id) {
            $menu=Menu::where('id', $id)->first();
            $menu->position=$key;
            $menu->save();
        }
        return response()->json(['success' => true]);
    }


}
