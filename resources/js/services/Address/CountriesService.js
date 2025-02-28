import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";

export default class CountriesService {
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_COUNTRIES, params, {}, true, false).then(({data}) => {
            return data;
        })
    }
}
