<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStockRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['stocks', 'colors', 'sizes']);
        return view('admin.productStock.index', compact('product'));
    }

    public function edit(Product $product)
    {
        $stocks = $product->stock()->get();
        return view('admin.productStock.form', compact('stocks'));
    }

    public function store(ProductStockRequest $request, Product $product)
    {
        $data = $request->validated();

        foreach ($data['stock'] as $key => $stock) {
            $product->stocks()->updateOrCreate(
                [
                    'product_size_id' => $data['size'][$key],
                    'color_id' => $data['color'][$key],

                ],
                [
                    'stock' => $stock
                ]
            );
        }
        request()->session()->flash('success', 'product stock successfully updated');
        return redirect()->back();
    }
}
