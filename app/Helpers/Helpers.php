<?php

use App\Models\Brand;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\District;
use App\Helpers\Utilities;
use App\Models\LikeReview;
use App\Models\OrderAsset;
use App\Models\ProductImage;
use App\Models\LikeReviewReply;
use App\Models\seller as Seller;
use App\Helpers\ProductFormHelper;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Enum\Product\ProductStatusEnum;
use App\Models\Category;
use App\Models\Color;

function checkActive($part)
{
    return  request()->url() == $part ? 'active open' : null;
}

function adminCheckActive($part)
{
    
    if(in_array(request()->fullUrl(),explode(',',$part)))
    {
        return 'active open';
    }
    else
    {
        return null;
    }
}
function checkChildActive($part)
{
    return  request()->url() == $part ? 'active' : null;
}


function uploadImage($image,$dir,$thumb="50x50")
{
    list($thumb_width,$thumb_height)=explode("x",$thumb);
    $path=public_path().'/Uploads/'.$dir;
    if(!File::exists($path))
    {
        File::makeDirectory($path,0777,true,true);
    }
    $image_name=ucfirst($dir)."-".date("YmdHis")."-".rand(0,9999).".".$image->getClientOriginalExtension();
    $status=$image->move($path,$image_name);
    if($status)
    {
        Image::make($path."/".$image_name)->resize($thumb_width,$thumb_height,function($constraint){
            return $constraint->aspectRatio();
        })->save($path."/".$image_name);

        return $image_name;
    }
    else
    {
        return null;
    }
}



 function deleteImage($image,$dir)
{
    $path=public_path()."/Uploads/".$dir."/".$image;
    if(file_exists($path))
    {
        unlink($path);
    }
}

function reviewLikeCount($review)
{
    
    $data=LikeReview::where('review_id',$review->id)->get();

    return count($data) ?? 0;

}

function reviewReplyLikeCount($reply)
{
    
    $data=LikeReviewReply::where('review_reply_id',$reply->id)->get();

    return count($data) ?? 0;

}


