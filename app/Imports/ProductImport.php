<?php

namespace App\Imports;

use Exception;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Local;
use App\Events\LogEvent;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Hub\Hub;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Actions\Product\ProductStoreAction;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Observers\Location\NearPlaceObserver;
use App\Actions\Product\ProductDangerousAction;
use App\Actions\Product\ProductAttributesAction;
use App\Actions\Product\Image\ProductImageAction;
use App\Models\Category;
use App\Models\Color;
use App\Observers\Location\DeliveryRouteObserver;

class ProductImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public $errors=[];
    public function collection(Collection $collection)
    {
        foreach($collection as $index=>$row){
            try{
                if($index > 0 && $row[0] !=null){
                    $request = new Request();
                    $data = $this->arrangeData($row);
                    // dd($data);
                    foreach ($data as $key => $value) {
                        $request->merge([$key => $value]);
                    }
                    $attributeValue=$this->checkAttributeData($data['category_id']);
                    $data = array_merge($data, $attributeValue);
                    $request->merge($attributeValue);
                    $product = (new ProductStoreAction($request))->store();
                    (new ProductAttributesAction($product, $data))->handle();
                
                    (new ProductImageAction($product, $data))->handle();
                    if ($request->has('dangerous_good')) {
                        (new ProductDangerousAction($product, $data))->handle();
                    }
                    session()->flash('success', 'product added successfully');
                    LogEvent::dispatch('Product Stored', 'Product Stored', route('product.show', $product->id));
                }
                // dd('Success');
            }catch(\Throwable $th){
                DB::rollBack();
                request()->session()->flash($th->getMessage());
                return redirect()->back();
            }
            

        }
    }

    public function checkAttributeData($catId){
        $url = "https://mystore.com.np/api/get-attributes/47";
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                ),
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
            if ($error) {
                return null;
            } else {
                $responseData = json_decode($response, true);
                foreach($responseData as $attribute){
                    $attributeData['key'][]=$attribute['id'];
                    $attributeData['attribute'][]=$attribute['id'];
                    $attributeData['attributes'][]=explode(',',$attribute['value'])[0];
                    $attributeData['value'][]=explode(',',$attribute['value'])[0];
                }
                return $attributeData;
            }
    }

    public function arrangeData($row){
        $data=[
            "name" =>$row[0],
            "brand_id" =>$this->getBrand($row[2]),
            "tags" =>$this->getTagData($row[3]),
            "vat_percent" => "1",
            "min_order" => $row[4] ?? 15,
            "returnable_time" => $row[5] ?? 10,
            "delivery_time" => $row[6] ?? 15,
            "policy_data" => "0",
            "return_policy" => null,
            "image_name" => $this->getImageName($row),
            "category_id" => $this->getCategory($row['1']),
            "url" => $row[24] ?? null,
            "short_description" => $row[9] ?? null,
            "long_description" => $row[10] ?? null,
            "image_color" => $this->getColor($row[11]),
            "color" =>  $this->getColor($row[11]),
            "price" => explode('|',$row[13]),
            "wholesaleprice" =>explode('|',$row[24]) ?? 0,
            // "special_price" =>explode('|',$row[14]),
            // "special_from" => array:2 [▶],
            // "special_to" => array:2 [▶],
            "quantity" => explode('|',$row[14]),
            "sellersku" => explode(',',$row[15]),
            // "free_items" => explode('|',$row[17]),
            "additional_charge" => ($row[16] != null) ? explode('|',$row[16]) : 0,
            "warranty_type" => $row[17] ?? 'No Warranty',
            "package_weight" => $row[18] ?? 0,
            "dimension_length" => $row[19] ?? 0,
            "dimension_width" => $row[20] ?? 0,
            "dimension_height" => $row[21] ?? 0,
            "dangerous_good" => [
                "None"
            ],
            "meta_title" => null,
            "og_image" => null,
            "meta_keywords" => null,
            "meta_description" => null,
            "user_id" => auth()->guard()->user()->id,
            "publishStatus" => false,
            'mimquantity'=>'1'
        ];
        // dd($data);
        return $data;
    }

    public function getBrand($item){
        $brand=Brand::where('name',$item)->first();
        if($brand){
            return (string)$brand->id;
        }
        throw new Exception('Invalid Brand Type !!');
    }

    public function getTagData($item){
        $tagItem=explode(',',$item);
        $tag=Tag::whereIn('title',$tagItem)->get()->map(function($data){
            return (string)$data->id;
        });
        if($tag){
            return $tag->toArray();
        }
        throw new Exception('Invalid Tag Type !!');
    }

    public function getCategory($item){
        $category=Category::where('title',$item)->first();
        if($category){
            return (string)$category->id;
        }
        throw new Exception('Invalid Category Type !!');
    }

    public function getImageName($row){
        $imageValue = [];
        $imageValue[] = $row[8] ?? null;
        $colorImage = explode('@', $row[12]);
        $imageValue = array_merge($imageValue, $colorImage);
        return $imageValue;
    }

    public function getColor($item){
        $colorItem=explode(',',$item);
        $color=Color::whereIn('title',$colorItem)->get()->map(function($data){
            return (string)$data->id;
        });
        if($color){
            return $color->toArray();
        }
        throw new Exception('Invalid Color Type !!');
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
}
