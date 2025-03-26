<?php
namespace App\Actions\Offer;

use App\Models\RetailerOfferSection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RetailerOfferAction
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
    }
    private function initializeData()
    {
        $this->input['slug'] = Str::slug($this->request->title).rand(0000, 9999);
    }
    public function store()
    {
        $this->initializeData();
        Validator::make($this->input, [
            "title" => "required|unique:top_offers,title",
            "slug" => "required|unique:top_offers,slug",
            "offer" => "required"
        ]);
        RetailerOfferSection::create($this->input);
    }
    public function update(RetailerOfferSection $topOffer)
    {
        $this->initializeData();
        $topOffer->update($this->input);
    }
}