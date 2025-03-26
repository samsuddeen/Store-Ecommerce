<?php
namespace App\Actions\CustomerCare;

use App\Models\CustomerCarePage;

class CustomerCareAction
{
    protected $request;
    public function __construct($request)
    {
        $this->request=$request;
    }

    public function store()
    {
        $care=CustomerCarePage::create(
            [
                'title'=>$this->request->title ?? null,
                'content'=>$this->request->content ?? null,
                'status'=>$this->request->status ?? 0
            ]
        );
    }

    public function update($id)
    {
        $customercaredata=CustomerCarePage::where('id',$id)->first();
        $customercaredata->update(
            [
                'title'=>$this->request->title ?? null,
                'content'=>$this->request->content ?? null,
                'status'=>$this->request->status ?? 0
            ]
        );
    }

    public function destroy($id)
    {
        $customercaredata=CustomerCarePage::where('id',$id)->first();
        $customercaredata->delete();
    }
}