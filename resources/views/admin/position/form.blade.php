@extends('layouts.app')
@section('content')
    <section id="default-breadcrumb">
        @if ($position->id)
            <form action="{{ route('position.update', $position->id) }}" method="post">
                @method('patch')
            @else
                <form action="{{ route('position.store') }}" method="post">
        @endif
        @csrf
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" id="title" aria-describedby="titleId"
                        placeholder="position title" value="{{ $position->title }}" required>
                    @error('title')
                        <small id="titleId" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">key</label>
                    <input type="text" class="form-control" name="key" id="key" aria-describedby="keyId"
                        placeholder="position key" value="{{ $position->key }}" required>
                    @error('key')
                        <small id="keyId" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-4 mt-2">
                <x-dashboard.button :create="isset($position->id)"></x-dashboard.button>

            </div>
        </div>

        </form>
    </section>
@endsection
