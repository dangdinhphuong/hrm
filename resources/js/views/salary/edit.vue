<template>
    <div id="page-content">
        <div class="page-title">
            <h2><b>{{ pageTitle }}</b></h2>
        </div>

        <div class="page-content mt-3" v-if="isLoadingComplete()">
            <a-card class=" mt-2 p-2">
                <app-form
                    :fields="fields"
                    :errors="errors"
                    :source-data="salary"
                    :submit="submit"
                    :cancel="cancel"
                    :is-multiple-sections="false"
                    classWrapperFormItem="col-12 col-sm-12 d-flex flex-wrap"
                    classFormItem="col-12 col-sm-6 p-2"
                />
            </a-card>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import router from "@/router";
import RouteNameConstant from "@/constants/RouteNameConstant";
import {formatToVietnameseCurrency, translate} from "@/helpers/CommonHelper";
import AppForm from "@/components/views/AppForm.vue";
import SalaryService from "@/services/Employee/SalaryService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper";
import {messageError, messageSuccess} from "@/helpers/MessageHelper";
import {getCurrentRouteParams} from "@/helpers/RouteHelper";
import {authStore} from "@/stores/AuthStore";
import {useLoading} from "@/composables/loading.js";

const salaryService = new SalaryService();
const errors = ref({});
const employeeId = getCurrentRouteParams('employeeId');
const pageTitle = translate('salary.detail_tile');
const salary = ref(0);

const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const fields = [
    {
        type: 'number',
        key: 'basic_salary',
        name: translate('salary.columns.basic_salary'),
        required: true,
        default_value: 0,
    },
    {
        type: 'number',
        key: 'kpi_salary',
        name: translate('salary.columns.kpi_salary'),
        default_value: 0,
    }, {
        type: 'number',
        key: 'bonus',
        name: translate('salary.columns.bonus'),
        default_value: 0,
    },
    {
        type: 'number',
        key: 'allowance_lunch',
        name: translate('payslip.columns.allowance_lunch'),
        default_value: 0,
    },
    {
        type: 'number',
        key: 'allowance_salary',
        name: translate('salary.columns.allowance_salary'),
        default_value: 0,
    }, {
        type: 'number',
        key: "income_travel",
        name: translate("payslip.columns.income_travel"),
        default_value: 0,
    },
    {
        type: 'number',
        key: "deduction_dependents",
        name: translate("payslip.columns.deduction_dependents"),
        default_value: 0,
    },
    {
        type: 'number',
        key: "deduction_tax",
        name: translate("payslip.columns.deduction_tax"),
        default_value: 0,
    },
    {
        type: 'number',
        key: "deduction_insurance",
        name: translate("payslip.columns.deduction_insurance"),
        default_value: 0,
    }
];
const getSalary = async () => {
    setLoading();
    await salaryService.find({'employee_id': employeeId}).then((data) => {
        salary.value = data;
        setLoadingComplete();
    });
};

const submit = async (formData) => {
    formData.employees_id = employeeId;
    try {
        const data = await salaryService.update(employeeId, formData);
        if (isSuccessRequest(data)) {
            messageSuccess(translate('salary.messages.update_success'));
            router.push({name: RouteNameConstant.EDIT_EMPLOYEE_SALARY});
        } else {
            messageError(translate('salary.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    } catch (error) {
        messageError(translate('salary.messages.update_fail'));
    }
};


const cancel = () => router.push({name: RouteNameConstant.EDIT_EMPLOYEE_SALARY});
onMounted(() => {
    getSalary();
});
</script>

<style lang="scss" scoped>
#page-content {
    padding: 24px;
    background: #fff;
    margin: 17px 16px 0;
}
</style>
