<?php

namespace App\Traits;

trait CommentTrait
{

    public function getCommentList($all_question, $user_specific_question = null)
    {
        $count = 1;
        $comment = [];
        if ($user_specific_question != null) {
            foreach ($user_specific_question as $user_question) {

                $comment[$count]['question'] = $user_question;
                $comment[$count]['question']['user'] = $user_question->user;
                $comment[$count]['answer'] = null;
                $count++;
            }
        }
        foreach ($all_question as $key => $result) {
            $comment[$count]['question'] = $result;
            $comment[$count]['question']['user'] = $result->user;
            $comment[$count]['answer'] = $result->answer ?? null;
            $comment[$count]['answer']['user'] = $result->answer->user ?? null;
            $count++;
        }

        return $comment;
    }
}
