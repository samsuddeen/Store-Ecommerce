<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeCategory;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

class AttributeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $categories = AttributeCategory::with('attrValues')->latest()->paginate(10);
        return view('admin.attribute.categroy.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attribute.categroy.form');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category_data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'publish' => $request->status
        ];

        $category = AttributeCategory::create($category_data);

        $values = $request->value;
        if($values)
        {
            foreach($values as $key => $val)
            {
                if($val !== null)
                {
                    AttributeValue::create([
                        'att_category_id' => $category->id,
                        'value' => $val
                    ]);
                }
            }
        }

        return redirect()->route('attribute-category.index')->with('success','Attribute created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 
    }

    public function editValue(Request $request, $id)
    {
        $value = AttributeValue::find($id);
        $value->value = $request->value;
        $value->status = $request->status;
        $value->save();
        return redirect()->back()->with('success','Attribute value has been updated successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attribute = AttributeCategory::find($id);
        $attribute->title = $request->title;
        $attribute->publish = $request->publish;
        $attribute->save();

        return redirect()->back()->with('success','Attribute updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute = AttributeCategory::find($id);
        if($attribute)
        {
            $attribute->delete();
            return redirect()->back()->with('success','Attribute deleted successfully!');
        }
    }

    public function addValues(Request $request)
    {
        $value = new AttributeValue();
        $value->att_category_id = $request->attr_cat_id;
        $value->value = $request->value;
        $value->status = true;
        $value->save();

        return redirect()->back()->with('success','New value has been added successfully!');

    }

    public function deleteValue($id)
    {
        $attr_value = AttributeValue::find($id);
        if($attr_value)
        {
            $attr_value->delete();
            return redirect()->back()->with('success','Attribute value deleted successfully!');
        }
    }
}
