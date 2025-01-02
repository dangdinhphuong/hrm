import AuthService from "@/services/system/AuthService.js";
import {translate} from "@/helpers/CommonHelper.js";
import {pushRouterWithMessage} from "@/helpers/RouteHelper.js";
import {messageWarning} from "@/helpers/MessageHelper.js";
import {authStore} from "@/stores/AuthStore.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";

const EXCEPTION_TYPE = {
    AUTHENTICATION: 'AuthenticationException',
    FORBIDDEN: 'ForbiddenException',
    NOT_FOUND_HTTP: 'NotFoundHttp',
    NOT_FOUND_MODEL: 'NotFoundModel'
};

export function throwAuthenticationException() {
    throwException(EXCEPTION_TYPE.AUTHENTICATION)
}

export function throwForbiddenException() {
    throwException(EXCEPTION_TYPE.FORBIDDEN)
}

export function throwNotFoundHttpException() {
    throwException(EXCEPTION_TYPE.NOT_FOUND_HTTP)
}

export function throwNotFoundModelException() {
    throwException(EXCEPTION_TYPE.NOT_FOUND_MODEL)
}

function throwException(exception) {
    switch (exception) {
        case EXCEPTION_TYPE.AUTHENTICATION:
            new AuthService().logout();
            break;
        case EXCEPTION_TYPE.FORBIDDEN:
            pushRouterWithMessage(messageWarning, translate('message.warning.forbidden'));
            break;
        case EXCEPTION_TYPE.NOT_FOUND_MODEL:
            pushRouterWithMessage(messageWarning, translate('message.warning.not_found_model'));
            break;
        case EXCEPTION_TYPE.NOT_FOUND_HTTP:
            if (authStore().isLoggedIn()) {
                pushRouterWithMessage(messageWarning, translate('message.warning.not_found_http'));
            } else {
                pushRouterWithMessage(messageWarning, translate('message.warning.not_found_http'), routeNameConstant.LOGIN);
            }

            break;
    }
    throw new Error(exception);
}
