<?php
namespace App\Data\MessageSetup;

use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Message\MessageSetup;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\isEmpty;

class MessageSetupData
{
    protected $filters;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        if(Arr::get($this->filters, 'title')){
            $message = MessageSetup::where('title', Arr::get($this->filters, 'title'))->first();
            if($message ==! null){
                return $message;
            }
        }
        return new MessageSetup();
    }
    public function getTitle()
    {
        
        $title = '';
        $messageEnum = new MessageSetupEnum();
        if(Arr::get($this->filters, 'title')){
            $title = $messageEnum->getSingleValue(Arr::get($this->filters, 'title'));
        }
        return $title;
    }
}