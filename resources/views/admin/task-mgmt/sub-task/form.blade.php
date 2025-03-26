@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Subtask Create')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="card">

                <div class="card-body">
                    @if (@$subtask->id)
                        <form class="row gy-1 pt-75" action="{{ route('subtask.update', @$subtask->id) }}" method="post">
                            @method('PUT')
                        @else
                            <form class="row gy-1 pt-75" action="{{ route('subtask.store') }}" method="post">
                    @endif
                    @csrf
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="name">For Task <span class="text text-danger">*</span></label>
                        <select name="task_id" id="task_id" class="form-control">
                            @if(@$task)
                                <option value="{{ $task->id }}" selected>{{ $task->title }}</option>
                            @else
                            @foreach ($all_tasks as $tasks)
                                <option value="{{ $tasks->id }}" {{ old('task_id', @$subtask->task_id) == $tasks->id ? 'selected' : '' }}>{{ @$tasks->title }}</option>
                            @endforeach
                            @endif
                        </select>
                        {{-- <input type="hidden" name="task_id" id="task_id" class="form-control" value="{{ $task->id }}">
                        <input type="text" id="title" name="task_title" class="form-control"
                            value="{{ old('title', @$task->title) }}" placeholder="task title" readonly/> --}}
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="name">Title <span class="text text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" required
                            value="{{ old('title',@$subtask->title) }}" placeholder="task title"/>
                        @error('title')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                     <div class="col-12 col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description',@$subtask->description) }}</textarea>
                  </div>
                    <div class="col-12 col-md-4">
                        <label for="priority" class="form-label">Priority <span class="text text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-control select2">
                            <option selected disabled>Select Priority</option>
                            <option value="None" {{ old('priority', @$subtask->priority) == 'None' ? 'selected' : '' }}>None</option>
                            <option value="Low" {{ old('priority', @$subtask->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', @$subtask->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority', @$subtask->priority) == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ old('priority', @$subtask->priority) == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="Emergency" {{ old('priority', @$subtask->priority) == 'Emergency' ? 'selected' : '' }}>Emergency</option>
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
                                <option value="{{ $action->id }}" {{ old('action_id', @$subtask->action_id) == $action->id ? 'selected' : '' }}>{{ $action->title }}</option>
                            @endforeach
                        </select>
                        @error('action_id')
                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="action" class="form-label">Status <span class="text text-danger">*</span></label>
                        <select name="status" id="status" class="form-control select2">
                            <option value="Assigned" {{ old('status', @$subtask->status) == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="Pending" {{ old('status', @$subtask->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In-Progress" {{ old('status', @$subtask->status) == 'In-Progress' ? 'selected' : '' }}>In-Progress</option>
                            <option value="Completed" {{ old('status', @$subtask->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ old('status', @$subtask->status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Cancelled" {{ old('status', @$subtask->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    @php
                        $currentDateTime = date('Y-m-d');
                    @endphp
                    <div class="col-12 col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control start_date" name="start_date" value="{{ old('start_date', @$subtask->start_date ?? $currentDateTime)}}">
                   </div>
                   <div class="col-12 col-md-6">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control due_date" name="due_date" value="{{ old('due_date', @$subtask->due_date ?? $currentDateTime)}}">
                        <span class="text text-danger" id="duedate-error"></span>
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="members" class="form-label">Involved Members</label>
                        <select name="members[]" id="members" class="form-control select2" multiple>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('members', @$selectedMembers->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $user->name }}</option>
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

@push('script')

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
