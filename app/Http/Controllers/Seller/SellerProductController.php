<?php

namespace App\Http\Controllers\Seller;

use Throwable;
use DataTables;

use App\Models\User;
use App\Models\Color;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Data\Filter\FilterData;
use PhpParser\Node\Expr\Throw_;
use App\Data\Product\ProductData;
use App\Models\Order\OrderStatus;
use App\Actions\Trash\TrashAction;
use App\Helpers\ProductFormHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Support\Facades\Validator;
use App\Actions\Product\ProductFormAction;
use App\Http\Requests\StoreProductRequest;
use App\Actions\Product\ProductStoreAction;
use App\Actions\Seller\SellerProductAction;
use App\Http\Requests\UpdateProductRequest;
use App\Data\Product\Seller\SellerProductData;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Actions\Product\ProductDangerousAction;
use App\Actions\Notification\NotificationAction;
use App\Actions\Product\ProductAttributesAction;
use App\Actions\Product\Image\ProductImageAction;

class SellerProductController extends Controller
{
    protected $product = null;
    public function __construct(Product $product)
    {
        $this->middleware(['permission:seller-product-read'], ['only' => ['index']]);
        $this->middleware(['permission:seller-product-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:seller-product-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:seller-product-delete'], ['only' => ['destroy']]);
        $this->product = $product;
    }

    public function index(Request $request)
    {

        $request->merge(['seller_id' => auth()->guard('seller')->user()->id ?? null]);
        $filters = (new FilterData($request))->getData();
        $data['category'] = Category::get();
        $data['filters'] =  $filters;
        $productData = new ProductData($filters);
        $data['counts'] = $productData->getCount();
        $data['products'] = $productData->getData();

        LogEvent::dispatch('Product List View', 'Product List View', route('seller-product.index'));
        return view('admin.seller.product.index', $data);
    }


