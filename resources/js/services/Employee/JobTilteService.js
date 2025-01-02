import {axiosGet} from "@/helpers/AxiosHelper.js";
import ApiPathConstant from "@/constants/ApiPathConstant.js";
export default class JobTilteService {
    async getAll(params = {}) {
        return await axiosGet(ApiPathConstant.HRM_JOB_TITLE, params).then(({data}) => {
            return data;
        })
    }
}
