<?php

namespace App\View\Components\Dashboard;

use App\Providers\RouteServiceProvider;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $path;
    public $url;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = 'admin/';
        $this->path = $this->getBreadCrumbRoute();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.breadcrumb');
    }

    private function getBreadCrumbRoute()
    {
        $path = explode('/', request()->path());
        array_shift($path);
        return $path;
    }
}
