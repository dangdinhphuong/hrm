import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
import {buildApiPathWithParams} from "@/helpers/CommonHelper.js";
export default class TimeSheetsService {
    async create(params) {
        return await axiosPost(ApiPathConstant.HRM_EMPLOYEE_CREATE, params).then((data) => {
            return data;
        })
    }
    async update(id, params) {
        return await axiosPatch(buildApiPathWithParams(ApiPathConstant.HRM_EMPLOYEE_EDIT, {id: id}), params).then((data) => {
            return data;
        })
    }
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_TIMESHEET_LIST, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
}
