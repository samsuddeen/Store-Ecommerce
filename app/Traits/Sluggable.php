<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    protected function bootSlug()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug('test at the best');
        });
    }
}
