@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Country')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/forms/select/select2.min.css') }}">
@endpush

@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Country</h4>
                    </div>
                    <div class="card-body">
                        @if ($countries->id)
                            <form action="{{ route('countries.update', $countries->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('countries.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', @$countries->name) }}" placeholder="Please Enter countries Name"
                                        required>
                                    @error('name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="zipcode">Zip Code</label>
                                    <input type="text" class="form-control" id="country_zip" name="country_zip"
                                        value="{{ old('country_zip', @$countries->country_zip) }}" placeholder="Please Enter countries zipcode"
                                        required>
                                    @error('country_zip')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name='flags' :value="@$countries->flags"></x-filemanager>
                                </div>
                               
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="status">Status</label>
                                {{ Form::select('status',['Active'=>'Active','Inactive'=>'In-Active'],@@$countries->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                               
                                @error('status')
                                    <p class="form-control-static text-danger" id="status">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="form">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                  <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <p  data-bs-toggle="tooltip" data-bs-placement="top" title="By Adding Near Place that help you to manage the shipment charge and the delivery route">
                                            Add Near Place
                                        </p>
                                    </button>
                                  </h2>
                                  <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4" id="first-row">
                                                <ul class="list-unstyled">
                                                    @foreach ($districts as $district)
                                                        <li>
                                                            <input class="checkbox-selection1" data-element="#second-row" type="checkbox" name="district_id[]" value="{{$district->id}}" data-id="{{$district->id}}" data-type="district"><label for="" class="ms-1">{{$district->np_name}}</label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-4" id="second-row">
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <input  type="checkbox" id="select-all-local" name="select_all_local"><label for="" class="ms-1">Select All</label>
                                                    </li>
                                                </ul>
                                                    
                                            </div>
                                            <div class="col-md-4" id="third-row">
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <input  type="checkbox" id="select-all-location" name="select_all_location"><label for="" class="ms-1">Select All</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div> --}}
                        <x-dashboard.button :create="isset($countries->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
  <script>
    let district_ids = [];
    let district_data = [];
    let local_ids = [];
    checkBoxSelection1();
    checkBoxSelection2();
    function checkBoxSelection1(){
        $('.checkbox-selection1').on('click', function(e){
        let id = $(this).data('id');
        let divElement = $(this).data('element');
        let type = $(this).data('type');
        if($(this).is(':checked')){
            district_ids.push(id);
            getAjaxValues(id, type, divElement);
        }else{
            const index = district_ids.indexOf(id);
            if (index > -1) { 
                district_ids.splice(index, 1); 
                $('#district-list-data-'+id).remove();
                $('#local-list-data-'+id).remove();
            }
        }
    });
    }

    function checkBoxSelection2(){
        $('.checkbox-selection2').on('click', function(e){
        let id = $(this).data('id');
        let divElement = $(this).data('element');
        let type = $(this).data('type');
        if($(this).is(':checked')){
            local_ids.push(id);
            getAjaxValues(id, type, divElement);
        }else{
            const index = local_ids.indexOf(id);
            if (index > -1) { 
                local_ids.splice(index, 1); 
                $('#local-list-data-'+id).remove();
            }
        }
    });
    }

    function getAjaxValues(id, type, divElement){
        $.ajax({
            url: "/admin/address-countries?id="+id+"&type="+type,
            type: "get",
            contentType: false,
            processData: false,
            dataType: 'json',
            data:"",
            success: function (response) {
                let html_string =  "<ul class='list-unstyled' id='";
                if(type == 'district'){
                    html_string +="district-list-data-"+response.id+"'>";
                }else{
                    html_string +="local-list-data-"+response.id+"'>";
                }
                    response.data.forEach(element=>{
                        html_string +="<li>";
                        html_string += "<input class='checkbox-selection2' data-element='#third-row' type='checkbox'";
                        if(type == "district"){
                            html_string += "name='local_id[]'";
                        }else{
                            html_string += "name='location_id[]'";
                        }
                        html_string +="value='"+element.id+"' data-id='"+element.id+"' data-type='local'>";
                        if(type == 'district'){
                            html_string +="<label for='' class='ms-1'>"+element.local_name+"</label>";
                        }else{
                            html_string +="<label for='' class='ms-1'>"+element.title+"</label>";
                        }
                        html_string +="</li>";
                    });
                    html_string +="</ul>";
                    $(divElement).append(html_string);
                    if(type == 'district'){
                        checkBoxSelection2();
                    }
                    
            },
            error: function (error) {
                
            }
        });
    }
  </script>
@endpush