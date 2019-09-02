<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class ResetPasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'reset_code' => 'required|exists:password_resets,token',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'reset_code.exists' => 'Reset code is invalid.'
        ];
    }
}
