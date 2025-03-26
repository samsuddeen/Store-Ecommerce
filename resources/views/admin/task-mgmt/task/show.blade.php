@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Task')
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
                            <h4>{{ $task->title }}</h4>
                                <span class="card-text item-company">Created By <a href="#"
                                        class="company-name">{{ $task->createdBy->name }}</a></span>
                                
                                <span class="card-text item-company ms-2">Created At <a href="#"
                                            class="company-name">{{ $task->created_at->format('d M Y, h:i A')}}</a></span>
                            <p class="card-text">Status<span
                                    class="text-success ms-1">{{ $task->status}}</span></p>
                            <p class="card-text">Action<span
                                        class="text-success ms-1">{{ $task->action->title ?? '-'}}</span></p>
                            <p class="card-text">Priority<span
                                            class="text-success ms-1">{{ $task->priority}}</span></p>

                            <p class="card-text">
                                {!! $task->description !!}
                            </p>
                            <hr />
                            <span class="card-text item-company">Start Date <a href="#"
                                class="company-name">{{ $task->start_date }}</a></span>
                            <span class="card-text item-company ms-2">Due Date<a href="#"
                                    class="company-name">{{ $task->due_date}}</a></span>
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
                            @if($task->order_id)
                            <p class="card-text">For Order<span
                                class="text-success ms-1"><a href="{{route('admin.viewOrder',@$task->order->ref_id)}}" target="_blank">{{ @$task->order ? $task->order->ref_id : '-'}}</a></span></p>
                            @endif
                            @if($task->product_id)
                            <p class="card-text">For Product<span
                                    class="text-success ms-1"><a href="{{route('product.show',@$task->product->id)}}" target="_blank">{{ @$task->product ? $task->product->name : '-'}}</a></span></p> 
                            @endif   
                            <div class="product-color-options">
                                <h6>Sub Tasks</h6>
                                <ul class="mb-0">
                                    @forelse ($task->subTasks as $subTask)
                                        <li class="selected">
                                            <a href="{{ route('subtask.show',$subTask->id) }}" target="_blank">{{ $subTask->title }}</a>
                                        </li>
                                    @empty
                                        <li>No sub task has been created for this task.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('task.edit',$task->id) }}" class="btn btn-warning btn-sm">Edit Task</a>
                    <a href="{{ route('add_subtask',$task->id) }}" class="btn btn-primary btn-sm ms-1">Add Sub Task</a>
                    <a href="{{ route('downloadTaskPdf',$task->id) }}" class="btn btn-danger btn-sm ms-1">Download PDF</a>
                </div>
            </div>

        </section>
    </div>
@endsection
