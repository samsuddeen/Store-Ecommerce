@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Province')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">provinces</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('province.update', $province->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="eng_name"> province Name </label>
                                        <input type="text" name="eng_name" id="eng_name" class="form-control"
                                            value="{{ old('eng_name', @$province->eng_name) }}">
                                    </div>
                                </div>
                            </div>
                            <x-dashboard.button :create="isset($province->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
