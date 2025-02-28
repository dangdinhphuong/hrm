import {defineStore} from 'pinia';

export const menuStore = defineStore('menuStore', {
    state: () => ({
        selectedKeys: [],
        openKeys: [],
    }),

    actions: {
        onSelectedKeys(data) {
            this.selectedKeys = data;
        }
    }
})
