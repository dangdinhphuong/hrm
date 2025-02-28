<template>
    <base-select-search-multiple :fetch-options="fetchOptions" v-if="multiple"></base-select-search-multiple>
    <base-select-search-one :fetch-options="fetchOptions" v-else></base-select-search-one>
</template>

<script setup>

import BaseSelectSearchMultiple from "@/components/inputs/selects/BaseSelectSearchMultiple.vue";
import BaseSelectSearchOne from "@/components/inputs/selects/BaseSelectSearchOne.vue";
import PackageService from "@/services/product/PackageServices.js";
import {defineProps} from "vue";

const props = defineProps({
    multiple: {type: Boolean, default: true},
});
function fetchOptions(params) {
    return new PackageService().getList(params);
}

</script>
