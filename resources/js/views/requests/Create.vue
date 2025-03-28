<template>
    <div id="page-content">
        <div class="page-title">
            <h2><b>{{ pageTitle }}</b></h2>
        </div>

        <div class="page-content mt-3">
            <a-card class="p-2">
                <app-form
                    :fields="fields"
                    :errors="errors"
                    :submit="submit"
                    :cancel="cancel"
                    :is-multiple-sections="false"
                    classWrapperFormItem="col-12 col-sm-8 d-flex flex-wrap"
                    classFormItem="col-12 col-sm-12 me-12"
                >
                    <template v-slot:more-field>
                        <a-card :bordered="false" style="background: #ff4d4f; color: white;">
                            <template #title>
                                <span style="color: white; font-weight: bold;">⚠ Chú Ý</span>
                            </template>
                            <h3>Yêu cầu nhập nội dung đúng theo mẫu nếu sai hệ thống không xử lý:</h3>
                            <ul>
                                <li><strong>Nghỉ phép:</strong> "Nghỉ 0.5 hoặc 1 công ngày 02/12/2024"</li>
<!--                                <li><strong>Nghỉ không lương:</strong> "Nghỉ 0.5 hoặc 1 công ngày 02/12/2024"</li>-->
                                <li><strong>Quên chấm công:</strong> "Quên chấm công ngày 02/12/2024"</li>
                                <li><strong>Công tác:</strong> "02/12/2024 - 10/12/2024" (Ngày bắt đầu và ngày kết thúc)</li>
                                <li><strong>OT:</strong> "30 phút" (tính theo phút)</li>
                            </ul>
                        </a-card>
                    </template>


                </app-form>
            </a-card>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import router from "@/router";
import RouteNameConstant from "@/constants/RouteNameConstant";
import { translate, convertConstantToDataSelect } from "@/helpers/CommonHelper";
import AppForm from "@/components/views/AppForm.vue";
import HrmCommonConstant from "@/constants/CommonConstant";
import RequestService from "@/services/Work/RequestService";
import { isSuccessRequest } from "@/helpers/AxiosHelper";
import { messageError, messageSuccess } from "@/helpers/MessageHelper";
import { getCurrentRouteParams } from "@/helpers/RouteHelper";
import { authStore } from "@/stores/AuthStore";

const requestService = new RequestService();
const errors = ref({});
const employeeId = getCurrentRouteParams('employeeId');
const pageTitle = translate('requests.columns.create_title');

const fields = [
    {
        type: 'text',
        key: 'employee_name',
        name: translate('requests.columns.employee_name'),
        default_value: authStore().user.name,
        disabled: true,
    },
    {
        type: 'text',
        key: 'code',
        name: translate('requests.columns.employee_code'),
        default_value: authStore().user.username,
        disabled: true,
    },
    {
        type: 'date',
        key: 'date',
        name: translate('requests.columns.date'),
        required: true
    },
    {
        type: 'select',
        key: 'leave_type',
        name: translate('requests.columns.request_type'),
        options: convertConstantToDataSelect(HrmCommonConstant.LEAVE_REASONS),
        required: true
    },
    {
        type: 'text-area',
        key: 'content',
        name: translate('requests.columns.request_content'),
        classAdvancedFormItem: 'col-sm-12',
        required: true
    }
];

const submit = async (formData) => {
    formData.employees_id = employeeId;
    try {
        const data = await requestService.create(formData);
        if (isSuccessRequest(data)) {
            messageSuccess(translate('requests.messages.create_success'));
            router.push({ name: RouteNameConstant.REQUESTS });
        } else {
            messageError(translate('requests.messages.create_fail'));
            errors.value = data.data ?? {};
        }
    } catch (error) {
        messageError(translate('requests.messages.create_fail'));
    }
};

const cancel = () => router.push({ name: RouteNameConstant.REQUESTS });

</script>

<style lang="scss" scoped>
#page-content {
    padding: 24px;
    background: #fff;
    margin: 17px 16px 0;
}
</style>
