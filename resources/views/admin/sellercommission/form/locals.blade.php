@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'local')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">locals</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('local.update', $local->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="province_id"> Province </label>
                                        <select name="province_id" id="province_id" class="form-control">
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ $local->province_id == $province->id ? 'selected' : '' }}>
                                                    {{ $province->eng_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 clo-sm-12">
                                    <div class="form-group">
                                        <label for="dist_id"> District </label>
                                        <select name="dist_id" id="dist_id" class="form-control">
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ $local->dist_id == $district->id ? 'selected' : '' }}>
                                                    {{ $district->np_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="local_name"> Local Name </label>
                                        <input type="text" name="local_name" id="local_name" class="form-control"
                                            value="{{ old('local_name', @$local->local_name) }}">
                                    </div>
                                </div>
                            </div>
                            <x-dashboard.button :create="isset($local->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
