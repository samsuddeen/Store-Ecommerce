<h4> SEO Info</h4>
<form action="{{ route('product-edit.seo', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                    value="{{ old('meta_title', @$product->meta_title) }}"
                    :errors="{{ json_encode($errors->toArray()) }}" required="true">
                </inputelement>

            </div>
            <div class="col-md-6 og-imagedash">
                <x-filemanager :value="@$product->og_image" :name="'og_image'">
                </x-filemanager>
            </div>
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label" for="name">Meta Keywords:</label><small class="text-danger">*</small>
                    <textarea class="form-control" rows="4" name="meta_keywords">{{ old('meta_keywords', @$product->meta_keywords) }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-1">
                    <label class="form-label" for="name">Meta Description:</label><small class="text-danger">*</small>
                    <textarea class="form-control" rows="4" name="meta_description">{{ old('meta_description', @$product->meta_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="bt btn-primary btn-sm float-end">Update</button>
    </div>

</form>
