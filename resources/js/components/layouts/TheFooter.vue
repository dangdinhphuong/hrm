<template>
    <div class="text-center" style="font-size:12px">
       {{ $t('footer.intro', { company: config.setting_company_name ?? 'Educa' }) }}
    </div>
</template>
<script setup>
import {configStore as useConfigStore} from "@/stores/ConfigStore.js";
import {computed} from "vue";

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);
</script>
