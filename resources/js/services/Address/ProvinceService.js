import {axiosGet, axiosPatch, axiosPost} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";

export default class ProvinceService {
    async getList(params = {}) {
        return await axiosGet(ApiPathConstant.DISTRICT_LIST, params).then(({data}) => {
            return data;
        })
    }
}
