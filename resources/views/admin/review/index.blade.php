@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product Review')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="card-header d-flex justify-space-between">
                        <h4 class="card-title text-capitalize">Review</h4>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="review-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>customer</th>
                                        <th>product</th>
                                        <th>seller</th>
                                        <th>rating</th>
                                        <th>comment</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <!-- list and filter end -->
            </div>
        </div>
    </section>
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script src="{{ asset('admin/review-list.js') }}" defer></script>
@endpush
