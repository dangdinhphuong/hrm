<template>
    <div id="container-page">
        <app-page
            :page-title="$t('salary.title')"
            :advanced-search-input="advancedSearchInput"
            :columns="columns"
            :fetch-data="fetchData"
            :table-row-selected="tableRowSelected ?? []"
            :scroll-table="{ x: 200, y: 300 }"
            :action-download="hasPermissionDownload ? actionDownload : null"
        />
    </div>
</template>

<script lang="jsx" setup>
import {ref, computed, watch} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import moment from "moment";
import {convertConstantToDataSelect, translate, formatToVietnameseCurrency} from "@/helpers/CommonHelper.js";

import {configStore as useConfigStore} from "@/stores/ConfigStore.js";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js"
import CommonConstant from "@/constants/CommonConstant.js";
import HrmCommonConstant from "@/constants/CommonConstant.js";
import {exportToExcelMultipleSheets} from "@/helpers/ExcelHelper.js";
import SalaryService from "@/services/Employee/SalaryService.js";
import EntitySelectConstant from "@/constants/EntitySelectConstant.js";

const salaryService = new SalaryService();
// Define current month and year as default values
const month = ref(moment().month() + 1);
const year = ref(moment().year());


// Store selected table rows
const tableRowSelected = ref([]);

// Define search input fields
const advancedSearchInput = [
    {
        type: 'search-select',
        key: 'employee_id',
        name: translate('salary.columns.employee_code'),
        options: [],
        valueType: 'number',
        multiple: false,
        entity: EntitySelectConstant.EMPLOYEES,
    },
];

// Define main table columns
const columns = [
    {
        title: translate('salary.columns.employee_name'),
        width: 15,
        dataIndex: 'employee_name',

        key: 'employee_name',
        customRender: ({text, record}) => (
            <div>
                <b>{record?.employee?.first_name} {record?.employee?.last_name}</b>
            </div>
        )
    },
    {
        title: translate('salary.columns.employee_code'),
        width: 15,
        dataIndex: 'code',
        key: 'code',
        customRender: ({text, record}) => (
            <div>
                <b>{record?.employee?.code}</b>
            </div>
        )
    },
    {
        title: translate('salary.columns.basic_salary'),
        width: 15,
        dataIndex: 'basic_salary',
        key: 'basic_salary',
        customRender: ({text, record}) => {
            return (
                <div>
                    <b>{formatToVietnameseCurrency(record?.salaries?.basic_salary)}</b>
                </div>
            );
        }
    },
    {
        title: translate('salary.columns.bonus'),
        width: 15,
        dataIndex: 'bonus',

        key: 'bonus',
        customRender: ({text, record}) => (
            <div>
                <b>{formatToVietnameseCurrency(record?.salaries?.bonus)}</b>
            </div>
        )
    },
    {
        title: translate('salary.columns.kpi_salary'),
        width: 15,
        dataIndex: 'kpi_salary',

        key: 'kpi_salary',
        customRender: ({text, record}) => (
            <div>
                <b>{formatToVietnameseCurrency(record?.salaries?.kpi_salary)}</b>
            </div>
        )
    },
    {
        title: translate('salary.columns.allowance_salary'),
        width: 15,
        dataIndex: 'allowance_salary',
        key: 'allowance_salary',
        customRender: ({text, record}) => {
            return (
                <div>
                    <b>{formatToVietnameseCurrency(record?.salaries?.allowance_salary)}</b>
                </div>
            );
        }
    },
    {
        title: translate('salary.columns.total_salary'),
        width: 10,
        dataIndex: 'total_salary',
        key: 'total_salary',
        customRender: ({text, record}) => {

            return (
                <div>
                    <b>{formatToVietnameseCurrency(record?.salaries?.total_salary)}</b>
                </div>
            );

        }
    }
];


const fetchData = async (params) => {
    return await salaryService.getList(params);
};

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_REQUESTS);
const hasPermissionDownload = hasPermissions(PermissionConstant.VIEW_EMPLOYEE_SALARY);
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
const actionDownload = async (param) => {
    const response = await fetchData(param);

    const columnData = [
        [
            translate('salary.columns.employee_name'),
            translate('salary.columns.employee_code'),
            translate('salary.columns.basic_salary'),
            translate('salary.columns.bonus'),
            translate('salary.columns.kpi_salary'),
            translate('salary.columns.allowance_salary'),
            translate('salary.columns.total_salary'),
        ],
        ...response.map((item) => [
            item.employee.first_name + ' ' + item.employee.last_name,
            item.employee.code,
            formatToVietnameseCurrency(item?.salaries?.basic_salary),
            formatToVietnameseCurrency(item?.salaries?.bonus),
            formatToVietnameseCurrency(item?.salaries?.kpi_salary),
            formatToVietnameseCurrency(item?.salaries?.allowance_salary),
            formatToVietnameseCurrency(item?.salaries?.total_salary),
        ]),
    ];
console.log('columnData', columnData);
    exportToExcelMultipleSheets([
        {sheetName: "salaries", data: columnData},
    ], 'salaries');
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
