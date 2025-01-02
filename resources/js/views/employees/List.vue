<template>
    <div id="container-page">
    <app-page
        :page-title="$t('hrm.employees.title')"
        :advanced-search-input="advancedSearchInput"
        :columns="columns"
        :fetch-data="fetchData"
        :action-edit="hasPermissionEdit ? actionEdit : null"
        :action-detail="hasPermissionView ? actionDetail : null"
        :table-row-selection-actions="tableRowSelectionAction"
        :action-download="hasPermissionEdit ? actionDownload : null"
        :action-upload="hasPermissionEdit ? actionUpload : null"
        :action-add="hasPermissionCreate ? actionAdd : null"
        :table-row-selected="tableRowSelected ?? []"/>
    </div>
</template>

<script lang="jsx" setup>
import {ref} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import {translate, convertConstantToDataSelect} from "@/helpers/CommonHelper.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import router from "@/router/index.js";
import CommonConstant from "@/constants/CommonConstant.js";

import SelectAction from "@/constants/RowSelectionConstant.js";
import EmployeeService from "@/services/Employee/EmployeeService.js";

const employeeService = new EmployeeService();

const tableRowSelected = ref([]);

const advancedSearchInput = [
    {
        type: 'phone',
        key: 'phone',
        name: translate('delivery_note.phone')
    },
    {
        type: 'date',
        key: 'order_date',
        name: translate('delivery_note.columns.order_date')
    }
];

const columns = [
    {
        title: translate('hrm.employees.employee_code'),
        width: 9,
        dataIndex: 'code',
        key: 'code'
    }, {
        title: translate('hrm.employees.full_name'),
        width: 5,
        dataIndex: 'first_name',
        key: 'first_name',
        customRender: ({text, record}) => {
            return (
                <div>
                    <b>{record.first_name ?? ''} {record.last_name ?? ''}</b>
                </div>
            );
        }
    },
    {
        title: translate('hrm.employees.job_title'),
        width: 5,
        dataIndex: 'position_name',
        key: 'position_name',
        customRender: ({text, record}) => {
            return (
                <div>
                    <b>{record.position.name ?? ''} </b>
                </div>
            );
        }
    },
    {
        title: translate('hrm.employees.status'),
        width: 5,
        dataIndex: 'status',
        key: 'status',
        customRender: ({text, record}) => {
            let statusText = CommonConstant.USER_STATUS;
            return (
                <div>
                    {statusText.get(record.status)}
                </div>
            );
        }
    },
    {
        title: translate('hrm.employees.bu'),
        width: 5,
        dataIndex: 'bu',
        key: 'bu',
        customRender: ({text, record}) => {
            return (
                <div>
                    {record.departments[0].business_unit_type ?? 0}
                </div>
            );
        }
    }
];

// todo sửa lại quyền sau
const hasPermissionView = hasPermissions(PermissionConstant.VIEW_EMPLOYEE_LIST);
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_EMPLOYEE);
const hasPermissionEdit = hasPermissions(PermissionConstant.EDIT_EMPLOYEE_DETAIL);

const fetchData = (params) => {
    return employeeService.getList(params);
};

//Action
const actionEdit = ({id}) => {
    router.push({name: RouteNameConstant.INFO_EDIT, params: {'employeeId': id}});
};
const actionDetail = ({id}) => {
    router.push({name: RouteNameConstant.INFO_DETAIL, params: {'employeeId': id}});
};
const clickActionOther = () => {
    router.push({name: RouteNameConstant.INFO});
};
const actionDownload = () => {
    router.push({name: RouteNameConstant.USER_CREATE});
}
const actionUpload = () => {
    router.push({name: RouteNameConstant.USER_CREATE});
}
const actionAdd = () => {
    router.push({name: RouteNameConstant.INFO_CREATE});
}

const tableRowSelectionAction = (selectedRowKeys) => {
    const select = [
        SelectAction.ALL,
        SelectAction.INVERT,
        SelectAction.NONE,
        {
            key: 'delete',
            text: 'Xóa phiếu giao hàng',
            onSelect: changableRowKeys => {
                console.log('Xóa phiếu giao hàng', tableRowSelected.value)
            },
        },
        {
            key: 'odd',
            text: 'Select Odd Row',
            onSelect: changableRowKeys => {
                let newSelectedRowKeys = [];
                newSelectedRowKeys = changableRowKeys.filter((_key, index) => index % 2 === 0);
                selectedRowKeys.value = newSelectedRowKeys;
                console.log('Select Odd Row', tableRowSelected.value)
            },
        },
        {
            key: 'even',
            text: 'Select Even Row',
            onSelect: changableRowKeys => {
                let newSelectedRowKeys = [];
                newSelectedRowKeys = changableRowKeys.filter((_key, index) => index % 2 !== 0);
                selectedRowKeys.value = newSelectedRowKeys;
            },
        }
    ];

    return select;
};
</script>
<style lang="scss" scoped>
#container-page {
    padding: 24px;
    background: #fff !important;
    margin: 17px 16px 0px 16px;
    border-radius: 6px;
}
</style>
