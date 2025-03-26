@extends('layouts.app')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <x-cardHeader :href="route('app-policy.create')" name="App Policy" />
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>SN.</th>
                                        <th>Title</th>
                                        <th>Content/Policy</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    @if($appPolicy)
                                    @foreach($appPolicy->assist as $index=>$assist)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$assist->title}}</td>
                                        <td>{!! $assist->policy !!}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <!-- list and filter end -->
            </div>
        </div>
    </section>
@endsection
