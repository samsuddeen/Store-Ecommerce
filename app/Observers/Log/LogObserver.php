<?php
namespace App\Observers\Log;

use App\Models\Log\Log;

class LogObserver
{
    protected $title, $url, $action;
    function __construct($title, $url, $action)
    {
        $this->title = $title;
        $this->url = $url;
        $this->action = $action;
    }
    function __invoke()
    {
        $this->observe();        
    }
    public function observe()
    {
        try {
            $guard = $this->getGuard();
            $data = [
                'log_model' => get_class(auth()->guard($guard)->user()),
                'log_role' => $this->getRoleName($guard),
                'guard'=>$guard,
                'log_id' => $this->getLogId($guard),
                'log_title'=>$this->title,
                'url'=>$this->url,
                'action'=>$this->action,
            ];
    
            Log::create($data);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }
    private function getGuard()
    {
        $guard = 'web';
        if(auth()->guard('seller')->check()){
            $guard = 'seller';
        }
        if(auth()->guard('customer')->check()){
            $guard = 'customer';
        }
        return $guard;
    }
    private function getRoleName($guard)
    {
        $role_name = '';
        if($guard == 'web'){
            $role_name = auth()->user()->getRoleName();
        }
        return $role_name;
    }
    private function getLogId($guard)
    {
        info($guard);
        return auth()->guard($guard)->user()->id;
    }
}