<template>
    <template v-if="type ==='text'">
        <a-input :value="modelValue" :size="size" :disabled="disabled" @input="handleChangeInput"/>
    </template>
    <template v-if="type ==='password'">
        <a-input-password :value="modelValue" :size="size" :disabled="disabled" @input="handleChangeInput"/>
    </template>
    <template v-if="type ==='number'">
        <a-input :value="modelValue" :size="size" :disabled="disabled" type="number" @input="handleChangeInput"/>
    </template>
    <template v-if="type ==='color'">
        <a-input :value="modelValue" :size="size" :disabled="disabled" type="color" @input="handleChangeInput"/>
    </template>
    <template v-if="type ==='email'">
        <a-input :value="modelValue" :size="size" :disabled="disabled" type="email" @input="handleChangeInput"/>
    </template>
    <template v-if="type === 'text-area'">
        <a-textarea :rows="textAreaRow" :value="modelValue" :size="size" :disabled="disabled" @input="handleChangeInput"
                    allow-clear/>
    </template>
    <template v-if="type ==='datetime-local'">
        <a-input :value="modelValue" type="datetime-local" :size="size" :disabled="disabled"
                 @input="handleChangeInput"/>
    </template>
    <template v-if="type === 'date'">
        <a-input :value="modelValue" type="date" :size="size" :disabled="disabled" @input="handleChangeInput" :max="maxDate" />
    </template>
    <template v-if="type ==='range-picker'">
        <a-range-picker :value="modelValue" :size="size" :disabled="disabled" @change="handleChangeInput"/>
    </template>
    <template v-if="type ==='range-date-picker'">
        <a-date-picker :value="modelValue" :size="size"  @change="handleChangePicker" />
    </template>
    <template v-if="type ==='range-week-picker'">
        {{ modelValue}}
        <a-date-picker :value="modelValue" :size="size" picker="week" @change="handleChangePicker" />
    </template>
    <template v-if="type ==='range-month-picker'">
        {{ modelValue}}
        <a-date-picker :value="modelValue" :size="size" picker="month" @change="handleChangePicker" />
    </template>
    <template v-if="type ==='range-quarter-picker'">
        <a-date-picker :value="modelValue" :size="size" picker="quarter" @change="handleChangePicker" />
    </template>
    <template v-if="type ==='range-year-picker'">
        <a-date-picker :value="modelValue" :size="size" picker="year" @change="handleChangePicker" />
    </template>
    <template v-if="type ==='phone'">
        <a-input :value="modelValue" type="phone" :size="size" :disabled="disabled" @input="handleChangeInput"/>
    </template>
    <template v-if="type ==='select'">
        <a-select :value="modelValue" :size="size" :disabled="disabled" :options="options" @change="handleChangeSelect"
                  style="width: 100%"/>
    </template>

    <template v-if="type ==='multi-select'">
        <a-select :value="modelValue ?? []" :size="size" :disabled="disabled" :max-tag-count="maxTagCount"
                  :options="options"
                  @change="handleChangeSelect" mode="multiple" style="width: 100%"/>
    </template>
    <template v-if="type === 'entity-select'">
        <component :is="entitySelectMapComponent[entity]" :value="modelValue" :multiple="multiple" :size="size"
                   :disabled="disabled"
                   :max-tag-count="maxTagCount"
                   @update:value="handleChangeSelect"></component>
    </template>

    <template v-if="type === 'select-province-district'">
        <select-province-district
            :input-key="inputKey ?? {}"
            :size="size"
            :errors="errors ?? {}"
            :style-added="styleAdded ?? ''"
            :form-data="formData"></select-province-district>
    </template>

    <template v-if="type === 'select-region-province-district'">
        <select-region-province-district
            :input-key="inputKey ?? {}"
            :size="size"
            :errors="errors ?? {}"
            :style-added="styleAdded ?? ''"
            :form-data="formData"></select-region-province-district>
    </template>

    <template v-if="type === 'select-country-province-district'">
        <select-country-province-district
            :input-key="inputKey ?? {}"
            :size="size"
            :errors="errors ?? {}"
            :disabled="disabled"
            :style-added="styleAdded ?? ''"
            :form-data="formData"></select-country-province-district>
    </template>

    <template v-if="type === 'select-hrm-country-province-district'">
        <select-hrm-country-province-district
            :input-key="inputKey ?? {}"
            :size="size"
            :errors="errors ?? {}"
            :disabled="disabled"
            :style-added="styleAdded ?? ''"
            :form-data="formData"></select-hrm-country-province-district>
    </template>

    <template v-if="type === 'search-select'">
        <component :is="entitySelectMapComponent[entity]"
                   :value="modelValue"
                   :multiple="multiple"
                   :size="size"
                   :max-tag-count="maxTagCount"
                   :disabled="disabled"
                   @update:value="handleChangeSelect"></component>
    </template>

    <template v-if="type === 'upload-file'">
        <component :is="uploadFile"
                   :value="modelValue"
                   :list-type-upload="listTypeUpload"
                   :errors="errors ?? {}"
                   :max-count="maxCount"
                   :form-data="formData"
                   :disabled="disabled"
                   @update:value="handleChangeSelect">
        </component>
    </template>
