<?php 
namespace App\Actions\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagStoreAction{

    protected $request;
    public function __construct($request)
    {
        $this->request=$request;
    }

    public function storeTag()
    {
       $tag=Tag::create($this->request);
       return $tag;
    }
}
