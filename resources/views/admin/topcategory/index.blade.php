@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Topcategories')
@section('content')

<!-- START PAGE CONTENT-->
<div class="page-heading">
    <h1 class="page-title">Top Categories List</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
        </li>
        <a class="btn btn-outline-primary" href="{{route('top-category.create')}}"><i class="fa fa-plus"></i> Add Top Category</a>
    </ol>
</div>
<br>
{{-- <div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Search Top Category </div>
    </div>
    <div class="ibox-body">
        <form class="form-inline">
            <label class="sr-only" for="slider-name">Category  Name</label>
            <input class="form-control mb-2 mr-sm-2 mb-sm-0" id="slider-name" type="text" placeholder="Category Name">
            <button class="btn btn-success" type="submit" id="all-filter">Search</button>
        </form>
    </div>
</div> --}}

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Top Categories</div>
        </div>
        <div class="ibox-body">
            {{-- @include('admin.includes.error') --}}

            <div id="table-data">
                @include('admin.topcategory.list')
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@endsection

@push('script')
<script type="text/javascript">

    let filterObject = {
        sliderName: null,
        page:null,
        path:null,
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            filterObject.page = $(this).attr('href').split('page=')[1];
            filterObject.path = "{{ URL::route('admin.slider.ajaxfetch') }}?page="+filterObject.page;
            serverRequest();
        });

        $('#all-filter').click(function(e){
            e.preventDefault();
            filterObject.sliderName = $('#slider-name').val();
            filterObject.page = $('#hidden_page').val();
            filterObject.path = "{{ URL::route('admin.slider.ajaxfetch') }}?page="+filterObject.page;
            serverRequest();
        });

        function serverRequest(){
        $.ajax({
                type:'GET',
                url:filterObject.path,
                data:filterObject,
                // }
                success:function(data){
                    $('#table-data').html('');
                    $('#table-data').html(data);
                },
                error:function(data){
                    console.log('error');
                }
            });
        }
    });

</script>
@endpush
