<template>
    <app-page
        :page-title="$t('hrm.contract.title')"
        :advanced-search-input="advancedSearchInput"
        :columns="columns"
        :fetch-data="fetchData"
        :can-search="false"
        :action-detail="hasPermissionEdit ? actionDetail : null"
        :action-edit="hasPermissionEdit ? actionEdit : null"
        :action-add="hasPermissionCreate ? actionAdd : null"
        :table-row-selected="tableRowSelected ?? []"/>
</template>

<script lang="jsx" setup>
import {defineProps, ref} from "vue";
import AppPage from "@/components/views/AppPage.vue";
import {translate, convertConstantObjectToDataSelect} from "@/helpers/CommonHelper.js";
import {hasPermissions} from "@/helpers/AuthHelper.js";
import PermissionConstant from "@/constants/PermissionConstant.js";
import RouteNameConstant from "@/constants/RouteNameConstant.js";
import router from "@/router/index.js";
import UserService from "@/services/system/UserService.js";
import OrderStatusConstant from "@/constants/OrderStatusConstant.js";
import moment from "moment";
import {getCurrentRouteParams} from "@/helpers/RouteHelper.js";
import ContractService from "@/services/Employee/ContractService.js";
import HrmCommonConstant from "@/constants/CommonConstant.js";

const userService = new UserService();
const contractService = new ContractService();

const tableRowSelected = ref([]);

const advancedSearchInput = [];

const props = defineProps({

    paramChildComponent: {
        type: [Array, Object],
        default: null
    },
});
const employeeId = getCurrentRouteParams('employeeId');
const columns = [
    {
        title: translate('hrm.contract.contact_code'),
        width: 5,
        dataIndex: 'contract_number',
        key: 'contract_number',
        customRender: ({text, record}) => {
            return (
                <div>
                    <b>{record.contract_number} </b>
                </div>
            );
        }
    },
    {
        title: translate('hrm.contract.status'),
        width: 5,
        dataIndex: 'status',
        key: 'status',
        customRender: ({text, record}) => {
            let statusText = HrmCommonConstant.HRM.STATUS[record.status];
            return (
                <div>
                    <b>{statusText} </b>
                </div>
            );
        }
    },
    {
        title: translate('hrm.contract.contact_type'),
        width: 5,
        dataIndex: 'contract_type',
        key: 'contract_type',
        customRender: ({text, record}) => {
            let statusText = HrmCommonConstant.HRM.CONTRACT_TYPE[record.contract_type];
            return (
                <div>
                    <b>{statusText} </b>
                </div>
            );
        }
    },
    {
        title: translate('hrm.contract.start_day'),
        width: 5,
        dataIndex: 'date',
        key: 'date',
        customRender: ({text, record}) => {
            return moment(record.start_time).format('DD/MM/YYYY');
        }
    },
    {
        title: translate('hrm.contract.end_day'),
        width: 5,
        dataIndex: 'date',
        key: 'date',
        customRender: ({text, record}) => {
            return moment(record.end_time).format('DD/MM/YYYY');
        }
    }
];

// todo sửa lại quyền sau
const hasPermissionCreate = hasPermissions(PermissionConstant.CREATE_CONTRACT);
const hasPermissionEdit = hasPermissions(PermissionConstant.EDIT_CONTRACT);

const fetchData = (params) => {
    const result = contractService.getList(employeeId, params);
    afterFetchData(params);
    return result;
};
const afterFetchData = (params = '') => {
}
const actionDetail = ({id}) => {
    router.push({name: RouteNameConstant.CONTRACT_DETAIL, params: {'employeeId': employeeId, 'id':id}});
};
const actionEdit = ({id}) => {
    router.push({name: RouteNameConstant.CONTRACT_EDIT, params: {'employeeId': employeeId, 'id':id}});
};

const actionAdd = () => {
    router.push({name: RouteNameConstant.CONTRACT_CREATE});
}

</script>
