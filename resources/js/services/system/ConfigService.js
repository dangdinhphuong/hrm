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
    async getListExternal(params = {}) {
        return await axiosGet(ApiPathConstant.EXTERNAL_HRM_CONFIG_LIST, params, {}, true, true).then(({ data }) => {
            return data;
        });
    }

}

