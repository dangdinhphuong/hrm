import {axiosGet} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
export default class DepartmentService {
    async getAll(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_DEPARTMENT_LIST, params).then(({data}) => {
            return data;
        })
    }
}
