<template>
    <div  id="page-content">
        <div class="page-title">
            <h2><b>{{ pageTitle }}</b></h2>
        </div>

        <div class="page-content mt-3">
            <div class="page-content mt-3">
                <a-card class="p-2">
                    <app-form
                        :fields="fields"
                        :errors="errors"
                        :source-data="user"
                        :submit="submit"
                        :cancel="cancel"
                        :is-multiple-sections=false
                        classWrapperFormItem="col-12 col-sm-8 d-flex flex-wrap"
                        classFormItem="col-12 col-sm-12 me-12"
                    >
                    </app-form>
                </a-card>
            </div>
        </div>
    </div>

</template>

<script setup>
import {ref, defineProps} from "vue";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {translate, cloneObject, convertConstantObjectToDataSelect} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";
import HrmCommonConstant from "@/constants/CommonConstant.js";
import ContractService from "@/services/Employee/ContractService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import {authStore} from "@/stores/AuthStore.js";

const contractService = new ContractService();
const currentUser = authStore().user;

const errors = ref({});

const employeeId = getCurrentRouteParams('employeeId');

let fields = [
    {
        type: 'text',
        key: 'employee_name',
        name: translate('requests.columns.employee_name'),
        default_value: currentUser.name,
        disabled: true,
    },
    {
        type: 'text',
        key: 'code',
        name: translate('requests.columns.employee_code'),
        default_value: currentUser.code,
    },
    {
        type: 'date',
        key: 'contract_type',
        name: translate('requests.columns.date')
    },
    {
        type: 'select',
        key: 'status',
        name: translate('requests.columns.request_type'),
        options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.STATUS),
        default_value: HrmCommonConstant.STATUS_ACTIVE,
    },
    {
        type: 'text-area',
        key: 'note',
        name: translate('requests.columns.request_content'),
        classAdvancedFormItem: 'col-sm-12',
    }
];
console.log('currentUser', currentUser);

const submit = async (formData) => {
    formData.employees_id = employeeId;
    await contractService.create(formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('requests.columns.messages.create_success'));
            router.push({name: RouteNameConstant.CONTRACT, params: {employeeId}});
        } else {
            messageError(translate('requests.columns.messages.create_fail'));
            errors.value = data.data ?? {};
        }
    });
}

const cancel = () => {
    router.push({name: RouteNameConstant.CONTRACT, params: {employeeId}});
}
</script>

<style lang="scss" scoped>
#page-content {
    padding: 24px;
    background: rgb(255, 255, 255);
    margin: 17px 16px 0px;
}

</style>
