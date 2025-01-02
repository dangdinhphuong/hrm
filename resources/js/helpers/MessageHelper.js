import {message} from "ant-design-vue";

export function messageWarning(mess) {
    message.warn(mess);
}

export function messageSuccess(mess) {
    message.success(mess);
}

export function messageError(mess) {
    message.error(mess);
}
