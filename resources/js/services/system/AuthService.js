import {axiosGet, axiosPost, isSuccessRequest} from "@/helpers/AxiosHelper.js";
import apiPathConstant from "@/constants/ApiPathConstant.js";
import {authStore} from "@/stores/AuthStore.js";
import router from "@/router/index.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";

export default class AuthService {
    accessTokenKey = 'access_token';

    setAccessToken(accessToken) {
        localStorage.setItem(this.accessTokenKey, accessToken);
    }

    getAccessToken() {
        return localStorage.getItem(this.accessTokenKey);
    }

    removeAccessToken() {
        return localStorage.removeItem(this.accessTokenKey);
    }

    login(username, password) {
        return axiosPost('login', {
            username: username,
            password: password
        }, {}, false).then((response) => {
            this.setAccessToken(response.data.access_token);
            authStore().setUser(response.data.user);
            router.push({name: routeNameConstant.HOME_PAGE});
        })
    }

    logout() {
        this.removeAccessToken();
        authStore().$reset();
        router.push({name: routeNameConstant.LOGIN});
    }

    async getCurrentUser() {
        let response = await axiosGet(apiPathConstant.ME_INFO);
        if (isSuccessRequest(response)) {
            return response.data
        }
        return {};
    }
}
