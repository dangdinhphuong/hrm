<?php

namespace App\Http\Requests\Hrm;

use App\Rules\UniqueInDatabase;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FileValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateContractRequest extends FormRequest
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
            'contract_number' => ['required', 'string', 'max:50', new UniqueInDatabase('contracts', 'contract_number', 'mysql')],
            'contract_type' => 'required|integer',
            'status' => 'required|integer',
            'start_time' => 'required|date|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'file.*' => ['nullable', new FileValidationRule(7 * 1024, 2 * 1024)], // 7MB total size, 2MB per file
        ];
    }
    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'contract_number.required' => 'Số hợp đồng là bắt buộc.',
            'contract_number.max' => 'Số hợp đồng không được vượt quá 50 ký tự.',
            'contract_type.required' => 'Loại hợp đồng là bắt buộc.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'start_time.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_time.before' => 'Ngày bắt đầu phải trước ngày kết thúc.',
            'end_time.required' => 'Ngày kết thúc là bắt buộc.',
            'end_time.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'file.*.required' => 'File là bắt buộc.',
            'file.*.max' => 'Dung lượng file không được vượt quá 5MB.',
            'file.*.mimes' => 'Chỉ chấp nhận các định dạng file: pdf, doc, docx, xls, xlsx, txt, jpg, jpeg, png.',
        ];
    }
}
