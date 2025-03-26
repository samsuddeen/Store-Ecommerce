<?php

namespace App\Imports;

use App\Models\Local;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Hub\Hub;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Observers\Location\NearPlaceObserver;
use App\Observers\Location\DeliveryRouteObserver;

class LocationImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public $errors=[];
    public function collection(Collection $collection)
    {
        //
        foreach($collection as $index=>$row){
            if($index > 0){
                $hub = $this->findHub($row[1]);
                $location = $row[2];
                $local_id = $this->findLocal($row[3]) ? $this->findLocal($row[3])->id : '';
                $charge = $row[4];
                $data = [
                    'hub_id' => $this->findHub($row[1])->id,
                    'local_id'=>$this->findLocal($row[3])->id,
                    'charge'=>$row[4],
                    'title'=>$row[2],
                    'slug'=>Str::slug($row[2]).rand(0000, 9999),
                    'user_id'=>auth()->id(),
                ];
                $location = Location::create($data);
                $request = new Request();
                $data['type'] = 'location';
                $request->replace($data);
                if($location){
                    (new DeliveryRouteObserver($hub, $request))->observe($location->id);
                    (new NearPlaceObserver($hub, $request))->observe($location->id);
                }else{
                    $this->errors['location'][]=[
                        'title'=>$row[2],
                        'message' =>' Location Could not be import ',
                    ];
                }
            }

        }
    }
    public function getErrors()
    {
        return $this->errors;
    }
    private function findHub($title):Hub
    {
        $hub = Hub::where('title', $title)->first();
        if($hub){
            return $hub;
        }else{
            $this->errors['hub'][]=[
                'title'=>$title,
                'message'=>'Not Found is system please check manual',
            ];
            return $hub;
        }
    }
    private function findLocal($local_name):Local
    {
        $local_address = Local::where('local_name', $local_name)->first();
        if($local_address){
            return $local_address;
        }else{
            $this->errors['locals'][]=[
                'title'=>$local_name,
                'message'=>'Not found in system please check manual',
            ];
            return $local_address;
        }
    }
}
