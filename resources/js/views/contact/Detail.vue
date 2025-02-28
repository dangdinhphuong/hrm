<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-content mt-3"  v-if="isLoadingComplete()">
        <a-card class="p-2">
            <app-form
                :fields="fields"
                :errors="errors"
                :source-data="contract"
                :submit="submit"
                :cancel="cancel"
                :can-submit="false"
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
import {translate, cloneObject, isEmptyObject, convertConstantObjectToDataSelect} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import ContractService from "@/services/Employee/ContractService.js";
import {useLoading} from "@/composables/loading.js";
import {throwNotFoundModelException} from "@/helpers/ExceptionHelper.js";
import HrmCommonConstant from "@/constants/CommonConstant.js";
import moment from "moment";

const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('hrm.contract.detail_title')
    },
    errors: {
        type: Object,
        default: {}
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

const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const errors = ref(cloneObject(props.errors));
const contractId = getCurrentRouteParams('id');
const contract = ref({});
let fields = [
    {
        groupName: translate('hrm.general_information'),
        items: [
            {
                type: 'text',
                key: 'contract_number',
                name: translate('hrm.contract.contact_code'),
                disabled: true,
                required: true
            },
            {
                type: 'select',
                key: 'contract_type',
                name: translate('hrm.contract.contact_type'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.CONTRACT_TYPE),
                disabled: true,
                default_value: HrmCommonConstant.CONTRACT_TYPE_DEFAULT,
            },
            {
                type: 'select',
                key: 'status',
                name: translate('hrm.contract.status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.STATUS),
                disabled: true,
                default_value: HrmCommonConstant.STATUS_ACTIVE,
            },
            {
                type: 'date',
                key: 'start_time',
                disabled: true,
                name: translate('hrm.contract.start_day')
            }, {
                type: 'date',
                key: 'end_time',
                disabled: true,
                name: translate('hrm.contract.end_day')
            }, {
                type: 'text-area',
                key: 'note',
                disabled: true,
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
                disabled: true,
                listTypeUpload: 'picture',
                maxCount: 5,
                classAdvancedFormItem: 'col-sm-12',
            }
        ]
    }

];
const contractService = new ContractService();

const getContract = () => {
    setLoading();
    contractService.getDetailById(contractId).then((data) => {
        if (isEmptyObject(data)) {
            throwNotFoundModelException();
        }
        data.start_time = moment(data.start_time).format('YYYY-MM-DD');
        data.end_time = moment(data.end_time).format('YYYY-MM-DD');
        contract.value = data;
        setLoadingComplete();
    })
}
getContract();

const prepareFields = () => {
    fields = fields.map(field => {
        if (props.disabledField.includes(field.key)) {
            return {...field, disabled: true};
        }
        return field;
    });
}
prepareFields();

const submit = (formData) => {

}

const cancel = () => {
    router.push({name: RouteNameConstant.USER_VIEW});
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
