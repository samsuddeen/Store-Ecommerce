@extends('layouts.app')
@section('title','Push Notification')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Push Notitfication</h4>
                    </div>
                    <div class="card-body">
                        @if ($pushNotification->id)
                            <form action="{{ route('push-notification.update', $pushNotification->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('push-notification.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span class="text-danger">*</span>:</label>
                                    <input type="text" name="title" class="form-control form-control-sm" value="{{old('title', @$pushNotification->title)}}" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">URL:</label>
                                    <input type="text" name="url" class="form-control form-control-sm" value="{{old('url', @$pushNotification->url)}}">
                                    @error('url')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Summary<span class="text-danger">*</span>:</label>
                                    <textarea name="summary" id="summary" class="form-control form-control-sm" rows="3" required>{{old('summary', @$pushNotification->summary)}}</textarea>
                                    @error('summary')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Description:</label>
                                    <textarea name="description" id="description" class="form-control form-control-sm" rows="3">{{old('description', @$pushNotification->description)}}</textarea>
                                    @error('description')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-md-4 col-12">
                                <x-filemanager :value="@$pushNotification->image"></x-filemanager>
                            </div>

                            
                            {{-- <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Users<span class="text-danger">*</span>:</label>
                                <select name="for" id="for" class="form-control form-control-sm">
                                    <option value="please_select">Please Select</option>
                                    @foreach($for_users as $index=>$for)
                                        <option value="{{$for}}" {{(int)@$pushNotification->for ==(int) $for ? 'selected' : ''}}>{{$index}}</option>
                                    @endforeach
                                </select>
                                @error('for')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div> --}}


                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Status<span class="text-danger">*</span>:</label>
                                <select name="status" id="status" class="form-control form-control-sm" required>
                                    <option value="">Please Select</option>
                                    @foreach($statuses as $index=>$status)
                                        <option value="{{$status}}" {{(old('status') ==(int) $status || (int)@$pushNotification->status ==(int) $status) ? 'selected' : ''}}>{{$index}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-12 col-12" id="selection-block" 

                            @if(@$pushNotification->for !==null && (int)@$pushNotification->for !== 1)
                            style="display:block"
                            @else
                            style="display: none"
                            @endif
                            >
                                <label class="form-label" for="publishStatus">Customers<span class="text-danger">*</span>:</label>

                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select</option>
                                    @foreach($customers as $index=>$customer)
                                        <option value="{{$customer->id}}" {{(in_array($customer->id, json_decode($pushNotification->selection) ?? [])) ? 'selected' : ''}}>{{$customer->name}}</option>
                                    @endforeach
                                </select>

                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($pushNotification->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('script')
<script>
    $(document).on('change', '#for', function(e){
        e.preventDefault();
        if($(this).val() !== 1){
            $('#selection-block').css({"display":"block"});
        } 
        if($(this).val() == 'please_select'){
            $('#selection-block').css({"display":"none"});
        } 
        if($(this).val() == 1){
            $('#selection-block').css({"display":"none"});
        }
    });
</script>
@endpush