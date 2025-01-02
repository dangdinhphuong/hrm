import {throwForbiddenException} from "@/helpers/ExceptionHelper.js";

export default function abilities({next, store, abilities}) {
    let can = store.authStore.hasPermissions(abilities);
    if (can !== true) {
        throwForbiddenException();
    }
    return next();
}
