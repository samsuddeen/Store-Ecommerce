<?php
namespace App\Data\ReviewReply;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewReplyData{

    protected $request;
    
    public function getData()
    {
        $data=Review::orderBy('created_at','DESC')->paginate(10);
        return $data;
    }

    public function getReviewData(Request $request)
    {
        $review=Review::where('id',$request->reviewId)->first();
        if(!$review)
        {
            $response=[
                'error'=>true,
                'data'=>null
            ];
        }
        else
        {
            $data=[
                'message'=>$review->message,
                'image'=>json_decode($review->image)
            ];
    
            $response=[
                'error'=>false,
                'data'=>$data
            ];
        }
        

        return $data;
    }
}