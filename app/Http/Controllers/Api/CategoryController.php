<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    protected $category = null;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    public function getCategory()
    {
        $category = [];
        // $categories = Category::whereNull('parent_id')->with('subcat')->get();
        try{
            $categories = Category::where('status', true)->whereNull('parent_id')->get();

            foreach ($categories as $key => $cat) {
                $category[$key]['id'] = $cat->id;
                $category[$key]['title'] = $cat->title;
                $category[$key]['slug'] = $cat->slug;
                $category[$key]['image'] = $cat->image;
                $category[$key]['showOnHome'] = $cat->showOnHome;
                $category[$key]['parent_id'] = $cat->parent_id;

                foreach ($cat->subcat as $value => $sub) {
                    $category[$key]['subcat'][$value]['id'] = $sub->id;
                    $category[$key]['subcat'][$value]['title'] = $sub->title;
                    $category[$key]['subcat'][$value]['slug'] = $sub->slug;
                    $category[$key]['subcat'][$value]['image'] = $sub->image;
                    $category[$key]['subcat'][$value]['parent_id'] = $sub->parent_id;
                }
            }
            $response = [
                'error' => false,
                'parent' => $category,
                'msg' => 'Parent Category'
            ];
            return response()->json($response, 200);
        }catch(\Exception $ex)
        {
            $response = [
                'error' => true,
                'parent' => $category,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }



    public function chilCategory(request $request, $slug)
    {
        try{
            $childrens = Category::descendantsAndSelf(Category::where('slug', $slug)->first());
            $cats = [];
            $new_cats = [];
            $all_products = [];
            $i = 0;
            $userType=$request->user_type ?? null;
            foreach ($childrens as $key => $cat) {
                $cats[$key]['id'] = $cat->id;
                $cats[$key]['title'] = $cat->title;
                $cats[$key]['slug'] = $cat->slug;
                $cats[$key]['image'] = $cat->image ?? asset('Uploads/images.png');
                $cats[$key]['parent_id'] = $cat->parent_id;
                $cats[$key]['products'] = null;
                if ($userType) {
                    $catProducts = $cat->products->filter(function ($item) use ($userType) {
                        if ($userType == '2') {
                            return $item->product_for != '2';
                        } else {
                            if ($item->product_for != '1') {
                                $item->stocks[0]->price = $item->stocks[0]->wholesaleprice;
                            }
                            return $item->product_for != '1';
                        }
                    })->values();
                }else{
                    $catProducts=$cat->products;
                }
                
               


                if ($catProducts->count() > 0 || $cat->parent_id != null) {
                    foreach ($catProducts as $value => $product) {
                        $priceValue=$product->getPrice->price;
                        if($userType=='1'){
                            $priceValue=$product->getPrice->wholesaleprice;
                        }
                        $cats[$key]['products'][$value]['id'] = $product->id;
                        $cats[$key]['products'][$value]['name'] = $product->name;
                        $cats[$key]['products'][$value]['slug'] = $product->slug;
                        $cats[$key]['products'][$value]['short_description'] = $product->short_description;
                        $cats[$key]['products'][$value]['long_description'] = $product->long_description;
                        $cats[$key]['products'][$value]['rating'] = $product->rating;
                        $cats[$key]['products'][$value]['price'] = number_format($priceValue);
                        $cats[$key]['products'][$value]['product_for'] = $product->product_for;
                        if ($product->getPrice->special_price != null) {
                            $cats[$key]['products'][$value]['special_price'] = number_format($product->getPrice->special_price);
                        } else {
                            $cats[$key]['products'][$value]['special_price'] = null;
                        }
                        $cats[$key]['products'][$value]['image'] = $product->images[0]['image'];
                        $cats[$key]['products'][$value]['varient_id'] = $product->stocks[0]->id;
                        $cats[$key]['products'][$value]['percent'] = apigetDiscountPercnet($product->id);
                        $all_products[$i] = $product;
                        $i++;
                    }
                } else {
                    $cats[$key]['products'] = [];
                }
            }
            foreach ($cats as $kk => $data) {
                if ($data['parent_id'] == null) {
                    foreach ($all_products as $k => $p) {
                        $priceValue1=$p->getPrice->price;
                        if($userType=='1'){
                            $priceValue1=$p->getPrice->wholesaleprice;
                        }
                        $cats[$kk]['products'][$k]['id'] = $p->id;
                        $cats[$kk]['products'][$k]['name'] = $p->name;
                        $cats[$kk]['products'][$k]['slug'] = $p->slug;
                        $cats[$kk]['products'][$k]['short_description'] = $p->short_description;
                        $cats[$kk]['products'][$k]['long_description'] = $p->long_description;
                        $cats[$kk]['products'][$k]['rating'] = $p->rating;
                        $cats[$kk]['products'][$k]['price'] = number_format($priceValue1);
                        $cats[$key]['products'][$value]['product_for'] = $product->product_for;
                        if ($p->getPrice->special_price != null) {
                            $cats[$kk]['products'][$k]['special_price'] = number_format($p->getPrice->special_price);
                        } else {
                            $cats[$kk]['products'][$k]['special_price'] = null;
                        }
                        $cats[$kk]['products'][$k]['image'] = $p->images[0]['image'];
                        $cats[$kk]['products'][$k]['varient_id'] = $p->stocks[0]->id;
                        $cats[$kk]['products'][$k]['percent'] = apigetDiscountPercnet($p->id);
                    }
                }
            }
            
            $response = [
                'error' => false,
                'category' => $cats,
                'msg' => 'Category with Product'
            ];
            return response()->json($response, 200);
        }catch(\Exception $ex)
        {
            $response = [
                'error' => true,
                'category' => $cats,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }
    public function diffCat(Request $request)
    {
        try{
            $parent_cat = Category::where('parent_id', null)->with('products')->get();
        $categories = Category::doesntHave('subcat')->whereHas('products')->get();
        $products1 = [];
        foreach ($categories as $category) {
            $products1[] = array(
                'category' => $category,
                'products' => Product::where('category_id', $category->id)->get(),
            );
        }
        $categories = Category::whereHas('subcat')->get();
        $products2 = [];
        foreach ($categories as $category) {
            $categories = $category->descendants()->pluck('id');
            $categories_id[] = $category->getKey();
            $products = Product::whereIn('category_id', $categories_id)->get();
            foreach ($categories_id as $categoryId) {
                $category = Category::whereHas('products')->where('id', $categoryId)->first();
                foreach ($products as $p) {
                    if ($p->category_id == $categoryId) {

                        $products2[] = array(
                            'category' => $category,
                            'products' => $products,
                        );
                    }
                }
            }
        }


        $products = array_merge($products1, $products2);
        $collection = collect($products);

        // $response=[
        //     'error'=>false,
        //     'data'=>json_encode($collection),
        //     'msg'=>'Parent Cat WIth Product'
        // ];
        return ($collection);
        // return response()->json($response, 200);
        }catch(\Exception $ex){
            $response=[
                    'error'=>true,
                    'data'=>json_encode($collection),
                    'msg'=>$ex->getMessage()
                ];
                return response()->json($response, 200);
        }


    }
}
