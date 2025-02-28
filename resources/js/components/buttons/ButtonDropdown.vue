<template>
    <a-space  :size="size" class="ms-3 mt-3 col-12 pr-0 pl-0">
        <a-dropdown>
            <template #overlay>
                <a-menu>
                    <a-menu-item v-for="item in data.listMenu" key="{{ item.key }}">{{ item.name }}</a-menu-item>
                </a-menu>
            </template>
            <a-button :size="size" type="primary" class=" col-12">
                {{ data.title }}
                <DownOutlined />
            </a-button>
        </a-dropdown>
    </a-space>
</template>
<script lang="ts" setup>
import { DownOutlined } from '@ant-design/icons-vue';
import {defineProps, reactive} from "vue";

const props = defineProps({
    data: Object,
    size: {
        type: String,
        default: 'large'
    }
});

const getIcon = (name) => {
    return new URL(`../../../images/icon/${name}.svg`, import.meta.url).href;
}
</script>
<style lang="scss" scoped>
:deep(.ant-space-item) {
    width: 100%;
}
</style>
