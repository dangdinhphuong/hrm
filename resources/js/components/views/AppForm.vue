<template>
    <a-form
        :model="formData"
    >
        <div class="row">
            <div v-if="isMultipleSections"
                 v-for="(field, index) in fields" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
                <div class="group-title">
                    <h3><b>{{ field.groupName }}</b></h3>
                </div>
                <div :class="classWrapperFormItem">
                    <a-form-item
                        v-for="(item,indexItem) in field.items"
                        :key="indexItem"
                        :validateStatus="errors[item.key] && !item.hiddenTitleAndError ? 'error' : undefined"
                        :help="errors[item.key] && !item.hiddenTitleAndError ? errors[item.key]: undefined"
                        :class="[classFormItem, item.classAdvancedFormItem ?? '']"
                    >
                        <span v-if="!item.hiddenTitleAndError">{{ item.name }}<span class="text-danger"
                                                                                    v-if="item.required">*</span></span>
                        <app-input
                            v-model="formData[item.key]"
                            :input-key="item.key"
                            :type="item.type"
                            :max-date="item.maxDate"
                            :options="item.options ?? []"
                            :entity="item.entity ?? null"
                            :multiple="item.multiple ?? false"
                            :disabled="item.disabled ?? false"
                            :errors="errors ?? {}"
                            :form-data="formData"
                            :list-type-upload="item.listTypeUpload"
                            :max-count="item.maxCount"
                        >
                        </app-input>
                    </a-form-item>
                </div>
            </div>

            <div :class="classWrapperFormItem" v-else>

                <a-form-item
                    v-for="(field,index) in fields"
                    :key="index"
                    :validateStatus="errors[field.key] && !field.hiddenTitleAndError ? 'error' : undefined"
                    :help="errors[field.key] && !field.hiddenTitleAndError ? errors[field.key]: undefined"
                    :class="[classFormItem, field.classAdvancedFormItem ?? '']"
                >
                    <span v-if="!field.hiddenTitleAndError">{{ field.name }}<span class="text-danger"
                                                                                  v-if="field.required">*</span></span>

                    <app-input
                        v-model="formData[field.key]"
                        :input-key="field.key"
                        :type="field.type"
                        :max-date="field.maxDate"
                        :options="field.options ?? []"
                        :entity="field.entity ?? null"
                        :multiple="field.multiple ?? false"
                        :disabled="field.disabled ?? false"
                        :errors="errors ?? {}"
                        :form-data="formData"
                        :list-type-upload="field.listTypeUpload"
                        :max-count="field.maxCount"
                    >
                    </app-input>
                </a-form-item>
            </div>
        </div>

        <slot name="more-field" :formData="formData"></slot>

        <div class="d-flex justify-content-center" v-if="canSubmit">
            <button-save @click="submit"></button-save>
            <button-cancel @click="cancel"></button-cancel>
        </div>

    </a-form>
</template>

<script setup>
import {ref, watch} from "vue";
import AppInput from "@/components/inputs/AppInput.vue";
import ButtonCancel from "@/components/buttons/ButtonCancel.vue";
import ButtonSave from "@/components/buttons/ButtonSave.vue";
import {isEmptyObject, isset} from "@/helpers/CommonHelper.js";

const props = defineProps({
    fields: {
        type: Array,
        default: []
    },
    sourceData: {
        type: Object,
        default: {}
    },
    errors: {
        type: [Array, Object],
        default: {}
    },
    classWrapperFormItem: {
        type: String,
        default: ''
    },
    classFormItem: {
        type: String,
        default: ''
    },
    submit: {
        type: Function,
        required: true
    },
    cancel: {
        type: Function,
        default: null
    },
    isMultipleSections: {
        type: Boolean,
        default: false
    },
    canSubmit: {
        type: Boolean,
        default: true
    },
    beforeSubmit: {
        type: Function,
        default: null
    },
});

const formData = ref(props.sourceData);

const setDefaultValue = () => {
    const fields = props.isMultipleSections
        ? props.fields.flatMap(group => group.items) // Lấy tất cả các items nếu có nhiều sections
        : props.fields; // Nếu không có nhiều sections, lấy fields trực tiếp
    fields.forEach(field => {
        if (isset(field.default_value)) {
            formData.value[field.key] = field.default_value;
        } else {
            formData.value[field.key] = '';
        }
    });
};

if (isEmptyObject(props.sourceData)) {
    formData.value = ref({});
    console.log('isEmptyObject', formData.value)
    setDefaultValue();
}

const beforeSubmit = () => {
    if (typeof props.beforeSubmit === 'function') {
        props.beforeSubmit(formData);
    } else {
        console.warn('beforeSubmit prop is not provided or is not a function.');
    }
};


const submit = () => {
    // Helper function to process fields
    const processFields = (fields, result) => {
        fields.forEach(field => {
            if (!field.disabled) { // Only process enabled fields
                if (typeof field.key === 'object') { // Check if the field key is an object
                    Object.keys(field.key).forEach(key => {
                        result[field.key[key]] = formData.value[field.key[key]];
                    });
                } else if (formData.value.hasOwnProperty(field.key)) { // Check if the formData has the field key
                    result[field.key] = formData.value[field.key];
                }
            }
        });
        return result;
    };

    let data = {};
    if (props.isMultipleSections) { // Check if there are multiple sections
        data = props.fields.reduce((result, field) => {
            return processFields(field.items, result); // Process items within each section
        }, {});
    } else {
        data = processFields(props.fields, {}); // Process single section
    }

    props.submit(data); // Uncomment this line to submit the data
};

const cancel = () => {
    props.cancel();
}

watch(formData, () => {
    beforeSubmit();
}, {deep: true});

</script>
