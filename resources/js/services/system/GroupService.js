import {axiosGet} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";

export default class GroupService {
    async getAll() {
        return await axiosGet(ApiPathConstant.ALL_DEPARTMENT).then(({data}) => {
            return data ?? {};
        })
    }

    async getList() {
        return await axiosGet(ApiPathConstant.GROUP_LIST).then(({data}) => {
            return data ?? {};
        })
    }
}
