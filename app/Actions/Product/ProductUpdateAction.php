<?php
namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Arr;

class ProductUpdateAction
{
    protected $request;
    protected $product;
    function __construct(Request $request, Product $product)
    {
        $this->request = $request;
        $this->product = $product;
    }
    public function update()
    {
        $data = $this->request->all();
        $data_image['color_id']=$this->product->stocks[0]->color_id;
        $data_image['product_id']=$this->product->id;
        $data_image['is_featured']='1';
        if(Arr::get($data, 'image_name')){
            ProductImage::where('product_id', $this->product->id)->where('is_featured', true)->delete();
            $product_image=new ProductImage();
            $data_image['image']=$data['image_name'][0];
            $product_image->fill($data_image);
            $product_image->save();
        }
        $this->product->update($data);

        $cities = $this->request->city_id;
        if($cities){
            $this->product->city()->sync($cities);
        }
    }
}
