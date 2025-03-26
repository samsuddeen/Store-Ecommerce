<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistAction
{

    public function __construct(Product $product)
    {
    }

    public function updateWishList(array $input)
    {

        if ($Wishlist = Wishlist::where('product_id', $input['product_id'])->first()) {
            $Wishlist->delete();
            return true;
        }

      return  Wishlist::create(
            [
                'product_id' => $input['product'],
                'customer_id' => $input['customer']
            ]
        );
    }
}
