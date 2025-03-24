<template>
    <div id="container-page">
        <app-page
            :page-title="$t('work.title')"
            :advanced-search-input="advancedSearchInput"
            :columns="columns"
            :inner-columns="innerColumns"
            :fetch-data="fetchData"
            :fetch-inner-data="fetchInnerData"
            :table-row-selected="tableRowSelected ?? []"
            :scroll-table="{ x: 200, y: 300 }"
            :can-search=false
        />
    </div>
</template>

<script lang="jsx" setup>
import {ref, computed, watch} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import moment from "moment";
import {translate, useDaysInMonth} from "@/helpers/CommonHelper.js";
import TimeSheetsService from "@/services/Work/TimeSheetsService.js";
import EntitySelectConstant from "@/constants/EntitySelectConstant.js";
import dayjs from 'dayjs';
import {configStore as useConfigStore} from "@/stores/ConfigStore.js";

// Define current month and year as default values
const month = ref(moment().month() + 1);
const year = ref(moment().year());

// Initialize service for fetching timesheets data
const timeSheetsService = new TimeSheetsService();

// Store selected table rows
const tableRowSelected = ref([]);

// Store inner table data
const innerData = ref([]);

// Define search input fields
const advancedSearchInput = [
    // {
    //     type: 'range-week-picker',
    //     key: 'week',
    //     valueType: 'time',
    //     name: translate('work.columns.monthly_work_table')
    // },
    // {
    //     type: 'range-month-picker',
    //     key: 'monthly_work',
    //     valueType: 'time',
    //     name: translate('work.columns.monthly_work_table')
    // },
    // {
    //     type: 'entity-select',
    //     key: 'username',
    //     name: translate('work.columns.employee_code'),
    //     entity: EntitySelectConstant.EMPLOYEES,
    //     valueType: 'number'
    // },

];

// Compute the number of days in the selected month
const daysInMonth = computed(() => useDaysInMonth(month.value, year.value));
// Define main table columns
const columns = [
    {
        title: translate('work.columns.employee_name'),
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
        title: translate('work.columns.employee_code'),
        width: 15,
        dataIndex: 'code',
        fixed: 'left',
        key: 'code',
        customRender: ({text, record}) => (
            <div>{record?.employee?.code}</div>
        )
    },
    {title: translate('work.columns.leave_days'), width: 15, dataIndex: 'leave_days', key: 'leave_days'},
    {title: translate('work.columns.holiday_leave'), width: 15, dataIndex: 'holiday_days', key: 'holiday_days'},
    {title: translate('work.columns.overtime_hours'), width: 15, dataIndex: 'overtime_hours', key: 'overtime_hours'},
    {
        title: translate('work.columns.late_early_minutes'),
        width: 20,
        dataIndex: 'total_late_early_minutes',
        key: 'total_late_early_minutes'
    }
];

// Generate daily columns dynamically
const day = daysInMonth.value.map((day, index) => ({
    title: (
        <div style="text-align: center;">
            {day.dayOfWeek}
            <br/>
            {day.date}
        </div>
    ),
    width: 15,
    dataIndex: `day_${index + 1}`, // Unique key for each day
    key: `day_${index + 1}`,
    customRender: ({text, record}) => (
        <div style="text-align: center;">
            <a-button type="primary" danger ghost style={{marginBottom: "5%", marginRight: "2%"}}>08:00</a-button>
            <a-button ghost style="border-color: green; color: green;"> 17:30</a-button>
        </div>
    )
}));

// Define columns for inner table (detailed timesheet data)
const innerColumns = [
    {title: translate('date'), dataIndex: 'date', key: 'date', width: 15},
    {title: translate('work.columns.check_in'), dataIndex: 'check_in', key: 'check_in', width: 15},
    {title: translate('work.columns.check_out'), dataIndex: 'check_out', key: 'check_out', width: 15},
    {title: translate('work.columns.arrive_late'), dataIndex: 'arrive_late', key: 'arrive_late', width: 15},
    {title: translate('work.columns.leave_early'), dataIndex: 'leave_early', key: 'leave_early', width: 15},
    // {title: translate('work.columns.status'), dataIndex: 'status', key: 'status', width: 15}
];

// Fetch detailed timesheet data for inner table
const fetchInnerData = async (response) => {
    return response.data.map(item => ({
        id: item.id,
        data: item.timesheets.map((timesheet, i) => {
            const getTime = (time) => (time ? dayjs(time, 'HH:mm:ss') : null);
            const workStart = getTime(config.value.setting_checkin + ':00');
            const workEnd = getTime(config.setting_checkout+':00');
            const checkIn = getTime(timesheet.check_in);
            const checkOut = getTime(timesheet.check_out);

            return {
                key: i,
                date: timesheet.work_date,
                check_in: timesheet.check_in,
                check_out: timesheet.check_out,
                // status: "Chưa duyệt đơn",
                arrive_late: checkIn?.isAfter(workStart) ? checkIn.diff(workStart, 'minute') : 0,
                leave_early: checkOut?.isBefore(workEnd) ? workEnd.diff(checkOut, 'minute') : 0
            };
        })
    }));
};

// Fetch main timesheet data
const fetchData = async (params) => {
    const response = await timeSheetsService.getList(params);
    return response;
};

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);

watch(() => useConfigStore().settings, (newValue) => {
    console.log("TheSidebar - Settings từ store:", newValue);
});
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
