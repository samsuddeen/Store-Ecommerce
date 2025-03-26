<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Models\CategoryAttribute;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Actions\Trash\TrashAction;
use App\Models\AttributeCategory;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(Category $category)
    {
        $this->middleware(['permission:category-read'], ['only' => ['index']]);
        $this->middleware(['permission:category-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:category-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:category-delete'], ['only' => ['destroy']]);

        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCategories = $this->category
            ->first();
            // ->groupBy('parent_id');      
                          
        return view('admin.category.index', compact('allCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category->orderBy('title', 'Asc')->get();
        return view('admin.category.form')
            ->with('category', $this->category)
            ->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {   
        $data = $request->validated();
        $data['showOnHome'] = $request->showOnHome;
        
        DB::beginTransaction();
        try {
            $category = $this->category->create($data);
            if($request->has('attribute')){
                $this->updateCategoryAttribute($data, $category);
            }
            DB::commit();
            session()->flash('success', 'category created successfully');
            return redirect()->route('category.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category,$catId)
    {
        $category=Category::where('id',$catId)->first();
        return view('admin.category.show')
        ->with('category',$category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = $this->category->orderBy('title', 'Asc')->whereNot('id', $category->id)->get();
        return view('admin.category.form')
            ->with('category', $category)
            ->with('categories', $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['showOnHome'] = $request->showOnHome;
        try {
            $category->update($data);
            if($request->attribute!==null){
            $this->updateCategoryAttribute($data, $category);
            }
            session()->flash('success', 'category update successfully');
            return redirect()->route('category.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            if ($category->children()->count()) {
                throw new Exception('Category has children, cannot be deleted');
            }

            (new TrashAction($category, $category->id))->makeRecycle();
            $test =   $category->delete();
            session()->flash('success', 'category deleted successfully');
            DB::commit();
            return redirect()->route('category.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    private function updateCategoryAttribute(array $data, Category $category)
    {   
        if ($data['attribute']) {
            foreach ($data['attribute'] as $key => $value) {
                $categoryAttribute = CategoryAttribute::where('title', $value)
                    ->where('category_id', $category->id ?? null)
                    ->first();
    
                if ($categoryAttribute) {
                    $categoryAttribute->update([
                        'helpText' => $data['helpText'][$key],
                        'value' => $data['value'][$key],
                        'stock' => isset($data['stock'][$key])
                    ]);
                } else {
                    CategoryAttribute::create([
                        'category_id' => $category->id,
                        'title' => $value,
                        'helpText' => $data['helpText'][$key],
                        'value' => $data['value'][$key],
                        'stock' => isset($data['stock'][$key])
                    ]);
                }
            }
        }
    }
}




