<template>
    <template v-for="(item, index) in tree">
        <template v-if="item.isVisible">
            <a-menu-item :key="item.sidebarKey" v-if="!item.children" :to="item.route">
                <template #icon v-if="item.icon">
                    <img :src="getIcon(item.icon)" alt="icon">
                </template>
                {{ item.name }}
            </a-menu-item>
            <a-sub-menu :key="renderSubKey(subKey ?? 'sub', index)" v-else>
                <template #icon v-if="item.icon">
                    <img :src="getIcon(item.icon)" alt="icon">
                </template>
                <template #title>{{ item.name }}</template>
                <menu-recursive :tree="item.children" :subKey="renderSubKey(subKey ?? 'sub', index)"></menu-recursive>
            </a-sub-menu>
        </template>
    </template>

</template>

<script setup>
import {reactive} from "vue";

const props = defineProps([
    'tree',
    'subKey'
]);
const state = reactive({
    tree: props.tree,
    subKey: props.subKey
})

const renderSubKey = (subKey, index) => {
    return `${subKey}-${index}`;
}

const getIcon = (name) => {
    return new URL(`../../../images/icon/${name}.svg`, import.meta.url).href;
}
</script>
