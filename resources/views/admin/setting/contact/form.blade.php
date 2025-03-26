@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Contact Setting')   
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Contact</h4>
                    </div>
                    <div class="card-body">
                        @if ($contactSetting->id)
                            <form action="{{ route('contact-setting.update', $contactSetting->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('contact-setting.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Contact Type<span
                                            class="text-danger">*</span>:</label>
                                    <select name="type" id="type" class="form-control form-control-sm" required>
                                        <option value="">Please Select</option>
                                        @foreach ($contact_types as $index => $contact_type)
                                            <option value="{{ $contact_type }}"
                                                {{ $contact_type == $contactSetting->type ? 'selected' : '' }}>
                                                {{ $index }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Conatct<span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" name="contact_no" class="form-control form-control-sm" required
                                        placeholder="" value="{{ old('contact_no', $contactSetting->contact_no) }}">
                                    @error('contact_no')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="mail_user_name">Status<span
                                            class="text-danger">*</span>:</label>
                                    <select name="sttaus" id="status" class="form-control form-control-sm">
                                        <option value="1" {{ (int) $contactSetting->status == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="2" {{ (int) $contactSetting->status == 2 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <x-dashboard.button :create="isset($contactSetting->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
