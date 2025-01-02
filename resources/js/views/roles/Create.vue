<template>
    <div class="page-title">
        <h2><b>{{ pageTitle }}</b></h2>
    </div>

    <div class="page-content mt-3">
        <div class="page-content mt-3">
            <a-card class="p-2">
                <app-form
                    :fields="fields"
                    :errors="errors"
                    :source-data="role"
                    classWrapperFormItem="col-12"
                    :submit="submit"
                    :cancel="cancel"
                >
                    <template v-slot:more-field>
                        <a-card class="mt-2 p-2">
                            <div class="permission_header fw-bold">
                                <div class="row">
                                    <div class="col-12">
                                        {{ $t('role.columns.permission') }}
                                    </div>
                                </div>
                            </div>
                            <div class="permission_content mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a-tree
                                            :tree-data="treeData"
                                            v-model:checkedKeys="checkedKeys"
                                            v-model:expandedKeys="expandedKeys"
                                            checkable
                                        >
                                            <template #title="{ title, key }">
                                                <span>{{ title }}</span>
                                            </template>
                                        </a-tree>
                                    </div>
                                </div>
                            </div>
                        </a-card>
                    </template>
                </app-form>
            </a-card>
        </div>
    </div>
</template>

<script setup>
import {ref, defineProps} from "vue";
import RoleService from "@/services/system/RoleService.js";
import router from "@/router/index.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import {isSuccessRequest} from "@/helpers/AxiosHelper.js";
import {messageSuccess, messageError} from "@/helpers/MessageHelper.js";
import {translate} from "@/helpers/CommonHelper.js";
import {cloneObject} from "@/helpers/CommonHelper.js";
import AppForm from "@/components/views/AppForm.vue";

const props = defineProps({
    pageTitle: {
        type: String,
        default: translate('role.title_create')
    },
    role: {
        type: Object,
        default: {}
    },
    errors: {
        type: Object,
        default: {}
    },
    updateRole: {
        type: Function,
        default: null
    }
});
const checkedKeys = ref([]);
const expandedKeys = ref([]);
const treeData = ref([]);
const errors = ref(cloneObject(props.errors));
const fields = [
    {
        type: 'text',
        key: 'name',
        name: translate('role.columns.name'),
        required: true,
        classAdvancedFormItem: 'col-sm-12',
    },
    {
        type: 'text-area',
        key: 'description',
        name: translate('role.columns.description'),
        required: false,
        classAdvancedFormItem: 'col-sm-12',
    }
];
const roleService = new RoleService();
const buildTreeData = async () => {
    const permissions = await roleService.getListModuleGroupPermission();

    let permissionTree = [];
    // Duyệt qua từng nhóm quyền (ví dụ DSNS, HDLD, ROLES)
    for (const [key, value] of Object.entries(permissions)) {
        // Mở rộng từng nhóm quyền
        expandedKeys.value.push(key);

        // Tạo đối tượng module đại diện cho nhóm quyền
        let module = {
            title: key, // Tên của nhóm quyền (ví dụ: DSNS)
            key: key,
            children: [] // Chứa các quyền con
        };

        // Duyệt qua từng quyền hạn trong nhóm quyền
        for (const [permissionKey, permissionTitle] of Object.entries(value)) {
            expandedKeys.value.push(permissionKey); // Mở rộng node quyền

            // Thêm quyền hạn vào danh sách children của module
            module.children.push({
                title: permissionTitle, // Ví dụ: "Xem danh sách nhân sự"
                key: permissionKey // Ví dụ: "VIEW_EMPLOYEE_LIST"
            });
        }

        // Thêm module đã được xây dựng vào cây quyền hạn
        permissionTree.push(module);
    }
    // Gán dữ liệu cây cho treeData
    treeData.value = permissionTree;
};

// Hàm xử lý checkedKeys sau khi treeData đã được load
const loadCheckedKeys = () => {
    // Gán giá trị checkedKeys từ props.role.permissions
    checkedKeys.value = cloneObject(props.role.permissions ?? [], 'array');
};

// Hàm load tổng hợp
const loadData = async () => {
    await buildTreeData(); // Đợi treeData load xong
    loadCheckedKeys();     // Sau đó mới load checkedKeys
};

loadData();

const getOnlyPermissionId = () => {
    let permissionIds = [];

    // Duyệt qua tất cả các nhóm quyền (node cha)
    treeData.value.forEach(module => {
        // Duyệt qua tất cả các quyền con của nhóm quyền
        module.children.forEach(child => {
            // Nếu quyền con này có trong checkedKeys, thêm vào danh sách kết quả
            if (checkedKeys.value.includes(child.key)) {
                permissionIds.push(child.key);
            }
        });
    });

    return permissionIds; // Chỉ trả về các quyền con
};

const submit = (formData) => {
    formData.permissions = getOnlyPermissionId();
    if (props.updateRole) {
        props.updateRole(formData);
        return;
    }

    roleService.create(formData).then((data) => {
        if (isSuccessRequest(data)) {
            messageSuccess(translate('role.messages.create_success'));
            router.push({name: RouteNameConstant.ROLE_VIEW});
            return;
        }
        messageError(translate('role.messages.create_fail'));
        errors.value = data.data ?? {};
    })
}

const cancel = () => {
    router.push({name: RouteNameConstant.ROLE_VIEW});
}
</script>
