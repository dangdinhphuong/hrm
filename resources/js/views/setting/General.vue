<template>
    <div class="d-flex justify-content-between" style="border-bottom: 2px solid #CFCDCD; margin-bottom: 2%;">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>
    <div class="page-content mt-3">
        <a-card class="p-2">
            <app-form
                :fields="fields"
                :errors="errors"
                :submit="submit"
                :cancel="cancel"
                :before-submit="beforeSubmit"
                :is-multiple-sections=true
                classWrapperFormItem="col-12 col-sm-12 "
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
    cloneObject
} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";
import {useLoading} from "@/composables/loading.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";


const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('setting.general.title')
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
const employeeService = new EmployeeService();
const errors = ref(cloneObject(props.errors));
let fields = [
    {
        groupName: translate('hrm.basic_information'),
        items: [
            {
                type: 'upload-file',
                key: 'file',
                listTypeUpload: 'picture',
                maxCount: 1,
                classAdvancedFormItem: 'col-sm-12',
                name: translate('setting.general.columns.logo')
            },
            {
                type: 'upload-file',
                key: 'file',
                listTypeUpload: 'picture',
                maxCount: 1,
                classAdvancedFormItem: 'col-sm-12',
                name: translate('setting.general.columns.favicon')
            }
            ,
            {
                type: 'color',
                key: 'subsidebar_color',
                name: translate('setting.general.columns.sidebar_color'),
                default_value: '#0d6efd',
            }
        ]
    },
    {
        groupName: translate('hrm.business_information'),
        items: [
            {
                type: 'text',
                key: 'company_name',
                name: translate('setting.general.columns.company_name'),
                required: true
            },
            {
                type: 'phone',
                key: 'contact_phone',
                name: translate('setting.general.columns.contact_phone'),
                required: true
            }, {
                type: 'email',
                key: 'contact_email',
                name: translate('setting.general.columns.contact_email')
            },
            {
                type: 'text',
                key: 'company_address',
                name: translate('setting.general.columns.company_address'),
                required: true
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


const beforeSubmit = (formData) => {
    const { code, phone } = formData.value;

    formData.value.password = code && phone ? `${code}@${phone}` : '';
};

const submit = async (formData) => {
    console.log('formData', formData);
    // await employeeService.create(formData).then((data) => {
    //     if (isSuccessRequest(data)) {
    //         messageSuccess(translate('hrm.personal_information.messages.create_success'));
    //         router.push({name: RouteNameConstant.EMPLOYEE});
    //     } else {
    //         messageError(translate('hrm.personal_information.messages.create_fail'));
    //         errors.value = data.data ?? {};
    //     }
    // });
};

const cancel = () => {
    router.push({name: RouteNameConstant.WORK});
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
