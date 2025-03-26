<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\seller;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Product\ProductUpdateAction;
use App\Actions\Product\ProductDangerousAction;
use App\Actions\Notification\NotificationAction;
use App\Actions\Product\ProductAttributesAction;
use App\Actions\Product\Image\ProductImageAction;

class SellerProductEditController extends Controller
{
    //** initalize the product variables */
    public Product $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    //** initalize the basic details edit */
    public function basicEdit(Request $request, $id)
    {
        $data = $this->validate($request, [
            'name' => ['required'],
            'url' => ['nullable', 'url'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'image_name' => ['nullable'],
        ]);
        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            (new ProductUpdateAction($request, $product))->update();

            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-products-edit.basicEdit', $id));


            DB::commit();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
        return  $this->updateProduct();
    }

    //** initalize the price and stocks edits */
    public function priceAndStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        try {
            $data = $request->all();
            $test =  (new ProductAttributesAction($product, $data))->syncStock(true);
            (new ProductImageAction($product, $data))->updateImageWithColor();


            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-product-edit.priceAndStock', $id));
            return $this->updateProduct();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    //** product description edit */
    public function description(Request $request, $id)
    {
        $data = $this->validate($request, [
            'long_description' => 'string',
            'short_description' => 'string'
        ]);

        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            (new ProductUpdateAction($request, $product))->update();

            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-product-edit.description', $id));
            DB::commit();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
        return  $this->updateProduct();
    }
    //** product service and Delivery edit */
    public function serviceAndDelivery(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            (new ProductUpdateAction($request, $product))->update();
            if ($request->dangerous_good !== null) {
                (new ProductDangerousAction($product, $request))->handle();
            }

            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-product-edit.serviceAndDelivery', $id));
            DB::commit();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
        return $this->updateProduct();
    }
    //** product attribute update */
    public function productAttribute(Request $request, $id)
    {
        $data = $this->validate($request, [
            'attribute' => 'required',
            'value' => 'required',
        ]);
        $product = Product::findOrFail($id);
        try {
            DB::beginTransaction();
            (new ProductAttributesAction($product, $data))->handleSyncAttributes();

            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-product-edit.productAttribute', $id));
            DB::commit();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
        return $this->updateProduct();
    }

    private function updateProduct()
    {
        session()->flash('success', 'Product Updated  successfully');
        return redirect()->back();
    }
    public function seoUpdated(Request $request, $id)
    {
        $product = Product::find($id);
        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->meta_description = $request->meta_description;
        $product->og_image = $request->og_image;
        DB::beginTransaction();
        try {
            $product->save();
            session()->flash('success', 'Successfully Updated');
            LogEvent::dispatch('Product Updated', 'Product Updated', route('seller-product-edit.productAttribute', $id));
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return back()->withInput();
        }
    }
    public function removeImage(Request $request)
    {
        DB::beginTransaction();
        try {
            $image =  ProductImage::where([
                'product_id' => $request->product_id,
                'color_id' => $request->color_id,
                'image' => $request->img,
            ])->first();
            $image->delete();
            DB::commit();
            return response()->json("Deleted", 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json("Something is wrong", 500);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        $product = Product::find($id);
        DB::beginTransaction();
        try {
            $product->update([
                'category_id'=>$request->category_id,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Updated');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', "Sorry something went wrong");
            return back()->withInput();
        }
    }
}
