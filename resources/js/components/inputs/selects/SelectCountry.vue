<template>
    <base-select :fetch-options="fetchOptions" :fieldName="fieldName"></base-select>
</template>

<script setup>

import BaseSelect from "@/components/inputs/selects/BaseSelect.vue";
import {defineProps} from "vue";
import CountriesService from "@/services/Address/CountriesService.js";

const props = defineProps({
    fieldName: {
        type: Object,
        default: {label: 'name', value: 'id'}
    }
});
function fetchOptions() {
    return new CountriesService().getList();
}

</script>
