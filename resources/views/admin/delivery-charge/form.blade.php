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
                        @if ($deliveryCharge->id)
                            <form action="{{ route('delivery-charge.update', $deliveryCharge->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('delivery-charge.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">From -- To</label>
                                    <select name="route" id="from" class="form-control form-control-sm">
                                        <option value="">From -- To</option>

                                        @foreach($deliveryRoutes as $route)
                                        <option value="{{$route->id}}" @if(@$deliveryCharge->delivery_route_id == $route->id || old('route') == $route->id) selected @endif>
                                            {{$route->location_from->local_name .' --To-- '. $route->location_to->local_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('route')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Currency</label>
                                    <input type="text" class="form-control form-control-sm" name="currency" value="{{old('currency', @$deliveryCharge->currency)}}" placeholder="$">
                                    @error('currency')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Branch Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="branch_delivery" value="{{old('branch_delivery', @$deliveryCharge->branch_delivery)}}" placeholder="200">
                                    @error('branch_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Branch With Express Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="branch_express_delivery" value="{{old('branch_express_delivery', @$deliveryCharge->branch_express_delivery)}}" placeholder="200">
                                    @error('branch_express_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Branch With Normal Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="branch_normal_delivery" value="{{old('branch_normal_delivery', @$deliveryCharge->branch_normal_delivery)}}" placeholder="200">
                                    @error('branch_normal_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Door Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="door_delivery" value="{{old('door_delivery', @$deliveryCharge->door_delivery)}}" placeholder="200">
                                    @error('door_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Door Express Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="door_express_delivery" value="{{old('door_express_delivery', @$deliveryCharge->door_express_delivery)}}" placeholder="200">
                                    @error('door_express_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Charge To Door Normal Delivery</label>
                                    <input type="number" class="form-control form-control-sm" name="door_normal_delivery" value="{{old('door_normal_delivery', @$deliveryCharge->door_normal_delivery)}}" placeholder="200">
                                    @error('door_normal_delivery')
                                        <p class="form-control form-control-sm-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                {{-- <div class="mb-1">
                                    <x-filemanager name='logo' :value="$deliveryCharge->logo"></x-filemanager>
                                </div> --}}
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($deliveryCharge->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
