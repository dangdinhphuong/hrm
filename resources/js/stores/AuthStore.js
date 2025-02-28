import {defineStore} from 'pinia';
import AuthService from "@/services/system/AuthService.js";
import {toRaw, reactive } from "vue";
import {isEmptyObject, isArray, getEnv} from "@/helpers/CommonHelper.js";

export const authStore = defineStore('authStore', {
    state: () => ({
        user: reactive({})
    }),

    actions: {
        setUser(user) {
            this.user = user;
        },
        setAvatar(avatarUrl) {
            if (this.user) {
                this.user.avatar = avatarUrl;
            }
        },
        async loadUser() {
            if (!isEmptyObject(this.user)) {
                return;
            }
            if (new AuthService().getAccessToken()) {
                this.user = await new AuthService().getCurrentUser();
            }
        },
        isLoggedIn() {
            return !isEmptyObject(this.user);
        },
        hasPermissions(permissions) {
            if (this.isSuperAdmin()) {
                return true;
            }
            permissions = this.convertPermissionToArray(permissions);
            return permissions.every(item => this.getPermissions.includes(item));
        },
        hasAnyPermission(permissions) {
            if (this.isSuperAdmin()) {
                return true;
            }
            permissions = this.convertPermissionToArray(permissions);
            return permissions.some(item => this.getPermissions.includes(item));
        },
        convertPermissionToArray(permissions) {
            if (!isArray(permissions)) {
                permissions = [permissions];
            }
            return permissions;
        },
        isSuperAdmin() {
            const roleSuperAdmin = getEnv('APP_ROLE_SUPER_ADMIN');
            if (!roleSuperAdmin) {
                return false;
            }
            return this.getRoles.includes(roleSuperAdmin);
        }
    },

    getters: {
        getUser() {
            return toRaw(this.user);
        },
        getRoles() {
            return this.getUser.roles ?? [];
        },
        getPermissions() {
            return this.getUser.permissions ?? [];
        }
    }
})
