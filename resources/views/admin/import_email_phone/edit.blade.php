@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Topcategories')
@section('content')

<!-- START PAGE CONTENT-->
<div class="page-heading">
    <h1 class="page-title">Phone and email List</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
        </li>
        {{-- <a class="btn btn-outline-primary" href="{{route('importemail.create')}}"><i class="fa fa-plus"></i> Add Email / Phone</a> &nbsp; --}}
     
    </ol>
   
</div>
<br>

<div class="page-content fade-in-up">
    <div class="ibox">
       
        <div class="ibox-body">

            <form action="{{route('importemail.update',$find->id)}}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" name="email" value="{{$find->email}}" aria-describedby="emailHelp" placeholder="Enter email">
                      </div>
                        @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                        @endif
                    
                  </div>
        
                  <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{$find->phone}}" aria-describedby="emailHelp" placeholder="Enter phone">
                      </div>
                      @if($errors->has('phone'))
                      <div class="error">{{ $errors->first('phone') }}</div>
                      @endif
                  </div>
                  <br>
                  <input type="submit" value="Update" class="btn btn-sm btn-primary">
            </form>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@endsection

@push('script')

@endpush
