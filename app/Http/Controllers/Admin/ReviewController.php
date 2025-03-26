<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::get();
        return view('admin.review.index',compact('reviews'));
    }

    public function destroy($id){
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'This review deleted succefully');
    }
}
