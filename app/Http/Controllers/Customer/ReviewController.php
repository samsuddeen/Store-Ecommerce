<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enum\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use App\Http\Requests\CustomerReviewRequest;
use App\Actions\Notification\NotificationAction;
use App\Helpers\PaginationHelper;
class ReviewController extends Controller
{
    protected $review = null;
    public function __construct(Review $review)
    {
        $this->review = $review;
    }
    public function review()
    {
        $orders = Order::where('user_id', auth()->guard('customer')->user()->id)->where('status', OrderStatusEnum::DELIVERED)->get();
        $order_products = [];
        foreach ($orders as $order) {
            foreach ($order->orderAssets as $product) {
                $order_products[] = $product;
            }
        }
        $order_products = collect($order_products)->sortByDesc('created_at');

        $url = route('Creview');
        $order_products = PaginationHelper::paginate(collect($order_products), 10)->withPath($url);
        return view('frontend.customer.review', compact('order_products'));
    }

    public function getRejectedReason(Request $request)
    {
        $return =ReturnOrder::where('id',$request->returnId)->first();
        if(!$return)
        {
            $response=[
                'error'=>true,
                'data'=>null,
            ];
    
            return response()->json($response,200);
        }
        $data=$return->refundData->orderStatus->remarks ?? null;

        $response=[
            'error'=>false,
            'data'=>$data,
        ];

        return response()->json($response,200);
        
    }

    public function save(CustomerReviewRequest $request)
    {
        
        $data = $request->all();

        if ($request->product_image) {
            $temp = [];
            foreach ($request->product_image as $image) {
                $image = uploadImage($image, 'review', '50x50');
                if ($image) {
                    $temp[]['title'] = $image;
                }
            }

            $data['image'] = json_encode($temp);
        }

        DB::beginTransaction();
        try {
            $product = Product::find($request->product_id);
            // if ($product->seller_id != null) {
            //     $seller_id = $product->seller_id ?? User::first()->id;
            // } else {
            //     $seller_id = User::first()->id;
            // }

            if (!auth()->guard('customer')->user()) {
                request()->session()->flash('error', 'Plz Login first !!');
                return redirect()->back();
            }
            $data['user_id'] = auth()->guard('customer')->user()->id;
            // $data['seller_id'] = $seller_id ?? 1;
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
            DB::commit();
            $request->session()->flash('success', 'Thanks For Rating in Our Product.');

            $notification_data = [
                'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                'from_id' => auth()->guard('customer')->user()->id,
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
            //         'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
            //         'from_id' => auth()->guard('customer')->user()->id,
            //         'to_model' => get_class(Seller::getModel()) ?? null,
            //         'to_id' => $product->seller_id,
            //         'title' => 'New Comment From Customer On Product ',
            //         'summary' => 'Comment On Product',
            //         'url' => route('replyreview.index'),
            //         'is_read' => false,
            //     ];
            //     (new NotificationAction($notification_data))->store();
            // }
            return redirect()->back();
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'OOPs, Please Try Again !!!');
            DB::rollBack();
            return back();
        }
    }
}
