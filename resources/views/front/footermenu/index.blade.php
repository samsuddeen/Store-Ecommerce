@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
 @if ($message = Session::get('update'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
@section('title')

    Footer list | {{ env('APP_NAME') }}

@stop
@extends('layouts.app')
@section('blog','active')
@section('blog_list','active')
@section('content')


    <div class="page-heading">
        <h1 class="page-title">Footer_Menu_list</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('footermenu.create')}}"class="btn btn-outline-primary"><i class="fa fa-plus">&emsp13;</i>Add Footer Menu</a>
            </li>
        </ol>
    </div>
        <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">
                    <?php if (isset($selectCategory)) {
                        echo 'Edit Product Category';
                    } else {
                        echo 'Add Footer_Menu';
                    } ?>

                </div>

                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                    <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                </div>
            </div>



    <div class="card shadow mb-4">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"id="table">
                    <thead>
                        <tr>
                            <th>S.No</th>

                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $i =>$item)


                        <tr>
                            <td>{{$i+1}}</td>

                             <td>{{$item->title}}</td>


                                <td>
                             @if($item->status==1)
                            <a href="{{url('menustatus',$item->id )}}"class="btn btn-green"style="text-decoration:none;color:green">Active</a>
                            @else
                            <a href="{{url('menustatus',$item->id )}}"class="btn btn-green"style="text-decoration:none;color:red">InActive</a>
                            @endif

                             </td>
                            <td>
                                <div class="btn-group">

                                    <a href="{{url('footermenu/edit',$item->id)}}" class="btn btn-success btn-sm mr-1"><i class="fa fa-edit">Edit</i></a>
                                    <button class="btn btn-danger btn-sm mr-1 " data-toggle="modal" data-target="#deletemodal {{$item->id}}"id="trash"><i class="fa fa-trash">Trash</i></a>
                                    </button>

                                    </div>


                            </td>

                        </tr>
                        <div id="deletemodal{{$item->id}}" class="modal fade">
                            <div class="modal-dialog modal-confirm">
                            <form action="{{url('footermenu/delete',$item->id)}}" method="POST" id="deletecomment">
                              @csrf
                              @method('DELETE')

                                <div class="modal-content">
                                    <div class="modal-header bg-danger ">
                                        <h4 class="modal-title w-100">Are you sure?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Do you really want to delete these records? This process cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" >submit</button>

                                    </div>
                                </div>


                            </form>
                            </div>
                        </div>



                        @endforeach
                     </tbody>
                </table>
                <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
                <script>
                    $(document).on('click', '#trash', function() {
                        if (confirm("Are you sure?")) {
                            var url = "{{url('footermenu/delete',$item->id)}}";
                            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                method: 'POST',
                                url: url,
                                data: {
                                    _token: CSRF_TOKEN,
                                    _method: 'DELETE'
                                },
                                cache:false,
                                success: function(response) {
                                    $("#dataTable").load(location.href + " #dataTable");

                                }
                            });
                        }
                        return false;
                    });
                </script>
                <script>
    function handeldelete(id){
        var form = document.getElementById('deletecomment')
        form.action='comment/' +id

        $('#deletemodal').modal('show')
    }
    $(function() {
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/blog',
                data: {'status': status, 'id': id},
                success: function(data){
                    alert(data.success)
                }
            });
        })
      })
</script>
            </div>
        </div>
    </div>


</div>

@endsection
