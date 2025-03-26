<template>
  <div class="col-12">
    <div class="input-group">
      <div class="mb-1">
        <label class="form-label" for="title">Image</label>
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            name="image_name[]" 
            value=""
            placeholder="Image"
            :id="'thumbnail'+this.lfmId" required
          />
          <a
            :id="'lfm'+this.lfmId"
            :data-input="'thumbnail'+this.lfmId"
            :data-preview="'holder'+this.lfmId"
            class="btn btn-outline-primary waves-effect"
            type="button"
            >Go</a
          >
          <div :id="'holder'+this.lfmId" class="col-12 mt-2">
            <img
              src=""
              style="margin-top: 15px; max-height: 100px"
            />
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "ProductImage",
  props:{
    lfmId:Number,
    name:String,
  },
  created() {
    // props are exposed on `this`
    console.log(this.lfmId)
    console.log(this.name);
  },
  mounted() {
    console.log(this.lfmId)
    console.log(this.name);
    loadUniSharp();
  },
};
</script>