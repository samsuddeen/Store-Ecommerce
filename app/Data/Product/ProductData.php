<?php

namespace App\Data\Product;

use App\Models\Product;
use Illuminate\Support\Arr;
use App\Helpers\PaginationHelper;
use App\Enum\Product\ProductStatusEnum;

class ProductData
{
    protected $filters;
    function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function getCount()
    {
        $counts = [];
        $counts['all'] = Product::withTrashed()->when(Arr::get($this->filters, 'seller_id'), function($q, $value) {
            $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->get()->count();
        
        $counts['online'] = Product::where([
            'is_new' => false,
            'publishStatus' => true,
            'status' => 1,
        ])->when(Arr::get($this->filters, 'seller_id'), function($q, $value) {
            $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->get()->count();

        $counts['pendings'] = Product::where('is_new', true)->when(Arr::get($this->filters, 'seller_id'), function($q, $value){
                $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->where('status', ProductStatusEnum::INACTIVE)->count();


        $counts['out_of_stocks'] = $this->getCountOfOutOfStock();
        $counts['in_stocks'] = $this->getCountOfInStock();

        $counts['suspended'] = Product::where('status', ProductStatusEnum::SUSPEND)->when(Arr::get($this->filters, 'seller_id'), function($q, $value){
                $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->count();

        $counts['deleted'] = Product::onlyTrashed()->when(Arr::get($this->filters, 'seller_id'), function($q, $value){
                $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->count();
        
        return $counts;
    }
    public function getData()
    {
        $products = [];
        if ((Arr::get($this->filters, 'per') == 'all') || (Arr::get($this->filters, 'per') == null)) {
            $products = $this->getProductWithOutPagination();
        } else {
            $products = $this->getProductWithPagination(Arr::get($this->filters, 'per'));
        }
        return $products;
    }

    public function getDataReport()
    {
        $products = Product::when(Arr::get($this->filters, 'category'), function ($q, $value) {
            $q->where('category_id', $value);
        })
            ->when(Arr::get($this->filters, 'product_name'), function ($q, $value) {
                $q->where('name', $value);
            })
            ->get();
        return $products;
    }

    private function getCountOfOutOfStock()
    {
        $products = Product::when(Arr::get($this->filters, 'seller_id'), function($q, $value) {
            $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })->get();
        $out_of_stocks = 0;
        $out_of_stocks = collect($products)->map(function ($row, $index) {
            if ($row->getTotalStock() <= 0) {
                return 1;
            } else {
                return 0;
            }
        })->sum();
        return $out_of_stocks;
    }
    private function getCountOfInStock()
    {
        $products = Product::when(Arr::get($this->filters, 'seller_id'), function($q, $value) {
            $q->where('seller_id', $value);
        })->when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
            if ($q == 'in-house') {
                $q->where('seller_id', null);
            }
            if ($value == 'seller-products') {
                $q->where('seller_id', '!=', null);
            }
        })
        ->get();
        $in_stocks = 0;
        $in_stocks = collect($products)->map(function ($row, $index) {
            if ($row->getTotalStock() <= 0) {
                return 0;
            } else {
                return 1;
            }
        })->sum();
        return $in_stocks;
    }



    private function getProductWithOutPagination()
    {
        if (Arr::get($this->filters, 'type') == 4 || Arr::get($this->filters, 'type') == 5) {
            $products = Product::when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
                if ($q == 'in-house') {
                    $q->where('seller_id', null);
                }
                if ($value == 'seller-products') {
                    $q->where('seller_id', '!=', null);
                }
            })->with('stocks')
            ->when(Arr::get($this->filters, 'filter_type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('category_id', $value);
                        });
                        break;
                    case 2:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('id', $value);
                        });
                        break;
                    case 3:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('name', $value);
                        });
                        break;
                    case 5:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('seller_id', $value);
                        });
                        break;
                    default:
                        # code...
                        break;
                }
            })
                ->when(Arr::get($this->filters, 'seller_id'), function ($q, $value) {
                    $q->where('seller_id', $value);
                })->orderByDesc('created_at')->get();
                

            $products = collect(collect($products)->map(function($row, $index){
                    $stock_qty = collect($row->stocks)->pluck('quantity')->sum();
                    if(Arr::get($this->filters, 'type') == 4){
                        if($stock_qty > 0){
                            return $row;
                        }else{
                            return null;
                        }
                    }else{
                        if($stock_qty == 0){
                            return $row;
                        }else{
                            return null;
                        }
                    }
            }))->whereNotNull();
        } else {
            $products = Product::when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
                if ($q == 'in-house') {
                    $q->where('seller_id', null);
                }
                if ($value == 'seller-products') {
                    $q->where('seller_id', '!=', null);
                }
            })->when(Arr::get($this->filters, 'type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->withTrashed();
                        break;
                    case 2:
                        $q->where([
                            'is_new' => false,
                            'publishStatus' => true,
                            'status' => 1,
                        ]);
                        break;
                    case 3:
                        $q->where('is_new', true)->where('status', ProductStatusEnum::INACTIVE);
                        break;
                    case 6:
                        $q->where('status', ProductStatusEnum::SUSPEND);
                        break;
                    case 7:
                        $q->onlyTrashed();
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->when(Arr::get($this->filters, 'filter_type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('category_id', $value);
                        });
                        break;
                    case 2:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('id', $value);
                        });
                        break;
                    case 3:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('name', $value);
                        });
                        break;
                    case 5:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('seller_id', $value);
                        });
                        break;
                    default:
                        # code...
                        break;
                }
            })
                ->when(Arr::get($this->filters, 'seller_id'), function ($q, $value) {
                    $q->where('seller_id', $value);
                })->orderByDesc('created_at')->get();
        }
        return $products;
        return $products;
    }
    private function getProductWithPagination($count)
    {
        $urlSinPaginado = url()->full();
        $pos = strrpos(url()->full(), 'page=');
        if ($pos) {
            $urlSinPaginado = substr(url()->full(), 0, $pos - 1);
        }
        if (Arr::get($this->filters, 'type') == 4 || Arr::get($this->filters, 'type') == 5) {
            $products = Product::when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
                if ($q == 'in-house') {
                    $q->where('seller_id', null);
                }
                if ($value == 'seller-products') {
                    $q->where('seller_id', '!=', null);
                }
            })->with('stocks')
            ->when(Arr::get($this->filters, 'filter_type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('category_id', $value);
                        });
                        break;
                    case 2:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('id', $value);
                        });
                        break;
                    case 3:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('name', $value);
                        });
                        break;
                    case 5:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('seller_id', $value);
                        });
                        break;
                    default:
                        # code...
                        break;
                }
            })
                ->when(Arr::get($this->filters, 'seller_id'), function ($q, $value) {
                    $q->where('seller_id', $value);
                })->orderByDesc('created_at')->get();
                

            $products = collect(collect($products)->map(function($row, $index){
                    $stock_qty = collect($row->stocks)->pluck('quantity')->sum();
                    if(Arr::get($this->filters, 'type') == 4){
                        if($stock_qty > 0){
                            return $row;
                        }else{
                            return null;
                        }
                    }else{
                        if($stock_qty == 0){
                            return $row;
                        }else{
                            return null;
                        }
                    }
            }))->whereNotNull();
            $products = PaginationHelper::paginate($products, $count)->withPath($urlSinPaginado);
        } else {
            $products = Product::when(Arr::get($this->filters, 'product_type'), function ($q, $value) {
                if ($q == 'in-house') {
                    $q->where('seller_id', null);
                }
                if ($value == 'seller-products') {
                    $q->where('seller_id', '!=', null);
                }
            })->when(Arr::get($this->filters, 'type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->withTrashed();
                        break;
                    case 2:
                        $q->where([
                            'is_new' => false,
                            'publishStatus' => true,
                            'status' => 1,
                        ]);
                        break;
                    case 3:
                        $q->where('is_new', true)->where('status', ProductStatusEnum::INACTIVE);
                        break;
                    case 6:
                        $q->where('status', ProductStatusEnum::SUSPEND);
                        break;
                    case 7:
                        $q->onlyTrashed();
                        break;
                    default:
                        # code...
                        break;
                }
            })
            ->when(Arr::get($this->filters, 'filter_type'), function ($q, $value) {
                switch ((int)$value) {
                    case 1:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('category_id', $value);
                        });
                        break;
                    case 2:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('id', $value);
                        });
                        break;
                    case 3:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('name', $value);
                        });
                        break;
                    case 5:
                        $q->when(Arr::get($this->filters, 'search_string'), function($q, $value){
                            $q->where('seller_id', $value);
                        });
                        break;
                    default:
                        # code...
                        break;
                }
            })
                ->when(Arr::get($this->filters, 'seller_id'), function ($q, $value) {
                    $q->where('seller_id', $value);
                })->orderByDesc('created_at')->paginate($count)->withPath($urlSinPaginado);
        }
        return $products;
    }




}
