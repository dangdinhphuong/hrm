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
import SalaryService from "@/services/Employee/SalaryService.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import {authStore} from "@/stores/AuthStore.js";


const salaryService = new SalaryService();

const employeeColumns = ref([
    {title: '', dataIndex: "label", key: "label", width: "50%"},
    {title: '', dataIndex: "value", key: "value", width: "50%"}
]);
const columns = ref([
    {title: translate("payslip.columns.item"), dataIndex: "label1", key: "label1", width: "25%"},
    {title: translate("payslip.columns.value"), dataIndex: "value1", key: "value1", width: "25%", align: "left"},
    {title: translate("payslip.columns.item"), dataIndex: "label2", key: "label2", width: "25%"},
    {title: translate("payslip.columns.value"), dataIndex: "value2", key: "value2", width: "25%", align: "left"}
]);

const employeeInfo = ref([]);
const salaryData = ref([]);
const incomeData = ref([]);
const bankInfo = ref([]);
const employeeId = getCurrentRouteParams('employeeId') ?? (authStore().getUser.employeeId ?? 0);
const fetchData = async () => {
    try {
        const response = await salaryService.getPaySlip(employeeId);
        const data = response.data;
        // Cập nhật thông tin nhân viên
        employeeInfo.value = [
            {key: "employee_code", label: translate("payslip.columns.employee_code"), value: data.employee_code},
            {key: "employee_name", label: translate("payslip.columns.employee_name"), value: data.employee_name},
            {key: "department", label: translate("payslip.columns.department"), value: data.department},
            {key: "position", label: translate("payslip.columns.position"), value: data.position}
        ];

        // Cập nhật bảng lương
        salaryData.value = [
            {
                key: "work_days_standard",
                label: translate("payslip.columns.work_days_standard"),
                value: data.work_days_standard,
                isBold: true
            },
            {
                key: "salary_basic",
                label: translate("payslip.columns.salary_basic"),
                value: formatToVietnameseCurrency(data.salary_basic),
                isBold: true
            },
            {key: "work_days_total", label: translate("payslip.columns.work_days_total"), value: data.work_days_total},
            {
                key: "salary_kpi",
                label: translate("payslip.columns.salary_kpi"),
                value: formatToVietnameseCurrency(data.salary_kpi)
            },
            {
                key: "work_days_normal",
                label: translate("payslip.columns.work_days_normal"),
                value: data.work_days_normal
            },
            {
                key: "allowance_lunch",
                label: translate("payslip.columns.allowance_lunch"),
                value: formatToVietnameseCurrency(data.allowance_lunch)
            },
            {
                key: "work_days_holiday",
                label: translate("payslip.columns.work_days_holiday"),
                value: data.work_days_holiday
            },
            {
                key: "allowance_other",
                label: translate("payslip.columns.allowance_other"),
                value: formatToVietnameseCurrency(data.allowance_other)
            },
            {
                key: "total_salary",
                label: translate("payslip.columns.total_salary"),
                value: formatToVietnameseCurrency(data.total_salary),
                isBold: true
            }
        ];

        // Cập nhật bảng thu chi
        incomeData.value = [
            {
                key: "income_basic",
                label: translate("payslip.columns.income_basic"),
                value: formatToVietnameseCurrency(data.income_basic)
            },
            {
                key: "deduction_insurance",
                label: translate("payslip.columns.deduction_insurance"),
                value: formatToVietnameseCurrency(data.deduction_insurance)
            },
            {
                key: "income_overtime",
                label: translate("payslip.columns.income_overtime"),
                value: formatToVietnameseCurrency(data.income_overtime)
            },
            {
                key: "deduction_dependents",
                label: translate("payslip.columns.deduction_dependents"),
                value: data.deduction_dependents
            },
            {
                key: "income_travel",
                label: translate("payslip.columns.income_travel"),
                value: formatToVietnameseCurrency(data.income_travel)
            },
            {
                key: "deduction_tax",
                label: translate("payslip.columns.deduction_tax"),
                value: formatToVietnameseCurrency(data.deduction_tax)
            },
            {
                key: "income_bonus",
                label: translate("payslip.columns.income_bonus"),
                value: formatToVietnameseCurrency(data.income_bonus)
            },
            {
                key: "deduction_leave",
                label: translate("payslip.columns.deduction_leave"),
                value: formatToVietnameseCurrency(data.deduction_leave)
            },
            {
                key: "income_total",
                label: translate("payslip.columns.income_total"),
                value: formatToVietnameseCurrency(data.income_total),
                isBold: true
            },
            {
                key: "deduction_total",
                label: translate("payslip.columns.deduction_total"),
                value: formatToVietnameseCurrency(data.deduction_total),
                isBold: true
            },
            {
                key: "net_income",
                label: translate("payslip.columns.net_income"),
                value: formatToVietnameseCurrency(data.net_income),
                isBold: true
            }
        ];

        // Cập nhật thông tin ngân hàng
        bankInfo.value = [
            {key: "bank_account", label: translate("payslip.columns.bank_account"), value: data.bank_account},
            {key: "bank_holder", label: translate("payslip.columns.bank_holder"), value: data.bank_holder},
            {key: "bank_name", label: translate("payslip.columns.bank_name"), value: data.bank_name}
        ];
    } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
    }
};

// Gọi API khi component được mounted
fetchData();

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
