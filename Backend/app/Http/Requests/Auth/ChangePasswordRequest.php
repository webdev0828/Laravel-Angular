<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class ChangePasswordRequest extends Request
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
        
        $rules = array(
            'password'          => 'required|min:4',
            'new_password'              => 'required|min:4|different:password',
            'password_confirmation' => 'required|same:new_password'
            );
        return $rules;
//  return [
        //     'password'          => 'required|min:4',
        //     'new_password'              => 'min:4|confirmed|different:password',
        //     'password_confirmation' => 'required_with:new_password|min:4'
        //     // 'password' => 'required',
        //     // 'confirm_password' => 'required|same:password',
        // ];
    }
}
