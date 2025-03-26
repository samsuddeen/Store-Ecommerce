<template>
  <div class="row">
    <div class="col-2">
      <span class="text-muted" v-for="(category, index) in categories" :key="'parents' + index">{{
        category.title + category.depth
      }}</span>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";

var categories = ref([]);
var children = ref([]);
onMounted(() => {
  axios
    .post(BASE_URL + "/search-category")
    .then((response) => (categories.value = response.data));
});
</script>
<style>
</style>
