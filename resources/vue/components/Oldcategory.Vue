<template>
  <div class="mb-1">
    <label class="form-label">Category </label>
    <input type="hidden" name="category_id" v-model="selectedCategory" />
    <small class="text-danger">*</small>
    <div
      class="form-control form-control-sm"
      name="category"
      @click="toggleModel"
    >
      <span v-for="cat in categoryName" :key="cat + 'name'"
        >{{ cat.title }} >>
      </span>
    </div>
  </div>
  <button
    type="button"
    class="d-none"
    data-bs-toggle="modal"
    data-bs-target="#categoryModel"
    ref="model"
  >
    Show
  </button>
  <!-- Category Model -->
  <div
    class="modal fade"
    id="categoryModel"
    tabindex="-1"
    aria-labelledby="CategegoryTypes"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-transparent">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body px-sm-5 mx-50 pb-5">
          <h1 class="text-center mb-1" id="CategegoryTypes">Select Category</h1>
          <p class="text-center">Search and select the category to assign</p>

          <!-- form -->
          <form
            id="addNewCardValidation"
            class="row gy-1 gx-2 mt-75"
            onsubmit="return false"
          >
            <div class="col-12">
              <div class="form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="floating-label1"
                  placeholder="Search"
                />
                <label for="floating-label1">Search</label>
              </div>
            </div>
            <hr />
            <div class="col-12 row mt-2">
              <div
                class="col-2 scrollable"
                v-for="(number, n) in 6"
                :key="n + 'categoryLevel'"
              >
                <div>
                  <ul class="list-group" v-if="currentLevel[n]">
                    <li
                      class="list-group-item d-flex align-items-center"
                      v-for="cat in currentLevel[n]"
                      :key="cat.slug"
                      :class="{ active: selectedCategories.includes(cat.id) }"
                      @click="
                        getLevelCategories(cat.id, cat.ancestors_count + 1)
                      "
                    >
                      <span> {{ cat.title }}</span>
                      <span class="badge bg-primary rounded-pill ms-auto">{{
                        cat.descendants_count
                      }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-12 text-right">
              <button
                type="button"
                class="btn btn-primary me-1 mt-1"
                data-bs-dismiss="modal"
              >
                Confirm Selection
              </button>
              <button
                type="button"
                class="btn btn-outline-secondary mt-1"
                data-bs-dismiss="modal"
                aria-label="Close"
                @click="selectedCategories = []"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, computed, reactive } from "vue";
import axios from "axios";
import { useErrors } from "../composables/errorComposable";
import ErrorVue from "./Error.vue";
import Error from "./Error.vue";
export default {
  props: {
    cat: {
      type: Number,
      default: null,
    },
    errors: {
      type: Object,
      default: {},
    },
  },
  setup(props) {
    //initalize required
    var selectedCategory = ref(props.cat);
    var category = ref([]);
    var currentLevel = ref([]);

    var selectedCategories = ref([]);
    var categoryName = computed(() => {
      return category.value.filter((item) =>
        selectedCategories.value.includes(item.id)
      );
    });
    //getting categories
    function getLevelCategories(parent_id = null, ancestors_count = 0) {
      //toggleActive
      toggleActive(parent_id, ancestors_count);
      currentLevel.value[ancestors_count] = category.value.filter(
        (item) => item.parent_id === parent_id
      );
      currentLevel.value.splice(ancestors_count + 1, currentLevel.value.length);
    }
    function toggleActive(parent_id, ancestors_count) {
      parent_id
        ? ((selectedCategories.value[ancestors_count] = parent_id),
          (selectedCategory.value = parent_id))
        : null;
      selectedCategories.value.splice(
        ancestors_count + 1,
        selectedCategories.value.length
      );
    }
    //toggle Category Model
    var model = ref(null);

    function toggleModel() {
      model.value.click();
    }
    //Fetching All categories
    onMounted(() => {
      axios
        .post(BASE_URL + "/categories")
        .then((response) => (category.value = response.data.allCategory))
        .then(() => getLevelCategories());
    });
    return {
      category,
      toggleModel,
      model,
      getLevelCategories,
      currentLevel,
      selectedCategories,
      categoryName,
      selectedCategory,
    };
  },
  components: { Error },
};
</script>

<style scoped>
.scrollable {
  height: 500px;
  overflow: scroll;
}
</style>
