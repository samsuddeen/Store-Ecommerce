@extends('layouts.app')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    @if ($customer->id)
                    <form class="row gy-1 pt-75" action="{{ route('customer.update', $customer->id) }}" method="post">
                        @method('PATCH')
                        @else
                        <form class="row gy-1 pt-75" action="{{ route('customer.store') }}" method="post">
                            @endif
                            @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Customer name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $customer->name) }}" placeholder="john.doe.007" required/>
                        @error('name')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email"> Email: {{ $customer->email }}</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email', $customer->email) }}" placeholder="example@domain.com" required/>
                        @error('email')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="province">Province</label>
                        <select name="province" class="form-control province" required>
                            @if(isset($customer))
                                <option> {{$customer->province}} </option>
                            @else   
                                <option> select option </option>
                            @endif
                            @foreach($province as $pro)
                                <option value="{{$pro->id}}">{{$pro->eng_name}}</option>
                            @endforeach
                        </select>
                        @error('province')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="district">District (Please select province first)</label>
                        <select type="text" id="district" name="district" class="form-control district">
                            @if(isset($customer))
                                <option> {{$customer->district}} </option>
                            @else   
                                <option> select option </option>
                            @endif
                        </select>
                        @error('district')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="area">Address (Please select district first)</label>
                        <select type="text" id="area" name="area" class="form-control local" required>
                            
                            @if(isset($customer))
                                <option> {{$customer->area}} </option>
                            @else   
                                <option> select option </option>
                            @endif
                        </select>
                        @error('area')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="phone">Contact</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                            value="{{ old('phone', $customer->phone) }}" placeholder="9134622145" />
                        @error('phone')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="zip">Zip</label>
                        <input type="text" id="zip" name="zip" class="form-control"
                            value="{{ old('zip', $customer->zip) }}" placeholder="44043" />
                        @error('zip')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>



                    {{--
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="birthday">Birth Date</label>
                        <input  value="{{ old('birthday', $customer->birthday) }}" type="date" id="birthday" name="birthday" class="form-control"
                            placeholder="dds/mm/yyyy" />
                        @error('birthday')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="birthday">Gender</label>
                        <div class="demo-inline-spacing">
                            @foreach ($genders as $key => $gender)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender"
                                        id="{{ $gender->name . 'id' }}" value="{{ $key }}" {{ $gender == $customer->gender ? 'checked' : null }} />
                                    <label class="form-check-label"
                                        for="{{ $gender->name . 'id' }}">{{ $gender->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}


                    @if (!$customer->id)
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="password"> password:</label>
                            <input type="password" id="password" name="password" class="form-control" required/>
                            @error('password')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="password_confirmation">password confirmation</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" />
                        </div>
                    @endif

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="customSwitch10" name="status"
                                    {{ $customer->status == '1' ? 'checked' : null }} />
                                <label class="form-check-label" for="customSwitch10">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="customSwitch10">User Status</label>
                            @error('status')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" aria-label="Close">
                            Discard
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $(document).ready(function() {
        $('.province').on('change', function() {
            var province_id = $('.province').val();
            $.ajax({
                url: "{{ route('getDistrict') }}",
                type: 'post',
                data: {
                    province_id: province_id,
                },
                // dataType: 'JSON',
                success: function(response) {
                        console.log(response);
                        $('.district').empty();
                        $('.district').append('<option >Select District</option>');
                        $.each(response.districts, function (key, value) {
							$('.district').append('<option value="'+ value.id +'">'+ value.np_name +'</option>');
						})
                },
                error: function(response) {
                }
            });
        })
    })
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $(document).ready(function() {
        $('.district').on('change', function() {
            var district_id = $('.district').val();

            $.ajax({
                url: "{{ route('getLocal') }}",
                type: 'post',
                data: {
                    district_id: district_id,
                },
                // dataType: 'JSON',
                success: function(response) {
                        console.log(response);
                        $('.local').empty();
                        $('.local').append('<option >Select Local</option>');
                        $.each(response.locals, function (key, value) {
							$('.local').append('<option value="'+ value.id +'">'+ value.city_name +'</option>');
						})
                },
                error: function(response) {
                }
            });
        })
    })
</script>
@endpush
