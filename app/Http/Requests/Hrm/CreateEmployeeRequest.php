<?php

namespace App\Http\Requests\Hrm;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueInDatabase;
use App\Rules\ExistsInDatabase;

class CreateEmployeeRequest extends FormRequest
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
            'first_name' => 'required|string|min:1|max:100',
            'last_name' => 'required|string|min:1|max:100',
            'personal_email' => 'nullable|email|max:255',
            'phone' => 'required|string|min:1|max:20',
            'birthday' => 'required|date|before_or_equal:today',
            'gender' => 'nullable|in:1,2,3',
            'marital_status' => 'nullable|integer',
            'status' => 'required|integer',
            'business_email' => 'nullable|email|max:255',
            'password' => [
                'required',
                'string',
                'min:10',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/',
            ],
            // Unique fields using the 'mysql' connection
            'code' => ['nullable', 'string','min:1', 'max:11', new UniqueInDatabase('employees', 'code', 'mysql')],
            'fingerprint_code' => ['nullable', 'string', 'max:100', new UniqueInDatabase('employees', 'fingerprint_code', 'mysql')],
            'skype_id' => ['nullable', 'string', 'max:100', new UniqueInDatabase('employees', 'skype_id', 'mysql')],
//            'user_id' => ['nullable', 'integer', new UniqueInDatabase('employees', 'user_id', 'mysql')],
            'old_identity_card_number' => ['nullable', 'string', 'max:20', new UniqueInDatabase('employees', 'old_identity_card_number', 'mysql')],
            'identity_card_number' => ['required', 'string', 'max:20', new UniqueInDatabase('employees', 'identity_card_number', 'mysql')],
            'tax_code' => ['nullable', 'string', 'max:20', new UniqueInDatabase('employees', 'tax_code', 'mysql')],
            'social_insurance_number' => ['nullable', 'string', 'max:20', new UniqueInDatabase('employees', 'social_insurance_number', 'mysql')],
            'bank_account_number' => ['nullable', 'string', 'max:20', new UniqueInDatabase('employees', 'bank_account_number', 'mysql')],

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'old_identity_card_issue_date' => 'nullable|date|before_or_equal:today',
            'old_identity_card_place' => 'nullable|string|max:255',
            'identity_card_issue_date' => 'required|date|before_or_equal:today',
            'identity_card_place' => 'required|string|max:255',
            'place_origin' => 'nullable',
            'nationality' => 'nullable|integer',
            'residential_address' => 'nullable|string',
            'current_country_id' => 'required|integer',
            'current_province_id' => 'required|integer',
            'current_district_id' => 'required|integer',
            'current_address' => 'nullable|string',
            'bank_id' => ['nullable', 'integer', new ExistsInDatabase('banks', 'id', 'mysql')],
            'bank_account_name' => 'nullable|string|max:100',
            'position_id' => ['required', 'integer', new ExistsInDatabase('positions', 'id', 'mysql')],
            'department_id' => ['required', 'integer', new ExistsInDatabase('departments', 'id', 'mysql')],
            'level' => 'nullable|integer',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Họ là trường bắt buộc.',
            'first_name.string' => 'Họ phải là chuỗi ký tự.',
            'first_name.max' => 'Họ không được vượt quá 100 ký tự.',
            'last_name.required' => 'Tên là trường bắt buộc.',
            'last_name.string' => 'Tên phải là chuỗi ký tự.',
            'last_name.max' => 'Tên không được vượt quá 100 ký tự.',
            'personal_email.email' => 'Email không hợp lệ.',
            'phone.required' => 'Số điện thoại là trường bắt buộc.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'birthday.required' => 'Ngày sinh là trường bắt buộc.',
            'birthday.date' => 'Ngày sinh phải là định dạng ngày hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'status.required' => 'Trạng thái là trường bắt buộc.',
            'code.required' => 'Mã nhân viên là trường bắt buộc.',
            'code.max' => 'Mã nhân viên không được vượt quá 50 ký tự.',
            'identity_card_place.required' => 'Nơi cấp CCCD là trường bắt buộc.',
            'fingerprint_code.unique' => 'Mã vân tay đã tồn tại.',
            'skype_id.unique' => 'ID Skype đã tồn tại.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'identity_card_issue_date.required' => 'Ngày cấp CCCD là trường bắt buộc.',
            'identity_card_issue_date.date' => 'Ngày cấp CCCD phải là định dạng ngày hợp lệ.',
            'user_id.unique' => 'CRM User ID đã tồn tại.',
            'old_identity_card_number.unique' => 'Số CMT cũ đã tồn tại.',
            'identity_card_number.unique' => 'Số CCCD đã tồn tại.',
            'identity_card_number.required' => 'Số CCCD là trường bắt buộc.',
            'tax_code.unique' => 'Mã số thuế đã tồn tại.',
            'social_insurance_number.unique' => 'Số bảo hiểm xã hội đã tồn tại.',
            'current_country_id.required' => 'Quốc gia hiện tại là trường bắt buộc.',
            'current_province_id.required' => 'Tỉnh/thành phố hiện tại là trường bắt buộc.',
            'current_district_id.required' => 'Quận/huyện hiện tại là trường bắt buộc.',
            'position_id.required' => 'Chức danh là trường bắt buộc.',
            'position_id.exists' => 'Chức danh không tồn tại.',
            'department_id.required' => 'Phòng ban là trường bắt buộc.',
            'department_id.exists' => 'Phòng ban không tồn tại.',
            'level.integer' => 'Trình độ phải là số nguyên.',
            'bank_id.exists' => 'Ngân hàng không tồn tại.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ cái viết hoa, một số và một ký tự đặc biệt.',
        ];
    }
}
