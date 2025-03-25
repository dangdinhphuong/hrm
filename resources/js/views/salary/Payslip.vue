<template>
    <div>
        <!-- Bảng thông tin nhân viên -->
        <h3>Thông tin nhân viên (T2/2025)</h3>
        <a-table
            :bordered="true"
            :pagination="false"
            :dataSource="employeeInfo"
            :columns="employeeColumns"
            size="small"
        >
            <template #bodyCell="{ column, record }">
                <strong>{{ record[column.dataIndex] }}</strong>
            </template>
        </a-table>

        <br/>

        <!-- Bảng thông tin lương -->
        <h3>Thông tin lương (T2/2025)</h3>
        <a-table
            :bordered="true"
            :pagination="false"
            :dataSource="formattedSalaryData"
            :columns="columns"
            size="small"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex.startsWith('value') && record.isBold">
                    <strong style="color: red">{{ record[column.dataIndex] }}</strong>
                </template>
                <template v-else>
                    {{ record[column.dataIndex] }}
                </template>
            </template>
        </a-table>

        <br/>

        <!-- Bảng các khoản thu chi -->
        <h3>Các khoản thu chi (T2/2025)</h3>
        <a-table
            :bordered="true"
            :pagination="false"
            :dataSource="formattedIncomeData"
            :columns="columns"
            size="small"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex.startsWith('value') && record.isBold">
                    <strong style="color: red">{{ record[column.dataIndex] }}</strong>
                </template>
                <template v-else>
                    {{ record[column.dataIndex] }}
                </template>
            </template>
        </a-table>

        <br/>

        <!-- Bảng thông tin ngân hàng -->
        <h3>Thông tin ngân hàng</h3>
        <a-table
            :bordered="true"
            :pagination="false"
            :dataSource="bankInfo"
            :columns="employeeColumns"
            size="small"
        >
            <template #bodyCell="{ column, record }">
                <strong>{{ record[column.dataIndex] }}</strong>
            </template>
        </a-table>
    </div>
</template>

<script setup>
import {ref, computed} from "vue";
import {translate, formatToVietnameseCurrency} from "@/helpers/CommonHelper.js";

const employeeColumns = ref([
    {title: '', dataIndex: "label", key: "label", width: "50%"},
    {title: '', dataIndex: "value", key: "value", width: "50%"}
]);

const employeeInfo = ref([
    {key: "employee_code", label: translate("payslip.columns.employee_code"), value: "NV001"},
    {key: "employee_name", label: translate("payslip.columns.employee_name"), value: "Nguyễn Văn A"},
    {key: "department", label: translate("payslip.columns.department"), value: "Kinh doanh"},
    {key: "position", label: translate("payslip.columns.position"), value: "Trưởng phòng"}
]);

const columns = ref([
    {title: translate("payslip.columns.item"), dataIndex: "label1", key: "label1", width: "25%"},
    {title: translate("payslip.columns.value"), dataIndex: "value1", key: "value1", width: "25%", align: "left"},
    {title: translate("payslip.columns.item"), dataIndex: "label2", key: "label2", width: "25%"},
    {title: translate("payslip.columns.value"), dataIndex: "value2", key: "value2", width: "25%", align: "left"}
]);

const salaryData = ref([
    {key: "work_days_standard", label: translate("payslip.columns.work_days_standard"), value: "26", isBold: true}, // Ngày công tiêu chuẩn
    {
        key: "salary_basic",
        label: translate("payslip.columns.salary_basic"),
        value: formatToVietnameseCurrency(13000000),
        isBold: true
    }, // Lương cơ bản
    {key: "work_days_total", label: translate("payslip.columns.work_days_total"), value: "24"}, // Tổng số ngày công thực tế
    {key: "salary_kpi", label: translate("payslip.columns.salary_kpi"), value: formatToVietnameseCurrency(2000000)}, // Lương KPI
    {key: "work_days_normal", label: translate("payslip.columns.work_days_normal"), value: "22"}, // Ngày công làm việc bình thường
    {
        key: "allowance_lunch",
        label: translate("payslip.columns.allowance_lunch"),
        value: formatToVietnameseCurrency(500000)
    }, // Phụ cấp ăn trưa
    {key: "work_days_holiday", label: translate("payslip.columns.work_days_holiday"), value: "2"}, // Ngày công làm việc vào ngày lễ
    {
        key: "allowance_other",
        label: translate("payslip.columns.allowance_other"),
        value: formatToVietnameseCurrency(300000)
    }, // Các khoản phụ cấp khác
    {
        key: "total_salary",
        label: translate("payslip.columns.total_salary"),
        value: formatToVietnameseCurrency(15800000),
        isBold: true
    }, // Tổng lương
]);

