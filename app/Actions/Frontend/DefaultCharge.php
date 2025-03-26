<?php

    namespace App\Actions\Frontend;

    final class DefaultCharge{
        public function getCharge($provinces)
        {
            if(count($provinces) <=0)
            {
                return null;
            }

            $default_province=$provinces[2]->eng_name ?? null;
            $default_district=$provinces[2]->districts[0]->np_name ?? null;
            $default_area=$provinces[2]->districts[0]->localarea[0]->local_name ?? null;
            if($default_province ==null || $default_district ==null || $default_area ==null)
            {
                return null;
            }
            $charge=[];
            foreach($provinces[2]->districts[0]->localarea[0]->getRouteCharge ?? [] as $route)
            {
                $charge[]=$route->deliveryRoute->charge;
            }
            sort($charge);
            
            $final_data=[
                'province'=>$default_province,
                'district'=>$default_district,
                'area'=>$default_area,
                'charge'=>$charge[0] ?? null,
            ];
            
            return $final_data;
        }
    }