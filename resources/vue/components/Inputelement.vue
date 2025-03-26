<template>
    <div class="mb-1">
        <label class="form-label" :for="name">{{ name }}</label>
        <small class="text-danger">*</small>
        <input :type="type" :id="name" class="form-control form-control-sm" :placeholder="placeholder" :name="name"
            :value="value" :required="required" />

        <error :errors="errors" :name="name"></error>

    </div>
</template>

<script>
import { useErrors } from '../composables/errorComposable'
import Error from './Error.vue';
export default {
    components: { Error },
    props: {
        name: String,
        placeholder: {
            type: String,
        },
        type: {
            type: String,
            default: "text",
        },
        value: {
            type: String,
            default: null,
        },
        errors: {
            type: Object,
            default: {}
        },
        required: {
            type: [Boolean,String],
            default: false
        }
    },
    setup(props) {
        const errorMessage = useErrors().getErrors(props.errors, props.name);
        return { errorMessage };
    },

};
</script>

<style scoped>
label {
    text-transform: capitalize;
}
</style>
