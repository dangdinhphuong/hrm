<template>
    <div id="container-page">
        <app-page
            :page-title="$t('work.title')"
            :advanced-search-input="advancedSearchInput"
            :columns="columns"
            :fetch-data="fetchData"
            :action-edit="hasPermissionEdit ? actionEdit : null"
            :action-detail="hasPermissionView ? actionDetail : null"
            :action-download="hasPermissionEdit ? actionDownload : null"
            :action-upload="hasPermissionEdit ? actionUpload : null"
            :action-add="hasPermissionCreate ? actionAdd : null"
            :table-row-selected="tableRowSelected ?? []"
            :scroll-table="{ x: 9000 }"
        />
    </div>
</template>

<script lang="jsx" setup>
import {ref, computed } from "vue";
import AppPage from "@/components/views/AppPage.vue";
import moment from "moment";
import {translate, useDaysInMonth} from "@/helpers/CommonHelper.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import router from "@/router/index.js";
import CommonConstant from "@/constants/CommonConstant.js";

import SelectAction from "@/constants/RowSelectionConstant.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";


const month = ref(moment().month() + 1); // Mặc định là tháng hiện tại
const year = ref(moment().year());

const employeeService = new EmployeeService();

const tableRowSelected = ref([]);

const advancedSearchInput = [
    {
        type: 'phone',
        key: 'phone',
        name: translate('delivery_note.phone')
    },
    {
        type: 'date',
        key: 'order_date',
        name: translate('delivery_note.columns.order_date')
    }
];

const daysInMonth = computed(() => useDaysInMonth(month.value, year.value));

const columns = computed(() => {
    const beforeDay = [
        {
            title: translate('work.columns.employee_name'),
            width: 15,
            dataIndex: 'code',
            fixed: 'left',
            key: 'code'
        }
    ];

    const day = daysInMonth.value.map((day, index) => ({
        title: (<div style="text-align: center;">
                {day.dayOfWeek}
                <br />
                {day.date}
            </div> ),
        width: 15,
        dataIndex: `day_${index + 1}`, // Tạo key duy nhất
        key: `day_${index + 1}`,
        customRender: ({ text, record }) => (
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <a-button type="primary" danger ghost style={{ marginBottom: "5%" }} >Check in: 08:00</a-button>
                <a-button ghost style="border-color: green; color: green;">
                    Check out: 17:30
                </a-button>

            </div>
        )
    }));

    const afterDay = [
        {
            title: translate('work.columns.leave_days'),
            width: 15,
            dataIndex: 'first_name',
            key: 'first_name',
            customRender: ({ text, record }) => (
                <div>
                    <b>5</b>
                </div>
            )
        },
        {
            title: translate('work.columns.work_description'),
            width: 15,
            dataIndex: 'position_name',
            key: 'position_name',
            customRender: ({ text, record }) => (
                <div>
                    <b>2 </b>
                </div>
            )
        },
        {
            title: translate('work.columns.holiday_leave'),
            width: 15,
            dataIndex: 'status',
            key: 'status',
            customRender: ({ text, record }) => {
                let statusText = CommonConstant.USER_STATUS;
                return <div>1</div>;
            }
        },
        {
            title: translate('work.columns.overtime_hours'),
            width: 15,
            dataIndex: 'bu',
            key: 'bu',
            customRender: ({ text, record }) => (
                <div>
                    {record.departments[0].business_unit_type ?? 0}
                </div>
            )
        },
        {
            title: translate('work.columns.late_early_minutes'),
            width: 15,
            dataIndex: 'bu',
            key: 'bu',
            customRender: ({ text, record }) => (
                <div>
                    {record.departments[0].business_unit_type ?? 0}
                </div>
            )
        }
    ];

    return [...beforeDay, ...day, ...afterDay];
});

console.log('daysInMonth',daysInMonth.value)

const columns2 = computed(() => {
    return daysInMonth.value.map((day, index) => ({
        title: day.date, // Hiển thị ngày tháng làm tiêu đề
        width: 9,
        dataIndex: `day_${index + 1}`, // Tạo key duy nhất cho từng ngày
        key: `day_${index + 1}`
    }));
});

console.log(columns2.value);

// todo sửa lại quyền sau
const hasPermissionView = hasPermissions(PermissionConstant.VIEW_EMPLOYEE_LIST);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_EMPLOYEE);
const hasPermissionEdit = hasPermissions(PermissionConstant.EDIT_EMPLOYEE_DETAIL);

const fetchData = (params) => {
    return employeeService.getList(params);
};

//Action
const actionEdit = ({id}) => {
    router.push({name: RouteNameConstant.INFO_EDIT, params: {'employeeId': id}});
};
const actionDetail = ({id}) => {
    router.push({name: RouteNameConstant.INFO_DETAIL, params: {'employeeId': id}});
};
const clickActionOther = () => {
    router.push({name: RouteNameConstant.INFO});
};
const actionDownload = () => {
    router.push({name: RouteNameConstant.USER_CREATE});
}
const actionUpload = () => {
    router.push({name: RouteNameConstant.USER_CREATE});
}
const actionAdd = () => {
    router.push({name: RouteNameConstant.INFO_CREATE});
}

const tableRowSelectionAction = (selectedRowKeys) => {
    const select = [
        SelectAction.ALL,
        SelectAction.INVERT,
        SelectAction.NONE,
        {
            key: 'delete',
            text: 'Xóa phiếu giao hàng',
            onSelect: changableRowKeys => {
                console.log('Xóa phiếu giao hàng', tableRowSelected.value)
            },
        },
        {
            key: 'odd',
            text: 'Select Odd Row',
            onSelect: changableRowKeys => {
                let newSelectedRowKeys = [];
                newSelectedRowKeys = changableRowKeys.filter((_key, index) => index % 2 === 0);
                selectedRowKeys.value = newSelectedRowKeys;
                console.log('Select Odd Row', tableRowSelected.value)
            },
        },
        {
            key: 'even',
            text: 'Select Even Row',
            onSelect: changableRowKeys => {
                let newSelectedRowKeys = [];
                newSelectedRowKeys = changableRowKeys.filter((_key, index) => index % 2 !== 0);
                selectedRowKeys.value = newSelectedRowKeys;
            },
        }
    ];

    return select;
};
</script>
<style lang="scss" scoped>
#container-page {
    padding: 24px;
    background: #fff !important;
    margin: 17px 16px 0px 16px;
    border-radius: 6px;
}
</style>
