@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">SMS</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><span class="text-danger">{{ $error }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($sMS->id)
                            <form action="{{ route('sms.update', $sMS->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('sms.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title/Subject <span class="text-danger">*</span>:</label>
                                    <input type="text" name="title" class="form-control form-control-sm" value="{{old('title', @$sMS->title)}}" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">URL:</label>
                                    <input type="text" name="url" class="form-control form-control-sm" value="{{old('url', @$sMS->url)}}">
                                    @error('url')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Content<span class="text-danger">*</span>:</label>
                                    <textarea name="content" id="content" class="form-control form-control-sm" rows="3" required>{{old('content', @$sMS->content)}}</textarea>
                                    @error('content')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Users<span class="text-danger">*</span>:</label>
                                <select name="for" id="for" class="form-control form-control-sm" required>
                                    <option value="please_select">Please Select</option>
                                    @foreach($for_users as $index=>$for)
                                   
                                    @if($for == 8 || $for == 7)

                                    @else    
                                        <option value="{{$for}}" {{(int)@$sMS->for ==(int) $for ? 'selected' : ''}} >{{$index}}</option>

                                    @endif

                                    @endforeach
                                </select>
                                @error('for')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- <div class="col-md-4 col-12" id="selection-block" 
                            @if(@$sMS->for !==null && (int)@$sMS->for !== 1)
                            style="display:block"
                            @else
                            style="display: none"
                            @endif
                            >
                                <label class="form-label" for="publishStatus">Customers<span class="text-danger">*</span>:</label>

                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select</option>
                                    @foreach($customers as $index=>$customer)
                                        <option value="{{$customer->id}}" {{(in_array($customer->id, json_decode($sMS->selection) ?? [])) ? 'selected' : ''}}>{{$customer->name}}</option>
                                    @endforeach
                                </select>

                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-4 col-12" id="customer_selection">
                                <label class="form-label" for="publishStatus">Customers<span class="text-danger">*</span>:</label>
                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select user</option>
                                    @foreach($customers as $index=>$customer)
                                        <option value="{{$customer->id}}" {{(in_array($customer->id, json_decode($sMS->selection) ?? [])) ? 'selected' : ''}}>{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="col-md-4 col-12" id="customer_selection">
                                <label class="form-label" for="publishStatus">Customers<span class="text-danger">*</span>:</label>
                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select user</option>
                                    @foreach($phone_lists as $index=>$phone_lists)
                                        <option value="{{$phone_lists->id}}" {{(in_array($phone_lists->id, json_decode($sMS->selection) ?? [])) ? 'selected' : ''}}>{{$phone_lists->phone}}</option>
                                    @endforeach
                                </select>
                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4 col-12" id="district">
                                <label class="form-label" for="publishStatus">District<span class="text-danger">*</span>:</label>
                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select district</option>
                                    @foreach($customers as $index=>$customer)
                                        <option value="{{$customer->id}}" {{(in_array($customer->id, json_decode($sMS->selection) ?? [])) ? 'selected' : ''}}>{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {{-- <div class="col-md-4 col-12" id="province">
                                <label class="form-label" for="publishStatus">Province<span class="text-danger">*</span>:</label>
                                <select name="selection[]" id="selection" class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Select province</option>
                                    @foreach($addr as $index=>$province)
                                        <option value="{{$province['id']}}" >{{$province['name']}}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {{-- @dd(@$addr) --}}
                            {{-- @if($sMS->) --}}
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Status<span class="text-danger">*</span>:</label>
                                <select name="status" id="status" class="form-control form-control-sm" required>
                                    <option value="">Please Select</option>
                                    @foreach($statuses as $index=>$status)
                                        <option value="{{$status}}" {{(old('status') ==(int) $status || (int)@$sMS->status ==(int) $status) ? 'selected' : ''}}>{{$index}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>
                        <x-dashboard.button :create="isset($sMS->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('script')
{{-- <script>
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
</script> --}}


<script>
   
    $(document).ready(function(){
       
        $('#customer_selection').hide();

                $('#district').hide();
                $('#province').hide();
        $('#for').on('change',function(){
            if($(this).val() == 1){
                $('#customer_selection').hide();
                $('#district').hide();
                $('#province').hide();
            }
            if($(this).val() == 2 || $(this).val() == 3){
                $('#customer_selection').show();
                $('#district').hide();
                $('#province').hide();
            }   
            if($(this).val() == 4){
                $('#district').show();
                $('#customer_selection').hide();
                $('#province').hide();

            }
            if($(this).val() == 5){
                $('#customer_selection').hide();
                $('#province').show();
                $('#district').hide();

            }
        });

        @if($sMS->for == 2)
        $('#customer_selection').show();
        @endif
    });

</script>

@endpush