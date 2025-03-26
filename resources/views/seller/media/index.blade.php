@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Media')
@section('content')
    <section class="app-user-list">
        @if(Auth::guard('seller')->check())
            <iframe src="/laravel-filemanager?type=image" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
        @endif
    </section>
@endsection
 