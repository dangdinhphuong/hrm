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
                :source-data="settings"
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
import ConfigService from "@/services/system/ConfigService.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";


const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('setting.work.title')
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
const configService = new ConfigService();
const errors = ref(cloneObject(props.errors));
let fields = [
    {
        groupName: translate('hrm.basic_information'),
        items: [
            {
                type: 'time',
                key: 'setting_checkin',
                name: translate('setting.work.columns.checkin')
            },
            {
                type: 'time',
                key: 'setting_checkout',
                name: translate('setting.work.columns.checkout')
            },
            {
                type: 'text',
                key: 'setting_leave_day',
                default_value: 1,
                name: translate('setting.work.columns.days_off_per_month')
            }
        ]
    }
];
const settings = ref(0);
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
const getSettings = async () => {
    setLoading();
    await configService.getList().then((data) => {
        // if (isEmptyObject(data)) {
        //     throwNotFoundModelException();
        // }
        settings.value = data;
        setLoadingComplete();
    })
}
getSettings();

const submit = async (formData) => {
    console.log('formData getList', formData);
    await configService.create(formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('setting.messages.update_success'));
        } else {
            messageError(translate('setting.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    });
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
