<template>
    <div id="layout">
        <a-layout>

            <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible :width="260">
                <div class="logo text-center mb-3" @click="actionHome" style="cursor: pointer;">
                    <h2 class="text-white">
                        <b v-if="collapsed">HRM</b>
                        <b v-else>EDUPIA HRM</b>
                    </h2>
                </div>
                <the-sidebar></the-sidebar>
            </a-layout-sider>

            <a-layout>

                <a-layout-header style="background: #fff;padding:0 20px;box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25)">
                    <the-header
                        :sidebarCollapsed="collapsed"
                        @triggerSidebarCollapse="triggerSidebarCollapse">
                    </the-header>
                </a-layout-header>

                <a-layout-content
                >
                    <router-view></router-view>
                </a-layout-content>

                <a-layout-footer class="d-none d-sm-block" :style="{ padding:'10px' }">
                    <the-footer></the-footer>
                </a-layout-footer>
            </a-layout>

        </a-layout>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import TheSidebar from "../components/layouts/TheSidebar.vue";
import TheHeader from "../components/layouts/TheHeader.vue";
import TheFooter from "../components/layouts/TheFooter.vue";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";

const collapsed = ref(false)
const actionHome = () => {
    router.push({name: RouteNameConstant.HOME_PAGE});
}
const triggerSidebarCollapse = () => {
    collapsed.value = !collapsed.value;
}
</script>

<style lang="scss" scoped>
#layout {
    :deep(.ant-layout) {
        min-height: 100vh;
    }

    :deep(.trigger) {
        font-size: 18px;
        line-height: 64px;
        padding: 0 24px;
        cursor: pointer;
        transition: color 0.3s;
    }

    :deep(.trigger:hover) {
        color: #1890ff;
    }

    :deep(.logo) {
        margin-top: 5px;
        padding: 10px;
    }

    :deep(.ant-layout-sider) {
        background: var(--sidebar-color);
    }


    :deep(.site-layout .site-layout-background) {
        background: #fff;
    }

    :deep(.ant-layout-content) {
        border-radius: 6px;
    }
}
</style>
