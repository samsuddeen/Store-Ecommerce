@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Location')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Location</h4>
                    </div>
                    <div class="card-body">
                        @if ($location->id)
                            <form action="{{ route('location.update', $location->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('location.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Select Local:</label>
                                    <select name="local_id" id="local_id" class="form-control select2">
                                        <option value="">Please Select</option>
                                        @foreach ($locals as $local)
                                            <option value="{{ $local->id }}" <?php echo $location->local_id === $local->local_level_id ? 'selected' : ''; ?>>
                                                {{ $local->city_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('local_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control" id="name" name="title"
                                        value="{{ $location->title }}" placeholder="Enter brand name">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Near Hub:</label>
                                    <select name="hub_id" id="hub_id" class="form-control select2">
                                        <option value="">Please Select</option>
                                        @foreach ($hubs as $hub)
                                            <option value="{{ $hub->id }}" <?php echo @$hub->id === @$location->nearPlace->hub->id ? 'selected' : ''; ?>>
                                                {{ $hub->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hub_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Hub to Place Price:</label>
                                    <input type="number" name="charge" class="form-control"
                                        value="{{ old('charge', @$location->deliveryRoute->charge) }}">
                                    @error('charge')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label" for="zip_code">Zip Code</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control"
                                    value="{{ old('zip_code', @$location->zip_code) }}">
                                @error('zip_code')
                                    <p class="form-control-static text-danger" id="publishStatus">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Status</label>
                                {{ Form::select('publishStatus', [1 => 'Active', 0 => 'In-Active'], @$location->publishStatus, ['class' => 'select2 form-select', 'required' => true, 'placeholder' => '------------Select Any One-----------']) }}

                                @error('publishStatus')
                                    <p class="form-control-static text-danger" id="publishStatus">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($location->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
