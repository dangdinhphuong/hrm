<?php

namespace App\Http\Requests\Hrm;

use App\Rules\ExistsInDatabase;
use Illuminate\Foundation\Http\FormRequest;


class UpdateSalaryRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'employees_id'       => ['required', 'integer', new ExistsInDatabase('employees', 'id')],
            'basic_salary'       => ['required', 'numeric', 'min:3250000'],
            'kpi_salary'         => ['nullable', 'numeric', 'min:0'],
            'bonus'              => ['nullable', 'numeric', 'min:0'],
            'allowance_salary'   => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'employees_id.required' => 'Nhân viên là bắt buộc.',
            'employees_id.integer'  => 'ID nhân viên phải là số nguyên.',
            'basic_salary.required' => 'Lương cơ bản không được để trống.',
            'basic_salary.numeric'  => 'Lương cơ bản phải là số.',
            'basic_salary.min'      => 'Lương cơ bản không được nhỏ hơn 3.250.000 VNĐ',
            'kpi_salary.numeric'    => 'Lương KPI phải là số.',
            'kpi_salary.min'        => 'Lương KPI không được nhỏ hơn 0 VNĐ',
            'bonus.numeric'         => 'Thưởng phải là số.',
            'bonus.min'             => 'Thưởng không được nhỏ hơn 0 VNĐ',
            'allowance_salary.numeric' => 'Phụ cấp phải là số.',
            'allowance_salary.min'     => 'Phụ cấp không được nhỏ hơn 0 VNĐ',
        ];
    }

}
