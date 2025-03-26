@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Hub')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('hub.create')" name="color" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="hub-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Location/Local</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($places as $index=>$place)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        {{($place->is_location) ? $place->location->title : $place->local->local_name}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-action="{{route('near-place.destroy', $place->id)}}">
                                            <i data-feather='trash'></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form action="#" id="delete-form" method="post" hidden>
            @csrf
            @method("DELETE")
        </form>
    </section>
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
<script>
    $(document).ready(function(){
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            $('#delete-form').attr('action', $(this).data('action'));
            if(confirm("Are You Sure")){
                $('#delete-form').submit();
            }
        });
    })
</script>
@endpush
