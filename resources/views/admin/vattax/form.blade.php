@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Vat Tax </h4>
                    </div>
                    <div class="card-body">
                        @if ($vatTax->id)
                            <form action="{{ route('vat-tax.update', $vatTax->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('vat-tax.store') }}" method="POST">
                        @endif
                        @csrf
                        
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="vat_percent">Vat Percent</label>
                                    <input type="text" class="form-control" id="vat_percent" name="vat_percent"
                                        value="{{old('vat_percent', $vatTax->vat_percent )}}" placeholder="Enter tag name" required>
                                    @error('vat_percent')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>    
                            
                            <div class="col-xl-4 col-md-6 col-12">
                                <label for="tax_percent"> Tax Percent</label>
                                <input type="text" name="tax_percent" id="tax_percent" class="form-control" value="{{old('tax_percent', @$vatTax->tax_percent)}}" required>
                                @error('tax_percent')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>                           

                        </div>
                        {{-- <div class="col-md-4 col-12">
                            <label class="form-label" for="publishStatus">Status</label>
                            {{ Form::select('publishStatus',[1=>'Active',0=>'In-Active'],@$vatTax->publishStatus,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}

                            @error('publishStatus')
                                <p class="form-control-static text-danger" id="publishStatus">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        

                        <x-dashboard.button :create="isset($vatTax->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
