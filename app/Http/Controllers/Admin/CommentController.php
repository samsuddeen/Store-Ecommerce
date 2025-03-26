<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use App\Datatables\CommentDatatables;
use App\Models\QuestionAnswer;

class CommentController extends Controller
{
    private $datatable;
    public function index(request $request)
    {
        $filters = (new FilterData($request))->getData();
        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        $data['retrive_request'] = $retrive_request;
        $product=QuestionAnswer::get();
        
        $comment=collect($product)->whereNull('parent_id');
        
        $allcomment=$comment;
        return view('admin.order.comment', $data,compact('allcomment'));
    }

    public function updateStatus(Request $request,$status)
    {
        dd($request->all());
    }

    public function getAllComment(Request $request)
    {
        
        $filters = (new FilterData($request));
        $datatable = new CommentDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getAllComment();
    }

    public function newComment(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        $data['retrive_request'] = $retrive_request;
        
        return view('admin.order.newcomment', $data);
    }

    public function commentStatus(Request $request)
    {
       
        $comment=QuestionAnswer::findOrFail($request->order_id);
        
        if($request->type=='active')
        {
            
            $comment->status=1;
        }
        else
        {
            $comment->status=0;
        }
        $comment->save();
        session()->flash('success', 'Successfully Status has been ' . $request->type);
        return back();
    }
}
