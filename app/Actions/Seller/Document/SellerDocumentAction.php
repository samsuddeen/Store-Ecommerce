<?php
namespace App\Actions\Seller\Document;

use Illuminate\Http\Request;
use App\Models\Seller\SellerDocument;

class SellerDocumentAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store()
    {
        $sellerDocument = SellerDocument::updateOrCreate([
            'seller_id'=>$this->request->seller_id,
            'title'=>$this->request->title,
        ], 
        [
            'document'=>$this->request->document,
        ]);
    }
}