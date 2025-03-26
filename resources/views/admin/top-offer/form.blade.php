@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Top Offers')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Top Offer</h4>
                    </div>
                    <div class="card-body">
                        @if ($topOffer->id)
                            <form action="{{ route('top-offer.update', $topOffer->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('top-offer.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{ $topOffer->title }}" placeholder="Enter brand name" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">From:</label>
                                    <input type="date" class="form-control form-control-sm" id="" name="from"
                                        value="{{ old('from', @$topOffer->from) }}" placeholder="">
                                    @error('from')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">To:</label>
                                    <input type="date" class="form-control form-control-sm" id="" name="to"
                                        value="{{ old('to', @$topOffer->to) }}" placeholder="">
                                    @error('to')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Offer :</label>
                                    <input type="number" class="form-control form-control-sm" id="" name="offer"
                                        value="{{ old('offer', @$topOffer->offer) }}" placeholder="" required>
                                    @error('offer')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 mt-3">
                                    <label class="form-label" for="count">Is Fixed...?:</label>
                                    <input type="checkbox" name="is_fixed" value="1" {{(@$topOffer->isFixed) ? 'checked' : ''}}>
                                    @error('is_fixed')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="input-group-btn">
                                        <a id="lfm11" data-input="thumbnail" data-preview="holder"
                                            class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (600 *
                                                600)</span>
                                        </a>
                                    </label>
                                    <input id="thumbnail" class="form-control" type="text" name="image" value="{{old('image', @$topOffer->image)}}" >
                                    @error('image')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                    @if($topOffer->image !=null)
                                        <img src="{{$topOffer->image}}" alt="" class="img img-thumbnail img-fluid" style="height:100px;width:100px">
                                    @endif
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Status:</label>
                                    <select name="status"  class="form-control form-control-sm" id="">
                                        <option value="1"  {{(@$topOffer->status == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0"  {{(@$topOffer->status == 0) ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <x-dashboard.button :create="isset($topOffer->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
    $('#lfm11').filemanager('image');
</script>
@endpush
