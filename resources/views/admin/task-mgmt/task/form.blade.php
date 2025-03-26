@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Task Create')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="card">

                <div class="card-body">
                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li><span class="text-danger">{{ $error }}</span></li>
                            @endforeach
                        </ul>
                    @endif
                    @if (@$task->id)
                        <form class="row gy-1 pt-75" action="{{ route('task.update', @$task->id) }}" method="post">
                            @method('PUT')
                        @else
                            <form class="row gy-1 pt-75" action="{{ route('task.store') }}" method="post" id="taskForm">
                    @endif
                    @csrf
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="name">Title <span class="text text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control"
                            value="{{ old('title', @$task->title) }}" placeholder="task title" required/>
                        @error('title')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', @$task->description) }}</textarea>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="priority" class="form-label">Priority <span class="text text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-control select2" required>
                            <option selected disabled>Select Priority</option>
                            <option value="None" {{ old('priority', @$task->priority) == 'None' ? 'selected' : '' }}>None</option>
                            <option value="Low" {{ old('priority', @$task->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', @$task->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority', @$task->priority) == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ old('priority', @$task->priority) == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="Emergency" {{ old('priority', @$task->priority) == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        </select>
                        @error('priority')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="action" class="form-label">Action <span class="text text-danger">*</span></label>
                        <select name="action_id" id="action_id" class="form-control select2" required>
                            <option selected disabled>Select Action</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action->id }}" {{ old('action_id', @$task->action_id) == $action->id ? 'selected' : '' }}>{{ $action->title }}</option>
                            @endforeach
                        </select>
                        @error('action_id')
                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="action" class="form-label">Status <span class="text text-danger">*</span></label>
                        <select name="status" id="status" class="form-control select2" required>
                            <option value="Assigned" {{ old('status', @$task->status == 'Assigned' ? 'selected' : '') }}>Assigned</option>
                            <option value="Pending" {{ old('status', @$task->status == 'Pending' ? 'selected' : '') }}>Pending</option>
                            <option value="In-Progress" {{ old('status', @$task->status == 'In-Progress' ? 'selected' : '') }}>In-Progress</option>
                            <option value="Completed" {{ old('status', @$task->status == 'Completed' ? 'selected' : '') }}>Completed</option>
                            <option value="On Hold" {{ old('status', @$task->status == 'On Hold' ? 'selected' : '') }}>On Hold</option>
                            <option value="Cancelled" {{ old('status', @$task->status == 'Cancelled' ? 'selected' : '') }}>Cancelled</option>
                        </select>
                        @error('status')
                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                   <div class="col-12 col-md-6">
                        <label for="order" class="form-label">Order</label>
                        
                        <select name="order_id" id="order_id" class="form-control select2">
                            <option value="" selected>Select order or Leave Blank</option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}" {{ old('order_id', @$task->order_id) == $order->id ? 'selected' : '' }}>{{ $order->ref_id }}</option>
                            @endforeach
                        </select>
                   </div>
                   <div class="col-12 col-md-6">
                        <label for="order" class="form-label">Product</label>
                        
                        <select name="product_id" id="product_id" class="form-control select2">
                            <option value="" selected>Select product or Leave Blank</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', @$task->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $currentDateTime = date('Y-m-d');
                    @endphp
                    <div class="col-12 col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control start_date" name="start_date" value="{{ old('start_date', @$task->start_date ?? $currentDateTime)}}">
                   </div>
                   <div class="col-12 col-md-6">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control due_date" name="due_date" value="{{ old('due_date', @$task->due_date ?? $currentDateTime)}}">
                        <span class="text text-danger" id="duedate-error"></span>
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="members" class="form-label">Involved Members</label>
                        <select name="members[]" id="members" class="form-control select2">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{  in_array($user->id, old('members', $selectedMembers ?? [])) ? 'selected' : ''  }}>{{ $user->name }} - {{ $user->roles->first()->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1 submitBtn">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" aria-label="Close">
                            Discard
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
<style>
    .error{
        color: red !important;
    }
</style>
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
         $('#lfm').filemanager('image');

         var options = {
            filebrowserImageBrowseUrl: '/admin/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/admin/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/admin/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/admin/laravel-filemanager/upload?type=Files&_token='
        };
         CKEDITOR.replace('description', options);

    </script>

    <script>
        $(document).ready(function(){
            $('#taskForm').validate();
            $(document).on('change', '.due_date', function(){
                let due_date = $(this).val();
                let start_date = $('.start_date').val();
                if(due_date < start_date)
                {   
                    $('#duedate-error').html('Due date cannot be before the start date');
                    $('.submitBtn').prop('disabled', true);
                }else{
                    $('#duedate-error').html('');
                    $('.submitBtn').prop('disabled', false);
                }
            });
        });
    </script>
@endpush
