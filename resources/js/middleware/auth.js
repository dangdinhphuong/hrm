import {throwAuthenticationException} from "@/helpers/ExceptionHelper.js";

export default function auth({next, store}) {
    if (!store.authStore.isLoggedIn()) {
        throwAuthenticationException();
    }
    return next();
}
