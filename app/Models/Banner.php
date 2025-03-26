<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'image',
        'title',
        'content',
        'btn_text',
        'url',
        'order',
        'publishStatus',
    ];
    public function activeAll()
    {
        return $this->where('publishStatus', true)->orderBy('order', 'asc')->get();
    }
}
