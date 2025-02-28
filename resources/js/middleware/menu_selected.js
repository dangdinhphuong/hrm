import {menuStore} from "@/stores/MenuStore.js";

export default function menuSelected({to, next}) {
    //TODO, nhiều cấp
    const sidebarKey = to.meta.sidebarKey;
    const subSidebarKey = to.meta.sidebarKeySub ?? sidebarKey;
    if (sidebarKey) {
        menuStore().onSelectedKeys([sidebarKey,subSidebarKey]);
    }
    return next();
}
