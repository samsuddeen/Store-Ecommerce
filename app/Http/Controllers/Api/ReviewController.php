<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Product,
    Review
};
// use Dotenv\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Actions\Notification\NotificationAction;
use App\Models\Seller;
use App\Models\Order;
use App\Enum\Order\OrderStatusEnum;
use App\Models\Delivery\DeliveryFeedback;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    protected $user = null;
    protected $product = null;
    protected $review = null;

    public function __construct(User $user, Product $product, Review $review)
    {
        $this->user = $user;
        $this->product = $product;
        $this->review = $review;
    }

    public function getAllReview()
    {
        $user=\Auth::user();
        if(!$user)
        {
            $response=[
                'error'=>true,
                'msg'=>'UnAuthorized Access !!'
            ];
            return response()->json($response,200);
        }
        $orders = Order::where('user_id', $user->id)->where('status', OrderStatusEnum::DELIVERED)->latest()->get();
        $order_products = [];
        foreach ($orders as $order) {
            foreach ($order->orderAssets as $product) {
                $order_products[] = $product;
            }
        }

        // dd($order_products);

        $order_products=collect($order_products)->map(function($item)
        {
            // dd();
            if($item->product !=null)
            {
                $item->image=$item->product->images[0]->image;
            }
            
            return $item;
        });

        $response=[
            'error'=>false,
            'data'=>$order_products,
            'msg'=>'Completed Order'
        ];
        return response()->json($response,200);
    }

    public function getReview(Request $request)
    {
        $this->product = $this->product->find($request->product_id);
        $review = Review::where('product_id',$request->product_id)->whereNull('parent_id')->orderBy('id','DESC')->get();
        $review=collect($review)->each(function($item)
        {
            $value=collect(json_decode($item->image))->map(function($imageValue)
            {
                return url('Uploads/review/'.$imageValue->title);
            });
            return $item->image=$value;
        });
        foreach ($review as $user) {
            $user->user;
        }
        $review=collect($review)->each(function($item)
        {   
            return $item->answer=$item->getReviewReply;
        });

        $response = [
            'error' => false,
            'data' => $review,
            'msg' => 'User Review'
        ];

        return response()->json($response, 200);
    }

    public function addReview(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer',
                'message' => 'required|string',
                'image'=>'nullable',
                'response'=>'required:in:1,2,3'
            ]
        );

        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];

            return response()->json($response, 500);
        }
        $data = $request->all();
        
        $user= \Auth::user();
        
        if ($request->hasFile('image')) {
            $temp = [];
            foreach ($request->file('image') as $image) {
                $image = uploadImage($image, 'review', '50x50');
                if ($image) {
                    $temp[]['title'] = $image;
                }
            }

            $data['image'] = json_encode($temp);
        }

        DB::beginTransaction();
        // try {
            $product = Product::find($request->product_id);
            // if ($product->seller_id != null) {
            //     $seller_id = $product->seller_id;
            // } else {
            //     $seller_id = User::first()->id;
            // }

            if (!$user) {
                $response=[
                    'error'=>false,
                    'msg'=>'UnAuthorized Access !!'
                ];
                return response()->json($response,200);
            }

            $data['user_id'] = $user->id;
            $data['seller_id'] = User::first()->id;

            $this->review->fill($data);
            $status=$this->review->save();
            if($status)
            {
                $total_rating = null;
                $all_rating = $this->review->where('product_id', $request->product_id)->where('rating', '!=', 0)->get();
                foreach ($all_rating as $key => $product) {
                    $total_rating = $total_rating + $product->rating;
                }

                $rating = $total_rating / ($key + 1);

                $product = Product::where('id', $request->product_id)->update([
                    'rating' => $rating
                ]);
            }
            

            $notification_data = [
                'from_model' => get_class(\Auth::user()->getModel()),
                'from_id' => $user->id,
                'to_model' => get_class(User::getModel()) ?? null,
                'to_id' => User::first()->id,
                'title' => 'New Comment From Customer On Product ',
                'summary' => 'Comment On Product',
                'url' => route('replyreview.index'),
                'is_read' => false,
            ];

            (new NotificationAction($notification_data))->store();

            // if ($product->seller_id != null) {
            //     $notification_data = [
            //         'from_model' => get_class(\Auth::user()->getModel()),
            //         'from_id' => $user->i,
            //         'to_model' => get_class(Seller::getModel()) ?? null,
            //         'to_id' => $product->seller_id,
            //         'title' => 'New Comment From Customer On Product ',
            //         'summary' => 'Comment On Product',
            //         'url' => route('save.review'),
            //         'is_read' => false,
            //     ];
            //     (new NotificationAction($notification_data))->store();
            // }
            DB::commit();
            $response=[
                'error'=>false,
                'msg'=>'Review Added Successfully !!'
            ];
            return response()->json($response,200);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     $response=[
        //         'error'=>false,
        //         'msg'=>'Something Went Wrong !!'
        //     ];
        //     return response()->json($response,200);
        // }

    }

    public function userReview(Request $request)
    {
        $this->user = \Auth::user();
        if (!$this->user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Unauthorized User !!'
            ];

            return response()->json($response, 500);
        }


        $user_review = $this->review->where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->with(['user', 'product'])->get();

        $user_review->makeHidden(
            [
                "created_at",
                "updated_at",
            ]
        );

        foreach ($user_review as $user) {
            $user->user->makeHidden(
                [
                    "provider_id",
                    "created_at",
                    "updated_at"
                ]
            );
        }

        $response = [
            'error' => false,
            'data' => $user_review,
            'msg' => 'Individual User Reviews'
        ];

        return response()->json($response, 200);
    }

    public function deliveryFeedback(Request $request)
    {   
        $validate = Validator::make(
            $request->all(),
            [
                'rating' => 'required|numeric|max:5',
                'order_id' => 'required',
                'image.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            ]
        );

        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];

            return response()->json($response, 422);
        }

        $feedback_exists = DeliveryFeedback::where('order_id',$request->order_id)->where('customer_id',Auth::user()->id)->first();
        if($feedback_exists)
        {
            $response = [
                'status' => 200,
                'error' => false, 
                'msg' => 'You have already reviewed this order'
            ];

            return response()->json($response);
        }

        $input = [
            'order_id' => $request->order_id,
            'customer_id' => Auth::user()->id,
            'rating' => $request->rating,
            'message' => $request->message
        ];

        try{
            $uploadImages = [];
        
            if($request->hasFile('image'))
            {
                foreach ($request->file('image') as $image) {
                    $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $filename);
                    $uploadImages[] = $filename;
                }
    
                $imageFilenames = implode('|', $uploadImages);
                $input['image'] = $imageFilenames;
            }
            $data = DeliveryFeedback::create($input);
            
            $response = [
                'status' => 201,
                'error' => false,
                'data' => $data,
                'msg' => 'Feedback sent successfully'
            ];
            return response()->json($response);
        }catch(Exception $e)
        {
            return response()->json(['status'=>500, 'msg'=>'Something went wrong!']);
        }
    }
}
