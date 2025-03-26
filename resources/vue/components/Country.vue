<template>
    <div class="mb-1">
        <label class="form-label" for="countrySelect">Production Country</label>
        <select class="form-select select2" id="countrySelect" name="country_id">
            <option value="">Select Country</option>
            <option v-for="country in countries" :key="country.name" :value="country.id"
                :selected="country.id == value">
                {{ country.name }}
            </option>
        </select>
        <error :errors="errors" name="country_id"></error>
    </div>
</template>

<script>
import { ref } from "@vue/reactivity";
import { onMounted } from "@vue/runtime-core";
import axios from "axios";
import { useErrors } from '../composables/errorComposable';
import Error from './Error.vue';
export default {
    components: { Error },
    name: "country",
    props: {
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
        var countries = ref([]);
        onMounted(() => {
            axios
                .get(BASE_URL + "/countries")
                .then((response) => (countries.value = response.data));
        });

        return { countries };
    },
};
</script>

<style>
</style>
