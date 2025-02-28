<template>
    <base-select :fetch-options="fetchOptions" :fieldName="fieldName"></base-select>
</template>

<script setup>

import BankService from "@/services/system/BankService.js";
import BaseSelect from "@/components/inputs/selects/BaseSelect.vue";
import {defineProps} from "vue";

const props = defineProps({
    fieldName: {
        type: Object,
        default: {label: 'name', value: 'id'}
    }
});
function fetchOptions() {
    return new BankService().getAll();
}

</script>
