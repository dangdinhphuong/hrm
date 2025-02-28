import {axiosGet, axiosPatch, axiosPost, axiosPut} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
import {buildApiPathWithParams} from "@/helpers/CommonHelper.js";

export default class ContractService {
    async create(params) {
        return await axiosPost(ApiPathConstant.HRM_CONTRACT_CREATE, params, {'Content-Type': 'multipart/form-data'}).then((data) => {
            return data;
        })
    }

    async update(id, params) {
        return await axiosPost(buildApiPathWithParams(ApiPathConstant.HRM_CONTRACT_EDIT, {id: id}), params, {'Content-Type': 'multipart/form-data'}).then((data) => {
            return data;
        })
    }

    async getList($employeesId, params) {
        return await axiosGet(buildApiPathWithParams(ApiPathConstant.HRM_CONTRACT_LIST, {employeesId: $employeesId}), params, {}, true, true).then(({data}) => {
            return data ?? {};
        })
    }

    async getMyContract(params) {
        return await axiosGet(ApiPathConstant.HRM_MY_CONTRACT_LIST, params, {}, true, true).then(({data}) => {
            return data ?? {};
        })
    }

    async getDetailById(id) {
        return await axiosGet(buildApiPathWithParams(ApiPathConstant.HRM_CONTRACT_DETAIL, {id: id})).then(({data}) => {
            return data ?? {};
        })
    }
}
