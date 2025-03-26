<template>
  <div class="row" id="table-bordered">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Attributes</h4>
        </div>
        <div class="card-body">
          <p class="card-text">
            Provide the attribute of categories below.
            <code>For eg :- Mobile has RAM, Battery</code>
          </p>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>S.n</th>
                <th>
                  Attribute
                  <span class="badge badge-light-info" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Title of the attribute. like RAM/ROM ,Battery, ">
                    ?
                  </span>
                </th>
                <th>
                  Help Text
                  <span class="badge badge-light-info" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Help text to let seller know about the inputs ">
                    ?
                  </span>
                </th>
                <th>
                  Value
                  <span class="badge badge-light-info" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Help text to let seller know about the inputs ">
                    ?
                  </span>
                </th>
                <th>
                  Stocks
                  <span class="badge badge-light-info" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Whether this attribute be used as stock keeping unit">
                    ?
                  </span>
                </th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody v-if="attributes">
              <tr v-for="(attribute, key) in attributes" :key="key">
                <td>{{ key + 1 }}</td>
                <td>
                  <input type="text" class="form-control form-control-sm" :name="'attribute[' + key + ']'"
                    v-model="attribute.title" required />
                </td>
                <td>
                  <input type="text" class="form-control form-control-sm" :name="'helpText[' + key + ']'"
                    v-model="attribute.helpText" />
                </td>
                <td>
                  <textarea class="form-control form-control-sm" :name="'value[' + key + ']'" v-model="attribute.value" />
                </td>
                <td>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" :name="'stock[' + key + ']'"
                        :checked="attribute.stock" v-model="attribute.stock" />
                    </label>
                  </div>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-danger text-nowrap px-1" type="button"
                    @click="deleteRow(key, attribute.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg"
                      viewBox="0 0 16 16">
                      <path fill-rule="evenodd"
                        d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"></path>
                      <path fill-rule="evenodd"
                        d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"></path>
                    </svg><span>Delete</span>
                  </button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="8">
                  <button class="btn btn-sm btn-primary" type="button" @click="addRow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="feather feather-plus me-25">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Add</span>
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import {
  onMounted,
  ref
} from "@vue/runtime-core";
export default {
  props: {
    category: {
      type: String,
      default: null,
    },
  },

  setup(props) {
    var attributes = ref([]);

    onMounted(() => getAttributes());

    function getAttributes() {
      if (props.category) {
        axios
          .get(BASE_URL + "/category-attribute", {
            params: {
              category_id: props.category,
            },
          })
          .then(function (response) {
            attributes.value = response.data;
          });
      }
    }

    function addRow() {
      attributes.value.push({
        title: null,
        helptext: null,
        value: null,
        stock: null,
      });
    }

    function deleteRow(index, id) {
      if (confirm("Do you really want to delete this attributes this would not be able to revert")) {
        if (id == null || id == undefined || id == NaN) {
          attributes.value.splice(index, 1);
        } else {
          axios
            .patch(BASE_URL + "/category-attribute/" + id)
            .then(function (response) {
              attributes.value.splice(index, 1);
            });
        }
      }
    }
    return {
      attributes,
      addRow,
      deleteRow
    };
  },
};
</script>

<style></style>
