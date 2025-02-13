import {buildApiUrl, isset} from "@/helpers/CommonHelper.js";
import {throwAuthenticationException} from "@/helpers/ExceptionHelper.js";
import { userLoader } from "@/composables/loader.js";

export function axiosGet(path, params = {}, headers = {}, isUseAccessToken = true, isLoading = false) {
    return request('get', path, params, headers, isUseAccessToken, isLoading);
}

export function axiosPost(path, params = {}, headers = {}, isUseAccessToken = true, isLoading = false) {
    return request('post', path, params, headers, isUseAccessToken, isLoading);
}

export function axiosPut(path, params = {}, headers = {}, isUseAccessToken = true, isLoading = false) {
    return request('put', path, params, headers, isUseAccessToken, isLoading);
}

export function axiosPatch(path, params = {}, headers = {}, isUseAccessToken = true, isLoading = false) {
    return request('patch', path, params, headers, isUseAccessToken, isLoading);
}

export function isSuccessRequest(response) {
    return response.isHttpSuccess ?? false;
}

function request(method, path, params, headers, isUseAccessToken, isLoading) {
    delete params["__v_isShallow"];
    delete params["__v_isRef"];
    delete params["_rawValue"];
    delete params["_value"];

    if (isLoading) {
        var loader = userLoader().show();

    }

    try {
        let url = buildApiUrl(path);

        if (isUseAccessToken && localStorage.getItem('access_token')) {
            headers.Authorization = `Bearer ${localStorage.getItem('access_token')}`;
            console.log('headers.Authorization',`Bearer ${localStorage.getItem('access_token')}`)
        }

        let axiosConfig = {
            method: method,
            url: url,
            headers: headers
        };

        if (method === 'get') {
            axiosConfig.params = params;
        } else {
            axiosConfig.data = params;
        }

        return axios(axiosConfig).then(({data}) => {
            return {
                code: data.code,
                data: data.data,
                message: data.message,
                isHttpSuccess: true
            };
        }).catch(({response}) => {
            if (response.status === 401 || response.status === 403) {
                throwAuthenticationException();
            }
            return {
                code: response.data.code,
                data: response.data.data,
                message: response.data.message,
                httpCode: response.status,
                isHttpSuccess: false
            };
        }).finally(function () {
            if (isset(loader)) {
                userLoader().hide();
            }
        });
    } catch (e) {
        if (isset(loader)) {
            userLoader().hide();
        }
    }
}


