<template>
    <base-sub-sidebar :menu-data="menuData" :handle-click-redirect="handleClickRedirect">
        <template #logo>
            <div class="avatar">
                <div class="avatar-wrapper">
                    <img :src="avatarUser || avatarDefault" class="avatar-img">
                    <div class="overlay" @click.stop="showUploadModal = true">
                        <img src="@assets/images/icon/edit.svg" alt="Edit" style="width:18px">
                    </div>
                    <upload-avatar
                        :showModal="showUploadModal"
                        @update:showModal="showUploadModal = $event"
                        @update:avatar="updateAvatar"
                    ></upload-avatar>
                </div>
            </div>
        </template>
    </base-sub-sidebar>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import avatarDefault from '@assets/images/logo/user.png';
import { useRoute, useRouter } from "vue-router";
import { authStore } from "@/stores/AuthStore.js";
import SidebarKeyConstant from "@/constants/SidebarKeyConstant.js";
import BaseSubSidebar from "@/components/layouts/BaseSubSidebar.vue";
import { translate } from "@/helpers/CommonHelper.js";
import { getCurrentRouteParams } from "@/helpers/RouteHelper.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import UploadAvatar from "@/components/modal/UploadAvatar.vue";
import routeNameConstant from "@/constants/RouteNameConstant.js";
import { hasPermissions } from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";

const router = useRouter();
const route = useRoute();
const employeeService = new EmployeeService();

const employeeId = ref(getCurrentRouteParams('employeeId') ?? 0);
const avatarUser = ref('');
const showUploadModal = ref(false);

const menuData = computed(() => {
    const employeeSpecificTabs = [
        {
            name: translate('hrm.personal_information.title'),
            route: routeNameConstant.INFO_DETAIL,
            sidebarKey: SidebarKeyConstant.INFORMATION,
            isVisible: hasPermissions(PermissionConstant.VIEW_EMPLOYEE_DETAIL),
            requiresEmployeeId: true,
        },
        {
            name: translate('hrm.contract.title'),
            route: routeNameConstant.CONTRACT,
            sidebarKey: SidebarKeyConstant.CONTRACT,
            isVisible: hasPermissions(PermissionConstant.VIEW_CONTRACT_DETAIL),
            requiresEmployeeId: true,
        },
        {
            name: translate('salary.title'),
            route: routeNameConstant.EDIT_EMPLOYEE_SALARY,
            sidebarKey: SidebarKeyConstant.EDIT_EMPLOYEE_SALARY,
            isVisible: hasPermissions(PermissionConstant.EDIT_EMPLOYEE_SALARY),
            requiresEmployeeId: true,
        }
        ,
        {
            name: translate('payslip.title'),
            route: routeNameConstant.VIEW_PAYSLIP,
            sidebarKey: SidebarKeyConstant.VIEW_PAYSLIP,
            isVisible: hasPermissions(PermissionConstant.EDIT_EMPLOYEE_SALARY),
            requiresEmployeeId: true,
        }
    ];

    const generalTabs = [
        {
            name: translate('hrm.personal_information.title'),
            route: routeNameConstant.ME_INFO_DETAIL,
            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
            isVisible: hasPermissions(PermissionConstant.VIEW_PERSONAL_INFO),
        },
        {
            name: translate('hrm.contract.title'),
            route: routeNameConstant.ME_CONTRACT,
            sidebarKey: SidebarKeyConstant.ME_CONTRACT,
            isVisible: hasPermissions(PermissionConstant.VIEW_PERSONAL_INFO),
        },
        {
            name: translate('change_password.title'),
            route: routeNameConstant.CHANGE_PASSWORD,
            sidebarKey: SidebarKeyConstant.CHANGE_PASSWORD,
            isVisible: hasPermissions(PermissionConstant.VIEW_PERSONAL_INFO)
        },
        {
            name: translate('salary.title'),
            route: routeNameConstant.VIEW_OWN_SALARY,
            sidebarKey: SidebarKeyConstant.VIEW_OWN_SALARY,
            isVisible: hasPermissions(PermissionConstant.VIEW_OWN_SALARY)
        }
        ,
        {
            name: translate('payslip.title'),
            route: routeNameConstant.VIEW_OWN_PAYSLIP,
            sidebarKey: SidebarKeyConstant.VIEW_OWN_PAYSLIP,
            isVisible: hasPermissions(PermissionConstant.VIEW_OWN_SALARY)
        }
    ];

    return [...employeeSpecificTabs, ...generalTabs].filter(tab =>
        tab.isVisible && (employeeId.value > 0 ? tab.requiresEmployeeId : !tab.requiresEmployeeId)
    );
});

const handleClickRedirect = (menuInfo) => {
    if (route.name === menuInfo.item.to) {
        router.go(0);
    } else {
        router.push({ name: menuInfo.item.to });
    }
};

const updateAvatar = (newAvatarUrl) => {
    avatarUser.value = newAvatarUrl;
};

const getAvatar = async () => {
    if (employeeId.value) {
        const response = await employeeService.getAvatar(employeeId.value);
        avatarUser.value = response?.file || '';
    } else {
        const isMeRoute = [routeNameConstant.ME_INFO_DETAIL, routeNameConstant.ME_CONTRACT, routeNameConstant.CHANGE_PASSWORD].includes(route?.name);
        avatarUser.value = isMeRoute ? authStore().getUser.avatar : avatarDefault;
    }
};

watch(route, (newRoute) => {
    employeeId.value = newRoute?.params.employeeId ?? 0;
    getAvatar();
}, { immediate: true });
</script>


<style lang="scss" scoped>
.avatar-wrapper {
    position: relative;
    display: inline-block;
    width: 20vh;
    height: 20vh;

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(187, 183, 183, 0.65);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;

        &:hover {
            opacity: 1;
        }
    }
}

:deep(.avatar) {
    text-align: center;

    img {
        width: 20vh;
        height: 20vh;
        border-radius: 50%;
    }
}
</style>
