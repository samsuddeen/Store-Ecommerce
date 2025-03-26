<?php
namespace App\Actions\Policy;

use App\Data\Policy\ArrayPolicy\PolicyData;
use App\Models\Policy\AppPolicy;
use Illuminate\Http\Request;

class PolicyAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store()
    {
        $app_policy = AppPolicy::createOrUpdate([
            'year'=>$this->request->year,
        ],[]);
        $this->syncPolicies($app_policy);
    }
    public function update(AppPolicy $appPolicy)
    {
       $appPolicy->update([
            'year'=>$this->request->year,
       ]);
       $this->syncPolicies($appPolicy);
    }
    private function syncPolicies(AppPolicy $appPolicy)
    {
        foreach($this->request->title as $index=>$value){
            $appPolicy->assist()->updateOrCreate([
                'policy_id'=>$appPolicy->id,
                'title'=>$index,
            ], [
                'policy'=>$value,

            ]);
        }
               
    }
}