    public function create()
    {
        if (Auth::guard('seller')->user()->can('seller-product-create')) {
            $product = new Product();
            $data = (new ProductFormAction($product))->getData();
            return view('admin.seller.product.create', $data);
        } else {
            abort(404);
        }
    }
    public function store(StoreProductRequest $request)
    {
        // dd($request->all());
        $data = $request->all();
        DB::beginTransaction();
        try {
            $product = (new ProductStoreAction($request))->store();
            (new ProductAttributesAction($product, $data))->handle();
            (new ProductImageAction($product, $data))->handle();
            if ($request->has('dangerous_good')) {
                (new ProductDangerousAction($product, $data))->handle();
            }

            LogEvent::dispatch('Product Created', 'Product Created', route('seller-product.show', $product->id));

            $notification_data = [
                'from_model' => get_class(seller::getModel()),
                'from_id' => auth()->guard('seller')->user()->id,
                'to_model' => get_class(User::getModel()),
                'to_id' => 1,
                'title' => 'New Product Added By ' . auth()->guard('seller')->user()->name,
                'summary' => 'Product Created By Seller.',
                'url' => route('product.show', $product->id),
                'is_read' => false,
            ];
            (new NotificationAction($notification_data))->store();
            session()->flash('success', 'product added successfully');

            $notification_data = [
                'from_model' => get_class(seller::getModel()),
                'from_id' => auth()->guard('seller')->user()->id,
                'to_model' => get_class(User::getModel()),
                'to_id' => User::first()->id,
                'title' => 'Product Created',
                'summary' => 'Product Created By Seller.',
                'url' => url('admin/product/' . $product->id),
                'is_read' => false,
            ];
            (new NotificationAction($notification_data))->store();

            DB::commit();
            return redirect()->route('seller-product.show', $product->id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function show(Product $product)
    {
        $colors = [];
        foreach ($product->images as $key => $img) {
            $colors[$key]['color'] = Color::find($img->color_id);
            $colors[$key]['image'] = ProductImage::where('color_id', $img->color_id)->where('product_id', $product->id)->get();;
        }

        $colors = collect($colors)->whereNotNull('color')->map(function ($item) {
            return [
                'id' => $item['color']->id,
                'title' => $item['color']->title,
                'color_code' => $item['color']->colorCode,
                'image' => $item['image'],
            ];
        });
        $colors = collect($colors)->unique('id');
        return view('admin.seller.product.view', compact('product', 'colors'));
    }

    public function updateStatus($id)
    {
        $datas = QuestionAnswer::find($id);
        if ($datas->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $array = array('status' => $status);
        DB::table('question_answers')->where('id', $id)->update($array);
    }
    public function edit(Product $product)
    {

        $data = (new ProductFormAction($product))->getData();
        $data['featured_images'] = ProductFormHelper::getFeaturedImage($product);
        $data['dangerous_goods'] = ProductFormHelper::getDangerousGoods($product);
        $category = Category::find($product->category_id);
        $category_element = "";
        if ($category) {
            $root_element = $category->ancestors;
            if (count($root_element) > 0) {
                foreach ($root_element as $element) {
                    $category_element .= $element->title . ' > ';
                }
            }
            $category_element .= $category->title;
        }
        $data['category_element'] = $category_element;
        $data['categories'] = Category::where('parent_id', '=', null)->orderBy('created_at')->get();

        $data['stockways'] = $product->stockways()->get()->mapWithKeys(fn ($item) => [$item->key => $item->value])->toArray();
        return view('admin.seller.product.edit', $data);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {

        $this->authorize('update', $product);
        $data = $request->validated();
        try {
            //updating the product
            if($request->policy_data==0)
            {
                $data['policy_data']=0;
                $data['return_policy']=null;
            }
            else
            {
                $data['policy_data']=1;
                $data['return_policy']=$request->return_policy;
            }
            $product->update($data);
            (new ProductAttributesAction($product, $data))->handle();
            session()->flash('success', 'product updated successfully');
            return redirect()->route('seller-product.show', $product->id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy(Product $product)
    {
        Schema::disableForeignKeyConstraints();
        DB::beginTransaction();
        try {
            (new TrashAction($product, $product->id))->makeRecycle();
            $product->delete();
            LogEvent::dispatch('Product Delete', 'Product Delete', route('seller-product.destroy', $product->id));
            session()->flash('success', 'Successfully Deleted');
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return redirect()->route('seller-product.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Schema::enableForeignKeyConstraints();
            session()->flash('error', 'Something is wrong');
            return back();
        }
    }
    public function addTocart(request $request)
    {
        return response()->json($request->all(), 200);
    }
    public function updateStatusSecond(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            session()->flash('error', 'Something is wrong');
            return back()->withInput();
        }
        $product = Product::findOrFail($request->product_id);
        DB::beginTransaction();
        try {
            $product->update([
                'status' => $request->type,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Updated');
            return redirect()->route('seller-product.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Sorry Something is wrong');
            return back()->withInput();
        }
    }

    public function updatePublishStatus(request $request, $id, $status)
    {
        $this->product = $this->product->findOrFail($id);
        if ($status == 1) {
            $this->product->publishStatus = 0;
        } else {
            $this->product->publishStatus = 1;
        }
        $this->product->save();
        session()->flash('success', 'Product Publish Status Updated Successfully !!');
        return redirect()->back();
    }

    public function getSellerDetailsData(Request $request,$orderStatus)
    {
        // dd('ok');
        $seller_order=SellerOrder::findOrFail($orderStatus);
        $order=Order::findOrFail($seller_order->order_id);
        $seller_order_details=$seller_order->sellerProductOrder;
        $seller_details=Seller::findOrFail($seller_order->seller_id);
        $order_status = SellerOrderStatus::where('seller_order_id', $seller_order->id)->get();
        $order_status = collect($order_status)->map(function ($item) {
            return [
                'created_at' => $item->created_at,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'DELIVERED_TO_HUB' : (($item->status == 6) ? 'CANCELED' : 'REJECTED'))))))
            ];
        });
        // dd($seller_order);
        return view('seller.bill.billdetailseller',compact('seller_order','seller_order_details','seller_details','order','order_status'));
    }
}

