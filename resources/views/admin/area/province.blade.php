@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Provinces')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="vat-tax-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Province</th>
                                <th>Publish</th>
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
        <!-- Modal -->
        {{-- @foreach ($provinces as $item)
 <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{route}}"></form>
        
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Province Name</h5>          
        </div>
        <div class="modal-body">
            <input type="province_name" name="province_name" id="province_name" class="form-control" value="{{$item->eng_name}}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      
    </div>
  </div>
 @endforeach --}}


    </section>
@endsection

@push('style')
    @include('admin.includes.datatables')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('script')
    <script>
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('provinces.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'area',
                        name: 'area'
                    },
                    {
                        data: 'publish',
                        name: 'publish'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });
    </script>
@endpush
