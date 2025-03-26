<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use App\Models\CategoryAttribute;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Observers\Category\CategoryObserver;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CategoryAttributeUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $categoryAttribute;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CategoryAttribute $categoryAttribute)
    {
        //
        $this->categoryAttribute = $categoryAttribute;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new CategoryObserver($this->categoryAttribute->category, $this->categoryAttribute))->observe();
    }
}
