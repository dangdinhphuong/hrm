import auth from "@/middleware/auth.js";
import ability from "@/middleware/ability.js";
import notAllowLoggedIn from "@/middleware/not_allow_logged_in.js";
import abilities from "@/middleware/abilities.js";
import menuSelected from "@/middleware/menu_selected.js";
import routeNameConstant from "@/constants/RouteNameConstant.js";
import permissionConstant from "@/constants/PermissionConstant.js";
import SidebarKeyConstant from "@/constants/SidebarKeyConstant.js";

const admin = [
    {
        path: "/login",
        name: routeNameConstant.LOGIN,
        component: () => import("../views/Login.vue"),
        meta: {
            middleware: notAllowLoggedIn
        }
    },
    {
        path: "/",
        component: () => import("../layouts/admin.vue"),
        meta: {
            middleware: [auth, menuSelected]
        },
        children: [
            {
                path: "",
                name: routeNameConstant.HOME_PAGE,
                component: () => import("../views/HomePage.vue"),
                meta: {}
            },
            {
                path: "info",
                component: () => import("../components/subSideBar/SubSidebarEmployee.vue"),
                children: [
                    {
                        path: "view",
                        name: routeNameConstant.ME_INFO_DETAIL,
                        component: () => import("../views/employees/MeInfo.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_PERSONAL_INFO},
                            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.ME_INFORMATION,
                        }
                    },
                    {
                        path: ":employeeId/view",
                        name: routeNameConstant.INFO_DETAIL,
                        component: () => import("../views/employees/Info.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_EMPLOYEE_DETAIL},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.INFORMATION,
                        }
                    },
                    {
                        path: "create",
                        name: routeNameConstant.INFO_CREATE,
                        component: () => import("../views/employees/Create.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.CREATE_EMPLOYEE},
                            sidebarKey: SidebarKeyConstant.INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.INFORMATION,
                        }
                    },
                    {
                        path: ":employeeId/edit",
                        name: routeNameConstant.INFO_EDIT,
                        component: () => import("../views/employees/Update.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.EDIT_EMPLOYEE_DETAIL},
                            sidebarKey: SidebarKeyConstant.INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.INFORMATION,
                        }
                    }
                ]
            },
            {
                path: "contract",
                component: () => import("../components/subSideBar/SubSidebarEmployee.vue"),
                children: [
                    {
                        path: "view",
                        name: routeNameConstant.ME_CONTRACT,
                        component: () => import("../views/contact/MeList.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_PERSONAL_INFO},
                            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.ME_CONTRACT,
                        }
                    },
                    {
                        path: ":employeeId/view",
                        name: routeNameConstant.CONTRACT,
                        component: () => import("../views/contact/List.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_CONTRACT_LIST},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.CONTRACT,
                        }
                    },
                    {
                        path: ":employeeId/create",
                        name: routeNameConstant.CONTRACT_CREATE,
                        component: () => import("../views/contact/Create.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.CREATE_CONTRACT},
                            sidebarKey: SidebarKeyConstant.INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.CONTRACT,
                        }
                    },
                    {
                        path: ":employeeId/edit/:id",
                        name: routeNameConstant.CONTRACT_EDIT,
                        component: () => import("../views/contact/Edit.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.EDIT_CONTRACT},
                            sidebarKey: SidebarKeyConstant.INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.CONTRACT,
                        }
                    },
                    {
                        path: ":employeeId/detail/:id",
                        name: routeNameConstant.CONTRACT_DETAIL,
                        component: () => import("../views/contact/Detail.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_CONTRACT_DETAIL},
                            sidebarKey: SidebarKeyConstant.INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.CONTRACT,
                        }
                    }
                ]
            },
            {
                path: "employees",
                component: () => import("../components/subSideBar/SubSidebarSystem.vue"),
                children: [
                    {
                        path: "",
                        name: routeNameConstant.EMPLOYEE,
                        component: () => import("../views/employees/List.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_EMPLOYEE_LIST},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.EMPLOYEE,
                        }
                    },
                    {
                        path: "roles",
                        name: routeNameConstant.ROLE_VIEW,
                        component: () => import("../views/roles/List.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.ROLE_VIEW},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.ROLE,
                        }
                    },
                    {
                        path: "roles/create",
                        name: routeNameConstant.ROLE_CREATE,
                        component: () => import("../views/roles/Create.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.ROLE_CREATE},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.ROLE,
                        }
                    },
                    {
                        path: ":id/edit",
                        name: routeNameConstant.ROLE_EDIT,
                        component: () => import("../views/roles/Edit.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.ROLE_EDIT},
                            sidebarKey: SidebarKeyConstant.EMPLOYEE,
                            sidebarKeySub: SidebarKeyConstant.ROLE,
                        }
                    }
                ]
            },
            {
                path: "/worktime",
                name: routeNameConstant.WORK,
                component: () => import("../views/works/List.vue"),
                meta: {
                    middleware: {function: abilities, abilities: permissionConstant.ROLE_VIEW},
                    sidebarKey: SidebarKeyConstant.WORK,
                }
            },
            {
                path: "setting",
                component: () => import("../components/subSideBar/SubSidebarSetting.vue"),
                children: [
                    {
                        path: "general",
                        name: routeNameConstant.SETTING_GENERAL,
                        component: () => import("../views/setting/General.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_PERSONAL_INFO},
                            sidebarKey: SidebarKeyConstant.SETTING_GENERAL,
                            sidebarKeySub: SidebarKeyConstant.SETTING_GENERAL,
                        }
                    }
                ]
            },
        ]
    }
];

export default admin;
