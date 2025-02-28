<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name' => 'required|min:1|max:200',
            'username' => 'required|unique:users|min:1|max:200',
            'password' => 'required|min:6|max:25|confirmed',
            'password_confirmation' => 'required|min:6|max:25',
            'gtc_extension' => 'nullable|numeric|digits_between:1,11',
            'email' => 'min:0|max:200',
            'department' => 'required'
        ];
    }
}
