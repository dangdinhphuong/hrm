import {axiosGet} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
export default class BankService {
    async getAll(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_BANK_LIST, params).then(({data}) => {
            return data;
        })
    }
}