function getUserReviewLike($review)
{
    $user=auth()->guard('customer')->user();
    if($user)
    {
        $reviewPresent=LikeReview::where('review_id',$review->id)->where('user_id',$user->id)->first();
        if($reviewPresent)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    return false;
}

function formattedNepaliNumber($value)
{
    $locale = 'en_IN';
    $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
    $number = $value;
    $formattedNumber = $formatter->format($number);
    return $formattedNumber;
}


function getUserReviewReplyLike($reply)
{
    $user=auth()->guard('customer')->user();
    if($user)
    {
        $reviewPresent=LikeReviewReply::where('review_reply_id',$reply->id)->where('user_id',$user->id)->first();
        if($reviewPresent)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    return false;
}


function getDistrictByProvinceId($province_id)
{
    $districts = District::where('province', $province_id)->orderBy('np_name')->get();
    return $districts;
}

function getOfferProduct($product,$stock)
{
    // return $stock;
    $price=$stock->price;
    $special_price=$stock->special_price;
    $top_offer=[];
    foreach($product->getOfferProduct as $data)
    {
        
        if($data->getOffer)
        {
            $top_offer[]=$data->getOffer->offer;
        }
        
    }
    if(count($top_offer) >0)
    {
        rsort($top_offer);
        if($special_price !=null)
        {
            $amount=($special_price-($top_offer[0]*$special_price)/100);
        }
        else
        {
            $amount=($price-($top_offer[0]*$price)/100);
        }
        
        return (round($amount));
    }
    else
    {
        return null;
    }   
}

function getDetailOfferProduct($product,$stock)
{
    $price=$stock['price'];
    $special_price=$stock['special_price'];
    $top_offer=[];
    foreach($product->getOfferProduct as $data)
    {
       if($data->getOffer)
       {
        $top_offer[]=$data->getOffer->offer;
       }
        
    }
    if(count($top_offer) >0)
    {
        rsort($top_offer);
        if($special_price !=null)
        {
            $amount=($special_price-($top_offer[0]*$special_price)/100);
        }
        else
        {
            $amount=($price-($top_offer[0]*$price)/100);
        }
        
        return (round($amount));
    }
    else
    {
        return null;
    }   
}

    function getSpecification($product=null)
    {
        if($product==null)
        {
            return null;
        }
        $stocks=$product->stockways;
        $stock_key=[];
        foreach($stocks as $key=>$stock)
        {
                        
            $stock_key[$key]['title']=$stock->getOption->title ?? '';
            $stock_key[$key]['key']=$stock->key;
            $stock_key[$key]['value']=$stock->value;
        }
        $data=collect($stock_key)->groupBy('key');
        $final_data=[];
        foreach($data as $key=>$value)
        {   
            foreach($value as $val=>$attr)
            {
                $final_data[$key]['title']=$attr['title'];
                $final_data[$key]['value'][$val]=$attr['value'];
            }
        }
        return $final_data;
        
    }

function activeGuard()
{
    foreach(array_keys(config('auth.guards')) as $guard){
        if(auth()->guard($guard)->check()) return $guard;
    
    }
    return null;
}

function get_class_name( $without_namespace = true ) {
    $class = get_called_class();
    if ( $without_namespace ) {
        $class = explode( '\\', $class );
        end( $class );
        $last  = key( $class );
        $class = $class[ $last ];
    }

    return $class;
}

function getOptions($order_id, $product_id)
{
    $options = OrderAsset::where('order_id', $order_id)->where('product_id', $product_id)->first();
    if($options){
        return $options->options;
    }else{
        return [];
    }
}

function getStartupStatus($row)
{
    $status = '';
    if ((int)$row->pending == 1) {
        $status = '<div class="d-flex"><span class="badge bg-primary">Pending</span><span class="badge bg-warning">New</span></div>';
    } else {
        $status = getProductStatus($row->status);
    }
    $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
    if(!auth()->guard('seller')->check()) {
        $action .= getProductAction($row);
    }
    $action .= '</div></div></div>';
    return $action;
}

function getProductStatus($status)
{
    $return_status = '';
    switch ($status) {
        case ProductStatusEnum::ACTIVE:
            $return_status =  '<div class="badge bg-primary success-status">ACTIVE</div>';
            break;
        case ProductStatusEnum::SUSPEND:
            $return_status =  '<div class="badge bg-danger pending-status">SUSPEND</div>';
            break;
        case ProductStatusEnum::BLOCKED:
            $return_status =  '<div class="badge bg-warning reject-status">BLOCKED</div>';
            break;
        default:
            # code...
            break;
    }
    return $return_status;
}
function getProductAction($row)
{

    $action = '';
    switch ($row->status) {
        case ProductStatusEnum::ACTIVE:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="2" data-product_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Suspend</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="3" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Block</span></a>';
            break;
        case ProductStatusEnum::SUSPEND:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
            break;
        case ProductStatusEnum::BLOCKED:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
            break;
        default:
            # code...
            break;
    }
    return $action;
}


function getProductName($product)
{
    $retun_string = '';
    // $featured_image = ProductFormHelper::getFeaturedImage($product);
    $retun_string = "<div>";
    // $retun_string .= "<img src='$featured_image' alt='Image Not Found' class='img-fluid'>";
    if(auth()->guard('seller')->check()){
        $retun_string .= '<a href="' . route('product.show', $product->id) . '">' . substr($product->name, 0, 20) . '...</a>';
    }else{
        $retun_string .= '<a href="' . route('product.show', $product->id) . '">' . substr($product->name, 0, 20) . '...</a>';
    }
    $retun_string .= "</div>";
    return $retun_string;
}


    function getFeaturedImage(Product $product)
    {
        $productImage = ProductImage::where('product_id', $product->id)->where('is_featured', true)->get();
        $images=[];
        foreach($productImage as $img){
            array_push($images, $img->image);
        }
        return implode(",", $images);
    }

    function getEmailImage($product)
    {
        $product=Product::where('id',$product->product_id)->first();
        $image=$product->images->first()->image;
       return $image;
    }


function getProductFinalAction($row)
{
    
    if(auth()->guard('seller')->check()){
        $edit =  Utilities::button(href: route('seller-product.edit', $row->id), icon: "edit", color: "primary process-status", title: 'Edit Product');
        $show = Utilities::button(href: route('seller-product.show', $row->id), icon: "eye", color: "primary success-status", title: 'Show Product');
        if($row->deleted_at !=null)
        {
            $delete = Utilities::button(href: route('seller-product.restore', $row->id), icon: "repeat", color: "warning ", title: 'Restore Product');
        }
        else
        {
            $delete = Utilities::delete(href: route('seller-product.destroy', $row->id), id: $row->id);
        }
        
    }else{
        $edit =  Utilities::button(href: route('product.edit', $row->id), icon: "edit", color: "primary process-status", title: 'Edit Product');
        $show = Utilities::button(href: route('product.show', $row->id), icon: "eye", color: "primary success-status", title: 'Show Product');
        if($row->deleted_at !=null)
        {
            $delete = Utilities::button(href: route('seller-product.restore', $row->id), icon: "repeat", color: "warning ", title: 'Restore Product');
        }
        else
        {
            $delete = Utilities::delete(href: route('product.destroy', $row->id), id: $row->id);
        }
        
    }
    return  '<div class="btn-group">'.$edit . '' . $show . '' . $delete . '</div>';
}

function getDiscountPercent($offerprice,$originalprice)
{
    // dd($originalprice);
    $discount=($originalprice-$offerprice)*100/$originalprice;
    return $discount;
}

function getDiscountValue($top_sell)
{
   
    $discountPercentage=null;
    foreach($top_sell->stocks as $key => $stock)
    {
        
        if($key == 0)
        {
            $offer = getOfferProduct($top_sell, $stock);
            if($offer != null)
            {
                $discountPercentage=getDiscountPercent($offer,$stock->price);
            }
            elseif($stock->special_price)
            {
                $discountPercentage=getDiscountPercent($stock->special_price,$stock->price);
            }
        }
    }
        if($discountPercentage !=null)
        {
            $data=(float)$discountPercentage;
            $value=explode('.',$data);
            $first=$value[1][0] ?? null;
            if($first >=1)
            {
                $values=2;
            }
            else
            {
                $values=0;
            }
            $html='';
            $html.='<div class="product-off">';
            $html.='<span>Off <b>'.number_format((float)$discountPercentage, $values, '.', '').'%</b></span>';
            $html.='</div>';
            return $html;
        }
        else
        {
            return null;
        }
        
       
   
}

function apigetDiscountPercnet($id)
{
    $top_sell=Product::where('id',$id)->first();
    $discountPercentage=null;
    foreach($top_sell->stocks as $key => $stock)
    {
        
        if($key == 0)
        {
            $offer = getOfferProduct($top_sell, $stock);
            if($offer != null)
            {
                $discountPercentage=getDiscountPercent($offer,$stock->price);
            }
            elseif($stock->special_price)
            {
                $discountPercentage=getDiscountPercent($stock->special_price,$stock->price);
            }
        }
    }
        if($discountPercentage !=null)
        {
            $data=(float)$discountPercentage;
            $value=explode('.',$data);
            $first=$value[1][0] ?? null;
            if($first >=1)
            {
                $values=2;
            }
            else
            {
                $values=0;
            }
            return number_format((float)$discountPercentage, $values, '.', '');
        }
        else
        {
            return null;
        }
}

function getDetailDiscount($offer_price,$original_price)
{   
    if($original_price !== 0)
    {
        $discount=($original_price-$offer_price)*100/$original_price;
    }

    // $discount = 0;

    $data=(float)$discount;
        $value=explode('.',$data);
        $first=$value[1][0] ?? null;
        if($first >=1)
        {
            $values=2;
        }
        else
        {
            $values=0;
        }
        $html="<strong>- ".number_format((float)$discount,$values, '.', '')."%</strong>";
   
    return $html;
}


function getSeller($product)
{
    $seller_id=Product::where('id',$product->product_id)->first();
    $seller=Seller::where('id',$seller_id->seller_id)->first();
    if(!$seller)
    {
        return null;
    }

    return $seller->name;
}

function seller($sellerId)
{
    
    $seller=Seller::where('id',$sellerId)->first();
    if(!$seller)
    {
        return null;
    }

    return $seller->name;
}


function productImage($product)
{
    $imageData=null;
    foreach($product->getImage as $image)
    {
        if($image->image !=null)
        {
            $imageData=$image->image;
            return $imageData;
        }
    }
    return $imageData;
    
}

function productDetailImage($product)
{
    $imageData=null;
    if($product && $product->images)
    {
        foreach($product->images as $image)
        {
            if($image && $image->image !=null)
            {
                $imageData=$image->image;
                return $imageData;
            }
        }
        return $imageData ?? asset('dummyimage.png');
    }
    else
    {
        return asset('dummyimage.png');
    }
}

function getSpecificationHtmlgenerate($data)
{
    if(count(getSpecification($data)) > 0 && !empty(getSpecification($data)) && getSpecification($data) != null)
    {
        $string='';
        $string.='<table style="align-items: start">';
        $string.='<tbody>';
        foreach(getSpecification($data) as $product_data)
        {
            $string.='<tr>'; 
            $string.='<th style="padding:20px">'.$product_data['title'].'</th>';
            $string.='<td style="padding:20px">';
            foreach ($product_data['value'] as $key=>$value)
            {
                $string.=$value;
                if($key < count($product_data['value']) - 1)
                {
                    $string.=',';
                }
                
            }
            $string.='</td>';
            $string.='</tr>';
        }
        $string.='</table>';
        $string.='</tbody>';
        return $string;
        // $specificationData = getSpecification($data);

        // $specificationArray = [];
        // foreach ($specificationData as $productData) {
        //     $values = implode(',', $productData['value']);
        //     $specificationArray[] = [
        //         'title' => $productData['title'],
        //         'value' => $values
        //     ];
        // }
        
        // $specificationJson = json_encode($specificationArray, JSON_UNESCAPED_SLASHES);

        // return $specificationJson;
    }
    else
    {
        return null;
    }
   
   
    
}
function getSpecificationHtmlgenerateApi($data)
{
    if(count(getSpecification($data)) > 0 && !empty(getSpecification($data)) && getSpecification($data) != null)
    {
        return collect(getSpecification($data))->values();   
    }
    return null;
   
   
    
}

function getTaxStatus($item)
{
    $product=Product::where('id',$item->product_id)->first();
    if($product->vat_percent !=1 && $product->vat_percent ==0)
    {
        return true;
    }
    return false;
}

function convertNumberIntoWord($number=0)
{

    $no = (int)floor($number);
    $point = (int)round(($number - $no) * 100);
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    if ($point > 20) {
        $points = ($point) ? ("" . ($words[floor($point / 10) * 10] ?? null) . " " . $words[$point = $point % 10]) : '';
    } else {
        $points = $words[$point];
    }
    if ($points != '') {
        echo $result . "Rupees  and " . $points . " CENTS Only";
    } else {
        echo $result . "Rupees Only";
    }
}

function orderSeller(Order $order)
{
    $orderAsset=$order->orderAssets;
    if(count($orderAsset) >0)
    {
        $sellerId=collect($orderAsset)->map(function($item)
        {
            return $item->product_id;
        });
        
        $product=Product::whereIn('id',$sellerId)->get();
        $seller=collect($product)->map(function($item)
            {
                return $item->seller_id;
            });
        $seller=Seller::whereIn('id',$seller)->get();
        $seller=collect($seller)->map(function($item)
            {
                return $item->name;
            });
        return $seller;
    }
    return null;
    
}

function completedOrderImage($product)
{
    $imageValue=null;
    if(count($product) >0)
    {
        foreach($product as $image)
        {
            if($image->image !=null)
            {
                $imageValue=$image->image;
                return $imageValue;
            }
            
        }
        if($imageValue==null)
        {
            return asset('dummyimage.png');
        }
        return $imageValue;
    }
    else
    {
        return asset('dummyimage.png');
    }
}

function calculatePercent($mainPrice=0,$sprice=0)
{
    $percentPrice = ($sprice / $mainPrice) * 100;
    $roundedPrice = number_format($percentPrice, 2);
   return $roundedPrice;
    
}

function getAdminOrder($row)
{
    $status = '';
    $cancelOrder = $row->orderAssets;
    $cancelOrder = collect($cancelOrder)->filter(function ($item) {
        if ($item->cancel_status == '0') {
            return $item;
        }
    });
    if (count($cancelOrder) > 0) {
        if ((int)$row->pending == 1) {
            $status = '<div class="d-flex"><span class="badge btn-secondary">Pending</span><span class="badge bg-warning success-status">New</span></div>';
        } else {
            $status = getStatusAdmin($row->status);
        }
        $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

        $action .= getActionsAdmin($row);

        $action .= '</div></div></div>';
    } else {
        if ($row->status == 6 || $row->status == 7) {
            $status = getStatusAdmin($row->status);
            $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

            $action .= getActionsAdmin($row);

            $action .= '</div></div></div>';
        } else {
            $status = getStatusAdmin(8);
            $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
            $action .= getCancelActions($row);
            $action .= '</div></div></div>';
        }
    }
    return $action;
}
function getStatusAdmin($status)
{
    $return_status = '';
    switch ($status) {
        case 1:
            $return_status =  '<div class="badge bg-primary success-status">SEEN</div>';
            break;
        case 2:
            $return_status =  '<div class="badge bg-info">READY_TO_SHIP</div>';
            break;
        case 3:
            $return_status =  '<div class="badge bg-info process-status">DISPATCHED</div>';
            break;
        case 4:
            $return_status =  '<div class="badge bg-warning reject-status">SHIPPED</div>';
            break;
        case 5:
            $return_status =  '<div class="badge bg-success success-status">DELIVERED</div>';
            break;
        case 6:
            $return_status =  '<div class="badge bg-danger pending-status">CANCELED</div>';
            break;
        case 7:
            $return_status =  '<div class="badge bg-danger pending-status">REJECTED</div>';
            break;
        case 8:
            $return_status =  '<div class="badge bg-danger pending-status">CANCELLED ON REQUEST</div>';
            break;

        default:
            # code...
            break;
    }
    return $return_status;
}
function getActionsAdmin($row)
{
    $action = '';
    switch ($row->status) {
        case 1:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="ready_to_ship" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Redy To Ship</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="dispatched" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shipped</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
            break;
        case 2:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="dispatched" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shipped</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
            break;
        case 3:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shiped</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
            break;
        case 4:
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
            break;
        default:
            # code...
            break;
    }
    return $action;
}
function getCancelActions($row)
{

    $action = '';
    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
    return $action;
}
function adminOrderAction($row)
{
    $show =  Utilities::button(href: route('admin.viewOrder', $row->ref_id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
    return  $show;
}

function getItem($item,$type){
    $itemArray=collect($item)->pluck('foreign_id')->toArray();
    $data=null;
    switch((int)$type){
        case 1:
            $data=Category::whereIn('id',$itemArray)->get();
            break;
        case 2:
            $data=Brand::whereIn('id',$itemArray)->get();
            break;
        case 3:
            $data=Product::whereIn('id',$itemArray)->get();
            break;
    }
    return $data;
}


function logAction($row){
    switch($row->guard)
        {
            case 'customer':
                return $row->user->name ?? null;
                break;
            case 'seller':
                return $row->seller->name ?? null;
                break;
            case 'web':
                return $row->admin->name ?? null;
                break;
        }
}

function productColor($colorId){
   if(!$colorId || $colorId==null)
   {
        return null;
   }
   $color=Color::where('id',$colorId)->first();
   return $color->colorCode;
}


function checkRetailerLoginStatus(){
   $status=false;
   if(auth()->guard('customer')->user() && auth()->guard('customer')->user()->wholeseller){
    $status=true;
   }
   return $status;
}

function checkRetailerPrice($product,$originalPrice,$specialPrice,$offerPrice,$status){
    dd($product);
    if(!$status){
        return null;
    }
    if($offerPrice !=null){
        dd('offer Price');
        checkRetailerOfferPrice($offerPrice);
    }elseif($specialPrice !=null){
        dd('special price');
        checkRetailerOfferPrice($specialPrice);
    }else{
        // dd('none',$originalPrice);
        checkRetailerOfferPrice($originalPrice);
    }
 }

 function checkRetailerOfferPrice($price){
    dd('ok');
 }
