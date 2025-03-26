@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Reward Section')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
           
            <x-cardHeader :href="route('rewardsection.create')" name="Reward Section" />
          
            <div class="card-body border-bottom">
                <a href="{{route('rewardsetup')}}" class="btn btn-sm btn-success">
                    Reward Setup
                </a>
                <div class="card-datatable table-responsive pt-0">
                    <table class="brand-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Point</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rewardsSections as $key=>$data)

                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$data->title}}</td>
                                    <td>
                                        <span class="badge badge-{{($data->status==1) ? 'success':'danger'}} text-dark">{{($data->status==1) ? 'Active':'In-Active'}}</span>
                                    </td>
                                    <td>{{$data->points}}</td>
                                    <td>
                                        <a href="{{route('rewardsection.edit',$data->id)}}" class="btn btn-info waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Edit">Edit</a>
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
@push('style')
   
@endpush
