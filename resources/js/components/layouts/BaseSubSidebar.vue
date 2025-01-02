<template>
    <a-layout id="container-page" class="horizontal-layout">
        <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible breakpoint="lg"
                        class="section-1">
            <div v-if="menuMode != 'horizontal'" class="logo text-center mb-3" @click="actionHome"
                 style="cursor: pointer;">
                <slot name="logo"></slot>
            </div>
            <div id="sidebar">
                <a-menu
                    v-model:openKeys="openKeys"
                    v-model:selectedKeys="selectedKeysWithoutFirst"
                    style="width: 100%"
                    :mode="menuMode"
                    @click="handleClick"
                >
                    <menuRecursive :tree="menuData"></menuRecursive>
                </a-menu>
            </div>
        </a-layout-sider>
        <a-layout class="section-2">
            <a-layout-content>
                <router-view></router-view>
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>

<script setup>
import { ref, onMounted, computed, defineProps, watch } from 'vue';
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import { menuStore } from "@/stores/MenuStore.js";
import MenuRecursive from "@/components/layouts/MenuRecursive.vue";
import { storeToRefs } from "pinia";

const props = defineProps({
    menuData: {
        type: Array,
        default: []
    },
    handleClickRedirect: {
        type: Function,
        default: null
    },
});
const collapsed = ref(true);
const windowWidth = ref(window.innerWidth);

const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener('resize', updateWindowWidth);
});

const menuMode = computed(() => {
    return windowWidth.value < 1024 ? 'horizontal' : 'vertical';
});

const actionHome = () => {
    router.push({name: RouteNameConstant.HOME_PAGE});
}

const triggerSidebarCollapse = () => {
    collapsed.value = !collapsed.value;
}

const handleClick = (menuInfo) => {
    props.handleClickRedirect(menuInfo);
};

const { selectedKeys, openKeys } = storeToRefs(menuStore());

// Tạo một ref để lưu trữ selectedKeysWithoutFirst
const selectedKeysWithoutFirst = ref([]);

watch(selectedKeys, (newValue) => {
    selectedKeysWithoutFirst.value = newValue.slice(1);
}, { immediate: true });
</script>


<style lang="scss" scoped>
#container-page {
    display: flex;
    flex-direction: row;
    gap: 16px;
    padding: 17px;
    background: #f5f5f5 !important;
    border-radius: 6px;

    @media (max-width: 1024px) {
        flex-direction: column;
    }
}

:deep(.ant-layout-sider) {
    background: #fff !important;
}

:deep(.section-1) {
    width: 280px;
    background: #fff;
    border-radius: 10px;
    transition: width 0.3s;
    max-width: 100% !important;
    flex: none !important;
    @media (max-width: 1024px) {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        flex: none !important;
    }
    @media (min-width: 1024px) {
        width: 16% !important;
    }
}

:deep(.section-2) {
    flex: 1;
    padding: 2%;
    background: #fff;
    border-radius: 10px;
    width: 100% !important;
}

:deep(.section-1),
:deep(.section-2) {
    background: #fff;
    border-radius: 10px;
}

#sidebar {
    :deep(ul.ant-menu) {
        background: #fff;
        border-right: revert;
    }

    :deep(.ant-menu-item),
    :deep(.ant-menu-submenu-title),
    :deep(.ant-menu-submenu-arrow) {
        color: #000000;
        font-weight: bold;
    }

    :deep(.ant-menu-item-active),
    :deep(.ant-menu-submenu-active) {
        background: #dedede;
    }

    :deep(.ant-menu-submenu-selected) {
        background: #dedede;
    }

    :deep(.ant-menu-item-selected),
    :deep(.ant-menu-submenu-selected > .ant-menu-submenu-title),
    :deep(.ant-menu-submenu-selected .ant-menu-submenu-arrow) {
        background: #dedede;
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

:deep(.avatar-wrapper) {
    display: inline-block;
}

:deep(.avatar) {
    margin-top: 15%;
}

:deep(.avatar img) {
    width: 5vh;
    height: 5vh;
    border-radius: 50%;
}
</style>
