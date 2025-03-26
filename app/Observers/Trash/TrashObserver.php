<?php
namespace App\Observers\Trash;

use ReflectionClass;
use App\Models\Trash\Trash;
use Illuminate\Database\Eloquent\Model;

class TrashObserver
{
    protected $model;
    protected $model_id;
    protected $guard;
    function __construct($model, $model_id, $guard='web')
    {
        $this->model = $model;
        $this->model_id = $model_id;
        $this->guard = $guard;
    }
    public function observe()
    {
        $reflection = new ReflectionClass($this->model);
        $name = $reflection->getShortName();
        Trash::create([
            'model'=>$this->model,
            'name'=>$name,
            'user_id'=>auth()->user()->id,
            'model_id'=>$this->model_id,
            'guard'=>$this->guard,
        ]);
    }
}