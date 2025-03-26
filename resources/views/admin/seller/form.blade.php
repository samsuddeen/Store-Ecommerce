@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sellers')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tag</h4>
                    </div>
                    <div class="card-body">
                        @if ($seller->id)
                            <form action="{{ route('seller.update', @$seller->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PATCH')
                            @else
                                <form action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Name <span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="name"
                                        value="{{old('name', @$seller->name )}}" placeholder="Enter tag name">
                                    @error('name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Email <span class="text-danger">*</span>:</label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email"
                                        value="{{old('email', @$seller->email )}}" placeholder="Enter tag name">
                                    @error('email')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Phone <span class="text-danger">*</span>:</label>
                                    <input type="number" class="form-control form-control-sm" id="phone" name="phone"
                                        value="{{old('phone', @$seller->phone )}}" placeholder="Enter tag name">
                                    @error('phone')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Company Name :</label>
                                    <input type="text" class="form-control form-control-sm" name="company_name" value="{{old('company_name', @$seller->company_name)}}">
                                    @error('company_name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Address <span class="text-danger">*</span>:</label>
                                    <input type="address" class="form-control form-control-sm" id="address" name="address"
                                        value="{{old('address', @$seller->address )}}" placeholder="Enter tag name">
                                    @error('address')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Province :</label>
                                    <select name="province_id" id="province_id" data-element="#district_id" class="form-control form-control-sm">
                                        <option value="">Please Select</option>
                                        @foreach($provinces as $province)
                                           <option value="{{$province->id}}" {{(old('province_id') == $province->id || @$seller->province_id == $province->id) ? 'selected' : ''}}>{{$province->eng_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    {{-- @if(isset($seller)) --}}
                                        @php
                                            $districts = getDistrictByProvinceId(@$seller->province_id ?? old('province_id'));    
                                        @endphp
                                    {{-- @endif --}}
                                    <label class="form-label" for="title">District :</label>
                                    <select name="district_id" id="district_id" class="form-control form-control-sm">
                                        <option value="">Please Select</option>
                                        @if(isset($seller))
                                          @foreach($districts as $district)
                                           <option value="{{$district->id}}" {{(old('district_id') == $district->id || $district->id == @$seller->district_id) ?  'selected' : ''}}>{{$district->np_name}}</option>
                                          @endforeach
                                        @endif
                                    </select>
                                    @error('district_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Zip :</label>
                                    <input type="text" class="form-control form-control-sm" name="zip" value="{{@$seller->zip}}">
                                    @error('zip')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                           

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name='image' :value="@$seller->photo"></x-filemanager>
                                   
                                </div>
                                
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($seller->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
<script>
    $('#province_id').on('change', function(e){
        e.preventDefault();
        let province_id = $(this).val();
        let setup_element = $(this).data('element');
        getDistricts(province_id, setup_element);

    });
    function getDistricts(province_id, setup_element){
        $.ajax({
            url: "/get-districts?province_id="+province_id,
            type: "get",
            contentType: false,
            processData: false,
            dataType: 'json',
            data: "",
            success: function(response) {
                console.log(response);
                let html_string = '<option value="">Please Select</option>';
                response.forEach(element => {
                    html_string += "<option value='" + element.id + "' data-room='" + JSON
                        .stringify(element) + "'>" + element.np_name + "</option>";
                });
                $(setup_element).html(html_string);
            },
            error: function(error) {

            }
        });
    }
</script>
@endpush
