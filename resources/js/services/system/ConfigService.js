import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
import {buildApiPathWithParams} from "@/helpers/CommonHelper.js";
export default class ConfigService {
    async create(params) {
        return await axiosPost(ApiPathConstant.HRM_CONFIG_CREATE, params, {'Content-Type': 'multipart/form-data'}).then((data) => {
            return data;
        })
    }
    async update(id, params) {
        return await axiosPatch(buildApiPathWithParams(ApiPathConstant.HRM_EMPLOYEE_EDIT, {id: id}), params).then((data) => {
            return data;
        })
    }
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_CONFIG_LIST, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
    async getTimesheets(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_EMPLOYEE_TIMESHEETS_LIST, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
    async getMyEmployeeDetail(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_MY_EMPLOYEE_DETAIL, params, {}, true, true).then(({data}) => {
            return data;
        })
    }

    async getDetailByUserId(userId) {
        return await axiosGet(buildApiPathWithParams(ApiPathConstant.HRM_EMPLOYEE_DETAIL_BY_USER_ID, {userId: userId}), {}, {}, true, true).then(({data}) => {
            return data ?? {};
        })
    }
    async uploadAvatar(employeeId, params, headers = {}) {
        return await axiosPost(buildApiPathWithParams(ApiPathConstant.HRM_EMPLOYEE_AVATAR_UPLOAD, {employeeId: employeeId}), params, headers).then((data) => {
            return data;
        })
    }

    async getAvatar(employeeId) {
        return await axiosGet(buildApiPathWithParams(ApiPathConstant.HRM_EMPLOYEE_AVATAR, {employeeId: employeeId})).then(({data}) => {
            return data ?? {};
        })
    }
}
