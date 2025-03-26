@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Review Section')
@section('content')

    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-body border-bottom">

                <div class="card-datatable table-responsive pt-0">
                    <table class="brand-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th width="5%">S.N</th>
                                <th width="20%">User</th>
                                <th width="20%">Product</th>
                                <th width="10%">Rating</th>
                                <th width="10%">Status</th>
                                <th width="35%">action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach($reviews as $key=>$review)
                                <tr>
                                    <td>{{@$key+1}}</td>
                                    <td>{{@$review->user->name}}</td>
                                    <td>{{@$review->product->name}}</td>
                                    <td>{{@$review->rating}}</td>
                                    <td><a href="javascript:void(0);" class=" @if($review->status == 1) text-success @else text-danger @endif changeStatus" data-id="{{ $review->id }}" data-status="{{ $review->status }}">{{ $review->status == 1 ? 'Active' : 'Inactive' }}</a></td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-sm btn-primary sendReplyMessage"  data-id="{{@$review->id}}" data-bs-toggle="modal" data-bs-target="#replyReview">Reply</a>
                                        <a href="javascript:;" class="seeReview btn btn-sm btn-info" data-id="{{@$review->id}}"  data-bs-toggle="modal" data-bs-target="#exampleModal">View Msg</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{@$reviews->links()}}
                </div>
            </div>


        </div>
        <!-- list and filter end -->
    </section>

    <!-- Review View -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Review Details</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="reviewMessage">
          ...
        </div>
      </div>
    </div>
  </div>

   <!-- Review Reply -->
<div class="modal fade" id="replyReview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Review Reply</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="javascript:;" methdo="post" id="sendReply">
            @csrf
            <div class="modal-body" >
                <textarea name="reply" id="" cols="30" rows="10"></textarea>
                <input type="text" hidden name="review_id" id="review_id">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary sendMessage">Send</button>
            </div>
        </form>   
      </div>
    </div>
  </div>
    <!-- brand ends -->
@endsection
@push('script')
    <script>
        $(document).ready(function()
        {   
            $(document).on('click','.changeStatus',function(){
                let row_id = $(this).data('id');
                let row_status = $(this).data('status');
                $.ajax({
                    type: "POST",
                    url: "{{ route('replyreview.status_update') }}",
                    data:{
                        review_id: row_id,
                        status: row_status == 0 ? 1 : 0,
                        _token:"{{ csrf_token() }}"
                    },
                    dataType:"json",
                    success:function(response)
                    {
                        location.reload();
                    }
                    
                });
            });
            $(document).on('click','.seeReview',function()
            {
                var reviewId=$(this).data('id');
                $('#reviewMessage').html('');
                $.ajax({
                    url:"{{route('getIndividualReview')}}",
                    type:"get",
                    data:{
                        reviewId:reviewId
                    },
                    success:function(response)
                    {
                        if(response.error)
                        {
                            $('#reviewMessage').html('');
                            return false;
                        }

                        var html='';
                        var html='<div>';
                        var image_html='';
                        $.each(response.image,function(index,value)
                        {
                            image_html+='<img src="{{asset('Uploads/review/')}}'+'/'+value.title+'">';
                            // alert(image_html);
                        });
                        html+=image_html;
                        html+='<p>'+response.message+'</p>';
                        html+='</div>';
                        // alert(html);
                        $('#reviewMessage').html(html);
                    }
                });
            });

            $(document).on('click','.sendReplyMessage',function()
            {
                var reviewId=$(this).data('id');
                $('#review_id').val(reviewId);

            });

            $(document).on('click','.sendMessage',function()
            {
                
                var form=document.getElementById('sendReply');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $.ajax({
                    url:"{{route('sendReview')}}",
                    type:"post",
                    data:{
                        reviewId:form['review_id'].value,
                        reply:form['reply'].value
                    },
                    success:function(response)
                    {
                        if(response.error)
                        { 
                            return false;
                        }
                        $('#replyReview').modal('hide');
                        location.reload();
                        
                    }
                });
            });
        });
    </script>
@endpush
