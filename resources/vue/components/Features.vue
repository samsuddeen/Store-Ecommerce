<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Features</h4>
            </div>
            <div class="card-body">

                <div class="row" v-for="(feature, index) in features" :key="feature + 'feature'">
                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="features[]" id="floating-label1"
                                placeholder="Feature" v-model="feature.name" />
                            <label for="floating-label1">Feature</label>
                        </div>
                    </div>

                    <div class="col-md-2 col-12">
                        <div class="mb-1">
                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button"
                                @click="deleteRow(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z" />
                                    <path fill-rule="evenodd"
                                        d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z" />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <error :errors="errors" name="features"></error>
                    <div class="col-12">
                        <button class="btn btn-icon btn-primary" type="button" @click="addRow">
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
        test: {
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
        var features = ref([]);
        function deleteRow(index) {
            confirm("Do you really want to delete this feature?")
                ? features.value.splice(index, 1)
                : null;
        }
        function addRow() {
            features.value.push({ name: null });
        }

        onMounted(() => (features.value = props.test));
        const errorMessage = useErrors().getErrors(props.errors, props.name);

        return { features, deleteRow, addRow, errorMessage };
    },
};
</script>

<style>
</style>
