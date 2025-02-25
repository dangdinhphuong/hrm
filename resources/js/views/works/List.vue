<template>
    <div id="container-page">
        <app-page
            :page-title="$t('work.title')"
            :advanced-search-input="advancedSearchInput"
            :columns="columns"
            :inner-columns="innerColumns"
            :fetch-data="fetchData"
            :inner-data="innerData"
            :action-edit="hasPermissionEdit ? actionEdit : null"
            :action-detail="hasPermissionView ? actionDetail : null"
            :action-add="hasPermissionCreate ? actionAdd : null"
            :table-row-selected="tableRowSelected ?? []"
            :scroll-table="{ x:200,  y: 300 }"
        />
    </div>
</template>

<script lang="jsx" setup>
import {ref, computed} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import moment from "moment";
import {translate, useDaysInMonth} from "@/helpers/CommonHelper.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import router from "@/router/index.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import EntitySelectConstant from "@/constants/EntitySelectConstant.js";


const month = ref(moment().month() + 1); // Mặc định là tháng hiện tại
const year = ref(moment().year());

const employeeService = new EmployeeService();

const tableRowSelected = ref([]);

const advancedSearchInput = [
    {
        type: 'range-picker',
        key: 'order_date',
        name: translate('delivery_note.columns.order_date')
    },
    {
        type: 'entity-select',
        key: 'sale',
        name: translate('user.columns.username'),
        entity: EntitySelectConstant.EMPLOYEES,
        valueType: 'number'
    },
];

const daysInMonth = computed(() => useDaysInMonth(month.value, year.value));
const columns = [
    {
        title: translate('work.columns.employee_name'),
        width: 15,
        dataIndex: 'code',
        fixed: 'left',
        key: 'code',
        customRender: ({text, record}) => (
            <div>
                <b>{record.first_name} {record.last_name}</b>
            </div>
        )
    },
    {
        title: translate('work.columns.employee_code'),
        width: 15,
        dataIndex: 'code',
        fixed: 'left',
        key: 'code'
    },
    {
        title: translate('work.columns.leave_days'),
        width: 15,
        dataIndex: 'leave_days',
        key: 'leave_days',
        customRender: ({text, record}) => (
            <div>
                {record?.monthly_timesheets?.length > 0 ? record.monthly_timesheets[0].leave_days : 0}
            </div>
        )

    },
    // {
    //     title: translate('work.columns.work_description'),
    //     width: 15,
    //     dataIndex: 'business_trip_days',
    //     key: 'business_trip_days',
    //     customRender: ({text, record}) => (
    //         <div>
    //             {record?.monthly_timesheets?.length > 0 ? record.monthly_timesheets[0].business_trip_days : 0}
    //         </div>
    //     )
    // },
    {
        title: translate('work.columns.holiday_leave'),
        width: 15,
        dataIndex: 'holiday_days',
        key: 'holiday_days',
        customRender: ({text, record}) => (
            <div>
                {record?.monthly_timesheets?.length > 0 ? record.monthly_timesheets[0].holiday_days : 0}
            </div>
        )
    },
    {
        title: translate('work.columns.overtime_hours'),
        width: 15,
        dataIndex: 'overtime_hours',
        key: 'overtime_hours',
        customRender: ({text, record}) => (
            <div>
                {record?.monthly_timesheets?.length > 0 ? record.monthly_timesheets[0].overtime_hours : 0}
            </div>
        )
    },
    {
        title: translate('work.columns.late_early_minutes'),
        width: 20,
        dataIndex: 'total_late_early_minutes',
        key: 'total_late_early_minutes',
        customRender: ({text, record}) => (
            <div>
                {record?.monthly_timesheets?.length > 0 ? record.monthly_timesheets[0].total_late_early_minutes : 0}
            </div>
        )
    }
];
const day = daysInMonth.value.map((day, index) => ({
    title: (<div style="text-align: center;">
        {day.dayOfWeek}
        <br/>
        {day.date}
    </div>),
    width: 15,
    dataIndex: `day_${index + 1}`, // Tạo key duy nhất
    key: `day_${index + 1}`,
    customRender: ({text, record}) => (
        <div style=" align-items: center; text-align: center;">
            <a-button type="primary" danger ghost style={{marginBottom: "5%", marginRight: "2%"}}>08:00</a-button>
            <a-button ghost style="border-color: green; color: green;"> 17:30</a-button>
        </div>
    )
}));

const innerColumns = [
    {title: translate('date'), dataIndex: 'date', key: 'date', width: 15},
    {title: translate('work.columns.check_in'), dataIndex: 'check_in', key: 'check_in', width: 15},
    {title: translate('work.columns.check_out'), dataIndex: 'check_out', key: 'check_out', width: 15}
];


const innerData = (data) =>{
    data = data.data;
    console.log("fetchData",data,data.length);
    const innersData = [];




    for (let i = 1; i <= data.length; ++i) {

        innersData.push({
                key: i,
                date: '2014-12-24',
                check_in: `08:00`,
                check_out: '17:12',
            });
}
    console.log("innersData",innersData);    return innersData;
};



// todo sửa lại quyền sau
const hasPermissionView = hasPermissions(PermissionConstant.VIEW_EMPLOYEE_LIST);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_EMPLOYEE);
const hasPermissionEdit = hasPermissions(PermissionConstant.EDIT_EMPLOYEE_DETAIL);

const fetchData = (params) => {
    // todo lấy dữ từ bảng monthly_timesheet_summary xong truy xuất sang timesheets, và employees
    return employeeService.getTimesheets(params);
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

</script>
<style lang="scss" scoped>
#container-page {
    padding: 24px;
    background: #fff !important;
    margin: 17px 16px 0px 16px;
    border-radius: 6px;
}
</style>
