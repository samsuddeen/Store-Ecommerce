@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Topcategories')
@section('content')

<!-- START PAGE CONTENT-->
<div class="page-heading">
    <h1 class="page-title">Phone and Email List</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
        </li>
        {{-- <a class="btn btn-outline-primary" href="{{route('importemail.create')}}"><i class="fa fa-plus"></i> Add Email / Phone</a> &nbsp; --}}
        <a class="btn btn-outline-primary" href="{{asset('sample.xlsx')}}"><i class="fa fa-plus"></i>Download Sample</a> &nbsp;
        <form action="{{route('importemail.import')}}" style="display:flex;gap:10px" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" class="form-control" required>
            <input type="submit" class="btn btn-sm btn-primary">
            <span>

                @if($errors->has('file'))
                <div class="error">{{ $errors->first('file') }}</div>
            @endif
            </span>
        </form>
    </ol>
   
</div>
<br>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">List of phone and email</div>
        </div>
        <div class="ibox-body">
            <div id="table-data">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $key => $data)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$data->phone}}</td>
                                <td>{{$data->email}}</td>
                               
                                <td>
                                    <a href="{{route('importemail.edit',$data->id)}}" class="btn btn-info waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Edit">Edit</a>
                                    <a href="{{route('importemail.delete',$data->id)}}" class="btn btn-warning waves-effect waves-float waves-light delete-banner" onclick="return confirm('Are you sure?')" data-toggle="tooltip" data-original-title="Delete" >Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$datas->links()}}
                
                </div>
             
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@endsection

@push('script')

@endpush
