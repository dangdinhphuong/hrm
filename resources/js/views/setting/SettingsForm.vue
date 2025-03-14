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
                :is-multiple-sections="true"
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
import { ref, defineProps, onMounted } from "vue";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {translate, cloneObject, getConfigs} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";
import { useLoading } from "@/composables/loading.js";
import ConfigService from "@/services/system/ConfigService.js";
import { isSuccessRequest } from "@/helpers/AxiosHelper.js";
import { messageError, messageSuccess } from "@/helpers/MessageHelper.js";

const { isLoadingComplete, setLoading, setLoadingComplete } = useLoading();
const props = defineProps({
    pageTitle: String,
    fields: Array,
    errors: Object,
    disabledField: Array,
    beforeSubmit: {
        type: Function,
        default: null
    },
});

const configService = new ConfigService();
const errors = ref(cloneObject(props.errors));
const settings = ref(0);


const getSettings = async () => {
    setLoading();
    await configService.getList().then((data) => {
        settings.value = data;
        setLoadingComplete();
    });
};

const submit = async (formData) => {
    await configService.create(formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('setting.messages.update_success'));
            getConfigs();
        } else {
            messageError(translate('setting.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    });
};

const cancel = () => {
    router.push({ name: RouteNameConstant.WORK });
};

onMounted(() => {
    getSettings();
});
</script>
