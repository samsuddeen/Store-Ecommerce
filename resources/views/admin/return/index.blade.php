@extends('layouts.app')
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
                            <table class="return-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>product</th>
                                        <th>customer</th>
                                        <th>amount</th>
                                        <th>Reason</th>
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
    <script src="{{ asset('admin/return-list.js') }}" defer></script>
@endpush
