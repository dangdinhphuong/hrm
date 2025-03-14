<template>
    <SettingsForm
        :pageTitle="$t('setting.general.title')"
        :fields="fields"
    />
</template>

<script setup>
import {ref} from "vue";
import SettingsForm from "@/views/setting/SettingsForm.vue"; // Kiểm tra đường dẫn
import {
    translate
} from "@/helpers/CommonHelper.js";

const fields = ref([
    {
        groupName: translate('hrm.basic_information'),
        items: [
            {
                type: 'upload-file',
                key: 'setting_small_logo',
                classAdvancedFormItem: 'col-sm-12',
                listTypeUpload: 'picture',
                name: translate('setting.general.columns.small_logo')
            },
            {
                type: 'upload-file',
                key: 'setting_logo',
                classAdvancedFormItem: 'col-sm-12',
                listTypeUpload: 'picture',
                name: translate('setting.general.columns.background_login')
            },
            {
                type: 'upload-file',
                key: 'setting_favicon',
                classAdvancedFormItem: 'col-sm-12',
                listTypeUpload: 'picture',
                name: translate('setting.general.columns.favicon')
            },
            {
                type: 'color',
                key: 'setting_subsidebar_color',
                classAdvancedFormItem: 'col-sm-12',
                name: translate('setting.general.columns.sidebar_color'),
                default_value: '#0d6efd'
            }
        ]
    },
    {
        groupName: translate('hrm.business_information'),
        items: [
            {
                type: 'text',
                key: 'setting_company_name',
                name: translate('setting.general.columns.company_name'),
                required: true
            },
            {
                type: 'phone',
                key: 'setting_contact_phone',
                name: translate('setting.general.columns.contact_phone'),
                required: true
            },
            {type: 'email', key: 'setting_contact_email', name: translate('setting.general.columns.contact_email')},
            {
                type: 'text',
                key: 'setting_company_address',
                name: translate('setting.general.columns.company_address'),
                required: true
            }
        ]
    }
]);
</script>
