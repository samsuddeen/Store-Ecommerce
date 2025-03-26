@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Payout Setting')   
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payout Period</h4>
                    </div>
                    <div class="card-body">
                        @if ($payoutSetting->id)
                            <form action="{{ route('payout-setting.update', $payoutSetting->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('payout-setting.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Payout Period<span class="text-danger">*</span>:</label>
                                    <select name="period" id="period" class="form-control form-control-sm" required>
                                        <option value="">Please Select</option>
                                        @foreach($periods as $index=>$period)
                                            <option value="{{$period}}" {{(old('period') == $period || @$payoutSetting->period == $period) ? 'selected' : '' }}>{{$index}}</option>
                                        @endforeach
                                    </select>
                                    @error('period')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">Status<span class="text-danger">*</span>:</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="1" {{(old('status') == 1 ||  $period == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="2" {{(old('status') == 2 ||  $period == 2) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 mt-2">
                                    <label class="form-label" for="mail_encryption">Is Default:</label>
                                    <input type="checkbox" name="is_default" value="1" {{(old('is_default') || (@$payoutSetting->is_default == 1)) ? 'checked' : ''}}>
                                    @error('is_default')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <x-dashboard.button :create="isset($payoutSetting->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
