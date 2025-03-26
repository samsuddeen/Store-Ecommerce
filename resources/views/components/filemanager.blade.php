<div class="input-group">
    <div class="mb-1">
        <label class="form-label" for="title">{{ $name }}</label>
        <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="{{ $name }}" value="{{ old($name, $value) }}"
                 placeholder="Image{{($name=='image') ? '(1200*100)' : '(800*200)'}}" id="thumbnail{{ $name }}" readonly required>
            <a id="lfm{{ $name }}" data-input="thumbnail{{ $name }}"
                data-preview="holder{{ $name }}" class="btn btn-primary btn-outline-primary waves-effect" type="button" style="text-align: center">Go</a>
            <div id="holder{{ $name }}" class="col-12 mt-2">
                @foreach (explode(',', $value) as $item)
                    <img src="{{ $item }}" style="margin-top:15px;max-height:100px;">
                @endforeach
            </div>
            @error($name)
                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>


@once
    @push('script')
        <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    @endpush
@endonce

@push('script')
    <script>
        $('#lfm{{ $name }}').filemanager("image");
    </script>
@endpush
