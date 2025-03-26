<template>
    <p class="form-text text-danger" v-if="errorMessage && errorMessage.length">
        <span v-for="(msg, index) in errorMessage" :key="msg + index">
            {{ msg }}
        </span>
    </p>
</template>

<script>
import { useErrors } from '../composables/errorComposable';
export default {
    props: {
        errors: {
            type: Object,
            default: {}
        },
        name: {
            type: String,
            default: null
        }
    },
    setup(props) {
        const errorMessage = useErrors().getErrors(props.errors, props.name);
        return { errorMessage };
    }
}
</script>

<style>
</style>
