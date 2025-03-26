<?php
namespace App\Actions\ReviewReply;

use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewReplyAction{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function store()
    {
        $reviewReply=ReviewReply::create(
            [
                'review_id'=>$this->request->reviewId,
                'reply'=>$this->request->reply,
                'user_id'=>auth()->user()->id
            ]
        );
        
    }
}