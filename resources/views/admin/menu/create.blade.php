@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Menu')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create Menu</h4>
                    </div>
                    <div class="card-body">
                        @if ($menu->id)
                            <form action="{{ route('menu.update', $menu->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('menu.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Name <span
                                            class="text-danger">*</span>:</label>
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        value="{{ old('name', @$menu->name) }}" required>
                                    @error('name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-4 col-12">
                                <label class="form-label" for="title">Parent Menu:</label>
                                <select name="parent_id" id="parent_id" class="form-control form-control-sm">
                                    <option value="">Please Select</option>
                                    @foreach ($menus as $m)
                                        <option value="{{ $m->id }}"
                                            {{ $m->id == @$menu->parent_id ? 'selected' : '' }}>{{ $m->name }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                @enderror

                            </div>


                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Menu Type <span class="text-danger">*</span> :</label>
                                            @foreach ($menu_types as $index => $type)
                                                <p class="mt-2">
                                                    <input class="form-check-input menu-type" type="radio"
                                                        data-value="{{ $type }}" name="menu_type"
                                                        {{ @$menu->menu_type == $type ? 'checked' : '' }}
                                                        value="{{ $type }}"> <span>{{ $index }}</span>
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-9" id="menu-content">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="show_on">Show On <span class="text-danger">*</span>:</label>
                                    <p>
                                        @foreach ($show_on as $index => $show)
                                            <input class="form-check-input ms-2" type="radio"
                                                data-value="{{ $index }}" name="show_on"
                                                {{ @$menu->show_on == $show ? 'checked' : '' }}
                                                value="{{ $show }}"> <span>{{ $index }}</span>
                                        @endforeach
                                    </p>
                                </div>
                                @error('show_on')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                @enderror
                            </div>



                            <div class="col-md-4 col-12">
                                <div class="input-group">
                                    <label class="form-label" for="title">{{ 'Banner Image' }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm"
                                            name="{{ 'banner_image' }}"
                                            value="{{ old('banner_image', @$menu->banner_image) }}" placeholder="Image"
                                            id="thumbnail{{ 'banner_image' }}" readonly>
                                        <a id="lfm{{ 'banner_image' }}" data-input="thumbnail{{ 'banner_image' }}"
                                            data-preview="holder{{ 'banner_image' }}"
                                            class="btn btn-primary btn-outline-primary waves-effect" type="button"
                                            style="text-align: center">Go</a>
                                        <div id="holder{{ 'banner_image' }}" class="col-12 mt-2">
                                            @foreach (explode(',', @$menu->banner_image) as $item)
                                                <img src="{{ $item }}" style="margin-top:15px;max-height:100px;">
                                            @endforeach
                                        </div>
                                        @error('banner_image')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-12">
                                <div class="input-group">

                                    <label class="form-label" for="title">{{ 'OG Image' }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" name="og_image"
                                            value="{{ old('og_image', @$menu->og_image) }}" placeholder="Image"
                                            id="thumbnail{{ 'og_image' }}" readonly>
                                        <a id="lfm{{ 'og_image' }}" data-input="thumbnail{{ 'og_image' }}"
                                            data-preview="holder{{ 'og_image' }}"
                                            class="btn btn-primary btn-outline-primary waves-effect" type="button"
                                            style="text-align: center">Go</a>
                                        <div id="holder{{ 'og_image' }}" class="col-12 mt-2">
                                            @foreach (explode(',', @$menu->og_image) as $item)
                                                <img src="{{ $item }}" style="margin-top:15px;max-height:100px;">
                                            @endforeach
                                        </div>
                                        @error('og_image')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 col-12">
                                <div class="input-group">

                                    <label class="form-label" for="title">{{ 'Extra image' }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" name="image"
                                            value="{{ old('image', @$menu->image) }}" placeholder="Image"
                                            id="thumbnail{{ 'image' }}" readonly>
                                        <a id="lfm{{ 'image' }}" data-input="thumbnail{{ 'image' }}"
                                            data-preview="holder{{ 'image' }}"
                                            class="btn btn-primary btn-outline-primary waves-effect" type="button"
                                            style="text-align: center">Go</a>
                                        <div id="holder{{ 'image' }}" class="col-12 mt-2">
                                            @foreach (explode(',', @$menu->image) as $item)
                                                <img src="{{ $item }}" style="margin-top:15px;max-height:100px;">
                                            @endforeach
                                        </div>
                                        @error('image')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Meta Title:</label>
                                    <input type="text" name="meta_title" class="form-control form-control-sm"
                                        value="Glass Pipe Nepal |  Best Glass Pipes & Accessories at Glass Pipe Nepal">
                                    @error('meta_title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Meta Keywords:</label>
                                    <textarea type="text" name="meta_keywords" class="form-control" rows="3"></textarea>
                                    @error('meta_keywords')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Meta Description:</label>
                                    <textarea type="text" name="meta_description" class="form-control" rows="3"></textarea>
                                    @error('meta_description')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Status:</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="">Plesae Select</option>
                                        <option value="1" {{ (int) @$menu->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ (int) @$menu->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>





                        </div>
                        <x-dashboard.button :create="isset($menu->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('script')
    <script>
        $('#lfmog_image').filemanager("image", {
            prefix: "/laravel-filemanager"
        });


        $('#lfmbanner_image').filemanager("image", {
            prefix: "/laravel-filemanager"
        });



        $('#lfmimage').filemanager("image", {
            prefix: "/laravel-filemanager"
        });




        $(document).on('change', '#for', function(e) {
            e.preventDefault();
            if ($(this).val() !== 1) {
                $('#selection-block').css({
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

        $('.menu-type').on('click', function(e) {
            let menu_type = $(this).data('value');
            getContent(menu_type);
        });

        function getContent(menu_type) {
            $.ajax({
                url: "/admin/get-menu-type-content",
                type: 'get',
                data: {
                    menu_type: menu_type,
                },
                success: function(response) {
                    $('#menu-content').html(response);
                    loadCKEditor3();
                    console.log(response);
                },
                error: function(response) {
                }
            });
        }
    </script>
@endpush
