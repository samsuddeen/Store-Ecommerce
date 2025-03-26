<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Currency;
use App\Models\CurrencyExchange;
use App\Models\StockWays;
use Illuminate\Support\Facades\Http;

class BackendHelper
{
    public function setExchange()
    {
        $date = Carbon::now()->toDateString();
        $final_result = $this->getExchange($date);
        $count = count($final_result);
        for ($i = 0; $i < 21; $i++) {
            $curr = Currency::where('iso3', $final_result[$i]['currency']['iso3'])->first();
            $currency = new Currency();
            $currEx = null;
            if (!$curr) {
                $currency->iso3 = $final_result[$i]['currency']['iso3'];
                $currency->name = $final_result[$i]['currency']['name'];
                $currency->unit = $final_result[$i]['currency']['unit'];
                $currency->save();
            }
            if ($curr) {
                $currEx = CurrencyExchange::where('currency_id', $curr->id)->where('date', $date)->first();
            }
            if (!$currEx) {
                $currencyExchnage = new CurrencyExchange();
                $currencyExchnage->create([
                    'currency_id' => $currency->id,
                    'buy' => $final_result[$i]['buy'],
                    'sell' => $final_result[$i]['sell'],
                    'date' => $date,
                ]);
            }
        }
    }
    public function getExchange($date)
    {
        $url = "https://www.nrb.org.np/api/forex/v1/rates?from=" . $date . "&to=" . $date . "&per_page=100&page=1";
        $response =  Http::get($url, [
            'from' => $date,
            'to' => $date,
            'per_page' => 100,
            'page' => 1,
        ]);
        $contents = json_decode($response->getBody()->getContents(),true);
        $final_result =  $contents['data']['payload'][0]['rates'];
        return $final_result;
    }
    public static function attributeSelection($attribute, $stock)
    {
        $stockWays = StockWays::where([
            'stock_id'=>$stock->id,
            'key'=>$attribute->id,
        ])->first();
        if($stockWays){
            return $stockWays;
        }
        return [];
    }
}
