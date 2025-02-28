<template>
    <base-select :fetch-options="fetchOptions" :fieldName="fieldName"></base-select>
</template>

<script setup>

import UserService from "@/services/system/UserService.js";
import BaseSelect from "@/components/inputs/selects/BaseSelect.vue";
import {defineProps} from "vue";

const props = defineProps({
    fieldName: {
        type: Object,
        default: {label: 'username', value: 'id'}
    }
});
function fetchOptions() {
    return new UserService().getAll();
}

</script>
