@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'district')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">districts</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('district.update', $district->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="province"> Province </label>
                                        <select name="province" id="province" class="form-control">
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ $district->province == $province->id ? 'selected' : '' }}>
                                                    {{ $province->eng_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="np_name"> District Name </label>
                                        <input type="text" name="np_name" id="np_name" class="form-control"
                                            value="{{ old('np_name', @$district->np_name) }}">
                                    </div>
                                </div>
                            </div>
                            <x-dashboard.button :create="isset($district->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
