<template>
    <base-sub-sidebar :menu-data="menu" :handle-click-redirect="handleClickRedirect">
        <template #logo>
            <div class="avatar">
                <div class="avatar-wrapper">
                    <img :src="icon" alt="icon">
                </div>
            </div>
        </template>
    </base-sub-sidebar>
</template>

<script setup>
import {computed} from 'vue';
import {useRoute, useRouter} from "vue-router";
import {authStore} from "@/stores/AuthStore.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import permissionConstant from "@/constants/PermissionConstant.js";
import SidebarKeyConstant from "@/constants/SidebarKeyConstant.js";
import BaseSubSidebar from "@/components/layouts/BaseSubSidebar.vue";
import {translate} from "@/helpers/CommonHelper.js";

const router = useRouter();
const route = useRoute();

const currentUser = authStore();

const icon = computed(() => new URL(`../../../images/icon/setting-dark.svg`, import.meta.url).href);
//const icon = computed(() => new URL(`../../../images/icon/hrm.svg`, import.meta.url).href);

const menu = computed(() => [
    {
        name: translate('sidebar.personnel_list'),
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_EMPLOYEE_LIST),
        route: RouteNameConstant.EMPLOYEE,
        sidebarKey: SidebarKeyConstant.EMPLOYEE,
    },
    {
        name: translate('sidebar.role_list'),
        isVisible: currentUser.hasPermissions(permissionConstant.ROLE_VIEW),
        route: RouteNameConstant.ROLE_VIEW,
        sidebarKey: SidebarKeyConstant.ROLE,
    }
].filter(item => item.isVisible));

const handleClickRedirect = (menuInfo) => {
    if (route.name === menuInfo.item.to) {
        router.go(0);
    } else {
        router.push({name: menuInfo.item.to});
    }
};
</script>

<style lang="scss" scoped>
.avatar-wrapper {
    display: inline-block;
}

.avatar {
    margin-top: 15%;
}

.avatar img {
    width: 5vh;
    height: 5vh;
    border-radius: 50%;
}
</style>
