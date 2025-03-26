<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">sizes</h4>
            </div>
            <div class="card-body row">
                <div
                    class="col-4 row"
                    v-for="(size, index) in sizes"
                    :key="size + 'size'"
                >
                    <div class="col-md-9">
                        <div class="form-floating">
                            <input
                                type="size"
                                class="form-control"
                                name="sizes[]"
                                placeholder="size"
                                v-model="size.size"
                            />
                            <label for="">size</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-1">
                            <button
                                class="btn btn-outline-danger text-nowrap px-1"
                                data-repeater-delete
                                type="button"
                                @click="deleteRow(index)"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="currentsize"
                                    class="bi bi-x-lg"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"
                                    />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <error :errors="errors" name="sizes"></error>
                    <div class="col-12">
                        <button
                            class="btn btn-icon btn-primary"
                            type="button"
                            @click="addRow"
                        >
                            <i data-feather="plus" class="me-25"></i>
                            <span>Add New</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "@vue/reactivity";
import { onMounted } from "@vue/runtime-core";
import { useErrors } from '../composables/errorComposable'
import Error from './Error.vue';
export default {
    components: { Error },
    props: {
        selectedsizes: {
            components: { Error },
            type: Array,
            default: [],
        },
        errors: {
            type: Object,
            default: {}
        },
    },
    setup(props) {
        var sizes = ref([]);
        function deleteRow(index) {
            confirm("Do you really want to delete this size?")
                ? sizes.value.splice(index, 1)
                : null;
        }
        function addRow() {
            sizes.value.push({ name: null });
        }

        onMounted(() => (sizes.value = props.selectedsizes));
        const errorMessage = useErrors().getErrors(props.errors, props.name);

        return { sizes, deleteRow, addRow, errorMessage };
    },
};
</script>

<style>
</style>
