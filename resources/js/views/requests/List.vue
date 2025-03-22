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
            :click-action-other="hasPermissionApprove ?clickActionOther : null"
            :get-action-other="hasPermissionApprove ? getActionOther : null"
            :action-download="hasPermissionDownload ? actionDownload : null"
            :trigger-fetch-data="timeFetchData"
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
import {modalConfirm} from "@/helpers/ModalHelper.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {exportToExcel, exportToExcelMultipleSheets } from "@/helpers/ExcelHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";

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

    // {
    //     type: 'select',
    //     key: 'leave_type',
    //     options: convertConstantToDataSelect(HrmCommonConstant.LEAVE_REASONS),
    //     name: translate('requests.columns.request_type'),
    //     valueType: 'number'
    // }
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
        width: 10,
        dataIndex: 'hr_approval',
        key: 'hr_approval',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.APPROVAL_STATUS;
            let color = '';
            if (record.hr_status == CommonConstant.PENDING_APPROVAL) {
                color = "processing";
            } else if (record.hr_status == CommonConstant.APPROVED) {
                color = "success";
            } else if (record.hr_status == CommonConstant.REJECTED) {
                color = "error";
            }

            return (
                <div>
                    <a-tag color={color}>{statusText.get(record.hr_status)}</a-tag>
                </div>
            );

        }
    },
    {
        title: translate('requests.columns.manager_approval'),
        width: 10,
        dataIndex: 'overtime_hours',
        key: 'overtime_hours',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.APPROVAL_STATUS;
            let color = '';
            if (record.manager_status == CommonConstant.PENDING_APPROVAL) {
                color = "processing";
            } else if (record.manager_status == CommonConstant.APPROVED) {
                color = "success";
            } else if (record.manager_status == CommonConstant.REJECTED) {
                color = "error";
            }

            return (
                <div>
                    <a-tag color={color}>{statusText.get(record.manager_status)}</a-tag>
                </div>
            );

        }
    },
];


// Fetch main timesheet data APPROVE_LEAVE_REQUEST
const fetchData = async (params) => {
    const response = await requestService.getList(params);
    return response;
};

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_REQUESTS);
const hasPermissionApprove = hasPermissions(PermissionConstant.APPROVE_LEAVE_REQUESTS);
const hasPermissionDownload = hasPermissions(PermissionConstant.APPROVE_LEAVE_REQUESTS);
const actionAdd = () => {
    router.push({name: RouteNameConstant.REQUESTS_CREATE});
}

const getActionOther = (record) => {
    let otherAction = [];

    if (record.hr_status === CommonConstant.PENDING_APPROVAL || record.manager_status === CommonConstant.PENDING_APPROVAL) {
        otherAction.push({
            title: translate('requests.columns.approved'),
            event: CommonConstant.APPROVED
        });
        otherAction.push({
            title: translate('requests.columns.rejected'),
            event: CommonConstant.REJECTED
        });
    }

    return otherAction;
};

const clickActionOther = ({id, event}) => {

    let title = '';
    let content = '';
    let onOk = null;

    title = translate('requests.modal.title');
    content = translate('requests.modal.content');
    console.log('clickActionOther', id, event);
    if(event == CommonConstant.APPROVED){
        onOk = () => {
            requestService.update(id, {'approvalStatus': event}).then((result) => {
                messageAndRerenderAppTable(
                    result,
                    translate('requests.messages.update_success'),
                    translate('requests.messages.update_fail')
                )
            });
        }
    } else if(event == CommonConstant.REJECTED){
        onOk = () => {
            requestService.update(id, {'approvalStatus': event}).then((result) => {
                console.log('approvalStatus', result);
                messageAndRerenderAppTable(
                    result,
                    translate('requests.messages.update_success'),
                    translate('requests.messages.update_fail')
                )
            });
        }
    }

    modalConfirm(title, content, onOk);
}

const timeFetchData = ref(Date.now());

const messageAndRerenderAppTable = (resultRequestHttp, success, fail) => {
    if (isSuccessRequest(resultRequestHttp)) {
        messageSuccess(resultRequestHttp.message ?? success);
    } else {
        messageError(resultRequestHttp.message ?? fail);
    }
    timeFetchData.value = Date.now();
}

const actionDownload = async (param) => {
    const response = await fetchData(param);

    const columnData = [
        [
            translate('requests.columns.employee_name'),
            translate('requests.columns.employee_code'),
            translate('requests.columns.time'),
            translate('requests.columns.request_type'),
            translate('requests.columns.request_content'),
            translate('requests.columns.hr_approval'),
            translate('requests.columns.manager_approval'),
        ],
        ...response.map((item) => [
            item.employee.first_name + ' ' + item.employee.last_name,
            item.employee.code,
            item.date,
            CommonConstant.LEAVE_REASONS.get(item.leave_type),
            item.content,
            CommonConstant.APPROVAL_STATUS.get(item.hr_status),
            CommonConstant.APPROVAL_STATUS.get(item.manager_status),
        ]),
    ];

    exportToExcelMultipleSheets([
        { sheetName: "Requests", data: columnData },
    ], 'requests');
};


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
