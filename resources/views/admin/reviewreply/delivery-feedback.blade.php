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
                                <th width="20%">Customer</th>
                                <th width="20%">Order</th>
                                <th width="10%">Rating</th>
                                <th width="10%">Message</th>
                                {{-- <th width="10%">Delivered By</th> --}}
                                <th width="10%">Image</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach($feedbacks as $key=>$feedback)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <th>{{ $feedback->customer->name ?? 'Unknown'}}</th>
                                    <th><a href="{{ route('admin.viewOrder',$feedback->order_id) }}">{{ $feedback->order->ref_id }}</a></th>
                                    <th>{{ $feedback->rating }}</th>
                                    <th>{!! $feedback->message !!}</th>
                                    {{-- @php 
                                        if($feedback->order->task)
                                        {
                                            $delivery = $feedback->order->task->assignedTo->name;
                                        }       
                                    @endphp
                                    <th>{{ @$delivery ?? '-' }}</th> --}}
                                    @php
                                        $image = explode('|',$feedback->image);
                                    @endphp
                                    <th>
                                        @foreach ($image as $img)
                                            <img src="{{ asset('images/' . $img) }}" alt="Image" width="50">
                                        @endforeach
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{@$feedbacks->links()}}
                </div>
            </div>


        </div>
        <!-- list and filter end -->
    </section>
@endsection

