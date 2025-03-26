@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Order Settings')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            {{-- <x-cardHeader :href="route('task.create')" name="users" /> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="task-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Day Off</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        @php
                            $today = Carbon\Carbon::now()->format('l');
                        @endphp
                        <tbody class="text-capitalize">
                            @foreach ($settings as $setting)
                                <tr @if($setting->day == $today) style="background-color:#00cfe84f;"@endif>
                                    <td>{{ $setting->day }}</td>
                                    <td>{{ $setting->start_time }}</a></td>
                                    <td>{{ $setting->end_time }}</td>
                                    <td>{{ $setting->day_off == 1 ? 'Yes' : 'No' }}</td> 
                                    <td>
                                        <a href="javascript:void(0);" class="edit btn btn-warning btn-sm" data-id="{{ $setting->id }}" data-start="{{ $setting->start_time }}" data-end="{{ $setting->end_time }}" data-day-off="{{ $setting->day_off }}"><i data-feather="edit"></i></a>&nbsp;
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MODAL --}}
            <div class="common-popup medium-popup modal fade" id="editSettingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Update Time</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="changeSettingForm" method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="start_time">Start Time</label>
                                            <input type="time" class="form-control start_time" name="start_time" id="start_time">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="end_time">End Time</label>

                                            <input type="time" class="form-control end_time" name="end_time">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="day_off">Day Off</label>

                                            <select name="day_off" id="day_off" class="form-control day_off">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex form-group my-1">
                                            <button type="submit" class="btn btn-danger">Change Status</button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- MODAL END --}}
        </div>
        <!-- list and filter end -->
    </section>
    <!-- users list ends -->
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script src="{{ asset('admin/task-list.js') }}" defer></script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>

    <script>
        $(document).on("click", ".badge-status", function() {
            const id = $(this).attr("toggle-id");
            test = changeStatus('tasks', id, '.task-list-table')

        });
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('click','.edit',function(){
                let setting_id = $(this).data('id');
                let start_time = $(this).data('start');
                let end_time = $(this).data('end');
                let day_off = $(this).data('day-off');
                $('#editSettingModal').modal('show');
                $('.start_time').val(start_time);
                $('.end_time').val(end_time);
                $('.day_off').val(day_off).attr('selected');
                let updateUrl = "{{ route('order_settings.update',':id') }}";
                    updateUrl = updateUrl.replace(':id',setting_id);
                $('#changeSettingForm').on('submit', function(e){
                    e.preventDefault();
                    let start = $('.start_time').val();
                    let end = $('.end_time').val();
                    let day_off = $('.day_off').val();
                    $.ajax({
                        type:"POST",
                        url:updateUrl,
                        data:{
                            setting_id: setting_id,
                            start_time: start,
                            end_time: end,
                            day_off: day_off,
                            _method: 'PATCH',
                            _token: "{{ csrf_token() }}"
                        },
                        dataType:"json",
                        success:function(response)
                        {
                            location.reload();
                        }
                    });
                });
            });

            $(document).on('click','.status', function(){
                let task_id = $(this).data('task-id');
                let status = $(this).data('status');
                $('#statusChangeModal').modal('show');
                $('#status_dropdown').val(status).attr('selected');

                $('#changeStatusForm').on('submit', function(e){
                    e.preventDefault();
                    let updateUrl = "{{ route('task.update', ':id') }}";
                    updateUrl = updateUrl.replace(':id', task_id);
                    let newStatus = $('#status_dropdown').val();

                    $.ajax({
                        type:"POST",
                        url: updateUrl,
                        data:{
                            task_id: task_id,
                            status: newStatus,
                            _method: "PUT",
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response){
                            location.reload();
                        }
                    });
                });

            });

            $(document).on('change','.dayOffSwitch',function(){
                let setting_id = $(this).data('set-id');
                let day_off = $(this).prop('checked');
                    
                console.log(setting_id, day_off);
                $.ajax({
                    type: "POST",
                    url: "{{ route('updateDayOff') }}",
                    data:{
                        setting_id: setting_id,
                        day_off: day_off,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType:"json",
                    success: function(response)
                    {
                        var day_off = response.day_off;
                        var setting_id = response.setting_id;
                        $(`.dayOffSwitch[data-set-id="${setting_id}"]`).text(day_off);
                    }
                });
            });
        });
    </script>
@endpush
