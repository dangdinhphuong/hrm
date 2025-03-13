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
    'employee.timesheets' => [
        'IN' => [
            'VIEW_TIMESHEETS'
        ]
    ],

    'data' => [
        'ROLES' => [
            [
                'code' => 'VIEW_ROLE_LIST',
                'name' => 'Xem danh sách vai trò',
                'description' => 'Hiển thị danh sách các vai trò trong hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'CREATE_ROLE',
                'name' => 'Tạo mới vai trò',
                'description' => 'Cho phép tạo mới vai trò trong hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'EDIT_ROLE',
                'name' => 'Chỉnh sửa vai trò',
                'description' => 'Cho phép chỉnh sửa thông tin của vai trò',
                'is_active' => 1
            ],
            [
                'code' => 'DELETE_ROLE',
                'name' => 'Xóa vai trò',
                'description' => 'Cho phép xóa một vai trò khỏi hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'ASSIGN_ROLE',
                'name' => 'Gán vai trò cho nhân sự',
                'description' => 'Cho phép gán vai trò cụ thể cho nhân sự trong hệ thống',
                'is_active' => 1
            ],
        ],
        'DSNS' => [
            [
                'code' => 'VIEW_EMPLOYEE_LIST',
                'name' => 'Xem danh sách nhân sự',
                'description' => 'Hiển thị tab Danh sách nhân sự Truy cập vào màn hình danh sách nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'CREATE_EMPLOYEE',
                'name' => 'Tạo mới nhân sự',
                'description' => 'Hiển thị button Tạo mới nhân sự & cho phép truy cập vào màn hình tạo mới nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'VIEW_EMPLOYEE_DETAIL',
                'name' => 'Xem chi tiết nhân sự',
                'description' => 'Hiển thị button "View" trong cột Hành động và cho phép xem chi tiết thông tin của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'EDIT_EMPLOYEE_DETAIL',
                'name' => 'Sửa chi tiết nhân sự',
                'description' => 'Hiển thị button Edit trong cột Hành động và cho phép sửa thông tin của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'VIEW_PERSONAL_INFO',
                'name' => 'Xem thông tin cá nhân',
                'description' => 'Cho phép xem thông tin cá nhân của nhân sự',
                'is_active' => 1
            ],
        ],
        'HDLDTEST' => [
            [
                'code' => 'VIEW_CONTRACT_LIST',
                'name' => 'Xem danh sách hợp đồng',
                'description' => 'Hiển thị danh sách hợp đồng hiện có',
                'is_active' => 1
            ],
            [
                'code' => 'CREATE_CONTRACT',
                'name' => 'Tạo mới hợp đồng',
                'description' => 'Cho phép tạo mới hợp đồng lao động',
                'is_active' => 1
            ],
            [
                'code' => 'VIEW_CONTRACT_DETAIL',
                'name' => 'Xem chi tiết hợp đồng',
                'description' => 'Cho phép xem thông tin chi tiết của hợp đồng',
                'is_active' => 1
            ],
            [
                'code' => 'EDIT_CONTRACT',
                'name' => 'Sửa hợp đồng',
                'description' => 'Cho phép chỉnh sửa thông tin hợp đồng hiện có',
                'is_active' => 1
            ],
        ],
    ],

];
?>
