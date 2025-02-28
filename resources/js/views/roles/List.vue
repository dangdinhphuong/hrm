<template>
    <app-page
        :page-title="$t('sidebar.role')"
        :advanced-search-input="advancedSearchInput"
        :columns="columns"
        :fetch-data="fetchData"
        :action-add="hasPermissionCreate ? actionAdd : null"
        :action-edit="hasPermissionEdit ? actionEdit : null"
    >
    </app-page>
</template>

<script lang="jsx" setup>
import {ref} from 'vue';
import RoleService from "@/services/system/RoleService.js";
import AppPage from "@/components/views/AppPage.vue";
import {translate} from "@/helpers/CommonHelper.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import router from "@/router/index.js";
import {modalConfirm} from "@/helpers/ModalHelper.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageError, messageSuccess} from "@/helpers/MessageHelper.js";

const roleService = new RoleService();

const advancedSearchInput = [
    {
        type: 'text',
        key: 'name',
        name: translate('role.columns.name')
    }
];
const columns = [
    {
        title: translate('role.columns.name'),
        width: 5,
        dataIndex: 'name',
        key: 'name',
    },
    {
        title: translate('role.columns.description'),
        width: 5,
        dataIndex: 'description',
        key: 'description',
    },
    {
        title: translate('role.columns.created_at'),
        width: 5,
        dataIndex: 'created_at',
        key: 'created_at',
    },
    {
        title: translate('role.columns.updated_at'),
        width: 5,
        dataIndex: 'updated_at',
        key: 'updated_at',
    }
];

const hasPermissionCreate = hasPermissions(PermissionConstant.ROLE_CREATE);
const hasPermissionEdit = hasPermissions(PermissionConstant.ROLE_EDIT);

const fetchData = (params) => {
    return roleService.getList(params);
}

//Action
const actionAdd = () => {
    router.push({name: RouteNameConstant.ROLE_CREATE});
}
const actionEdit = ({id}) => {
    router.push({name: RouteNameConstant.ROLE_EDIT, params: {id: id}});
}

</script>
