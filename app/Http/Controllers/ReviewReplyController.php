<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Data\ReviewReply\ReviewReplyData;
use App\Actions\ReviewReply\ReviewReplyAction;
use App\Models\Delivery\DeliveryFeedback;

class ReviewReplyController extends Controller
{
    public function getAllReview()
    {
        $reviews=(new ReviewReplyData())->getData();
        return view('admin.reviewreply.index',compact('reviews'));
    }

    public function getAllDeliveryFeedbacks()
    {
        $feedbacks = DeliveryFeedback::latest()->paginate(20);
        
        return view('admin.reviewreply.delivery-feedback',compact('feedbacks'));
    }

    public function getReview(Request $request)
    {
        $response=(new ReviewReplyData())->getReviewData($request);
        return response()->json($response,200);
    }

    public function sendReview(Request $request)
    {
        DB::beginTransaction();
        try{
            (new ReviewReplyAction($request))->store();
            DB::commit();
            $request->session()->flash('success','Review Reply Successfully !!');
            $response=[
                'error'=>false,
                'msg'=>'Success !!'
            ];
           
        }catch(\Throwable $th)
        {
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            $response=[
                'error'=>true,
                'msg'=>$th->getMessage()
            ];
            
        }

        return response()->json($response,200);
    }

    public function filterReview(Request $request)
    {
        // dd($request->all());
        if($request->productId !=0)
        {
            $reviews = Review::where('product_id',$request->productId)->whereNull('parent_id')->where('status',1)->orderBy('id','DESC')->get();
            $data=[];
            $final=[];
            

            switch($request->star)
            {
                case '0':
                    $data=$reviews;
                    break;
                case '1':
                    $data=collect($reviews)->where('rating','1');
                    break;

                case '2':
                    $data=collect($reviews)->where('rating','2');
                    break;

                case '3':
                    $data=collect($reviews)->where('rating','3');
                    break;
                case '4':
                    $data=collect($reviews)->where('rating','4');
                    break;
                case '5':
                    $data=collect($reviews)->where('rating','5');
                    break;

            }

            // dd($data->toArray());
            switch($request->sort)
            {
                case 'relevance':
                    $final=$data;
                    break;
                case 'recent':
                    $final=collect($data)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                    break;

                case 'high':
                    $final=collect($data)->sortByDesc('rating',SORT_NATURAL|SORT_FLAG_CASE);
                    break;

                case 'low':
                    $final=collect($data)->sortBy('rating',SORT_NATURAL|SORT_FLAG_CASE);
                    break;

            }
            return view('frontend.reviewfilter')->with('reviews',$final);
        }
       
    }

    public function updateReviewStatus(Request $request)
    {
        $review = Review::find($request->review_id);
        if($review)
        {
            $review->status = $request->status;
            $review->save();
            return response()->json(['status'=>200, 'message'=>'Status Updated successfully']);
        }
        return response()->json(['status'=>404, 'message'=>'Review not found']);
    }
}
