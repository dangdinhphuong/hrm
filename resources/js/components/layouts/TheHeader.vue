<template>
    <div class="float-start" style="font-size: 18px;">
        <menu-unfold-outlined
            v-if="collapsed"
            class="trigger"
            @click="triggerSidebarCollapse()"
        >
        </menu-unfold-outlined>
        <menu-fold-outlined v-else class="trigger" @click="triggerSidebarCollapse()">
        </menu-fold-outlined>
    </div>
    <div class="float-end" style="border-left: 1px solid var(--sidebar-color); padding-left:20px">
        <a-dropdown :trigger="['click']">
            <a class="ant-dropdown-link" @click.prevent>

                <img :src="user.avatar ?? logoUser" alt="img" class="d-none d-sm-inline img-logo">
                {{ user.name ?? user.username }}
                <DownOutlined/>
            </a>
            <template #overlay>
                <a-menu>
                    <a-menu-item>
                        <a @click="logout">{{ $t('logout') }}</a>
                    </a-menu-item>
                </a-menu>
            </template>
        </a-dropdown>
    </div>
</template>

<script setup>
import {ref, computed, onMounted, watch } from "vue";
import {MenuFoldOutlined, MenuUnfoldOutlined, DownOutlined} from "@ant-design/icons-vue";
import {authStore} from "@/stores/AuthStore.js";

import logoUser from '@assets/images/logo/user.png';
import AuthService from "@/services/system/AuthService.js";

const props = defineProps(['sidebarCollapsed']);
const emit = defineEmits(["triggerSidebarCollapse"]);

const collapsed = ref(props.sidebarCollapsed);
const triggerSidebarCollapse = () => {
    collapsed.value = !collapsed.value;
    emit('triggerSidebarCollapse');
};

const store = authStore();


// Load user khi component được mounted
onMounted(() => {
    store.loadUser();
});

// Lấy user từ store (dùng computed để tự động cập nhật)

const user = computed(() => store.user);


watch(() => store.user.avatar, (newAvatar) => {

});


const logout = () => {
    new AuthService().logout();
};
</script>

<style lang="scss" scoped>
:deep(.img-logo) {
    border-radius: 90%;
    width: 41px;
    height: 41px;
}
</style>
