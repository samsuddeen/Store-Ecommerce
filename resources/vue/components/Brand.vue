<template>
  <div class="mb-1">
    <label class="form-label" for="brandSelect">Brand</label>
    <select class="form-select select2" id="brandSelect" name="brand_id">
      <option value="">Select brands</option>
      <option
        v-for="brand in brands"
        :key="brand.name"
        :value="brand.id"
        :selected="brand.id == value"
      >
        {{ brand.name }}
      </option>
    </select>
  </div>
</template>

<script>
import { onMounted, ref } from "@vue/runtime-core";
import axios from "axios";
export default {
  name: "brand",
  props: {
    value: {
      type: String,
      default: null,
    },
  },
  setup() {
    var brands = ref([]);
    onMounted(() =>
      axios
        .get(BASE_URL + "/brands")
        .then((response) => (brands.value = response.data))
    );
    return { brands };
  },
};
</script>

<style></style>
