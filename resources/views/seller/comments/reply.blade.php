@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Reply on Comment </h4>
                    </div>
                    <div class="card-body">

                        <b> Reply To :</b>{{ $customer->name }}
                        <br>
                        <b> address :</b> {{ $customer->district }}, {{ $customer->area }},
                        @if ($comment->id)
                            <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('comments.store') }}" method="POST">
                        @endif
                        @csrf

                        <div class="row">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-8">
                                        @if ($comment->parent_id == null)
                                            <b> Question :</b> <span
                                                class="text-danger">{{ $comment->question_answer }}</span>
                                        @endif
                                        {{-- @if ($comment->parent_id != null) --}}
                                        <br>
                                        <span class="text-primary">
                                            Answer : {{ @$comment->answer->question_answer }}
                                        </span>
                                        @if (@$comment->answer->question_answer != null)
                                            <button type="button" class="btn btn-warning m-1" data-bs-toggle="modal"
                                                data-bs-target="#edit-answer">
                                                Edit Answer
                                            </button>
                                        @endif
                                        {{-- @endif --}}
                                        {{-- @dd(@$comment->answer->question_answer); --}}
                                        @if (@$comment->answer->question_answer == null)
                                            <textarea name="question_answer" class="form-control"></textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        {{-- <x-dashboard.button :create="isset($comment->id)"></x-dashboard.button> --}}
                        @if (@$comment->answer->question_answer == null)
                            <button type="submit" class="btn btn-primary ml-auto"> Send </button>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <form action="{{ route('edit.comment.answer', $comment->id) }}" method="post">
            @csrf
            @method('PATCH');
            <!-- Modal -->
            <div class="modal fade" id="edit-answer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="question_answer">Update Answer</label>
                                <textarea name="question_answer" id="question_answer" rows="3" class="form-control"> {{ old('question_answer', @$comment->answer->question_answer) }} </textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Change Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.banner_image').filemanager("image");
            $('.logo_image').filemanager("image");
        });
    </script>
@endpush
