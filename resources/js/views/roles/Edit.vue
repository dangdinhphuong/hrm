<template>
    <create-role-component
        :page-title="$t('role.title_edit')"
        :role="role"
        :errors="errors"
        :update-role="submit"
        v-if="isLoadingComplete()"
    ></create-role-component>
</template>

<script setup>
import {ref} from "vue";
import CreateRoleComponent from '@/views/roles/Create.vue';
import {translate} from "@/helpers/CommonHelper.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import RoleService from "@/services/system/RoleService.js";
import {throwNotFoundModelException} from "@/helpers/ExceptionHelper.js";
import {isEmptyObject} from "@/helpers/CommonHelper.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";
import router from "@/router/index.js";
import {useLoading} from "@/composables/loading.js";
import {authStore as useAuthStore} from "@/stores/AuthStore.js";

const {isLoadingComplete, setLoading, setLoadingComplete} = useLoading();
const roleId = getCurrentRouteParams('id');
const role = ref({});
const errors = ref({});
const authStore = useAuthStore(); // Gọi store đúng cách

const roleService = new RoleService();
const getDetailRole = () => {
    setLoading();
    roleService.detail(roleId).then((data) => {
        if (isEmptyObject(data)) {
            throwNotFoundModelException();
        }
        role.value = data;
        role.value.permissions = data.permissions;
        setLoadingComplete();
    })
}
getDetailRole();

const submit = async (formData) => {
    setLoading();
    try {
        const data = await roleService.update(roleId, formData);
        if (isSuccessRequest(data)) {
            messageSuccess(translate('role.messages.update_success'));
            await authStore.loadUser(true);
            router.back();
        } else {
            messageError(translate('role.messages.update_fail'));
            errors.value = data.data ?? {};
        }
    } catch (error) {
        messageError(translate('role.messages.update_fail'));
        console.error(error);
    } finally {
        setLoadingComplete(); // ✅ Đảm bảo luôn tắt loading
    }
};

</script>
