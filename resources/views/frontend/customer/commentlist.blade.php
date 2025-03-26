<div class="comments-list" id="comment-list-section">
    <div class="reply-comment">
        @foreach ($questionAnswer as $comment)
            <div class="comments-list-col">
                <div class="comments-list-media">
                    @if ($comment['question']->user->photo!=null)
                        <img src="{{ $comment['question']->user->photo}}" alt="images">
                    @else
                        <img src="{{ asset('frontend/images/review.png') }}" alt="images">
                    @endif
                </div>
                <div class="comments-list-info">
                    <b>By: {{ @$comment['question']->user->name }}</b>
                    <span>
                        {{ @$comment['question']->question_answer  }}
                    </span>
                    @isset( $comment['answer']->question_answer)
                        <p>answer: {{ @$comment['answer']->question_answer }}</p>
                    @endisset
                    
                </div>

            </div>
        @endforeach
    </div>
</div>