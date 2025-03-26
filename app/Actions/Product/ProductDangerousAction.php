<?php
namespace App\Actions\Product;

use App\Models\Product;

class ProductDangerousAction
{
    protected $data;
    protected $product;
    function __construct(Product $product, $data)
    {
        $this->product = $product;
        $this->data = $data;
    }
    public function handle()
    {
        $this->store();
    }
    public function store()
    {
        $this->product->dangerousGoods()->delete();
        $this->product->dangerousGoods()->createMany(collect($this->data['dangerous_good'])->map(fn ($item, $index) => ['dangerous_good' => $item])->toArray());
    }
}