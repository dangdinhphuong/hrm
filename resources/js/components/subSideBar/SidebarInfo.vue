<template>
    <div id="container-page">
        <div class="row h-100">
            <div class="col-xl-2 col-md-12 mb-5">
                <div class="section-1">
                    <div class="avatar">
                        <div class="avatar-wrapper">
                            <img :src="avatarUser || avatarDefault" class="avatar-img">
                            <div class="overlay" @click="showUploadModal = true">
                                <img src="@assets/images/icon/edit.svg" alt="Edit" style="width:18px">
                            </div>
                        </div>
                    </div>
                    <div class="menu mt-5">
                        <a-button
                            v-for="tab in menuTab.filter(t => t.isVisible)"
                            :key="tab.sidebarKey"
                            type="text"
                            :class="{ active: activeBtn === tab.sidebarKey }"
                            @click="handleClick(tab.route)"
                        >
                            {{ tab.name }}
                        </a-button>
                    </div>
                    <upload-avatar
                        :showModal="showUploadModal"
                        @update:showModal="showUploadModal = $event"
                        @update:avatar="updateAvatar"
                    ></upload-avatar>
                </div>
            </div>
            <div class="col-xl-10 col-md-12">
                <div class="section-2 ">
                    <router-view></router-view>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import {ref, watch} from "vue";
import avatarDefault from '@assets/images/logo/user.png';
import UploadAvatar from "@/components/modal/UploadAvatar.vue";
import {translate} from "@/helpers/CommonHelper.js";
import {useRoute, useRouter} from "vue-router";
import SidebarKeyConstant from "@/constants/SidebarKeyConstant.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";
import {authStore} from "@/stores/AuthStore.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";

// State
const showUploadModal = ref(false);
const avatarUser = ref('');
const activeBtn = ref('');
const route = useRoute();
const router = useRouter();

const employeeId = ref(getCurrentRouteParams('employeeId') ?? 0);
const employeeService = new EmployeeService();
const menuTab = ref([]);


// Tabs and components
const componentTabs = [
    {
        name: translate('hrm.personal_information.title'),
        route: routeNameConstant.INFO_DETAIL,
        sidebarKey: SidebarKeyConstant.INFORMATION,
        isVisible: hasPermissions(PermissionConstant.VIEW_EMPLOYEE_DETAIL),
        employeeId
    },
    {
        name: translate('hrm.contract.title'),
        route: routeNameConstant.CONTRACT,
        sidebarKey: SidebarKeyConstant.CONTRACT,
        isVisible: hasPermissions(PermissionConstant.VIEW_CONTRACT_DETAIL),
        employeeId
    },
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
    }
];

// Filter tabs based on employeeId
const filterComponentTabs = () => {
    menuTab.value = componentTabs.filter(tab => employeeId.value <= 0 ? !tab.employeeId : tab.employeeId);
}

// Watch route changes
watch(route, (newRoute) => {
    employeeId.value = newRoute?.params.employeeId ?? 0;
    filterComponentTabs();
    activeBtn.value = newRoute.meta.sidebarKeySub || '';
}, {immediate: true});

const handleClick = (routeName) => {
    router.push({name: routeName, params: {employeeId: employeeId.value}});
};

// Update avatar
const updateAvatar = (newAvatarUrl) => {
    avatarUser.value = newAvatarUrl;
};

// Fetch avatar
const getAvatar = async () => {
    const currentUserId = employeeId.value > 0 ? employeeId.value : (authStore().getUser.employeeId ?? 0);
    const response = await employeeService.getAvatar(currentUserId);
    avatarUser.value = response.file ?? '';
};

getAvatar();
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

#container-page {
    background: #f5f5f5 !important;
    margin: 17px 16px 0px 16px;
    border-radius: 6px;
}

:deep(.section-1) {
    background: #fff;
    padding: 5%;
    border-radius: 10px;

    @media (max-width: 576px) {
        border-right: none;
    }
}

:deep(.section-2) {
    background: #fff;
    background: #fff;
    padding: 2%;
    border-radius: 10px;

    @media (max-width: 576px) {
        border-right: none;
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

:deep(.menu .ant-btn) {
    height: 45px;
    width: 100%;
    text-align: left;
    font-weight: 500;

    &.active {
        background-color: #dedede;
        border-radius: 7px;
        color: #000;
    }
}

:deep(.ant-tabs-nav) {
    width: 100%;
}

:deep(.ant-tabs-tab-active) {
    background-color: #dedede;
    border-radius: 11px;
    color: #000;
}
</style>
