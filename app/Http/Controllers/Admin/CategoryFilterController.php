<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryFilterController extends Controller
{
    public function filter(Request $request)
    {
        $categories = Category::get()->toTree();
        return view('admin.category.categoryFilter', compact('categories'));
    }

    public function categoryFilter(Request $request)
    {

        parse_str($request->sort, $arr);
        foreach ($arr['test'] as $key => $value) {
            Category::where('id', $key)
                ->update(['parent_id' => (int) $value]);
        }
        Category::fixTree();
        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Category Updated'
        ], 200);
    }
}
