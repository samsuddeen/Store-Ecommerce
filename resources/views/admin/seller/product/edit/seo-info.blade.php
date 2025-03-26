SEO Info
<form action="{{ route('seller-product-edit.seo', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                    value="{{ old('meta_title', @$product->meta_title) }}"
                    :errors="{{ json_encode($errors->toArray()) }}" required="true">
                </inputelement>

                {{-- <x-filemanager :value="@$product->og_image" :name="'og_image'">
                </x-filemanager> --}}

                <div class="col-12">
                    <label for="og_image"> OG Image</label>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <a id="og_image" data-input="thumbnail1" data-preview="holder"
                                class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                            </a>
                        </span>
                        <input id="thumbnail1" class="form-control" type="text" name="og_image"
                            value="{{ old('og_image', @$product->og_image) }}" required>

                    </div>

                    @if (isset($product->og_image))
                        <img src="{{ $product->og_image }}" alt="Img"
                            style="width: 100px; height: auto;">
                    @endif

                    @error('og_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
                
            </div>
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label" for="name">Meta
                        Keywords:</label><small class="text-danger">*</small>
                    <textarea class="form-control" rows="2" name="meta_keywords">{{ old('meta_keywords', @$product->meta_keywords) }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-1">
                    <label class="form-label" for="name">Meta Description:</label><small class="text-danger">*</small>
                    <textarea class="form-control" rows="5" name="meta_description">{{ old('meta_description', @$product->meta_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="bt btn-primary btn-sm float-end">Update</button>
    </div>

</form>



@push('script')
    <script>
      
        $('#og_image').filemanager("image", {
            prefix: "/laravel-filemanager"
        });
    </script>
@endpush