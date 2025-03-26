@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'News Letter')
@push('style')
    <style>
        /**
         *	CKEditor editables are automatically set with the "cke_editable" class
         *	plus cke_editable_(inline|themed) depending on the editor type.
         */

        /* Style a bit the inline editables. */
        .cke_editable.cke_editable_inline {
            cursor: pointer;
        }

        /* Once an editable element gets focused, the "cke_focus" class is
           added to it, so we can style it differently. */
        .cke_editable.cke_editable_inline.cke_focus {
            box-shadow: inset 0px 0px 20px 3px #ddd, inset 0 0 1px #000;
            outline: none;
            background: #eee;
            cursor: text;
        }

        /* Avoid pre-formatted overflows inline editable. */
        .cke_editable_inline pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /**
         *	Samples index styles.
         */

        .twoColumns,
        .twoColumnsLeft,
        .twoColumnsRight {
            overflow: hidden;
        }

        .twoColumnsLeft,
        .twoColumnsRight {
            width: 45%;
        }

        .twoColumnsLeft {
            float: left;
        }

        .twoColumnsRight {
            float: right;
        }

        dl.samples {
            padding: 0 0 0 40px;
        }

        dl.samples>dt {
            display: list-item;
            list-style-type: disc;
            list-style-position: outside;
            margin: 0 0 3px;
        }

        dl.samples>dd {
            margin: 0 0 3px;
        }

        .warning {
            color: #ff0000;
            background-color: #FFCCBA;
            border: 2px dotted #ff0000;
            padding: 15px 10px;
            margin: 10px 0;
        }

        .warning.deprecated {
            font-size: 1.3em;
        }

        /* Used on inline samples */

        blockquote {
            font-style: italic;
            font-family: Georgia, Times, "Times New Roman", serif;
            padding: 2px 0;
            border-style: solid;
            border-color: #ccc;
            border-width: 0;
        }

        .cke_contents_ltr blockquote {
            padding-left: 20px;
            padding-right: 8px;
            border-left-width: 5px;
        }

        .cke_contents_rtl blockquote {
            padding-left: 8px;
            padding-right: 20px;
            border-right-width: 5px;
        }

        img.right {
            border: 1px solid #ccc;
            float: right;
            margin-left: 15px;
            padding: 5px;
        }

        img.left {
            border: 1px solid #ccc;
            float: left;
            margin-right: 15px;
            padding: 5px;
        }

        .marker {
            background-color: Yellow;
        }
    </style>
