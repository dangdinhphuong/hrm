import {createRouter, createWebHistory} from 'vue-router';
import admin from "./admin.js";
import {authStore} from "../stores/AuthStore.js";
import {isArray, isObject} from "@/helpers/CommonHelper.js";
import {throwNotFoundHttpException} from "@/helpers/ExceptionHelper";

const routes = [...admin];

const router = createRouter({
    history: createWebHistory(),
    routes
});

const getMiddlewares = (to) => {
    let middlewares = [];
    to.matched.forEach(function (item) {
        if (item.meta.middleware) {
            let middleware = item.meta.middleware;
            if (isArray(middleware)) {
                middlewares.push(...item.meta.middleware);
            } else {
                middlewares.push(middleware);
            }
        }
    });
    return middlewares;
}

const prepareMiddleware = (middleware, context) => {
    if (isObject(middleware)) {
        for (let key in middleware) {
            if (key !== "function") {
                context[key] = middleware[key];
            }
        }
        middleware = middleware.function;
    }
    return middleware;
}

const middlewarePipeline = (context, middleware, index) => {

    let nextMiddleware = middleware[index]
    if (!nextMiddleware) {
        return context.next
    }

    nextMiddleware = prepareMiddleware(nextMiddleware, context);

    return () => {
        const nextPipeline = middlewarePipeline(
            context, middleware, index + 1
        )
        nextMiddleware({...context, next: nextPipeline})
    }
}

const loadAndGetStore = async () => {
    const store = {};
    store.authStore = authStore();
    await store.authStore.loadUser();

    return store;
}

const isRouteExist = (routeName) => {
    return !!routeName;
}

router.beforeEach(async (to, from, next) => {
    const routeExist = isRouteExist(to.name);

    const middlewares = getMiddlewares(to);
    if (middlewares.length === 0 && routeExist) {
        return next()
    }

    const store = await loadAndGetStore();
    if (!routeExist) {
        throwNotFoundHttpException();
    }

    let middleware = middlewares[0];
    let context = {
        to,
        from,
        next,
        store
    }

    middleware = prepareMiddleware(middleware, context);
    return middleware({
        ...context,
        next: middlewarePipeline(context, middlewares, 1)
    })
})

export default router;
