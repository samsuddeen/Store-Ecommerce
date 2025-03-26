<?php
namespace App\Data\Color;

use App\Models\Color;

class ColorData
{
    function __construct()
    {
        
    }
    public function getColorTitle($id)
    {
        $color = Color::find($id);
        if($color){
            return $color->title;
        }else{
            return "";
        }
    }
}