@endpush
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">News Letter</h4>
                    </div>
                    <div class="card-body">
                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                        @if ($newsLetter->id)
                            <form action="{{ route('news-letter.update', $newsLetter->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('news-letter.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" name="title" class="form-control form-control-sm" required
                                        value="{{ old('title', @$newsLetter->title) }}">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">URL:</label>
                                    <input type="text" name="url" class="form-control form-control-sm"
                                        value="{{ old('url', @$newsLetter->url) }}">
                                    @error('url')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Summary<span
                                            class="text-danger">*</span>:</label>
                                    <textarea name="summary" id="summary" class="form-control form-control-sm" rows="3" required>{{ old('summary', @$newsLetter->summary) }}</textarea>
                                    @error('summary')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Description:</label>
                                    <textarea name="description" id="description" class="form-control my-editor" rows="4">{{ old('description', @$newsLetter->description) }}</textarea>
                                    @error('description')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>



                            <div class="col-md-4 col-12">
                                <x-filemanager :value="@$newsLetter->image"></x-filemanager>
                            </div>


                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Users<span
                                        class="text-danger">*</span>:</label>
                                        <select name="for" id="for" class="form-control form-control-sm" required>
                                            <option value="please_select">Please Select</option>
                                            @foreach ($for_users as $index => $for)
                                            @if($for == 8)
                                            @else   
                                            <option value="{{ $for }}"
                                            {{ (int) @$newsLetter->for == (int) $for ? 'selected' : '' }}>
                                            {{ $index }}</option>
                                            @endif
                                               
                                            @endforeach
                                        </select>
                                @error('for')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="col-md-4 col-12">
                                <label class="form-label" for="publishStatus">Status<span
                                        class="text-danger">*</span>:</label>
                                <select name="status" id="status" class="form-control form-control-sm" required>
                                    <option value="">Please Select</option>
                                    @foreach ($statuses as $index => $status)
                                        <option value="{{ $status }}"
                                            {{ old('status') == (int) $status || (int) @$newsLetter->status == (int) $status ? 'selected' : '' }}>
                                            {{ $index }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="col-md-12 col-12 customer-block" id="selection-block"
                                @if (
                                    @$newsLetter->for !== null &&
                                        (int) @$newsLetter->for !== 1 &&
                                        (int) @$newsLetter->for !== 7 &&
                                        (int) @$newsLetter->for !== 7) style="display:block"
                            @else
                            style="display: none" @endif>
                                <label class="form-label" for="publishStatus">Customers<span
                                        class="text-danger">*</span>:</label>

                                <select name="selection[]" id="selection" class="form-control form-control-sm select2"
                                    multiple>
                                    <option value="">Please Select</option>
                                    @foreach ($customers as $index => $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ in_array($customer->id, json_decode($newsLetter->selection) ?? []) ? 'selected' : '' }}>
                                            {{ $customer->name }}</option>
                                    @endforeach
                                </select>

                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>



                            {{-- new email  --}}
                            <div class="col-md-12 col-12 email-block" id="selection-block1"
                                @if (
                                    @$newsLetter->for !== null &&
                                        (int) @$newsLetter->for !== 1 &&
                                        (int) @$newsLetter->for !== 2) style="display:block"
                                   @else
                                   style="display:none" @endif>
                                <label class="form-label" for="publishStatus">Email<span
                                        class="text-danger">*</span>:</label>

                                        <select name="email_selection[]" id="selection1" class="form-control form-control-sm fav_clr" multiple="multiple">
                                            @foreach ($emails as $index => $email)
                                                <option value="{{ $email->id }}" {{ in_array($email->id, json_decode($newsLetter->email_selection) ?? []) ? 'selected' : '' }}>
                                                    {{ $email->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="checkbox" id="select-all-checkbox" value="check">Select All

                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- end email  --}}
                            {{-- new phone   --}}
                            {{-- <div class="col-md-12 col-12 email-block" id="selection-block2"
                                @if (
                                    @$newsLetter->for !== null &&
                                        (int) @$newsLetter->for !== 1 &&
                                        (int) @$newsLetter->for !== 2 &&
                                        (int) @$newsLetter->for !== 7) style="display:block"
                                           @else
                                           style="display:none" @endif>
                                <label class="form-label" for="publishStatus">Phone<span
                                        class="text-danger">*</span>:</label>

                                <select name="phone_selection[]" id="selection2"
                                    class="form-control form-control-sm select2" multiple>
                                    <option value="">Please Phone</option>
                                    @foreach ($emails as $index => $phone)
                                        <option value="{{ $phone->id }}"
                                            {{ in_array($phone->id, json_decode($newsLetter->phone_selection) ?? []) ? 'selected' : '' }}>
                                            {{ $phone->phone }}</option>
                                    @endforeach
                                </select>

                                @error('selection')
                                    <p class="form-control-static text-danger" id="">{{ $message }}</p>
                                @enderror
                            </div> --}}
                            {{-- end phone  --}}


                        </div>
                        <x-dashboard.button :create="isset($newsLetter->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{-- <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script> --}}
    <script>
        $(document).ready(function () {
            $('.fav_clr').select2(); // Initialize select2
    
            // "Select All" checkbox functionality
            $('#select-all-checkbox').on('click', function () {
                if ($(this).is(':checked')) {
                    $('.fav_clr > option').prop('selected', true);
                    $('.fav_clr').trigger('change');
                } else {
                    $('.fav_clr > option').prop('selected', false);
                    $('.fav_clr').trigger('change');
                }
            });
    
            // Handling the "select2:select" event
            $('.fav_clr').on('select2:select', function (e) {
                var data = e.params.data.text;
                if (data === 'all') {
                    $(".fav_clr > option").prop("selected", "selected");
                    $(".fav_clr").trigger("change");
                }
            });
        });
    </script>
    
    
    <script>
        // var options = {
        //     filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        //     filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        //     filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        //     filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        // };
        ClassicEditor.create(document.querySelector('.my-editor'), {
                licenseKey: '',
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error(
                    'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:'
                );
                console.warn('Build id: wizt6zz8gcop-10bq0f55gpit');
                console.error(error);
            });




        $(document).on('change', '#for', function(e) {

            e.preventDefault();
            if ($(this).val() !== 1) {
                $('#selection-block').css({
                    "display": "block"
                });
                $('#selection-block1').css({
                    "display": "none"
                });
                $('#selection-block2').css({
                    "display": "none"
                });
            }
            if ($(this).val() == 7) {

                $('#selection-block').css({
                    "display": "none"
                });
                $('#selection-block1').css({
                    "display": "block"
                });
                $('#selection-block2').css({
                    "display": "none"
                });
            }

            if ($(this).val() == 8) {

                $('#selection-block').css({
                    "display": "none"
                });
                $('#selection-block1').css({
                    "display": "none"
                });
                $('#selection-block2').css({
                    "display": "block"
                });
            }
            if ($(this).val() == 'please_select') {
                $('#selection-block').css({
                    "display": "none"
                });
            }
            if ($(this).val() == 1) {
                $('#selection-block').css({
                    "display": "none"
                });
            }

            
        });
    </script>
@endpush
