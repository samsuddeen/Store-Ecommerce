@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Coupon')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Coupon</h4>
                    </div>
                    <div class="card-body">
                        @if ($coupon->id)
                            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('coupon.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control" id="name" name="title"
                                        value="{{ old('title', @$coupon->title) }}" placeholder="Enter Coupon name" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Code</label>
                                    {{-- {!! Form::text("coupon_code", $coupon_code, ["class" => "form-control", "id" => "coupon_code", "placeholder" => "Enter Coupon Code"]) !!} --}}
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code"
                                        value="{{ old('coupon_code',@$coupon->coupon_code) }}" placeholder="Enter Coupon code">
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">From:</label>
                                    <input type="date" class="form-control" id="from" name="from"
                                        value="{{ old('from', @$coupon->from) }}" placeholder="Enter Starting Date" required>
                                    @error('from')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">To:</label>
                                    <input type="date" class="form-control" id="to" name="to"
                                        value="{{ (old('to', @$coupon->to)) }}" placeholder="Enter Coupon name" required>
                                    @error('to')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Discount</label>
                                    <div class="input-group">
                                        <input type="text" name="discount" value="{{ old('discount', @$coupon->discount) }}"
                                            class="form-control" aria-label="Text input with segmented dropdown button" required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            id="display-option">%</button>
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item select-options" data-value="USD" href="#">USD</a>
                                            </li>
                                            <li><a class="dropdown-item select-options" data-value="AUD" href="#">AUD</a>
                                            </li>
                                            <li><a class="dropdown-item select-options" data-value="$" href="#">$</a>
                                            </li>
                                            <li><a class="dropdown-item select-options" data-value="IRS" href="#">IRS</a>
                                            </li>
                                            <li><a class="dropdown-item select-options" data-value="%" href="#">%</a></li>
                                        </ul>
                                    </div>
                                    @error('discount')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Status:</label>
                                    <select class="form-control" id="publishStatus" name="publishStatus">
                                        <option value="1"   {{((old('publishStatus') == 1) || @$coupon->publishStatus == 1) ? 'selected' : ''}}>Active</option>
                                        <option value="0"   {{((old('publishStatus') == 0) || @$coupon->publishStatus == 0) ? 'selected' : ''}}>Inactive</option>
                                    </select>
                                    @error('publishStatus')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <input type="text" hidden name="check" id="check"
                                @if (!isset($coupon)) value="%"
                                @else
                                  value="{{ @$coupon->is_percentage == 'yes' ? '%' : '' }}" @endif>
                        </div>
                        <x-dashboard.button :create="isset($coupon->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.select-options').on('click', function(e) {
                e.preventDefault();
                var thisValue = $(this).data('value');
                $('#check').val(thisValue);
                $('#display-option').text(thisValue);
            });
        });
    </script>
@endpush
