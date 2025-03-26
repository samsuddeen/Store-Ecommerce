@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Customer Coupon')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Coupon</h4>
                    </div>
                    <div class="card-body">
                        @if ($customerCoupon->id)
                        <form action="{{ route('customer-coupon.update', $customerCoupon->id) }}" method="POST">
                            @method('PATCH')
                            @else
                            <form action="{{ route('customer-coupon.store') }}" method="POST">
                                @endif
                                {{-- @dd($coupons) --}}
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Choose Customer:</label>
                                    <select class="form-control classSelecte" id="customer" name="customer_id">
                                        <option value="">Please Select Customer</option>
                                        <option value="allData" {{($customerCoupon->customer_id==0) ? 'selected':''}}>All</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id || @$customerCoupon->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    @error('customer_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Choose Coupons:</label>
                                    <select class="form-control " id="" name="coupon_id" required>
                                        <option value="">Please Select Coupons</option>
                                        @foreach ($coupons as $coupon)
                                            <option value="{{ $coupon->id }}"
                                                {{ old('coupon_id') == $coupon->id || @$customerCoupon->coupon_id == $coupon->id ? 'selected' : '' }}>
                                                {{ $coupon->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('coupon_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 mt-3" >
                                    <label class="form-label" for="title">Code: <input
                                            type="text" class="form-control" name="code" value="{{ old('code',@$customerCoupon->code) }}"></label>
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 mt-3 sasmeData" >
                                    <label class="form-label userDataValue" for="title">Only For This User.....?: <input
                                            type="checkbox" value="1" name="is_for_same"
                                            {{ old('is_for_same') == 1 || @$customer->is_for_same == 1 ? 'checked' : '' }}></label>
                                    @error('is_for_same')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($customerCoupon->id)"></x-dashboard.button>
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

        $(document).on('change','.classSelecte',function()
        {
            var selecetdValue=$(this).val();
            
            if(selecetdValue=='allData')
            {
                $('.userDataValue').attr('hidden',true);
            }
            else
            {
                $('.userDataValue').removeAttr('hidden');
            }
        });
        @isset($customerCoupon)
        $('.classSelecte').change();
        @endisset
    </script>
@endpush
