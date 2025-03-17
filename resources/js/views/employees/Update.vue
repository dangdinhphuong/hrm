<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-content mt-3"  v-if="isLoadingComplete()">
        <a-card class="p-2">
            <app-form
                :fields="fields"
                :errors="errors"
                :source-data="employee"
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
import {
    translate,
    cloneObject,
    convertConstantObjectToDataSelect, convertConstantToDataSelect
} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";
import UserService from "@/services/system/UserService.js";
import HrmCommonConstant from "@/constants/CommonConstant.js";
import EntitySelectConstant from "@/constants/EntitySelectConstant.js";
import {useLoading} from "@/composables/loading.js";
import CreateUserRequest from "@/requests/hrm/CreateUserRequest.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import {isEmptyObject} from "@/helpers/CommonHelper.js";
import {throwNotFoundModelException} from "@/helpers/ExceptionHelper.js";

const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
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
const createUserRequest = new CreateUserRequest();
const employeeService = new EmployeeService();
const errors = ref(cloneObject(props.errors));
const employee = ref({});
const employeeId = getCurrentRouteParams('employeeId');
const maxDate = new Date().toISOString().split("T")[0];

let fields = [
    {
        groupName: translate('hrm.basic_information'),
        items: [
            {
                type: 'text',
                key: 'first_name',
                name: translate('hrm.personal_information.first_name'),
                required: true
            },
            {
                type: 'text',
                key: 'last_name',
                name: translate('hrm.personal_information.last_name'),
                required: true
            }, {
                type: 'text',
                key: 'personal_email',
                name: translate('hrm.personal_information.personal_email'),
                required: true
            },
            {
                type: 'phone',
                key: 'phone',
                name: translate('hrm.personal_information.phone_number'),
                required: true
            },
            {
                type: 'date',
                key: 'birthday',
                maxDate: maxDate,
                name: translate('hrm.personal_information.date_of_birth'),
                required: true
            },
            {
                type: 'select',
                key: 'gender',
                name: translate('hrm.personal_information.gender'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.GENDER),
                default_value: HrmCommonConstant.GENDER_MALE,
            },
            {
                type: 'select',
                key: 'marital_status',
                name: translate('hrm.personal_information.marital_status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.MARRIAGE_STATUS),
                default_value: HrmCommonConstant.MARRIAGE_SINGLE,
            },
            {
                type: 'select',
                key: 'status',
                name: translate('hrm.personal_information.status'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.STATUS),
                default_value: HrmCommonConstant.STATUS_ACTIVE,
                required: true
            }, {
                type: 'text',
                key: 'code',
                name: translate('hrm.personal_information.employee_code'),
                required: true
            }, {
                type: 'text',
                key: 'business_email',
                name: translate('hrm.personal_information.company_email')
            }, {
                type: 'text',
                key: 'fingerprint_code',
                name: translate('hrm.personal_information.fingerprint_code')
            }, {
                type: 'text',
                key: 'skype_id',
                name: translate('hrm.personal_information.skype_id')
            },
            {
                type: 'date',
                key: 'start_date',
                name: translate('hrm.personal_information.start_date')
            },
            {
                type: 'date',
                key: 'end_date',
                name: translate('hrm.personal_information.end_date')
            },
            {
                type: 'search-select',
                key: 'user_id',
                name: translate('hrm.personal_information.linked_account'),
                options: [],
                valueType: 'number',
                multiple: false,
                entity: EntitySelectConstant.HRM_USER,
                disabled: true,
            },
        ]
    },
    {
        groupName: translate('hrm.identity_information'),
        items: [
            {
                type: 'number',
                key: 'old_identity_card_number',
                name: translate('hrm.personal_information.id_card_number')
            },
            {
                type: 'date',
                key: 'old_identity_card_issue_date',
                maxDate: maxDate,
                name: translate('hrm.personal_information.id_card_issue_date')
            },
            {
                type: 'text',
                key: 'old_identity_card_place',
                name: translate('hrm.personal_information.id_card_issued_by')
            },
            {
                type: 'number',
                key: 'identity_card_number',
                name: translate('hrm.personal_information.citizen_id_number'),
                required: true
            },
            {
                type: 'date',
                key: 'identity_card_issue_date',
                maxDate: maxDate,
                name: translate('hrm.personal_information.citizen_id_issue_date'),
                required: true
            },
            {
                type: 'text',
                key: 'identity_card_place',
                name: translate('hrm.personal_information.citizen_id_issued_by'),
                required: true
            },
            {
                type: 'text',
                key: 'place_origin',
                name: translate('hrm.personal_information.native_place')
            },
            {
                type: 'entity-select',
                entity: EntitySelectConstant.HRM_COUNTRY,
                key: 'nationality',
                name: translate('hrm.personal_information.nationality'),
                required: true
            },
            {
                type: 'text',
                key: 'tax_code',
                name: translate('hrm.personal_information.tax_code')
            },
            {
                type: 'text',
                key: 'social_insurance_number',
                name: translate('hrm.personal_information.social_insurance_number')
            },
            {
                type: 'text',
                key: 'residential_address',
                name: translate('hrm.personal_information.permanent_address'),
                classAdvancedFormItem: 'col-sm-8',
            },
        ]
    },
    {
        groupName: translate('hrm.current_address'),
        items: [
            {
                type: 'select-hrm-country-province-district',
                entity: EntitySelectConstant.PROVINCE_DISTRICT,
                key: {
                    "country_id": "current_country_id",
                    "province_id": "current_province_id",
                    "district_id": "current_district_id"
                },
                classAdvancedFormItem: 'col-sm-12',
                styleAdded: '',
                groupField: true,
                valueType: 'number',
            },
            {
                type: 'text',
                key: 'current_address',
                name: translate('hrm.personal_information.address'),
                classAdvancedFormItem: 'col-sm-12',
            }
        ]
    },
    {
        groupName: translate('hrm.bank_account'),
        items: [
            {
                type: 'entity-select',
                entity: EntitySelectConstant.HRM_BANK,
                key: 'bank_id',
                name: translate('hrm.personal_information.bank_name')
            },
            {
                type: 'number',
                key: 'bank_account_number',
                name: translate('hrm.personal_information.bank_account_number')
            },
            {
                type: 'text',
                key: 'bank_account_name',
                name: translate('hrm.personal_information.account_holder_name')
            }
        ]
    },
    {
        groupName: translate('hrm.department_and_job_title'),
        items: [
            {
                type: 'entity-select',
                entity: EntitySelectConstant.HRM_DEPARTMENT,
                key: 'department_id',
                name: translate('hrm.personal_information.department'),
                required: true
            },
            {
                type: 'entity-select',
                entity: EntitySelectConstant.HRM_POSITION,
                key: 'position_id',
                name: translate('hrm.personal_information.position'),
                required: true
            },
            {
                type: 'select',
                key: 'level',
                name: translate('hrm.personal_information.current_level'),
                options: convertConstantObjectToDataSelect(HrmCommonConstant.HRM.LEVEL),
                default_value: HrmCommonConstant.LEVEL_DEFAULT,
            },
        ]
    }

];

const getEmployee = () => {
    setLoading();
    employeeService.getDetailByUserId(employeeId).then((data) => {
        if (isEmptyObject(data)) {
            throwNotFoundModelException();
        }
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

const userService = new UserService();

const submit = async (formData) => {
    const { isValid, errorMessages } = await createUserRequest.validate(formData);
    errors.value = isValid ? {} : errorMessages;

    if (!isValid) {
        messageError(translate('hrm.personal_information.messages.update_fail'));
        return;
    }
    await  employeeService.update(employeeId, formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('hrm.personal_information.messages.update_success'));
            router.push({ name: RouteNameConstant.EMPLOYEE });
        }else{
            messageError(translate('hrm.personal_information.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    });
};


const cancel = () => {
    router.push({name: RouteNameConstant.EMPLOYEE});
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
