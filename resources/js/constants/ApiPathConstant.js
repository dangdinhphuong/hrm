const API_PATH = {
    LOGIN: "login",
    LOGOUT: "logout",
    ME_INFO: "me",

    ALL_ROLE: 'all/roles',

    ROLE_LIST: 'roles',
    ROLE_CREATE: 'roles',
    ROLE_DETAIL: 'roles/{id}',
    ROLE_UPDATE: 'roles/{id}',
    ROLE_ACTIVE: 'roles/{id}/active',
    ROLE_DEACTIVATE: 'roles/{id}/deactivate',
    MODULE_GROUP_PERMISSION_LIST: 'roles/module-group-permission',

    PERMISSION_LIST: 'permissions',

    ALL_USER: 'all/users',
    USER_LIST: 'users',
    USER_FIND: 'find/users',
    USER_SALE: 'user/sale',
    USER_CREATE: 'users',
    USER_DETAIL: 'users/{id}',
    USER_UPDATE: 'users/{id}',

    ORDER_LIST: 'order',
    ORDER_STATUS: 'order/status',

    DELIVERY_NOTE: 'delivery-notes',
    DELIVERY_NOTE_CREATE: 'delivery-notes/create',
    DELIVERY_NOTE_DETAIL: 'delivery-notes/detail/{id}',
    DELIVERY_NOTE_UPDATE: 'delivery-notes/update/{id}',

    DISTRICT_LIST: 'district',

    GROUP_LIST: 'groups',

    PRODUCT_LIST: 'product',

    CATEGORY_LIST: 'category',

    CAMPAIGN_LIST: 'campaign',

    // hrm
    HRM_BANK_LIST: 'all/banks',
    HRM_DEPARTMENT_LIST: 'all/departments',
    HRM_POSITION_LIST: 'all/positions',
    HRM_JOB_TITLE: 'all/job-titles',

    HRM_EMPLOYEE_CREATE: 'employees',
    HRM_EMPLOYEE_EDIT: 'employees/{id}',
    HRM_EMPLOYEE_LIST: 'employees',
    HRM_EMPLOYEE_TIMESHEETS_LIST: 'employee/timesheets',
    HRM_EMPLOYEE_DETAIL_BY_USER_ID: 'employee/{userId}/detail',
    HRM_MY_EMPLOYEE_DETAIL: 'employee/me/detail',
    HRM_EMPLOYEE_AVATAR_UPLOAD: 'employee/{employeeId}/avatar/upload',
    HRM_EMPLOYEE_AVATAR: 'employee/{employeeId}/avatar',

    HRM_CONTRACT_CREATE: 'contract',
    HRM_CONTRACT_EDIT: 'contract/{id}',
    HRM_MY_CONTRACT_LIST: 'employee/me/contract',
    HRM_CONTRACT_LIST: 'employee/{employeesId}/contract',
    HRM_CONTRACT_DETAIL: 'contract/{id}',

    HRM_COUNTRIES: 'all/countries'

};

export default API_PATH;
