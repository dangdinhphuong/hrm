<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-content mt-3">
        <a-card class="p-2">
            <app-form
                :fields="fields"
                :errors="errors"
                :source-data="user"
                :submit="submit"
                :cancel="cancel"
                :is-multiple-sections=true
                classWrapperFormItem="col-12 col-sm-12 d-flex flex-wrap"
                classFormItem="col-12 col-sm-4 p-2"
            >
                <template v-slot:title-form>
                    <div class="page-title">
                        <h2><b>{{ pageTitle }}</b></h2>
                    </div>
                </template>
            </app-form>
        </a-card>
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

const contractService = new ContractService();
const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('hrm.contract.create_title')
    },
    user: {
        type: Object,
        default: {}
    },
    errors: {
        type: Object,
        default: {}
    },
    updateUser: {
        type: Function,
        default: null
    },
    disabledField: {
        type: Array,
        default: []
    },
    redirectChildComponent: {
        type: Function,
        default: null
    },
    paramChildComponent: {
        type: Object,
        default: {}
    },
});

const errors = ref(cloneObject(props.errors));

const employeeId = getCurrentRouteParams('employeeId');

let fields = [
    {
        groupName: translate('hrm.general_information'),
        items: [
            {
                type: 'text',
                key: 'contract_number',
                name: translate('hrm.contract.contact_code'),
                required: true
            },
            {
                type: 'select',
                key: 'contract_type',
                name: translate('hrm.contract.contact_type'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.CONTRACT_TYPE),
                default_value: HrmCommonConstant.CONTRACT_TYPE_DEFAULT,
            },
            {
                type: 'select',
                key: 'status',
                name: translate('hrm.contract.status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.STATUS),
                default_value: HrmCommonConstant.STATUS_ACTIVE,
            },
            {
                type: 'date',
                key: 'start_time',
                name: translate('hrm.contract.start_day'),
                required: true
            }, {
                type: 'date',
                key: 'end_time',
                name: translate('hrm.contract.end_day'),
                required: true
            }, {
                type: 'text-area',
                key: 'note',
                name: translate('hrm.contract.note'),
                classAdvancedFormItem: 'col-sm-12',
            }
        ]
    },
    {
        groupName: translate('hrm.attached_files'),
        items: [
            {
                type: 'upload-file',
                key: 'file',
                listTypeUpload: 'picture',
                maxCount: 5,
                classAdvancedFormItem: 'col-sm-12',
            }
        ]
    }

];

const prepareFields = () => {
    fields = fields.map(field => {
        if (props.disabledField.includes(field.key)) {
            return {...field, disabled: true};
        }
        return field;
    });
}
prepareFields();

const submit = async (formData) => {
    formData.employees_id = employeeId;
    console.log('formData', formData);
    await contractService.create(formData).then((data) => {
        console.log('submit', data);
        if (isSuccessRequest(data)) {
            messageSuccess(translate('hrm.contract.messages.create_success'));
            router.push({ name: RouteNameConstant.CONTRACT, params: {employeeId} });
        } else {
            messageError(translate('hrm.contract.messages.create_fail'));
            errors.value = data.data ?? {};
        }
    });
}

const cancel = () => {
    router.push({ name: RouteNameConstant.CONTRACT, params: {employeeId} });
}

const childComponent = () => {
    props.redirectChildComponent("infoUser.userList")
}
</script>

<style lang="scss" scoped>
:deep(.section-1) {
    border-right: 2px solid #cfcdcd;
    @media (max-width: 576px) {
        border-right: none;
    }
}

:deep(.page-content > .ant-card-bordered ) {
    border: none;
}

:deep(.avatar) {
    text-align: center;
}

:deep(.avatar img) {
    width: 14vh;
    height: 14vh;
    border-radius: 50%; /* Make the image circular */
}
</style>
