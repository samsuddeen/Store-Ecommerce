@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'FAQ')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('faq.create')" name="faq" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="faq-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>title</th>
                                <th>status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($faqs as $faq)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $faq->title }}</td>
                                    <td>{{ $faq->status == 1 ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('faq.edit',$faq->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm deleteBtn" data-toggle="modal" data-id="{{ $faq->id }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

                    {{-- MODAL --}}
                    <div class="common-popup medium-popup modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center" id="exampleModalLabel">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this item ?</p>
                                    <form action="" id="faqDeleteForm" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No, Go Back</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL END --}}
        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection

@push('script')
<script>
    $(document).ready(function(){
        $(document).on('click','.deleteBtn',function(){
            let faq_id = $(this).data('id');
            $('#deleteModal').modal('show');
            let deleteUrl = "{{ route('faq.destroy',':id') }}";
                deleteUrl = deleteUrl.replace(':id',faq_id);
            $('#faqDeleteForm').attr('action',deleteUrl);
            // return ;
            // $('#faqDeleteForm').on('submit', function(e){
            //     e.preventDefault();
            //     console.log('submit');
            //     $.ajax({
            //         type:"POST",
            //         url:deleteUrl,
            //         data:{
            //             faq_id: faq_id,
            //             _method: 'DELETE',
            //             _token: "{{ csrf_token() }}"
            //         },
            //         dataType:"json",
            //         success:function(response)
            //         {
            //             $('#deleteModal').modal('hide');
            //             location.reload();
            //         }
            //     });
            // });
        });
    });
</script>
@endpush


