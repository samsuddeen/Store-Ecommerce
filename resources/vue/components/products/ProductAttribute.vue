<template>
  <div class="row">
    <div class="col-md-">
       <div class="mb-1">
        <label class="form-label">{{ "Video URL" }}</label>
        <input type="url" class="form-control form-control-sm" name="url" value="" placeholder="Enter Video URL">
      </div>
    </div>
      <h1>Product Attributes</h1>
    <div class="col-md-4" v-for="(attribute, index) in this.attributes" :key="index">
      <div class="mb-1">
        <label class="form-label">{{ attribute.title }}</label>
        <small class="text-danger">*</small>
        <input type="text" class="form-control form-control-sm" name="attribute[]" :value="attribute.id" hidden>
        <select name="value[]" :id="attribute.title" class="form-control form-control-sm">
            <option v-for="(value, i) in attribute.value.split(',')" :key="i" :value="value">{{value}}</option>
        </select>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  name: "ProductAttribute",
  props: {},
  data() {
    return {
      attributes: null,
    };
  },
  methods: {
    getAttributes() {
      axios
         .get(BASE_URL + "/category-attribute", {
            params: {
              category_id: window.CATEGORY_ID,
            },
          })
        .then((response) => {
          console.log(response.data);
          this.attributes = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.load = false;
        });
    },
  },
  created() {
    this.getAttributes();
  },
};
</script>
