<template>
    <a-select v-if="multiple" mode="multiple"
              :value="value ?? []"
              :size="size"
              :options="options"
              :max-tag-count="maxTagCount"
              :field-names="fieldName"
              :disabled="disabled"
              @change="$emit('update:value', $event)"
              style="width: 100%"/>
    <a-select v-else
              :value="value"
              show-search
              :size="size"
              :options="options"
              :field-names="fieldName"
              :max-tag-count="maxTagCount"
              :disabled="disabled"
              :filter-option="(input, option) => option[fieldName.label].toLowerCase().includes(input.toLowerCase())"
              @change="$emit('update:value', $event)"
              style="width: 100%"/>
</template>

<script setup>

import {defineProps, ref} from "vue";

const props = defineProps({
    size: {
        type: String
    },
    value: {
        type: [String, Number, Array],
        default: null
    },
    multiple: {
        type: Boolean
    },
    fetchOptions: {
        type: Function,
        required: true
    },
    fieldName: {
        type: Object,
        default: {label: 'name', value: 'id'}
    },
    maxTagCount: {
        type: Number
    },
    disabled: {
        type: Boolean,
        default: false
    },
});

const options = ref([]);
props.fetchOptions().then((data) => {
    options.value = data;
});

</script>
