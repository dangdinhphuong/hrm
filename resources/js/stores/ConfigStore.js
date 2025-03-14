import { defineStore } from "pinia";
import ConfigService from "@/services/system/ConfigService.js";
import { reactive, toRaw } from "vue";
import {isEmptyObject, saveToLocalStorage} from "@/helpers/CommonHelper.js";

export const configStore = defineStore("configStore", {
    state: () => ({
        settings: reactive({}) // Lưu trữ các cấu hình
    }),

    actions: {
        setConfig(config) {
            this.settings = config;
        },

        async loadConfig() {
            if (!isEmptyObject(this.settings)) {
                return;
            }
            try {
                const response = await new ConfigService().getListExternal();
                if (response) {
                    this.setConfig(response);
                    saveToLocalStorage('configs', response);
                }
            } catch (error) {
                console.error("Lỗi khi tải cấu hình:", error);
            }
        },

        getConfigValue(key, defaultValue = null) {
            return this.settings[key] ?? defaultValue;
        }
    },

    getters: {
        getSettings() {
            return toRaw(this.settings);
        }
    }
});
