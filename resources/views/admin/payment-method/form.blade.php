@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Method</h4>
                    </div>
                    <div class="card-body">
                        @if ($paymentMethod->id)
                            <form action="{{ route('payment-method.update', $paymentMethod->id) }}" method="POST">
                                @method('PATCH')
                            @else
                            <form action="{{ route('payment-method.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Type <span class="text-danger">*</span>:</label>
                                    <select name="type" id="type" class="form-control">
                                        @foreach($types as $index=>$type)
                                        <option value="{{$type}}" {{(@$paymentMethod->type == $type) ? 'selected' : ''}}>{{$index}}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control form-control-sm" id="title" name="title"
                                        value="{{old('title', $paymentMethod->title )}}" placeholder="Enter Title" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Token <span class="text-danger">*</span>:</label>
                                    <input  id="token" type="text" class="form-control form-control-sm" name="token"
                                    value="{{old('token', $paymentMethod->getToken() ?? null )}}" placeholder="Enter Token">
                                    @error('token')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Is Default.....?:</label>
                                    <input type="checkbox"  id="is_default" value="1" name="is_default" {{(@$paymentMethod->is_default == true) ? 'checked' :''}}>
                                    @error('is_default')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($paymentMethod->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
