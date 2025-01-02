import {throwForbiddenException} from "@/helpers/ExceptionHelper.js";

export default function ability({next, store, ability}) {
    let can = store.authStore.hasAnyPermission(ability);
    if (can !== true) {
        throwForbiddenException();
    }
    return next();
}
