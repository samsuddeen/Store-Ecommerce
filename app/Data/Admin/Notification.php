<?php
namespace App\Data\Admin;

class Notification{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }

    public function getData()
    {
        dd($this->filters);
    }
}