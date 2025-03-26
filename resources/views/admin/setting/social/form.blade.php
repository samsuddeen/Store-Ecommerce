@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Social Setting')   
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Social Media</h4>
                    </div>
                    <div class="card-body">
                        @if ($socialSetting->id)
                            <form action="{{ route('social-setting.update', $socialSetting->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('social-setting.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Social Title<span class="text-danger">*</span>:</label>
                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="facebook.com" value="{{old('title', $socialSetting->title)}}" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">URL<span class="text-danger">*</span>:</label>
                                    <input type="text" name="url" class="form-control form-control-sm" placeholder="www.facebook.com" value="{{old('url', $socialSetting->url)}}" required>
                                    @error('url')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Status<span class="text-danger">*</span>:</label>
                                    <select name="sttaus" id="status" class="form-control form-control-sm">
                                        <option value="1" {{(int)$socialSetting->status == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="2" {{(int)$socialSetting->status == 2 ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <x-dashboard.button :create="isset($socialSetting->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
