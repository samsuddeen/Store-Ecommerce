<?php

    namespace App\Observers\Category;
    use App\Models\OrderAsset;
    use App\Models\Admin\TopCategory\TopCategory;
    final class TopCategoryObserver{

        function __invoke()
        {
            $this->observer();
        }

        private function observer()
        {
            $product_id=[];
            $category_id=[];
            $order_asset=OrderAsset::get();
            if(count($order_asset))
            {
                foreach($order_asset as $product)
                {
                    $category_id[]['id']=$product->product->category_id;
                }
                
                if(count($category_id) >0)
                {
                    $data=collect($category_id)->groupBy('id');
                    foreach($data as $key=>$value)
                    {
                        TopCategory::updateOrCreate(
                            [
                                'category_id'=>$key
                            ],
                            [
                                'category_id'=>$key,
                                'status'=>1
                            ]
                        );
                    }
                }
            }
            
            
        }
    }