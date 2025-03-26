<?php
namespace App\Actions\Product;

use App\Enum\Product\ProductStatusEnum;
use App\Models\Product;
use App\Models\ProductCity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductStoreAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store()
    {
        $product = new Product();
        $data = $this->request->all();
        
        if(Auth::guard('seller')->check()){
            $data['seller_id'] = Auth::guard('seller')->user()->id;
            $data['is_new'] = true;
            $data['publishStatus'] = true;
            $data['status'] = ProductStatusEnum::ACTIVE;
            unset($data['user_id']);
        }else{
            $data['is_new'] = false;
            $data['publishStatus'] = true;
            $data['status'] = ProductStatusEnum::ACTIVE;
            $data['seller_id']=1;
        }
        // $slugString = $this->getSlugString($data);
        $slug=$this->getSlugs($data['name']);
        $data['slug']=Str::slug($slug);
        // dd($data);

        if($this->request->policy_data==0)
        {
            $data['policy_data']=0;
            $data['return_policy']=null;
        }
        else
        {
            $data['policy_data']=1;
            $data['return_policy']=$this->request->return_policy;
        }
        $data['product_for']=$this->request->product_for ?? '3';
        // dd($data);
        $p = $product->create($data);
        
        // $cities = $this->request->city_id;
        // foreach($cities as $key => $city)
        // {
        //     ProductCity::create([
        //         'product_id' => $p->id,
        //         'city_id' => $city
        //     ]);
        // }

        return $p;
    }

    public function getSlugs($title)
    {
        $slug=\Str::slug($title);
        if(Product::where('slug',$slug)->count() >0)
        {
            $slug=$slug."-".rand(0,9999);
            $this->getSlugs($slug);
        }
        return $slug;
    }

    private function getSlugString($data)
    {
        $attribute = '';
        $value = '';
        if(Arr::get($data, 'attribute')){
            $attribute = $data['attribute'][0];
        }
        if(Arr::get($data, 'value')){
            $value = $data['value'][0];
        }
        $slugString = $data['name'].' '.$data['category_id'].' '.$attribute.' '.$value.' ';
        if(Product::where('slug', $slugString)->withTrashed()->first()){
            $slugString = $slugString.' '.rand(0000, 9999);
        }
        return $slugString;
    }
}


