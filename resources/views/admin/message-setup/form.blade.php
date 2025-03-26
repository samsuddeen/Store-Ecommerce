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
                        <h4 class="card-title">{{$title}}</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><span class="text-danger">{{ $error }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($messageSetup->id)
                            <form action="{{ route('message-setup.update', $messageSetup->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('message-setup.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <input type="hidden" name="title" value="{{old('title', @$messageSetup->title ?? @$filters['title'])}}">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="message">Message<span class="text-danger">*</span>:</label>
                                    <textarea type="text" rows="10" class="form-control editor" id="name" name="message" placeholder="Wrrite your message">{{old('message', $messageSetup->message )}}</textarea>
                                    @error('message')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($messageSetup->id)"></x-dashboard.button>
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
@endpush