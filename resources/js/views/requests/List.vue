<template>
    <div id="container-page">
        <app-page
            :page-title="$t('requests.title')"
            :advanced-search-input="advancedSearchInput"
            :columns="columns"
            :fetch-data="fetchData"
            :table-row-selected="tableRowSelected ?? []"
            :scroll-table="{ x: 200, y: 300 }"
            :action-add="hasPermissionCreate ? actionAdd : null"
        />
    </div>
</template>

<script lang="jsx" setup>
import {ref, computed, watch} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import moment from "moment";
import {translate, useDaysInMonth} from "@/helpers/CommonHelper.js";
import TimeSheetsService from "@/services/Work/TimeSheetsService.js";

import {configStore as useConfigStore} from "@/stores/ConfigStore.js";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";

// Define current month and year as default values
const month = ref(moment().month() + 1);
const year = ref(moment().year());

// Initialize service for fetching timesheets data
const timeSheetsService = new TimeSheetsService();

// Store selected table rows
const tableRowSelected = ref([]);

// Define search input fields
const advancedSearchInput = [
    {
        type: 'range-week-picker',
        key: 'week',
        valueType: 'time',
        name: translate('requests.columns.employee_code')
    },
    {
        type: 'range-week-picker',
        key: 'week',
        valueType: 'time',
        name: translate('requests.columns.request_type')
    }
];

// Define main table columns
const columns = [
    {
        title: translate('requests.columns.employee_name'),
        width: 15,
        dataIndex: 'code',
        fixed: 'left',
        key: 'code',
        customRender: ({text, record}) => (
            <div>
                <b>{record?.employee?.first_name} {record?.employee?.last_name}</b>
            </div>
        )
    },
    {
        title: translate('requests.columns.time'),
        width: 15,
        dataIndex: 'code',
        fixed: 'left',
        key: 'code',
        customRender: ({text, record}) => (
            <div>{record?.employee?.code}</div>
        )
    },
    {title: translate('requests.columns.request_type'), width: 15, dataIndex: 'leave_days', key: 'leave_days'},
    {title: translate('requests.columns.request_content'), width: 15, dataIndex: 'leave_days', key: 'leave_days'},
    {title: translate('requests.columns.hr_approval'), width: 15, dataIndex: 'holiday_days', key: 'holiday_days'},
    {title: translate('requests.columns.manager_approval'), width: 15, dataIndex: 'overtime_hours', key: 'overtime_hours'},
    {
        title: translate('requests.columns.manager_reject_reason'),
        width: 20,
        dataIndex: 'total_late_early_minutes',
        key: 'total_late_early_minutes'
    }
];

// Generate daily columns dynamically
// Fetch main timesheet data
const fetchData = async (params) => {
    const response = await timeSheetsService.getList(params);
    return response;
};

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_REQUESTS);
const actionAdd = () => {
    router.push({name: RouteNameConstant.REQUESTS_CREATE});
}
</script>

<style lang="scss" scoped>
#container-page {
    padding: 24px;
    background: #fff !important;
    margin: 17px 16px 0 16px;
    border-radius: 6px;
}

#container-page {
    overflow: hidden; // Prevents reflow issues
}

</style>
