<?php

namespace App\Data\Menu;

use App\Models\Tag;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Brand;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Enum\Menu\MenuTypeEnum;

class MenuContentData
{
    protected $request;
    protected $menu;
    function __construct(Request $request)
    {
        $this->menu = new Menu();
        $this->request = $request;
        if($request->menu_id !== null){
            $this->menu = Menu::find($request->menu_id);
        }
    }
    public function getData()
    {
        $contents = "";
        switch ($this->request->menu_type) {
            case MenuTypeEnum::CATEGORY:
                $contents = $this->getCategoryContent();
                break;
            case MenuTypeEnum::TAG:
                $contents = $this->getTagContent();
                break;
            case MenuTypeEnum::PAGE:
                $contents = $this->getPage();
                break;
            case MenuTypeEnum::SELLER:
                $contents = $this->getSellerContent();
                break;
            case MenuTypeEnum::BRAND:
                $contents = $this->getBrandContent();
                break;
            case MenuTypeEnum::PRODUCT:
                $contents = $this->getProductContent();
                break;
            case MenuTypeEnum::EXTERNAL_LINK:
                $contents = $this->getExternalContent();
                break;
            default:
                # code...
                break;
        }
        return $contents;
    }

    private function getCategoryContent()
    {
        $categories = Category::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Category()).'" hidden>';
        $contents .= '<select name="model_id" id="model_id" class="select2 form-control form-control-sm">';
        $contents .= "<option value=''>Please Select</option>";
        foreach($categories as $index=>$category){
            $contents .='<option value="'.$category->id.'"' ;
            if($this->menu->model_id == $category->id){
                $contents .='selected';
            }
            $contents .='>'.$category->title.'</option>';
        }

        $contents .='</select>';
        $contents .='</div>';
        return $contents;
    }
    private function getTagContent()
    {
        $categories = Tag::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Tag()).'" hidden>';
        $contents .= '<select name="model_id" id="model_id" class="select2 form-control form-control-sm">';
        $contents .= "<option value=''>Please Select</option>";
        foreach($categories as $index=>$category){
            $contents .='<option value="'.$category->id.'"' ;
            if($this->menu->model_id == $category->id){
                $contents .='selected';
            }
            $contents .='>'.$category->title.'</option>';
        }

        $contents .='</select>';
        $contents .='</div>';
        return $contents;
    }

    private function getPage()
    {
        $categories = Tag::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Page()).'" hidden>';
        $contents .= '<textarea name="content" class="form-control short-description dataPage" rows="12" id="content">'.$this->menu->content.'</textarea>';
        $contents .='</div>';
        return $contents;
    }

    private function getSellerContent()
    {
        $categories = Seller::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Seller()).'" hidden>';
        $contents .= '<select name="model_id" id="model_id" class="select2 form-control form-control-sm">';
        $contents .= "<option value=''>Please Select</option>";
        foreach($categories as $index=>$category){
            $contents .='<option value="'.$category->id.'"' ;
            if($this->menu->model_id == $category->id){
                $contents .='selected';
            }
            $contents .='>'.$category->name.'</option>';
        }

        $contents .='</select>';
        $contents .='</div>';
        return $contents;
    }



    private function getBrandContent()
    {
        $categories = Brand::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Brand()).'" hidden>';
        $contents .= '<select name="model_id" id="model_id" class="select2 form-control form-control-sm">';
        $contents .= "<option value=''>Please Select</option>";
        foreach($categories as $index=>$category){
            $contents .='<option value="'.$category->id.'"' ;
            if($this->menu->model_id == $category->id){
                $contents .='selected';
            }
            $contents .='>'.$category->name.'</option>';
        }

        $contents .='</select>';
        $contents .='</div>';
        return $contents;
    }



    private function getProductContent()
    {
        $categories = Product::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Product()).'" hidden>';
        $contents .= '<select name="model_id" id="model_id" class="select2 form-control form-control-sm">';
        $contents .= "<option value=''>Please Select</option>";
        foreach($categories as $index=>$category){
            $contents .='<option value="'.$category->id.'"' ;
            if($this->menu->model_id == $category->id){
                $contents .='selected';
            }
            $contents .='>'.$category->name.'</option>';
        }

        $contents .='</select>';
        $contents .='</div>';
        return $contents;
    }



    private function getExternalContent()
    {
        $categories = Product::all();
        $contents ='<div class="form-group">';
        $contents .='<label for="">Menu Content<span class="text-danger">*</span>:</label>';
        $contents .= '<input type="text" name="model" value="'.get_class(new Product()).'" hidden>';
        $contents .= '<input name="external_link" placeholder="Please Provide URL for External link" value="'.$this->menu->external_link.'" class="form-control form-control-sm" id="external_link">';
        $contents .='</div>';
        return $contents;
    }


}
