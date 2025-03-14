<?php

return [
    'roles.index' => [
        'IN' => [
            'VIEW_ROLE_LIST'
        ]
    ],
    'roles.store' => [
        'IN' => [
            'CREATE_ROLE'
        ]
    ],
    'roles.show' => [
        'IN' => [
            'ASSIGN_ROLE'
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
            'VIEW_ROLE_LIST'
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
    'config.store' => [
        'IN' => [
            'EDIT_CONFIG'
        ]
    ],
    'config.index' => [
        'IN' => [
            'VIEW_CONFIG'
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
            [
                'code' => 'UPLOAD_ROLE',
                'name' => 'Tải lên danh sách vai trò',
                'description' => 'Cho phép tải lên danh sách vai trò',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_ROLE',
                'name' => 'Tải xuống danh sách vai trò',
                'description' => 'Cho phép tải xuống danh sách vai trò',
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
            [
                'code' => 'UPLOAD_EMPLOYEE',
                'name' => 'Tải lên danh sách nhân sự',
                'description' => 'Cho phép tải lên danh sách nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_EMPLOYEE',
                'name' => 'Tải xuống danh sách nhân sự',
                'description' => 'Cho phép tải xuống danh sách nhân sự',
                'is_active' => 1
            ],
        ],
        'HDLDT' => [
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
            [
                'code' => 'UPLOAD_CONTRACT',
                'name' => 'Tải lên hợp đồng',
                'description' => 'Cho phép tải lên hợp đồng lao động',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_CONTRACT',
                'name' => 'Tải xuống hợp đồng',
                'description' => 'Cho phép tải xuống danh sách hợp đồng',
                'is_active' => 1
            ],
        ],
        'CONFIG' => [
            [
                'code' => 'VIEW_CONFIG',
                'name' => 'Xem cấu hình hệ thống',
                'description' => 'Cho phép truy cập và xem các thiết lập cấu hình hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'EDIT_CONFIG',
                'name' => 'Chỉnh sửa cấu hình hệ thống',
                'description' => 'Cho phép thay đổi các thiết lập cấu hình hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'UPLOAD_CONFIG',
                'name' => 'Tải lên cấu hình hệ thống',
                'description' => 'Cho phép tải lên các file cấu hình hệ thống',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_CONFIG',
                'name' => 'Tải xuống cấu hình hệ thống',
                'description' => 'Cho phép tải xuống dữ liệu cấu hình hệ thống',
                'is_active' => 1
            ],
        ],
        'LEAVE_REQUEST' => [
            [
                'code' => 'VIEW_LEAVE_REQUESTS',
                'name' => 'Xem danh sách đơn nghỉ',
                'description' => 'Hiển thị danh sách đơn nghỉ của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'APPROVE_LEAVE_REQUEST',
                'name' => 'Duyệt đơn nghỉ',
                'description' => 'Cho phép phê duyệt đơn nghỉ của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'REJECT_LEAVE_REQUEST',
                'name' => 'Từ chối đơn nghỉ',
                'description' => 'Cho phép từ chối đơn nghỉ của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'UPLOAD_LEAVE_REQUEST',
                'name' => 'Tải lên đơn nghỉ',
                'description' => 'Cho phép tải lên dữ liệu đơn nghỉ của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_LEAVE_REQUEST',
                'name' => 'Tải xuống đơn nghỉ',
                'description' => 'Cho phép tải xuống danh sách đơn nghỉ của nhân sự',
                'is_active' => 1
            ],
        ],
        'ATTENDANCE' => [
            [
                'code' => 'VIEW_ATTENDANCE_LIST',
                'name' => 'Xem danh sách chấm công',
                'description' => 'Hiển thị danh sách chấm công của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'EDIT_ATTENDANCE',
                'name' => 'Chỉnh sửa chấm công',
                'description' => 'Cho phép chỉnh sửa dữ liệu chấm công của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'APPROVE_ATTENDANCE',
                'name' => 'Duyệt dữ liệu chấm công',
                'description' => 'Cho phép duyệt bảng công của nhân sự',
                'is_active' => 1
            ],
            [
                'code' => 'UPLOAD_ATTENDANCE',
                'name' => 'Tải lên dữ liệu chấm công',
                'description' => 'Cho phép tải lên file chấm công',
                'is_active' => 1
            ],
            [
                'code' => 'DOWNLOAD_ATTENDANCE',
                'name' => 'Tải xuống dữ liệu chấm công',
                'description' => 'Cho phép tải xuống danh sách chấm công của nhân sự',
                'is_active' => 1
            ],
        ],
    ],
];
?>
