@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Reward Setup')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Reward Setup</h4>
                    </div>
                    <div class="card-body">
                            <form action="{{route('rewardsetup',$data->id)}}" method="POST">
                                @method('PATCH')
                           
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="point">Points</label>
                                    <input type="number" min="1" name="point" class="form-select" placeholder="Enter reward point Here" required value="{{@$data->point}}">
                                    @error('point')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="value">Value</label>
                                    <input type="number" min="1"  name="value" class="form-select" placeholder="Enter reward value Here" required value="{{@$data->value}}">
                                    @error('value')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="currency">Currency</label>
                                    {{ Form::select('currency',['nrs'=>'NRS','usd'=>'USD'],@$data->currency,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                                    @error('currency')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="currency_value">Currency Value</label>
                                    <input type="number" min="1" name="currency_value" class="form-select" placeholder="Enter reward currency_value Here" required value="{{@$data->currency_value}}">
                                    @error('currency_value')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                        <x-dashboard.button :create="isset($data->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
