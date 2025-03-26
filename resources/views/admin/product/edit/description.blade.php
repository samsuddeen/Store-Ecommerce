@push('style')
<style>
    :root {
	--ck-sample-base-spacing: 2em;
	--ck-sample-color-white: #fff;
	--ck-sample-color-green: #279863;
	--ck-sample-color-blue: #1a9aef;
	--ck-sample-container-width: 1285px;
	--ck-sample-sidebar-width: 350px;
	--ck-sample-editor-min-height: 400px;
	--ck-sample-editor-z-index: 10;
}

.editor__editable,
/* Classic build. */
main .ck-editor[role='application'] .ck.ck-content,
/* Decoupled document build. */
.ck.editor__editable[role='textbox'],
.ck.ck-editor__editable[role='textbox'],
/* Inline & Balloon build. */
.ck.editor[role='textbox'] {
	width: 100%;
	background: #fff;
	font-size: 1em;
	line-height: 1.6em;
	min-height: var(--ck-sample-editor-min-height);
	padding: 1.5em 2em;
}

.ck.ck-editor__editable {
	background: #fff;
	border: 1px solid hsl(0, 0%, 70%);
	width: 100%;
}

/* Because of sidebar `position: relative`, Edge is overriding the outline of a focused editor. */
.ck.ck-editor__editable {
	position: relative;
	z-index: var(--ck-sample-editor-z-index);
}

.editor-container {
	display: flex;
	flex-direction: row;
    flex-wrap: nowrap;
    position: relative;
	width: 100%;
	justify-content: center;
}

</style>
@endpush
<form action="{{ route('product-edit.description', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="mb-1">
        <label class="form-label" for="short-description">Short Description</label>
        <small class="text-danger">*</small>
        <textarea type="text" id="short-description" class="form-control form-control-sm short-description" rows="5" placeholder="Enter Video URL"
            name="short_description" required="required">{{ old('short_description', @$product->short_description) }}</textarea>
        <span class="text-danger">{{ $errors->first('short_description') }}</span>
    </div>
    <div class="mb-1">
        <label class="form-label" for="long-description">Long Description</label>
        <small class="text-danger">*</small>
        <textarea type="text" id="long-description" class="form-control form-control-sm long-description" rows="7" placeholder="Enter Video URL"
            name="long_description">{{ old('short_description', @$product->long_description) }}</textarea>
        <span class="text-danger">{{ $errors->first('long_description') }}</span>
    </div>
    <div class="mb-5 me-1">
        <button type="submit" class="btn btn-primary btn-sm float-end">Update</button>
    </div>
</form>

@push('script')
<script>
     var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('short-description', options);
            CKEDITOR.replace('long-description', options);
</script>
@endpush