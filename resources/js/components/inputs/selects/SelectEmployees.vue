<template>
    <base-select :fetch-options="fetchOptions" :fieldName="fieldName"></base-select>
</template>

<script setup>

import BaseSelect from "@/components/inputs/selects/BaseSelect.vue";
import {defineProps} from "vue";
import EmployeeService from "@/services/Employee/EmployeeService.js";

const props = defineProps({
    fieldName: {
        type: Object,
        default: {label: 'code', value: 'id'}
    }
});
function fetchOptions() {
    return new EmployeeService().getList({ paginate: false });

}

</script>
