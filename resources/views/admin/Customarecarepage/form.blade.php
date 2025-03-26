@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'create')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Care Page</h4>
                    </div>
                    <div class="card-body">
                        @if (@$data->id)
                            <form action="{{ route('customercarepage.update', $data->id) }}" method="POST">
                                @method('POST')
                            @else
                                <form action="{{ route('customercarepage.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ @$data->title }}" placeholder="Enter title">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                           
                        </div>

                        <div class="col-xl-12 col-md-12 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="content">Content</label>
                                <textarea type="text" rows="10" class="form-control editor1" id="content" name="content" placeholder="Write Content">{{old('content', @$data->content )}}</textarea>
                                @error('content')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="title">Status</label>
                                {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$data->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                                @error('status')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($data->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
<script>
    ClassicEditor.create( document.querySelector( '.editor1' ), {
            licenseKey: '',
        } )
        .config.protectedSource.push(/<\?[\s\S]*?\?>/g)
        .then( editor => {
            window.editor = editor;
        } )
        .catch( error => {
            console.error( 'Oops, something went wrong!' );
            console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
            console.warn( 'Build id: wizt6zz8gcop-10bq0f55gpit' );
            console.error( error );
        } );
    </script>
@endpush