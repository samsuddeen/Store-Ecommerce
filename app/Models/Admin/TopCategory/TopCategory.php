<?php

namespace App\Models\Admin\TopCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class TopCategory extends Model
{
    use HasFactory;
    protected $primaryKey='category_id';
    protected $fillable=[
        'category_id',
        'status'
    ];

    public function getCategory()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
}
