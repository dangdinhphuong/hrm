import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
import {buildApiPathWithParams} from "@/helpers/CommonHelper.js";
export default class SalaryService {
    async create(params) {
        return await axiosPost(ApiPathConstant.HRM_REQUESTS_CREATE, params).then((data) => {
            return data;
        })
    }
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_SALARIES, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
    async find(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_SALARY_FIND, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
    async me(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_SALARY_ME, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
    async update(id, params) {
        return await axiosPatch(buildApiPathWithParams(ApiPathConstant.HRM_SALARY_EDIT, {id: id}), params).then((data) => {
            return data;
        })
    }
    async getPaySlip(id, params) {
        return await axiosGet(buildApiPathWithParams(ApiPathConstant.HRM_PAYSLIP, {id: id}), params).then((data) => {
            return data;
        })
    }
}
