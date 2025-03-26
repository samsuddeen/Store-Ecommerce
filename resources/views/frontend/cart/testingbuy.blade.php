@extends('frontend.layouts.app')
@section('css')

<style>
    .address-btn
    {
        text-decoration: none;
    }

    .address-btn:hover .btn-texts
    {
        color: black;
    }

    
    .message-box:hover{
        box-shadow: 5px 5px #39a7f0;
    }
</style>
@endsection


@section('content')
<section id="checkOut_wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="form_wrapper">
					<h2>Delivery Detail</h2>
                        <div class="row">
                            <div class="col-md-3 card bg-danger " style="margin-left:150px">
                                <a href="javascript:;"  class="address-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <div class="card-header text-center text-white">
                                        <strong class="btn-texts"> Shipping Address <i class="las la-angle-right"></i></strong>
                                     </div>
                                </a>
                            </div>
                            <div class="col-md-3 card" style="margin-left:150px;background-color:orange">
                                <a href="javascript:;" class="address-btn" data-bs-toggle="modal" data-bs-target="#exampleModalbilling">
                                    <div class="card-header text-center text-white" >
                                        <strong class="btn-texts">Billing Address <i class="las la-angle-right"></i></strong>
                                    </div>
                                </a>
                            </div>
                        </div>
						{{-- <div class="row mb-3">
							<div class="col-lg-12">
								<h2 class="checkout_header">Payment</h2>
							</div>
							<div class="col-lg-4">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="payment" value="esewa" id="flexRadioDefault1" checked>
									<label class="form-check-label" for="flexRadioDefault1">
										esewa
									</label>
								</div>
							</div>
							<div class="col-lg-4">
							<div class="form-check">
									<input class="form-check-input" type="radio" name="payment" value="khalti" id="flexRadioDefault2">
									<label class="form-check-label" for="flexRadioDefault2">
										khalti
									</label>
								</div>
							</div>
							<div class="col-lg-4">
							<div class="form-check">
									<input class="form-check-input" type="radio" name="payment" value="COD" id="flexRadioDefault3">
									<label class="form-check-label" for="flexRadioDefault3">
										Cash On Delivery
									</label>
								</div>
							</div>
                            @php
                                $consumer = auth()->guard('customer')->user();
                                if($consumer->UserShippingAddress != null){
                                    if($consumer->UserShippingAddress->future_use == 1){
                                        $area_id = \App\Models\Local::where('local_name',$consumer->UserShippingAddress->area)->value('id');
                                    }else{
                                        $area_id = \App\Models\Local::where('local_name',$consumer->area)->value('id');
                                    }
                                }else{
                                    $area_id = \App\Models\Local::where('local_name',$consumer->area)->value('id');
                                }
                                $shipping_route = \App\Models\DeliveryRoute::where(['from'=>9, 'to'=>$area_id])->first();
                                if($shipping_route == null){
                                    $shipping_charge = 0;
                                }else{
                                    $shipping_charge = $shipping_route->charge->branch_delivery;
                                }

                            @endphp
                            @php
                                foreach($product->stocks as $key=>$stock){
                                    if($key==0){
                                        if($stock->special_price != null){
                                        $price = $stock->special_price;
                                        }
                                        else{
                                            $price = $stock->price;
                                        }
                                    }
                                }
                            @endphp
                            <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}">
                            <input type="hidden" id="grandTotal" name="grandTotal" value="{{$price + $shipping_charge}}">
                            <input type="hidden" id="shipping_charge" name="shipping_charge" value="{{$shipping_charge}}">
                            <input type="hidden" class="coupoon_code" name="coupoon_code" value="">
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="hide"><button class="btn btn-danger" id="checkout">Continue to Checkout</button></span>
							</div>
						</div> --}}
				</div>
			</div>
			{{-- <div class="col-lg-4">
				<div class="checkout_detail_wrap">
					<div class="row">
						<div class="col-lg-12">
                            <div class="sidebar_chekout">
                                <h2>Total Product</h2>
                                <div class="custom_badge">1</div>
                            </div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<ul>
                                <li>
                                    <div>
                                        <h6>{{$product->name}}</h6>
                                        <a href="{{route('product.details',$product->slug)}}"><small>Brief description</small></a>
                                    </div>
                                    @php
                                        foreach($product->stocks as $key=>$stock){
                                            if($key == 0){
                                                if($stock->special_price != null){
                                                    $price = $stock->special_price;
                                                }else{
                                                    $price = $stock->price;
                                                }
                                            }
                                        }
                                    @endphp
                                    <span>Rs {{$price}}</span>
                                </li>
                                <li>
                                    <div>
                                        <h6>Sub Total</h6>
                                    </div>
                                    <small>Rs <span class="all_sub_total">{{$price}}</span></small>
                                    <input type="hidden" id="all_sub_total" value="{{$price}}">
                                </li>
                                <li>
                                    <div>
                                        <h6>Shipping Charge</h6>
                                    </div>
                                    <small>Rs <span class="shipping_charge">{{$shipping_charge}}</span> </small>
                                </li>
                                <li>
                                    <div>
                                        <p>Total(Rs)</p>
                                    </div>
                                    @php
                                    foreach($product->stocks as $key=>$stock){
                                        if($key == 0){
                                            if($stock->special_price != null){
                                                $total_price = $stock->special_price;
                                            }else{
                                                $total_price = $stock->price;
                                            }
                                        }
                                    }
                                    @endphp
                                    <small class="allTotal">{{$total_price + $shipping_charge}} </small>
                                </li>
							</ul>
						</div>

					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="promo_code">
							<h2>Promo Code</h2>
                            <p class="not_found text-danger"></p>
                            <p class="found text-success"></p>
							<div class="copon_code">
								<input type="text" name="coupon_code" value=""  class="form-control coupon">
								<button class="btn btn-danger coupon_code" data-product_id="{{$product->id}}">Redeem</button>
							</div>
						</div>
					</div>
				</div>

			</div> --}}
		</div>
	</div>
 


    {{-- --------------------------Shipping Address--------------------- --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">My Shipping Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                  @foreach ($shipping_address as $key=>$s_address)
                    <div class="col-md-3 message-box" style="margin-left:20px;margin-bottom:20px;width: 45%;height:180px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                        <span>{{$s_address->name}}</span>
                        <a href="javascript:;" class="float-right" data-bs-toggle="modal" data-bs-target="#exampleModaleditshipping{{$key}}">Edit</a>
                        <p>{{$s_address->phone}}</p>
                        <p>{{$s_address->province}},{{$s_address->district}},{{$s_address->area}}</p>
                        <p>{{$s_address->additional_address}}</p>
                    </div>
                  @endforeach
                  
              </div>
            </div>
          </div>
        </div>
      </div>
    {{-- ----------------------Shipping Address----------------------------------- --}}

    {{-- --------------------------Billing Address--------------------- --}}
    <div class="modal fade" id="exampleModalbilling" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">My Billing Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                  @foreach ($billing_address as $bkey=>$b_address)
                    <div class="col-md-3 message-box" style="margin-left:20px;margin-bottom:20px;width: 45%;height:180px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                        <span>{{$b_address->name}}</span>
                        <a href="javascript:;" class="float-right" data-bs-toggle="modal" data-bs-target="#exampleModaleditbilling{{$bkey}}">Edit</a>
                        <p>{{$b_address->phone}}</p>
                        <p>{{$b_address->province}},{{$b_address->district}},{{$b_address->area}}</p>
                        <p>{{$b_address->additional_address}}</p>
                    </div>
                  @endforeach
                  
              </div>
            </div>
          </div>
        </div>
      </div>
    {{-- ----------------------Billing Address----------------------------------- --}}
    
    {{-- --------------------------Edit Shipping Address--------------------- --}}
    @foreach ($shipping_address as $key=>$s_address)
   
    <div class="modal fade" id="exampleModaleditshipping{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-center" id="exampleModalLabel">Edit Shipping Address</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                  {{ Form::open(['url'=>route('update-shipping-address',@$s_address->id)])}}
                  @method('post')
                    <div class="mb-3">
                        {{ Form::label('name','Name')}}
                        {{ Form::text('name',@$s_address->name,['class'=>'form-control form-control-sm '.($errors->has('name') ?'is-invalid':''),'placeholder'=>'Enter Your Name Here.....','required'=>true])}}
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('email','Email')}}
                        {{ Form::email('email',@$s_address->email,['class'=>'form-control form-control-sm '.($errors->has('email') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Your Email Here.....'])}}
                      <div id="email" class="form-text">We'll never share your email with anyone else.</div>
                      @error('email')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('phone','Phone')}}
                        {{ Form::text('phone',@$s_address->phone,['class'=>'form-control form-control-sm '.($errors->has('phone') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Your Phone Num Here.....'])}}
                        @error('phone')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('province','Province')}}
                        {{ Form::select('province',$provinces->pluck('eng_name','eng_name'),@$s_address->province,['class'=>'province form-control form-control-sm '.($errors->has('province') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('province')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3"> 
                        {{ Form::label('district','District')}}
                        {{ Form::select('district',[],@$s_address->district,['class'=>'district form-control form-control-sm '.($errors->has('district') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('district')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('area','Area')}}
                        {{ Form::select('area',[],@$s_address->area,['class'=>'area form-control form-control-sm '.($errors->has('area') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('area')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('addtional_address','Additional Area')}}
                        {{ Form::textarea('addtional_address',@$s_address->additional_address,['class'=>'form-control form-control-sm '.($errors->has('addtional_address') ?'is-invalid':''),'placeholder'=>'Enter Your Additional Address.....','rows'=>3,'style'=>'resize:none;'])}}
                        @error('addtional_address')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('zip','Zip Code')}}
                        {{ Form::number('zip',@$s_address->zip,['class'=>'form-control form-control-sm '.($errors->has('zip') ?'is-invalid':''),'placeholder'=>'zip Code .....','required'=>true,'min'=>'1'])}}
                        @error('zip')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class=" btn btn-success">Update<i class="las la-edit"></i></button>
                {{ Form::close()}}
                  
              </div>
            </div>
          </div>
        </div>
    </div>
    @endforeach
    {{-- ----------------------Edit Shipping Address----------------------------------- --}}

    {{-- --------------------------Edit Billing Address--------------------- --}}
    @foreach ($billing_address as $bkey=>$b_address)
    <div class="modal fade" id="exampleModaleditbilling{{$bkey}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title text-center" id="exampleModalLabel">Edit Shipping Address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                {{ Form::open(['url'=>route('update-billing-address',@$b_address->id)])}}
                @method('post')
                    <div class="mb-3">
                        {{ Form::label('name','Name')}}
                        {{ Form::text('name',@$b_address->name,['class'=>'form-control form-control-sm '.($errors->has('name') ?'is-invalid':''),'placeholder'=>'Enter Your Name Here.....','required'=>true])}}
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('email','Email')}}
                        {{ Form::email('email',@$b_address->email,['class'=>'form-control form-control-sm '.($errors->has('email') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Your Email Here.....'])}}
                    <div id="email" class="form-text">We'll never share your email with anyone else.</div>
                    @error('email')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('phone','Phone')}}
                        {{ Form::text('phone',@$b_address->phone,['class'=>'form-control form-control-sm '.($errors->has('phone') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Your Phone Num Here.....'])}}
                        @error('phone')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('province','Province')}}
                        {{ Form::select('province',$provinces->pluck('eng_name','eng_name'),@$b_address->province,['class'=>'province form-control form-control-sm '.($errors->has('province') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('province')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3"> 
                        {{ Form::label('district','District')}}
                        {{ Form::select('district',[],@$b_address->district,['class'=>'district form-control form-control-sm '.($errors->has('district') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('district')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('area','Area')}}
                        {{ Form::select('area',[],@$b_address->area,['class'=>'area form-control form-control-sm '.($errors->has('area') ?'is-invalid':''),'placeholder'=>'-----------Select Any One-----------','required'=>true])}}
                        @error('area')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('addtional_address','Additional Area')}}
                        {{ Form::textarea('addtional_address',@$b_address->additional_address,['class'=>'form-control form-control-sm '.($errors->has('addtional_address') ?'is-invalid':''),'placeholder'=>'Enter Your Additional Address.....','rows'=>3,'style'=>'resize:none;'])}}
                        @error('addtional_address')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        {{ Form::label('zip','Zip Code')}}
                        {{ Form::number('zip',@$b_address->zip,['class'=>'form-control form-control-sm '.($errors->has('zip') ?'is-invalid':''),'placeholder'=>'zip Code .....','required'=>true,'min'=>'1'])}}
                        @error('zip')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message}}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class=" btn btn-success">Update<i class="las la-edit"></i></button>
                {{ Form::close()}}
                
            </div>
            </div>
        </div>
        </div>
    </div>
    @endforeach
    {{-- ----------------------Edit Billing Address----------------------------------- --}}

</section>
@endsection
@push('script')
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>

<script>
    
    $('.province').change(function(e){
        e.preventDefault();
        const province_id=$(this).val();
        $.ajax({
                url:"{{route('show-province')}}",
                type:"get",
                data:{
                    province_id:province_id 
                },
                success:function(response)
                {
                    if(typeof(response) !='object')
                    {
                        response=JSON.parse(response);
                    }
                    var child_html="<option value=''>----------------Select Any One--------------------</option>";
                    if(response.error)
                    {
                        alert(response.error);
                    }
                    else
                    {
                        if(response.data.child.length >0)
                        {
                            $.each(response.data.child,function(index,value){
                                child_html+="<option value='"+value.np_name +"'";
                                child_html+=">"+value.np_name+"</option>";
                            });
                        }
                    }
                    $('.district').html(child_html);  
                }
            });
    });
</script>
<script>
  $('.district').change(function(e){
        e.preventDefault();
        const district_id=$(this).val();
        $.ajax({
            url:"{{route('show-district')}}",
            type:"get",
            data:{
                district_id:district_id
            },
            success:function(response)
                {
                    if(typeof(response) !='object')
                    {
                        response=JSON.parse(response);
                    }
                    var child_html="<option value=''>----------------Select Any One--------------------</option>";
                    if(response.error)
                    {
                        alert(response.error);
                    }
                    else
                    {
                        if(response.data.child.length >0)
                        {
                            $.each(response.data.child,function(index,value){
                                child_html+="<option value='"+value.local_name +"'";
                                child_html+=">"+value.local_name+"</option>";
                            });
                        }
                    }
                    $('.area').html(child_html);
                }
        });
  });
</script>
@endpush
