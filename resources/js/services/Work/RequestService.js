import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
import {buildApiPathWithParams} from "@/helpers/CommonHelper.js";
export default class RequestService {
    async create(params) {
        return await axiosPost(ApiPathConstant.HRM_REQUESTS_CREATE, params).then((data) => {
            return data;
        })
    }
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_REQUESTS_LIST, params, {}, true, true).then(({data}) => {
            return data;
        })
    }
}
