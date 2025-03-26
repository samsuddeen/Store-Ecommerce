<?php
namespace App\Actions\Trash;


use App\Observers\Trash\TrashObserver;
use Illuminate\Database\Eloquent\Model;

 class TrashAction
 {
    protected $model;
    protected $model_id;
    function __construct(Model $model, $model_id)
    {
        $this->model = $model;
        $this->model_id = $model_id;
    }

    public function makeRecycle()
    {
        $guard = activeGuard();
        (new TrashObserver(get_class($this->model->getModel()),  $this->model->id, $guard))->observe();
    }
    public function restore()
    {

    }
 }