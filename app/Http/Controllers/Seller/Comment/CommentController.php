<?php

namespace App\Http\Controllers\Seller\Comment;

use DataTables;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerReviewRequest;

class CommentController extends Controller
{
    protected $question_answer=null;
    public function __construct(QuestionAnswer $question_answer)
    {
        $this->question_answer=$question_answer;
    }
    public function getComment(Request $request)
    {
        $reviews=QuestionAnswer::where('seller_id',auth()->guard('seller')->user()->id)->where('status','1')->whereNull('parent_id')->get();
        $reviews=collect($reviews)->sortByDesc('id');

        $comment=QuestionAnswer::where('seller_id',auth()->guard('seller')->user()->id)->where('status','1')->whereNull('parent_id')->get();
        $comment=collect($comment)->sortByDesc('id');
        if($request->ajax())
        {
            $comment=QuestionAnswer::where('seller_id',auth()->guard('seller')->user()->id)->where('status','1')->whereNull('parent_id')->get();
            $comment = $comment->sortByDesc('id'); 
            $n=0;
            return Datatables::of($comment,$n)
                            ->addIndexColumn()
                            ->addColumn('id', function ($row) use (&$n){
                                $n++; // Increment $n for each row
                                return $n;
                            })
                            ->addColumn('product_name',function($row){
                                $product='';
                               if(isset($row->product))
                               {
                                $product.='<a href="'.route('seller-product.show',$row->product->id ?? 0).'">'.$row->product->name.'</a>';
                               }
                               else
                               {
                                $product.='<a href="'.route('seller-product.show',$row->product->id ?? 0).'">'.''.'</a>';
                               }
                                
                                
                                return $product;
                            })
                            ->addColumn('product_image',function($row){
                                $html="";
                                if(isset($row->product))
                               {
                                $html="<img src='".$row->product->images[0]->image."'>";
                               }
                               else
                               {
                                $html="<img src='".asset('dummyimage.png')."'>";
                               }
                                
                                $html.="</img>";
                                return $html;
                            })
                            ->addColumn('recieved_on',function($row){
                                return date('Y-m-d H:i A',strtotime($row->created_at));
                            })
                            ->addColumn('action',function($row){
                                $answer=$row->answer;
                                $btn="";
                                if($answer)
                                {
                                    $btn.='<a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentSection'.$row->id.'">Edit Reply</button>';
                                }
                                else
                                {
                                    $btn.='<a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentSection'.$row->id.'">Reply</button>';
                                }
                                return $btn;
                            })
                            ->rawColumns(['id','product_name','product_image','recieved_on','action'])
                            ->make(true);
                            
        }
        
        return view('admin.seller.comment.index')->with('reviews',$reviews);
    }

    public function sendComment(Request $request)
    {
        // $data=$request->all();
        // $comment=QuestionAnswer::where('id',$request->comment_id)->first();
       
        // if(!$comment)
        // {
        //     return response()->json(
        //         [
        //             'error'=>true,
        //             'msg'=>'Something Went Wrong !!'
        //         ]
        //     );
        // }
        // $product=Product::where('id',$comment->product_id)->first();
        // if(!$product)
        // {
        //     return response()->json(
        //         [
        //             'error'=>true,
        //             'msg'=>'Something Went Wrong !!'
        //         ]
        //     );
        // }
        // DB::beginTransaction();
        // try{
        //     $data['product_id']=$product->id;
        //     $data['parent_id']=$comment->id;
        //     $data['customer_id']=$comment->customer_id;
        //     $data['question_answer']=$data['message'];
        //     $data['seller_id']=auth()->guard('seller')->user()->id;
        //     $this->question_answer->fill($data);
        //     $this->question_answer->save();
        //     DB::commit();
        //     return response()->json(
        //         [
        //             'error'=>false,
        //             'msg'=>'Comment Sent Successfully !!'
        //         ]
        //     );
        // }catch(\Exception $ex){

        //     return response()->json(
        //         [
        //             'error'=>true,
        //             'msg'=>$ex->getMessage()
        //         ]
        //     );
        // }
        $data=$request->all();
        $comment=QuestionAnswer::where('id',$request->comment_id)->first();
        if(!$comment)
        {
            return response()->json(
                [
                    'error'=>true,
                    'msg'=>'Something Went Wrong !!'
                ]
            );
        }
        $product=Product::where('id',$comment->product_id)->first();
        if(!$product)
        {
            return response()->json(
                [
                    'error'=>true,
                    'msg'=>'Something Went Wrong !!'
                ]
            );
        }
        // dd('test');
        DB::beginTransaction();
        try{
            $data['product_id']=$product->id;
            $data['parent_id']=$comment->id;
            $data['customer_id']=$comment->customer_id;
            $data['question_answer']=$data['message'];
            $data['seller_id']=auth()->user()->id;
            $this->question_answer->fill($data);
            $this->question_answer->save();
            DB::commit();
            return response()->json(
                [
                    'error'=>false,
                    'msg'=>'Comment Sent Successfully !!'
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

    public function sendUpdateComment(Request $request)
    {
      
        $this->question_answer=QuestionAnswer::where('id',$request->seller_comment_id)->first();
       
        if(!$this->question_answer)
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
            $data['question_answer']=$request->message;
            $this->question_answer->fill($data);
            $this->question_answer->save();
            DB::commit();
            return response()->json(
                [
                    'error'=>false,
                    'msg'=>'Comment Updated Successfully !!'
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
