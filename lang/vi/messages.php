<?php
return [
    'order' => [
        'create' => [
            'user_not_found' => 'Không tìm thấy người dùng',
            'department_id_not_found' => 'Không tìm thấy department id',
            'campaign_not_found' => 'Không tìm thấy campaign',
            'product_not_found' => 'Không tìm thấy product',
            'create_success' => "Tạo đơn thành công",
            'create_error' => "Tạo đơn hàng thất bại ",
        ],
        'delivery_note' => [
            'create_success' => "Tạo phiếu giao hàng thành công",
            'create_error' => "Tạo phiếu giao hàng  thất bại ",
            'update_success' => "Cập nhât phiếu giao hàng thành công",
            'update_error' => "Cập nhât phiếu giao hàng  thất bại ",
        ],
        'payment_status'=>[
            "unpaid" => 'Chưa thanh toán',
            "paid" => 'Đã thanh toán',
            "are_being_paid" => 'Đang thanh toán'
        ]
    ],
    'hrm' => [
        'employees' => [
            'create_success' => "Tạo thông tin cá nhân thành công",
            'create_error' => "Tạo thông tin cá nhân  thất bại ",
            'update_success' => "Cập nhât thông tin cá nhân thành công",
            'update_error' => "Cập nhât thông tin cá nhân  thất bại ",
            'avatar' => [
                'create_success' => "Tạo avatar thành công",
                'create_error' => "Tạo avatar thất bại ",
                'update_success' => "Cập nhât avatarthành công",
                'update_error' => "Cập nhât avatar thất bại ",
            ]
        ],
        'contract' => [
            'create_success' => "Tạo hợp đồng thành công",
            'create_error' => "Tạo hợp đồng  thất bại ",
            'update_success' => "Cập nhât hợp đồng thành công",
            'update_error' => "Cập nhât hợp đồng  thất bại ",
        ],
    ]
];
