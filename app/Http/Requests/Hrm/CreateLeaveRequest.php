<?php

namespace App\Http\Requests\Hrm;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => ['required', 'string', function ($attribute, $value, $fail) {
                $leaveType = $this->input('leave_type');

                $dateRegex = '\d{2}/\d{2}/\d{4}'; // Định dạng ngày dd/mm/yyyy
                $numberRegex = '\d+(\.\d+)?'; // Định dạng số như 0.5, 1, 30

                $patterns = [
                    1 => "#^Nghỉ $numberRegex công ngày $dateRegex$#",
                    3 => "#^Quên chấm công ngày $dateRegex$#",
                    4 => "#^$dateRegex - $dateRegex$#",
                    5 => "#^$numberRegex phút$#",
                ];

                // Debug lại xem giá trị truyền vào có đúng không

                if (isset($patterns[$leaveType]) && !preg_match($patterns[$leaveType], $value)) {
                    $fail("Trường $attribute không đúng định dạng với leave_type = $leaveType.");
                }
            }],
            'date' => ['required', 'date_format:Y-m-d'],
            'leave_type' => ['required', 'integer', 'in:1,3,4,5'],
        ];
    }




    public function messages()
    {
        return [
            'content.required' => 'Nội dung không được để trống.',
            'content.string' => 'Nội dung phải là chuỗi.',
            'date.required' => 'Ngày không được để trống.',
            'date.date_format' => 'Ngày phải đúng định dạng YYYY-MM-DD.',
            'leave_type.required' => 'Loại nghỉ không được để trống.',
            'leave_type.in' => 'Loại nghỉ không hợp lệ.',
        ];
    }
}
