<?php

namespace App\Http\Requests\Hrm;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistsInDatabase;

class TimeSheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'employeeId'  => ['required', 'integer', new ExistsInDatabase('employees', 'id')],
            'username'    => ['required', 'string'],
            'attendances' => ['required', 'boolean'],
            'time'        => ['required', 'date_format:H:i:s'], // Kiểm tra định dạng HH:mm:ss
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'employeeId.required' => 'Mã nhân viên là bắt buộc.',
            'employeeId.integer' => 'Mã nhân viên phải là số nguyên.',
            'attendances.required' => 'Trường chấm công là bắt buộc.',
            'attendances.boolean' => 'Trường chấm công chỉ nhận giá trị true hoặc false.',
        ];
    }
}
