<?php
namespace App\Data\Filter;

class RetriveRequest
{
    protected $filters;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getRequest()
    {
      
    }
}