<?php
namespace App\Data\JustForYou;

use App\Models\Tag;
use Illuminate\Support\Arr;
use App\Data\Color\ColorData;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class JustForYouData
{
    protected $filters;
    protected $data=[];
    function __construct($filters=null)
    {
        $this->filters = $filters;
    }
    private function initializedData()
    {
        if(!Arr::get($this->filters, 'per_page')){
            $this->filters['per_page'] = 6;
        }
    }
    public function getIndexData()
    {
        
        $this->initializedData();
        // $tags = Tag::where('publishStatus', 1)->paginate(Arr::get($this->filters, 'per_page'));
        $tags = Tag::where('publishStatus', 1)->orderBy('title')->get();
        $new_tags = [];
        foreach($tags as $tag){
            $data =  [
                "id"=>$tag->id,
                "title"=>$tag->title,
                "summary"=>$tag->summary,
                "description"=>$tag->description,
                "image"=>$tag->image,
                "thumbnail"=>$tag->thubnail,
                "slug"=>$tag->slug,
            ];
            // if($tag->products->count() >0)
            // {
                $new_tags[] = $data;
            // }
        }
        $this->data['tags'] = $new_tags;
        $products = $this->getAllProducts($tags);
        $this->data['products'] = $products;
        return $this->data;
    }

    public function getIndexData1($city)
    {
        
        $this->initializedData();
        // $tags = Tag::where('publishStatus', 1)->paginate(Arr::get($this->filters, 'per_page'));
        $tags = Tag::where('publishStatus', 1)->get();
        $new_tags = [];
        foreach($tags as $tag){
            $data =  [
                "id"=>$tag->id,
                "title"=>$tag->title,
                "summary"=>$tag->summary,
                "description"=>$tag->description,
                "image"=>$tag->image,
                "thumbnail"=>$tag->thubnail,
                "slug"=>$tag->slug,
            ];
            if($tag->products->count() >0)
            {
                $new_tags[] = $data;
            }
        }
        $this->data['tags'] = $new_tags;
        $products = $this->getAllProducts1($tags, $city);
        $this->data['products'] = $products;
        return $this->data;
    }

    public function getAllProducts1($tags, $city)
    {   
        $products = [];

        foreach ($tags as $tag) {
            foreach ($tag->products as $product) {
                // Filter products by the requested city
                $filteredProducts = $product->city()->where('cities.city_name', $city)->get();
                
                // If a product matches the requested city, it will be included in the list
                if ($filteredProducts->isNotEmpty()) {
                    $data = new ProductResource($product);
                    $products[] = collect($data)->toArray();
                }
            }
        }
        
        $products = collect($products)->unique()->sortByDesc('id')->values()->all();
        return $products;
    }

    public function getAllProducts($tags)
    {
        $products = [];
        foreach($tags as $tag){
            foreach($tag->products as $product){
                $data = new ProductResource($product);
                $products[] = collect($data)->toArray();
            }
        }
        $products = collect($products)->unique()->sortByDesc('id')->values()->all();
        return $products;
    }
    public function getSlugData($slug)
    {
        $this->initializedData();
        $tag = Tag::where('publishStatus', 1)->where('slug', $slug)->first();
        $data =  [
            "id"=>$tag->id,
            "title"=>$tag->title,
            "summary"=>$tag->summary,
            "description"=>$tag->description,
            "image"=>$tag->image,
            "thumbnail"=>$tag->thubnail,
            "slug"=>$tag->slug,
        ];
        $this->data['tag'] = $data;
        $products = $this->getSlugProduct($tag);
        $this->data['products'] = $products;
        return $this->data;
    }
    private function getSlugProduct($tag)
    {
        $products = [];
        foreach($tag->products as $product){
            $data = new ProductResource($product);
            $products[] = collect($data)->toArray();
        }
        $products = collect($products)->unique()->sortByDesc('id')->values()->all();
        return $products;
    }
}