<template>
    <base-select :fetch-options="fetchOptions"></base-select>
</template>

<script setup>

import RoleService from "@/services/system/RoleService.js";
import BaseSelect from "@/components/inputs/selects/BaseSelect.vue";

function fetchOptions() {
    return new RoleService().getAll();
}

</script>
