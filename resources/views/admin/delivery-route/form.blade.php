@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Delivery Route</h4>
                    </div>
                    <div class="card-body">
                        @if ($deliveryRoute->id)
                            <form action="{{ route('delivery-route.update', $deliveryRoute->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('delivery-route.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">From</label>
                                    <select name="from" id="from" class="form-control">
                                        <option value="">From</option>
                                        {{-- @foreach($locations as $location)
                                        <option value="{{$location->id}}" @if(@$deliveryRoute->from == $location->id) selected @endif>{{$location->title}}</option>
                                        @endforeach --}}

                                        @foreach($hubs as $hub)
                                            <option value="{{$hub->id}}" @if(@$deliveryRoute->hub_id == $hub->id) selected @endif>{{$hub->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('from')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">To</label>
                                    <select name="to" id="to" class="form-control">
                                        <option value="">To</option>
                                        {{-- @foreach($locations as $location)
                                        <option value="{{$location->id}}" @if(@$deliveryRoute->to == $location->id) selected @endif>{{$location->title}}</option>
                                        @endforeach --}}

                                        @foreach($areas as $area)
                                            <option value="{{$area->id}}" @if(@$deliveryRoute->location_id == $area->id) selected @endif>{{$area->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('to')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                {{-- <div class="mb-1">
                                    <x-filemanager name='logo' :value="$deliveryRoute->logo"></x-filemanager>
                                </div> --}}
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($deliveryRoute->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
