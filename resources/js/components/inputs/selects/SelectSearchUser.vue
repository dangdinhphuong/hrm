<template>
    <base-select-search-multiple
        v-if="multiple"
        :fetch-options="fetchOptions"
        :field-name="fieldName"
        :disabled="disabled"
    ></base-select-search-multiple>

    <base-select-search-one
        v-else
        :fetch-options="fetchOptions"
        :field-name="fieldName"
        :disabled="disabled"
    ></base-select-search-one>
</template>

<script setup>
import BaseSelectSearchMultiple from "@/components/inputs/selects/BaseSelectSearchMultiple.vue";
import BaseSelectSearchOne from "@/components/inputs/selects/BaseSelectSearchOne.vue";
import UserService from "@/services/system/UserService.js";
import { defineProps } from "vue";

const props = defineProps({
    multiple: { type: Boolean, default: true },
    disabled: { default: false },
});


const fieldName = { label: 'username', value: 'id' };

function fetchOptions(params) {
    return new UserService().find(params);
}
</script>
