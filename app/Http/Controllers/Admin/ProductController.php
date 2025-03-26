<?php
namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Color;
use App\Models\Local;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\Category;
use App\Models\ProductCity;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Models\QuestionAnswer;
use App\Data\Filter\FilterData;
use App\Models\ProductAttribute;
use App\Models\seller as Seller;
use App\Data\Product\ProductData;
use App\Actions\Trash\TrashAction;
use App\Helpers\ProductFormHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Actions\Product\ProductFormAction;
use App\Http\Requests\StoreProductRequest;
use App\Actions\Product\ProductStoreAction;
use App\Http\Requests\UpdateProductRequest;
use App\Actions\Product\ProductDangerousAction;
use App\Actions\Product\ProductAttributesAction;
use App\Actions\Product\Image\ProductImageAction;
use Maatwebsite\Excel\Facades\Excel;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:product-read'], ['only' => ['index']]);
        $this->middleware(['permission:product-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:product-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:product-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.new
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $filters = (new FilterData($request))->getData();
        $data['category'] = Category::get();
        $data['filters'] =  $filters;
        $productData = new ProductData($filters);
        $data['counts'] = $productData->getCount();
        $data['products'] = $productData->getData();
        $data['sellers'] = Seller::orderByDesc('name')->get();        
        LogEvent::dispatch('Product List', 'View Product List', route('product.index'));        
        return view('admin.product.index', $data);
    }
    public function status(Request $request, $status, $id)
    {
        $status = QuestionAnswer::find($id);
        $status->status = $status;
        $status->save();
    }

    public function restore(Request $request,$id)
    {
        
        // dd(product::get()->toArray());
        $product=Product::withTrashed()->where('id',$id)->first();
        
        DB::beginTransaction();
        try {
            $product->restore();
            session()->flash('success', 'Successfully Restore');
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return redirect()->route('product.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Schema::enableForeignKeyConstraints();
            session()->flash('error', 'Something is wrong');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        $product = new Product();
        $data = (new ProductFormAction($product))->getData();
        $data['cities'] = City::get();
        LogEvent::dispatch('Product Form', 'Product Form Show', route('product.create'));
        return view('admin.product.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        // dd($request->all());
        $request['meta_title']=$request->name;
        $data = $request->all();
        DB::beginTransaction();
        try {
            $product = (new ProductStoreAction($request))->store();
            (new ProductAttributesAction($product, $data))->handle();
            (new ProductImageAction($product, $data))->handle();
            if ($request->has('dangerous_good')) {
                (new ProductDangerousAction($product, $data))->handle();
            }
            session()->flash('success', 'product added successfully');
            LogEvent::dispatch('Product Stored', 'Product Stored', route('product.show', $product->id));
            DB::commit();
            return redirect()->route('product.show', $product->id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
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
        LogEvent::dispatch('Product Show', 'Product Show', route('product.show', $product->id));
        $productBarCode=route('product.details',$product->slug);
        return view('admin.product.show', compact('product', 'colors','productBarCode'));
    }
    public function updatestatus($id)
    {
        $datas = QuestionAnswer::find($id);
        if ($datas->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $value = array('status' => $status);
        DB::table('question_answers')->where('id', $id)->update($value);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $data = (new ProductFormAction($product))->getData();
        $data['featured_images'] = ProductFormHelper::getFeaturedImage($product);
        $data['dangerous_goods'] = ProductFormHelper::getDangerousGoods($product);
        $category = Category::find($product->category_id);
        $category_element = "";
        if($category){
            $root_element = $category->ancestors;
            if(count($root_element) > 0){
                foreach($root_element as $element){
                    $category_element .= $element->title .' > ';
                }
            }
            $category_element .= $category->title;
        }
        $data['category_element'] = $category_element;
        $data['categories'] = Category::where('status',1)->where('parent_id', '=', null)->orderBy('created_at')->get();
        $data['cities'] = City::get();
        $data['current_cities'] = ProductCity::where('product_id',$product->id)->get();
        $data['p_attributes'] = ProductAttribute::where('product_id',$product->id)->get();
        return view('admin.product.edit-form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // dd($request->all());
        $this->authorize('update', $product);
        $data = $request->validated();
        try {

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
            //updating the product
            $product->update($data);

            (new ProductAttributesAction($product, $data))->handle();
            session()->flash('success', 'product updated successfully');
            return redirect()->route('product.show', $product->id);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Schema::disableForeignKeyConstraints();
        DB::beginTransaction();
        try {
            (new TrashAction($product, $product->id))->makeRecycle();
            $product->delete();
            LogEvent::dispatch('Product Delete', 'Product Delete', route('product.destroy', $product->id));
            session()->flash('success', 'Successfully Deleted');
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return redirect()->route('product.index');
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
        // dd($request->all());
                
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
            if ($request->type == 1) {
                $product->update([
                    'status' => $request->type,
                    'publishStatus' => true,
                ]);
            } else {
                $product->update([
                    'status' => $request->type,
                    'publishStatus' => false,
                ]);
            }
            DB::commit();
            session()->flash('success', 'Successfully Updated');
            return redirect()->route('product.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Sorry Something is wrong');
            return back()->withInput();
        }
    }

    public function updateProductPublish(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
       
        if(!$product)
        {
            session()->flash('success', 'Plz Restore Product First');
            return back();
        }
        if ($product->publishStatus == 0) {
            $product->publishStatus = 1;
        } else {
            $product->publishStatus = 0;
        }
        session()->flash('success', 'Successfully changed publish status');
        $product->update();
        return back();
    }



    public function getDecendent(Request $request)
    {
        $category = Category::where('id', $request->category_id)->first();
        $categories =  $category->getDescendants()->where('status',1);

        $return_html = '<ul class="list-group">';
            foreach($categories as $category){
                $return_html.= '<li class="list-group-item d-flex align-items-center category-selection" data-parent_id="'.$request->category_id.'" data-level="'.((int)$request->level+1).'" data-id="'.$category->id.'" data-child_count="'.count($category->getDescendants()).'">';
                $return_html.= '<span>'.$category->title.'</span>';
                $return_html .='<span class="badge bg-primary rounded-pill ms-auto">'.count($category->getDescendants()).'</span>';
                $return_html .='</li>';
            }
        $return_html .='</ul>';
        return $return_html;
    }
    public function getAcendent(Request $request)
    {
        $category = Category::find($request->category_id);
        $category_element = "";
        if($category){
            $root_element = $category->ancestors;
            if(count($root_element) > 0){
                foreach($root_element as $element){
                    $category_element .= $element->title .' > ';
                }
           }
            $category_element .= $category->title;
        }


        return $category_element;
    }

    public function showImportForm()
    {
        return view('admin.location.importproduct');
    }

    public function import(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        // try {
            $import = new ProductImport();
            Excel::import($import, $request->file);
            $errors = $import->getErrors();
            if(count($errors) > 0){
                DB::rollBack();
                return back()->with('error', json_encode($errors));
            }else{
                DB::commit();
                return redirect()->route('product.index')->with('success', 'Succesfully Product Imported');
            }
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return back()->with('error', 'Something is wrong');
        // }
    }
}
