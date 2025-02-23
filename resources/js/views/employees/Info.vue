<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-content mt-3" v-if="isLoadingComplete()">
        <a-card class="p-2">
            <app-form
                :fields="fields"
                :errors="errors"
                :submit="submit"
                :cancel="cancel"
                :source-data="employee"
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
import {
    translate,
    cloneObject,
    convertConstantObjectToDataSelect
} from "@/helpers/CommonHelper.js";

import AppForm from "@/components/views/AppForm.vue";
import HrmCommonConstant from "@/constants/CommonConstant.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {useLoading} from "@/composables/loading.js";
import {authStore} from "@/stores/AuthStore.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";


const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const employeeService = new EmployeeService();

const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('hrm.personal_information.title')
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
const employee = ref(0);
const errors = ref(cloneObject(props.errors));

const currentUserId = getCurrentRouteParams('employeeId');
let fields = [
    {
        groupName: translate('hrm.basic_information'),
        items: [
            {
                type: 'text',
                key: 'first_name',
                disabled: true,
                name: translate('hrm.personal_information.first_name'),

            },
            {
                type: 'text',
                key: 'last_name',
                disabled: true,
                name: translate('hrm.personal_information.last_name'),

            }, {
                type: 'text',
                key: 'personal_email',
                disabled: true,
                name: translate('hrm.personal_information.personal_email')
            },
            {
                type: 'phone',
                key: 'phone',
                disabled: true,
                name: translate('hrm.personal_information.phone_number'),

            },
            {
                type: 'date',
                key: 'birthday',
                disabled: true,
                name: translate('hrm.personal_information.date_of_birth'),

            },
            {
                type: 'select',
                key: 'gender',
                disabled: true,
                name: translate('hrm.personal_information.gender'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.GENDER),
                default_value: HrmCommonConstant.GENDER_MALE,
            },
            {
                type: 'select',
                key: 'marital_status',
                disabled: true,
                name: translate('hrm.personal_information.marital_status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.MARRIAGE_STATUS),
                default_value: HrmCommonConstant.MARRIAGE_SINGLE,
            },
            {
                type: 'select',
                key: 'status',
                disabled: true,
                name: translate('hrm.personal_information.status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.STATUS),
                default_value: HrmCommonConstant.STATUS_ACTIVE,

            }, {
                type: 'text',
                key: 'code',
                disabled: true,
                name: translate('hrm.personal_information.employee_code'),
                required: true

            }, {
                type: 'text',
                key: 'business_email',
                disabled: true,
                name: translate('hrm.personal_information.company_email')
            }, {
                type: 'text',
                key: 'fingerprint_code',
                disabled: true,
                name: translate('hrm.personal_information.fingerprint_code')
            }, {
                type: 'text',
                key: 'skype_id',
                disabled: true,
                name: translate('hrm.personal_information.skype_id')
            },
            {
                type: 'date',
                key: 'start_date',
                disabled: true,
                name: translate('hrm.personal_information.start_date')
            },
            {
                type: 'date',
                key: 'end_date',
                disabled: true,
                name: translate('hrm.personal_information.end_date')
            },
            {
                type: 'text',
                key: 'currentUserName',
                disabled: true,
                name: translate('hrm.personal_information.linked_account'),

            }
        ]
    },
    {
        groupName: translate('hrm.identity_information'),
        items: [
            {
                type: 'number',
                key: 'old_identity_card_number',
                disabled: true,
                name: translate('hrm.personal_information.id_card_number')
            },
            {
                type: 'date',
                key: 'old_identity_card_issue_date',
                disabled: true,
                name: translate('hrm.personal_information.id_card_issue_date')
            },
            {
                type: 'text',
                key: 'old_identity_card_place',
                disabled: true,
                name: translate('hrm.personal_information.id_card_issued_by')
            },
            {
                type: 'number',
                key: 'identity_card_number',
                disabled: true,
                name: translate('hrm.personal_information.citizen_id_number'),

            },
            {
                type: 'date',
                key: 'identity_card_issue_date',
                disabled: true,
                name: translate('hrm.personal_information.citizen_id_issue_date'),

            },
            {
                type: 'text',
                key: 'identity_card_place',
                disabled: true,
                name: translate('hrm.personal_information.citizen_id_issued_by'),

            },
            {
                type: 'text',
                key: 'place_origin',
                disabled: true,
                name: translate('hrm.personal_information.native_place')
            },
            {
                type: 'text',
                key: 'currentNationalityName',
                disabled: true,
                name: translate('hrm.personal_information.nationality'),
            },
            {
                type: 'text',
                key: 'tax_code',
                disabled: true,
                name: translate('hrm.personal_information.tax_code')
            },
            {
                type: 'text',
                key: 'social_insurance_number',
                disabled: true,
                name: translate('hrm.personal_information.social_insurance_number')
            },
            {
                type: 'text',
                key: 'residential_address',
                disabled: true,
                name: translate('hrm.personal_information.permanent_address'),
                classAdvancedFormItem: 'col-sm-8',
            },
        ]
    },
    {
        groupName: translate('hrm.current_address'),
        items: [
            {
                type: 'text',
                key: 'currentCountryName',
                disabled: true,
                name: translate('country.title'),
            },
            {
                type: 'text',
                key: 'currentProvinceName',
                disabled: true,
                name: translate('delivery_note.columns.province'),
            },
            {
                type: 'text',
                key: 'currentDistrictName',
                disabled: true,
                name: translate('delivery_note.columns.district')
            },
            {
                type: 'text',
                key: 'current_address',
                disabled: true,
                name: translate('hrm.personal_information.address'),
                classAdvancedFormItem: 'col-sm-12',
            }
        ]
    },
    {
        groupName: translate('hrm.bank_account'),
        items: [
            {
                type: 'text',
                key: 'currentBankName',
                disabled: true,
                name: translate('hrm.personal_information.bank_name')
            },
            {
                type: 'number',
                key: 'bank_account_number',
                disabled: true,
                name: translate('hrm.personal_information.bank_account_number')
            },
            {
                type: 'text',
                key: 'bank_account_name',
                disabled: true,
                name: translate('hrm.personal_information.account_holder_name')
            }
        ]
    },
    {
        groupName: translate('hrm.department_and_job_title'),
        items: [
            {
                type: 'text',
                key: 'currentDepartmentName',
                disabled: true,
                name: translate('hrm.personal_information.department'),
            }, {
                type: 'text',
                key: 'currentPositionName',
                disabled: true,
                name: translate('hrm.personal_information.position'),
            },
            {
                type: 'text',
                key: 'jobTitle',
                disabled: true,
                name: translate('hrm.personal_information.position'),
            },
            {
                type: 'select',
                key: 'level',
                disabled: true,
                name: translate('hrm.personal_information.current_level'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.LEVEL),
                default_value: HrmCommonConstant.LEVEL_DEFAULT,
            },
        ]
    }

];

const getEmployee = () => {
    setLoading();
    employeeService.getDetailByUserId(currentUserId).then((data) => {
        // if (isEmptyObject(data)) {
        //     throwNotFoundModelException();
        // }
        employee.value = data;
        setLoadingComplete();
    })
}
getEmployee();

const prepareFields = () => {
    fields = fields.map(field => {
        if (props.disabledField.includes(field.key)) {
            return {...field, disabled: true};
        }
        return field;
    });
}
prepareFields();


const submit = () => {
}

const cancel = () => {
}

const childComponent = () => {
    props.redirectChildComponent({key: "info.update", param: {'id': currentUserId}})
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
