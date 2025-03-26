<?php
namespace App\Data\Admin;

use Illuminate\Support\Arr;
use App\Models\Notification\Notification;
class NotificationDetail{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }

    public function getData()
    {
        $keyword = Notification::orderBy('created_at','DESC')->get();
        $data = $keyword
        ->when(Arr::get($this->filters, 'month'), function ($q, $value) {
            
            return $q->filter(function ($item) use ($value) {
                return date('m', strtotime($item->created_at)) == $value;
            });
        })
        ->when(Arr::get($this->filters, 'year'), function ($q, $value) {
            return $q->filter(function ($item) use ($value) {
                return date('Y', strtotime($item->created_at)) == $value;
            });
        });
              
        return $data;
    }
}