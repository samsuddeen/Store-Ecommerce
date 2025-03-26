<?php

namespace App\Http\Controllers\Seller;

use DataTables;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerReviewRequest;

class SellerReviewController extends Controller
{
    protected $review=null;
    public function __construct(Review $review)
    {
        $this->review=$review;
    }

    public function reviewList(Request $request)
    {
        $reviews=Review::where('seller_id',auth()->guard('seller')->user()->id)->whereNull('parent_id')->distinct()->get()->unique('product_id');
        if($request->ajax())
        {
            $reviews=Review::where('seller_id',auth()->guard('seller')->user()->id)->whereNull('parent_id')->distinct()->get()->unique('product_id');
           
            return Datatables::of($reviews)
                            ->addIndexColumn()
                            ->addColumn('id', function ($row) {
                                return $row->id;
                            })
                            ->addColumn('product_name',function($row){
                                return $row->product->name ?? null;
                            })
                            ->addColumn('product_image',function($row){
                                $html="";
                                if($row->product && completedOrderImage($row->product->images))
                                {
                                    $html="<img src='".completedOrderImage($row->product->images)."'>";
                                }
                                else
                                {
                                    $html="<img src=''>";
                                }
                               
                                $html.="</img>";
                                return $html ?? '';
                            })
                            ->addColumn('recieved_on',function($row){
                                return date('Y-m-d H:i A',strtotime($row->created_at));
                            })
                            ->addColumn('action',function($row){
                                $btn="";
                                $btn.='<a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop'.$row->id.'">View Message</button>';
                                return $btn;
                            })
                            ->rawColumns(['id','product_name','product_image','recieved_on','action'])
                            ->make(true);
        }
        return view('admin.seller.review.index')->with('reviews',$reviews);
    }

    public function sendReview(SellerReviewRequest $request)
    {
        
        $data=$request->all();
        $review=Review::where('id',$request->review_id)->first();
       
        if(!$review)
        {
            return response()->json(
                [
                    'error'=>true,
                    'msg'=>'Something Went Wrong !!'
                ]
            );
        }
        $product=Product::where('id',$review->product_id)->first();
        if(!$product)
        {
            return response()->json(
                [
                    'error'=>true,
                    'msg'=>'Something Went Wrong !!'
                ]
            );
        }
        DB::beginTransaction();
        try{
            $data['rating']=5;
            $data['product_id']=$product->id;
            $data['parent_id']=$review->id;
            $data['user_id']=$review->user_id;
            $data['seller_id']=auth()->guard('seller')->user()->id;
            $this->review->fill($data);
            $this->review->save();
            DB::commit();
            return response()->json(
                [
                    'error'=>false,
                    'msg'=>'Review Sent Successfully !!'
                ]
            );
        }catch(\Exception $ex){

            return response()->json(
                [
                    'error'=>true,
                    'msg'=>$ex->getMessage()
                ]
            );
        }
    }
}


