@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Customer Coupon')
@section('content')
    
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('customer-coupon.create')" name="coupon" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="location-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Customer</th>
                                <th>Coupon</th>
                                <th>Title</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($coupons as $index=>$model)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        @if($model->customer_id==0)
                                        All
                                        @else
                                        {{$model->customer->name}}
                                        @endif
                                       
                                    </td>
                                    <td>{{$model->code}}</td>
                                    <td>{{$model->coupon->title}}</td>
                                    <td>{{$model->coupon->from}}</td>
                                    <td>{{$model->coupon->to}}</td>
                                    <td>
                                        @if($model->is_expired)
                                           {{"Expired"}}
                                           @elseif(\Carbon\Carbon::parse($model->from)->toDateString() > \Carbon\Carbon::now()->toDateString())
                                           {{"Not Started"}}
                                           @elseif(\Carbon\Carbon::parse($model->to)->toDateString() < \Carbon\Carbon::now()->toDateString())
                                           {{"Expired"}}
                                           @else
                                           {{"Active"}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('customer-coupon.edit', $model)}}" class="btn btns-m btn-success">
                                            Edit
                                        </a>
                                        <form action="{{route('customer-coupon.destroy', $model)}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger btn-link">
                                                <i class="fa fa-trash"></i>delete
                                            </button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    @include('admin.includes.datatables')

  
@endpush

