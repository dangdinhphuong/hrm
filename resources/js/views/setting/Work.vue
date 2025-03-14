<template>
    <SettingsForm
        :pageTitle="$t('setting.work.title')"
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
            {type: 'time', key: 'setting_checkin', name: translate('setting.work.columns.checkin')},
            {type: 'time', key: 'setting_checkout', name: translate('setting.work.columns.checkout')},
            {
                type: 'text',
                key: 'setting_leave_day',
                default_value: 1,
                name: translate('setting.work.columns.days_off_per_month')
            },
            {
                type: 'number', // Hoặc 'text' nếu cần nhập số phút
                key: 'setting_late_minutes',
                default_value: 0, // Giá trị mặc định là 0 phút đi muộn
                name: translate('setting.work.columns.late_minutes_per_month')
            }
        ]
    }
]);
</script>


