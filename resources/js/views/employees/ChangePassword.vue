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
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import {authStore} from "@/stores/AuthStore.js";

const employeeService = new EmployeeService();
const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('change_password.title')
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

const userId = (authStore().getUser.id ?? 0);

let fields = [
    {
        type: 'text',
        key: 'current_password',
        name: translate('change_password.columns.current_password'),
        required: true
    },
    {
        type: 'text',
        key: 'password',
        name: translate('change_password.columns.new_password'),
        required: true
    },
    {
        type: 'text',
        key: 'password_confirmation',
        name: translate('change_password.columns.confirm_password'),
        required: true
    },

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
    formData.userId = userId;
    await employeeService.changePassword(formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('change_password.messages.update_success'));
        } else {
            messageError(translate('change_password.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    });
}

const cancel = () => {
    router.push({ name: RouteNameConstant.ME_INFO_DETAIL });
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
