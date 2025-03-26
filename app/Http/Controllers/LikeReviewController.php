<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LikeReview;
use App\Models\LikeReviewReply;
use App\Models\ReviewReply;

class LikeReviewController extends Controller
{
    public function likeReview(Request $request)
    {
        $user=auth()->guard('customer')->user();
        if(!$user)
        {
            $request->session()->flash('error','Plz Login First !!');
            $response=[
                'login'=>true,
                'url'=>route('Clogin')
            ];
            return response()->json($response,200);
        }
        $review=Review::where('id',$request->reviewId)->first();
       

        if(!$review)
        {
            $response=[
                'error'=>true,
            ];
            return response()->json($response,200);
        }

        $dataLike=LikeReview::where('review_id',$review->id)->where('user_id',$user->id)->first();
        if($dataLike)
        {
            $dataLike->delete();
            
            $response=[
                'exist'=>true,
                'count'=>count(LikeReview::where('review_id',$review->id)->get()),
                'msg'=>'Comment Unliked !!',

            ];
            return response()->json($response,200);
        }

        DB::beginTransaction();
        try{

            $likeReview=LikeReview::create(
                [
                    'review_id'=>$review->id,
                    'user_id'=>$user->id
                ]
            );
            DB::commit();
            
            $response=[
                'error'=>false,
                'count'=>count(LikeReview::where('review_id',$review->id)->get()),
                'msg'=>'Comment Liked !!'
            ];
            return response()->json($response,200);
        }catch(\Throwable $th)
        {
            DB::rollBack();
           
            $response=[
                'error'=>true,
            ];
            return response()->json($response,200);
        }
    }

    public function likeReviewReply(Request $request)
    {
        
        $user=auth()->guard('customer')->user();
       
        if(!$user)
        {
            $request->session()->flash('error','Plz Login First !!');
            $response=[
                'login'=>true,
                'url'=>route('Clogin')
            ];
            return response()->json($response,200);
        }
        $review=ReviewReply::where('id',$request->reviewReplyId)->first();
        // dd($request->reviewReplyId);
        if(!$review)
        {
            $response=[
                'error'=>true,
            ];
            return response()->json($response,200);
        }

        $dataLike=LikeReviewReply::where('review_reply_id',$review->id)->where('user_id',$user->id)->first();
        // dd($dataLike);
        if($dataLike)
        {
            $dataLike->delete();
            
            $response=[
                'exist'=>true,
                'count'=>count(LikeReviewReply::where('review_reply_id',$review->id)->get()),
                'msg'=>'Comment Unliked !!',

            ];
            return response()->json($response,200);
        }

        DB::beginTransaction();
        try{

            $likeReview=LikeReviewReply::create(
                [
                    'review_reply_id'=>$review->id,
                    'user_id'=>$user->id
                ]
            );
            // dd('ok');
            DB::commit();
            
            $response=[
                'error'=>false,
                'count'=>count(LikeReviewReply::where('review_reply_id',$review->id)->get()),
                'msg'=>'Comment Liked !!'
            ];
            return response()->json($response,200);
        }catch(\Throwable $th)
        {
            DB::rollBack();
            
            $response=[
                'error'=>true,
            ];
            return response()->json($response,200);
        }

    }
}
