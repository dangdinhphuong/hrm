import {throwForbiddenException} from "@/helpers/ExceptionHelper.js";

export default function notAllowLoggedIn({next, store}) {
    if (store.authStore.isLoggedIn()) {
        throwForbiddenException();
    }
    return next();
}