const incomeData = ref([
    { key: "income_basic", label: translate("payslip.columns.income_basic"), value: formatToVietnameseCurrency(13000000) }, // Thu nhập cơ bản
    { key: "deduction_insurance", label: translate("payslip.columns.deduction_insurance"), value: formatToVietnameseCurrency(- 500000) }, // Khấu trừ bảo hiểm
    { key: "income_overtime", label: translate("payslip.columns.income_overtime"), value: formatToVietnameseCurrency(1000000) }, // Thu nhập từ làm thêm giờ
    { key: "deduction_dependents", label: translate("payslip.columns.deduction_dependents"), value: 2 }, // Số người phụ thuộc
    { key: "income_travel", label: translate("payslip.columns.income_travel"), value: formatToVietnameseCurrency(300000) }, // Vé xe
    { key: "deduction_tax", label: translate("payslip.columns.deduction_tax"), value: formatToVietnameseCurrency(- 300000) }, // Thuế thu nhập cá nhân
    { key: "income_bonus", label: translate("payslip.columns.income_bonus"), value: formatToVietnameseCurrency(500000) }, // Thưởng
    { key: "deduction_leave", label: translate("payslip.columns.deduction_leave"), value: formatToVietnameseCurrency(- 300000) }, // Nghỉ không phép / thiếu giờ làm
    { key: "income_total", label: translate("payslip.columns.income_total"), value: formatToVietnameseCurrency(15000000), isBold: true }, // Tổng thu nhập
    { key: "deduction_total", label: translate("payslip.columns.deduction_total"), value: formatToVietnameseCurrency(- 800000), isBold: true }, // Tổng khoản khấu trừ
    { key: "net_income", label: translate("payslip.columns.net_income"), value: formatToVietnameseCurrency(14200000), isBold: true }, // Thu nhập thực nhận
]);

const bankInfo = ref([
    { key: "bank_account", label: translate("payslip.columns.bank_account"), value: "0976594507" }, // Số tài khoản ngân hàng
    { key: "bank_holder", label: translate("payslip.columns.bank_holder"), value: "Nguyễn Văn A" }, // Chủ tài khoản ngân hàng
    { key: "bank_name", label: translate("payslip.columns.bank_name"), value: "Vietcombank" } // Ngân hàng
]);

const formattedSalaryData = computed(() => {
    const rows = [];
    for (let i = 0; i < salaryData.value.length; i += 2) {
        rows.push({
            key: salaryData.value[i]?.key || `row-${i}`,
            label1: salaryData.value[i]?.label || "",
            value1: salaryData.value[i]?.value || "",
            isBold: salaryData.value[i]?.isBold || false,
            label2: salaryData.value[i + 1]?.label || "",
            value2: salaryData.value[i + 1]?.value || "",
            isBold2: salaryData.value[i + 1]?.isBold || false
        });
    }
    return rows;
});


const formattedIncomeData = computed(() => {
    const rows = [];
    for (let i = 0; i < incomeData.value.length; i += 2) {
        rows.push({
            key: incomeData.value[i]?.key || `row-${i}`,
            label1: incomeData.value[i]?.label || "",
            value1: incomeData.value[i]?.value || "",
            isBold: incomeData.value[i]?.isBold || false,
            label2: incomeData.value[i + 1]?.label || "",
            value2: incomeData.value[i + 1]?.value || "",
            isBold2: incomeData.value[i + 1]?.isBold || false
        });
    }
    return rows;
});
</script>
