@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Mail Setting')   
@section('content')
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('smtp.create')" name="SMTP" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="tag-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Email</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script src="{{ asset('admin/smtp-list.js') }}" defer></script>
@endpush
