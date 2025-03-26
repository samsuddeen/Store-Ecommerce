@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Reward Section')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Reward Section</h4>
                    </div>
                    <div class="card-body">
                        @if (@$rewardsection->id)
                            <form action="{{ route('rewardsection.update', $rewardsection->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('rewardsection.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" name="title" class="form-select" placeholder="Enter reward Title Here" required value="{{old("title", @$rewardsection->title)}}">
                                    @error('title')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="from">Start From</label>
                                    <input type="date" name="from" class="form-select" placeholder="Enter reward from Here" value="{{old("from", @$rewardsection->from)}}">
                                    @error('from')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="to">End To</label>
                                    <input type="date" name="to" class="form-select" placeholder="Enter reward to Here" value="{{old("to", @$rewardsection->to)}}">
                                    @error('to')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="points">Reward Points</label>
                                    <input type="number" name="points" class="form-select" placeholder="Enter reward points Here" required value="{{old("points", @$rewardsection->points)}}">
                                    @error('points')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-4">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Status</label>
                                    {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$rewardsection->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($rewardsection->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
