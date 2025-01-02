<?php

namespace App\Http\Requests\System;

use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserCreateRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'username' => Rule::unique('users')->ignore($this->route('user')),
            'password' => 'min:6|max:25|confirmed',
            'password_confirmation' => 'min:6|max:25',
        ]);
    }

}
