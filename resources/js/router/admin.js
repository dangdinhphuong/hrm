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
                // redirect: { name: routeNameConstant.ME_INFO_DETAIL }
                component: () => import("../components/subSideBar/SubSidebarEmployee.vue"),
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
                    },
                    {
                        path: "change-password",
                        name: routeNameConstant.CHANGE_PASSWORD,
                        component: () => import("../views/employees/ChangePassword.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_PERSONAL_INFO},
                            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.CHANGE_PASSWORD,
                        }
                    },
                ]
            },
            {
                path: "salary",
                component: () => import("../components/subSideBar/SubSidebarEmployee.vue"),
                children: [
                    {
                        path: "view",
                        name: routeNameConstant.VIEW_OWN_SALARY,
                        component: () => import("../views/salary/View.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_OWN_SALARY},
                            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.VIEW_OWN_SALARY,
                        }
                    },
                    {
                        path: ":employeeId/view",
                        name: routeNameConstant.EDIT_EMPLOYEE_SALARY,
                        component: () => import("../views/salary/edit.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.EDIT_EMPLOYEE_SALARY},
                            sidebarKey: SidebarKeyConstant.ME_INFORMATION,
                            sidebarKeySub: SidebarKeyConstant.EDIT_EMPLOYEE_SALARY,
                        }
                    }
                ]
            },
            {
                path: "payslip",
                component: () => import("../components/subSideBar/SubSidebarEmployee.vue"),
                children: [
                    {
                        path: "view",
                        name: routeNameConstant.VIEW_OWN_PAYSLIP,
                        component: () => import("../views/salary/Payslip.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_OWN_SALARY},
                            sidebarKey: SidebarKeyConstant.VIEW_OWN_PAYSLIP,
                            sidebarKeySub: SidebarKeyConstant.VIEW_OWN_PAYSLIP,
                        }
                    },
                    {
                        path: ":employeeId/view",
                        name: routeNameConstant.VIEW_PAYSLIP,
                        component: () => import("../views/salary/Payslip.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.EDIT_EMPLOYEE_SALARY},
                            sidebarKey: SidebarKeyConstant.VIEW_PAYSLIP,
                            sidebarKeySub: SidebarKeyConstant.VIEW_PAYSLIP,
                        }
                    },
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
                    middleware: {function: abilities, abilities: permissionConstant.VIEW_ATTENDANCE_LIST},
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
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_CONFIG},
                            sidebarKey: SidebarKeyConstant.SETTING_GENERAL,
                            sidebarKeySub: SidebarKeyConstant.SETTING_GENERAL,
                        }
                    },
                    {
                        path: "worktime",
                        name: routeNameConstant.SETTING_WORK_TIME,
                        component: () => import("../views/setting/Work.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_CONFIG},
                            sidebarKey: SidebarKeyConstant.SETTING_GENERAL,
                            sidebarKeySub: SidebarKeyConstant.SETTING_WORK_TIME,
                        }
                    }
                ]
            },
            {
                path: "/requests",
                children: [
                    {
                        path: "",
                        name: routeNameConstant.REQUESTS,
                        component: () => import("../views/requests/List.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_REQUESTS},
                            sidebarKey: SidebarKeyConstant.REQUESTS,
                        }
                    },
                    {
                        path: "create",
                        name: routeNameConstant.REQUESTS_CREATE,
                        component: () => import("../views/requests/Create.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.CREATE_REQUESTS},
                            sidebarKey: SidebarKeyConstant.REQUESTS,
                        }
                    }
                ]
            },
            {
                path: "/salaries",
                children: [
                    {
                        path: "",
                        name: routeNameConstant.VIEW_EMPLOYEE_SALARY,
                        component: () => import("../views/salary/List.vue"),
                        meta: {
                            middleware: {function: abilities, abilities: permissionConstant.VIEW_EMPLOYEE_SALARY},
                            sidebarKey: SidebarKeyConstant.VIEW_EMPLOYEE_SALARY,
                        }
                    }
                ]
            }
        ]
    }
];

export default admin;
