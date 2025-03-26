@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Message Setup')   
@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('css/ck-editor/styles.css')}}">
@endpush
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h4 class="card-title">{{$title}}</h4> --}}
                    </div>
                    <div class="card-body">
                        @isset($data->id)
                            <form action="{{ route('email.message.update',$data->id) }}" method="post">
                                @method('put')
                            @else
                                <form action="{{ route('email.message.store') }}" method="POST">
                        @endisset
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="note">Note<span class="text-danger">*</span>:</label>
                                    <textarea type="text" rows="05" class="form-control editor3" id="note" name="note" placeholder="Write your note">{{old('note', @$data->note )}}</textarea>
                                    @error('message')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="message">Message<span class="text-danger">*</span>:</label>
                                    <textarea type="text" rows="10" class="form-control editor" id="name" name="message" placeholder="Write your message">{{old('message', @$data->message )}}</textarea>
                                    @error('message')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="footer_message">Footer Message<span class="text-danger">*</span>:</label>
                                    <textarea type="text" rows="10" class="form-control editor1" id="name" name="footer_message" placeholder="Write your message">{{old('message', @$data->footer_message )}}</textarea>
                                    @error('footer_message')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $footer_message }}</p>
                                    @enderror
                                </div>
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
ClassicEditor.create( document.querySelector( '.editor' ), {
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
<script>
    ClassicEditor.create( document.querySelector( '.editor3' ), {
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