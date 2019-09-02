<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RegistrationRequest extends Request
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
        $provider = $this->input('provider');
        if($provider){
            return [
                'name'       => 'required',
                'user_type'  => 'required',
                'email'      => 'required|email|unique:users',
            ];
        }
        else{
            return [
                'name'       => 'required',
                'user_type'  => 'required',
                'email'      => 'required|email|unique:users',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ];
        }
    }
}