</template>

<script setup>

import {defineProps, defineEmits, computed } from "vue";
import EntitySelectConstant from "@/constants/EntitySelectConstant.js";
import SelectRole from "@/components/inputs/selects/SelectRole.vue";
import SelectDepartment from "@/components/inputs/selects/SelectDepartment.vue";
import SelectProvinceDistrict from "@/components/inputs/selects/SelectProvinceDistrict.vue";
import SelectRegionProvinceDistrict from "@/components/inputs/selects/SelectRegionProvinceDistrict.vue";
import SelectCountryProvinceDistrict from "@/components/inputs/selects/SelectCountryProvinceDistrict.vue";
import SelectHrmCountryProvinceDistrict from "@/components/inputs/selects/SelectCountryProvinceDistrict.vue";
import SelectUser from "@/components/inputs/selects/SelectUser.vue";
import SelectUserSale from "@/components/inputs/selects/SelectUserSale.vue";
import UploadFiles from "@/components/inputs/uploads/File.vue";
import SelectHrmBank from "@/components/inputs/selects/SelectBank.vue";
import SelectHrmDepartment from "@/components/inputs/selects/SelectDepartment.vue";
import SelectHrmPosition from "@/components/inputs/selects/SelectPosition.vue";
import SelectSearchUser from "@/components/inputs/selects/SelectSearchUser.vue";
import SelectCountry from "@/components/inputs/selects/SelectCountry.vue";
import SelectJobTitle from "@/components/inputs/selects/SelectJobTitle.vue";
import SelectEmployees from "@/components/inputs/selects/SelectEmployees.vue";

const props = defineProps({
    size: {
        type: String,
        default: 'large'
    },
    type: {
        type: String,
        required: true
    },
    textAreaRow: {
        type: Number,
        default: 5
    },
    modelValue: {
        type: [String, Number, Array, Object],
        default: null
    },
    listTypeUpload: {
        type: [String],
        default: "picture-card"
    },
    options: {
        type: Array,
        default: null
    },
    maxTagCount: {
        type: Number,
        default: 1
    },
    maxCount: {
        type: Number,
        default: 1
    },
    multiple: {
        default: false
    },
    disabled: {
        default: false
    },
    entity: {
        type: String,
        required: false
    },
    errors: {
        type: [Array, Object],
        default: {}
    },
    inputKey: {
        type: [String, Array, Object],
        default: {}
    },
    formData: {
        type: Object,
        default: {}
    },
    styleAdded: {
        type: String,
        default: ''
    },
    maxDate: { type: String, default:''}
});
const entitySelectMapComponent = {
    [EntitySelectConstant.ROLE]: SelectRole,
    [EntitySelectConstant.DEPARTMENT]: SelectDepartment,
    [EntitySelectConstant.HRM_JOB_TITLE]: SelectJobTitle,
    [EntitySelectConstant.USER]: SelectUser,
    [EntitySelectConstant.USER_SALE]: SelectUserSale,
    [EntitySelectConstant.HRM_BANK]: SelectHrmBank,
    [EntitySelectConstant.HRM_DEPARTMENT]: SelectHrmDepartment,
    [EntitySelectConstant.HRM_POSITION]: SelectHrmPosition,
    [EntitySelectConstant.HRM_USER]: SelectSearchUser,

    [EntitySelectConstant.HRM_COUNTRY]: SelectCountry,
    [EntitySelectConstant.EMPLOYEES]: SelectEmployees,
};
const entitySelectProvinceDistrict = SelectProvinceDistrict;
const uploadFile = UploadFiles;
const emit = defineEmits(['update:modelValue']);

const handleChangeRangePicker = (value) => {
    emit('update:modelValue', value);
};

const handleChangePicker = (value) => {

    console.log('handleChangePicker', value);
    emit('update:modelValue', value);
};
const handleChangeSelect = (value) => {
    emit('update:modelValue', value);
};
const handleChangeInput = (event) => {
    emit('update:modelValue', event.target.value);
}
const selectMultiplePackage = (value) => {
    emit('update:modelValue', value);
};
const disabledFutureDates = (current) => {
    return current && current > new Date();
};
</script>
