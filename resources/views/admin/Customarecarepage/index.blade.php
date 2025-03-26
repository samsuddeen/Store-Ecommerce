@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Customer Care Page')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('customercarepage.create')" name="Page" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="brand-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>title</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach($page as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->title}}</td>
                                    <td>
                                        @if($data->status==1)
                                            <span class="bg-success">Active</span>
                                        @else
                                        <span class="bg-danger">In-Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('customercarepage.edit',$data->id)}}" class="btn btn-sm btn-info">edit</a>
                                        <a href="{{route('customercarepage.destroy',$data->id)}}" class="btn btn-sm btn-danger">delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection
