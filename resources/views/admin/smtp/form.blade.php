@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Mail Setting')   
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">SMTP</h4>
                    </div>
                    <div class="card-body">
                        @if ($smtp->id)
                            <form action="{{ route('smtp.update', $smtp->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('smtp.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Mail User Name<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="mail_user_name" required
                                        value="{{old('mail_user_name', $smtp->mail_user_name )}}" placeholder="Enter Mail User Name">
                                    @error('mail_user_name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_driver">Mail Driver</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_driver" name="mail_driver"
                                        value="{{old('mail_driver', $smtp->mail_driver )}}" placeholder="Enter Mail Driver">
                                    @error('mail_driver')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_host">Mail Host <span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_host" name="mail_host"
                                        value="{{old('mail_host', $smtp->mail_host )}}" placeholder="Enter Mail Host">
                                    @error('mail_host')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_port">Mail Port</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_port" name="mail_port"
                                        value="{{old('mail_port', $smtp->mail_port )}}" placeholder="Enter Mail Port">
                                    @error('mail_port')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_password">Mail Password <span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_password" name="mail_password" required
                                        value="{{old('mail_password', $smtp->mail_password )}}" placeholder="Enter Mail Password">
                                    @error('mail_password')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_from_address">Mail From Address:</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_from_address" name="mail_from_address"
                                        value="{{old('mail_from_address', $smtp->mail_from_address )}}" placeholder="Enter Mail From Address">
                                    @error('mail_from_address')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_from_name">Mail From Name:</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_from_name" name="mail_from_name"
                                        value="{{old('mail_from_name', $smtp->mail_from_name )}}" placeholder="Enter Mail Host">
                                    @error('mail_from_name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_encryption">Mail Encryption:</label>
                                    <input type="text" class="form-control form-control-sm" id="mail_encryption" name="mail_encryption"
                                        value="{{old('mail_encryption', $smtp->mail_encryption )}}" placeholder="Enter Mail Encryption">
                                    @error('mail_encryption')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 mt-2">
                                    <label class="form-label" for="mail_encryption">Is Default:</label>
                                    <input type="checkbox" name="is_default" value="1" {{(old('is_default') || (@$smtp->is_default == 1)) ? 'checked' : ''}}>
                                    @error('is_default')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <x-dashboard.button :create="isset($smtp->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
