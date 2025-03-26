@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sub Task')
@push('style')
    <link href="{{ asset('dashboard/css/pages/app-ecommerce-details.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
@endpush
@section('content')
    <div class="ecommerce-application">
        <section class="app-ecommerce-details">
            <div class="card">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-7">`
                            <h4>{{ $subtask->title }} - <span class="text text-danger">Parent Task :  {{ $subtask->task->title }}</span></h4>
                                <span class="card-text item-company">Created By <a href="#"
                                        class="company-name">{{ $subtask->createdBy->name }}</a></span>
                                
                                <span class="card-text item-company ms-2">Created At <a href="#"
                                            class="company-name">{{ $subtask->created_at->format('d M Y, h:i A')}}</a></span>

                            {{-- <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                <h4 class="item-price me-1"></h4>
                                <ul class="unstyled-list list-inline ps-1 border-start">
                                    @foreach (range(1, $product->rating) as $rating)
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                    @endforeach
                                    @for ($i = 1; $i <= 5 - $product->rating; $i++)
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                                    @endfor
                                </ul>
                            </div> --}}
                            <p class="card-text">Status<span
                                    class="text-success ms-1">{{ $subtask->status}}</span></p>
                            <p class="card-text">Action<span
                                        class="text-success ms-1">{{ $subtask->action->title}}</span></p>
                            <p class="card-text">Priority<span
                                            class="text-success ms-1">{{ $subtask->priority}}</span></p>

                            <p class="card-text">
                                {!! $subtask->description !!}
                            </p>
                            <hr />
                            <span class="card-text item-company">Start Date <a href="#"
                                class="company-name">{{ $subtask->start_date }}</a></span>
                            <span class="card-text item-company ms-2">Due Date<a href="#"
                                    class="company-name">{{ $subtask->due_date}}</a></span>
                            <div class="product-color-options">
                                <h6>Involved Members</h6>
                                <ul class="mb-0">
                                    @foreach ($selectedMembers as $member)
                                        <li class="selected">
                                            <span>{{ $member->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
  
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('subtask.edit',$subtask->id) }}" class="btn btn-warning btn-sm">Edit Subtask</a>
                </div>
            </div>

        </section>
    </div>
@endsection
