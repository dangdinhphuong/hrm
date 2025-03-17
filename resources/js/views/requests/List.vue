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
import {convertConstantToDataSelect, translate, useDaysInMonth} from "@/helpers/CommonHelper.js";
import TimeSheetsService from "@/services/Work/TimeSheetsService.js";

import {configStore as useConfigStore} from "@/stores/ConfigStore.js";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js"
import RequestService from "@/services/Work/RequestService.js";
import CommonConstant from "@/constants/CommonConstant.js";
import HrmCommonConstant from "@/constants/CommonConstant.js";

const requestService = new RequestService();

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
        type: 'month',
        key: 'year-month',
        name: translate('requests.columns.employee_code')
    },
    {
        type: 'select',
        key: 'leave_type',
        options: convertConstantToDataSelect(HrmCommonConstant.LEAVE_REASONS),
        name: translate('requests.columns.request_type'),
        valueType: 'number'
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
            <div>{record?.date}</div>
        )
    }, {
        title: translate('requests.columns.request_type'),
        width: 15,
        dataIndex: 'leave_days',
        key: 'leave_days',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.LEAVE_REASONS;
            return (
                <div>
                    {statusText.get(record.leave_type)}
                </div>
            );
        }
    },
    {title: translate('requests.columns.request_content'), width: 15, dataIndex: 'content', key: 'content'},
    {
        title: translate('requests.columns.hr_approval'),
        width: 15,
        dataIndex: 'hr_approval',
        key: 'hr_approval',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.APPROVAL_STATUS;
            return (
                <div>
                    {statusText.get(record.hr_status)}
                </div>
            );
        }
    }, {
        title: translate('requests.columns.manager_approval'),
        width: 15,
        dataIndex: 'overtime_hours',
        key: 'overtime_hours',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.APPROVAL_STATUS;
            return (
                <div>
                    {statusText.get(record.manager_status)}
                </div>
            );
        }
    },
    {
        title: translate('requests.columns.manager_reject_reason'),
        width: 20,
        dataIndex: 'reject_reason',
        key: 'reject_reason'
    }
];

// Generate daily columns dynamically
// Fetch main timesheet data
const fetchData = async (params) => {
    const response = await requestService.getList(params);
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
