<template>
    <label class="form-label" :for="name">{{ name }}</label>
    <select class="form-select form-select-sm" :name="name">
        <option :value="list" v-for="list in lists" :key="list + name" :selected="list == value">
            {{ list }}
        </option>
    </select>
</template>

<script>
import { onMounted } from "@vue/runtime-core";
import { useErrors } from "../composables/errorComposable";
export default {
    props: {
        name: String,
        lists: {
            type: Array,
            default: [],
        },
        value: {
            type: String,
            default: null,
        },
        errors: {
            type: Object,
            defaul: {}
        }
    },

    setup(props) {
        const errorMessage = useErrors().getErrors(props.errors, props.name);
        return { errorMessage };
    },

};
</script>

<style>
</style>
