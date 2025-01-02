<?php

return [
    'roles.index' => [
        'IN' => [
            'VIEW_ROLE'
        ]
    ],
    'roles.store' => [
        'IN' => [
            'CREATE_ROLE'
        ]
    ],
    'roles.show' => [
        'IN' => [
            'VIEW_ROLE_DETAIL'
        ]
    ],
    'roles.update' => [
        'IN' => [
            'EDIT_ROLE'
        ]
    ],
    'roles.active' => [
        'IN' => [
            'EDIT_ROLE'
        ]
    ],
    'roles.deactivate' => [
        'IN' => [
            'EDIT_ROLE'
        ]
    ],
    'roles.module-group-permission' => [
        'IN' => [
            'CREATE_ROLE',
            'EDIT_ROLE'
        ]
    ],

    'permissions.index' => [
        'IN' => [
            'VIEW_ROLE'
        ]
    ],

    'users.index' => [
        'AND' => [
            'user-view'
        ]
    ],
    'users.store' => [
        'IN' => [
            'user-create'
        ]
    ],
    'users.show' => [
        'IN' => [
            'user-view'
        ]
    ],
    'users.update' => [
        'IN' => [
            'user-update'
        ]
    ],

    'employees.index' => [
        'IN' => [
            'VIEW_EMPLOYEE_LIST'
        ]
    ],
    'employees.store' => [
        'IN' => [
            'CREATE_EMPLOYEE'
        ]
    ],
    'employees.update' => [
        'IN' => [
            'VIEW_EMPLOYEE_LIST'
        ]
    ],
    'employee.detail' => [
        'IN' => [
            'VIEW_EMPLOYEE_DETAIL'
        ]
    ],

    'employee.contract.list' => [
        'IN' => [
            'VIEW_CONTRACT_LIST'
        ]
    ],
    'contract.store' => [
        'IN' => [
            'CREATE_CONTRACT'
        ]
    ],
    'contract.update' => [
        'IN' => [
            'EDIT_CONTRACT'
        ]
    ],
    'contract.detail' => [
        'IN' => [
            'VIEW_CONTRACT_DETAIL'
        ]
    ],

    'employee.me.detail' => [
        'IN' => [
            'VIEW_PERSONAL_INFO'
        ]
    ],
    'employee.me.contract.list' => [
        'IN' => [
            'VIEW_PERSONAL_INFO'
        ]
    ],
];
?>
