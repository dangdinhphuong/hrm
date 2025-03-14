<template>
    <div id="layout">
        <a-layout>

            <a-layout-sider
                v-model:collapsed="collapsed"
                :trigger="null"
                collapsible
                :width="260"
                :style="{ background: config.setting_subsidebar_color }"
            >
            <div class="logo text-center mb-3" @click="actionHome" style="cursor: pointer;">
                    <h2 class="text-white">
                        <b v-if="collapsed">HRM</b>
                        <b v-else> <img :src="config.setting_small_logo" alt="img" class=""></b>
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
import {watch , onMounted, ref} from "vue";
import TheSidebar from "../components/layouts/TheSidebar.vue";
import TheHeader from "../components/layouts/TheHeader.vue";
import TheFooter from "../components/layouts/TheFooter.vue";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import { configStore as useConfigStore } from "@/stores/ConfigStore.js"; // Đổi tên import

const config = ref({});
const collapsed = ref(false);
const actionHome = () => {
    router.push({ name: RouteNameConstant.HOME_PAGE });
};
const triggerSidebarCollapse = () => {
    collapsed.value = !collapsed.value;
};
const configStore = useConfigStore(); // Gọi store đúng cách
const updateFavicon = (url) => {
    let link = document.querySelector("link[rel~='icon']");
    if (!link) {
        link = document.createElement("link");
        link.rel = "icon";
        document.head.appendChild(link);
    }
    link.href = url;
    link.type = "image/x-icon";
}

onMounted(async () => {
    await configStore.loadConfig();
    config.value =  await configStore.settings;
    updateFavicon(config.value.setting_favicon)
});
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
