<template>
    <div id="sidebar">
        <a-menu
            v-model:openKeys="openKeys"
            v-model:selectedKeys="selectedKeys"
            style="width: 100%"
            mode="vertical"
            @click="handleClick"
            :style="{ background: config.setting_subsidebar_color }"
        >
            <menuRecursive :tree="menu"></menuRecursive>
        </a-menu>
    </div>
</template>

<script setup>
import router from "@/router/index.js";
import {translate} from "@/helpers/CommonHelper.js";
import {authStore} from "@/stores/AuthStore.js";
import {menuStore} from "@/stores/MenuStore.js";
import MenuRecursive from "@/components/layouts/MenuRecursive.vue";
import {storeToRefs} from "pinia";
import permissionConstant from "@/constants/PermissionConstant.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";
import SidebarKeyConstant from "@/constants/SidebarKeyConstant.js";
import {configStore as useConfigStore} from "@/stores/ConfigStore.js"; // Đổi tên import
import {useRoute} from "vue-router";
import {computed, watch} from "vue";

const route = useRoute();
const currentUser = authStore();

const menu = [
    {
        name: translate('sidebar.personal_information'),
        route: routeNameConstant.ME_INFO_DETAIL,
        sidebarKey: SidebarKeyConstant.ME_INFORMATION,
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_PERSONAL_INFO),
        icon: 'personal-information'
    },
    {
        name: translate('sidebar.working_day'),
        route: routeNameConstant.WORK,
        sidebarKey: SidebarKeyConstant.WORK,
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_ATTENDANCE_LIST),
        icon: 'leave'
    },
    {
        name: translate('salary.title'),
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_EMPLOYEE_SALARY),
        route: routeNameConstant.VIEW_EMPLOYEE_SALARY,
        sidebarKey: SidebarKeyConstant.VIEW_EMPLOYEE_SALARY,
        icon: 'salaries'
    },
    {
        name: translate('sidebar.requests'), // Thêm mục Đơn
        route: routeNameConstant.REQUESTS,  // Định nghĩa route tương ứng
        sidebarKey: SidebarKeyConstant.REQUESTS, // Định nghĩa sidebarKey tương ứng
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_REQUESTS), // Kiểm tra quyền
        icon: 'requests' // Icon tương ứng
    },
    {
        name: translate('sidebar.human_resource_management'),
        route: routeNameConstant.EMPLOYEE,
        sidebarKey: SidebarKeyConstant.EMPLOYEE,
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_EMPLOYEE_LIST),
        icon: 'hrm'
    },
    {
        name: translate('sidebar.setting'),
        isVisible: currentUser.hasPermissions(permissionConstant.VIEW_CONFIG),
        route: routeNameConstant.SETTING_GENERAL,
        sidebarKey: SidebarKeyConstant.SETTING_GENERAL,
        icon: 'system'
    }
];

const handleClick = menuInfo => {
    let routeName = menuInfo.item.to;
    if (route.name === routeName) {
        router.go(0);
    }
    router.push({name: routeName});
};
const {selectedKeys, openKeys} = storeToRefs(menuStore())

const configStore = useConfigStore(); // Gọi store đúng cách
const config = computed(() => configStore.settings);
</script>

<style lang="scss" scoped>

#sidebar {
    :deep(ul.ant-menu) {
        background: var(--sidebar-color);
        border-right: revert;
    }

    :deep(.ant-menu-item),
    :deep(.ant-menu-submenu-title),
    :deep(.ant-menu-submenu-arrow) {
        color: #fff;
        font-weight: bold;
    }

    :deep(.ant-menu-item-active),
    :deep(.ant-menu-submenu-active) {
        background: var(--hover-button);
    }

    :deep(.ant-menu-submenu-selected) {
        background-color: var(--hover-button);
    }


    :deep(.ant-menu-item-selected),
    :deep(.ant-menu-submenu-selected > .ant-menu-submenu-title),
    :deep(.ant-menu-submenu-selected .ant-menu-submenu-arrow) {
        background: var(--hover-button);
    }

    :deep(.ant-menu-item),
    :deep(.ant-menu-submenu),
    :deep(.ant-menu-submenu-title) {
        font-size: 16px;
        height: 58px;
        line-height: 58px;
    }

    :deep(.ant-menu.ant-menu-inline-collapsed > .ant-menu-item) {
        padding: 0 calc(50% - 20px / 2);
    }

    :deep(.ant-menu-vertical .ant-menu-submenu-title) {
        margin-bottom: 0px;
    }

}

</style>
