<?php
namespace App\Data\Menu;

use App\Models\Menu;

class MenuData
{
    protected $filters;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $menus = Menu::orderBy('position')->get();
        return $menus;
    }
}