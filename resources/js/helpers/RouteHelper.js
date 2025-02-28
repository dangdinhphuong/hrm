import router from "@/router/index.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";
import {useRoute} from "vue-router";
import {isArray} from "@/helpers/CommonHelper.js";

export function reloadCurrentRoute() {
    const currentRoute = router.currentRoute.value;
    const routeParams = currentRoute.params;
    const routeQuery = currentRoute.query;
    const routeName = currentRoute.name;

    router.push({name: routeName, params: routeParams, query: routeQuery});
}

export function pushRouterWithMessage(messageFunction, message, routeName = routeNameConstant.HOME_PAGE) {
    messageFunction(message);
    router.push({name: routeName});
}

export function getCurrentRouteParams(params = null) {
    const route = useRoute();
    const routeParams = route.params;

    if (!params) {
        return routeParams;
    }
    if (typeof params === 'string') {
        return routeParams[params];
    }
    if (isArray(params)) {
        var result = {};
        params.forEach(function (key) {
            if (routeParams.hasOwnProperty(key)) {
                result[key] = routeParams[key];
            }
        });
        return result;
    }

    return {};
}
