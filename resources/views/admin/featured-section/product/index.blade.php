@extends('layouts.app')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('top-offer.create')" name="Featured Section" />

            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="location-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Product</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                           {{-- @foreach ($top_offers as $index=>$product)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>
                                        {{ $product->pivot->pivotParent->title }}
                                    </td>
                                    <td>
                                        {{$product->name}}
                                    </td>
                                    <td> --}}
                                        {{-- <form action="{{ route('top-offer-product.destroy',$product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');event.stopPropagation();">Delete</button>
                                        </form> --}}
                                    {{-- </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
