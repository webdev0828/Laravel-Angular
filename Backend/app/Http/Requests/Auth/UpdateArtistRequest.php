<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class UpdateArtistRequest extends Request
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

        $password = $this->input('password');
        
        if($password) {

            return [ 
            'name'       => 'required',
            'first_name'       => 'required',
            'last_name'      => 'required',
            'country'       => 'required',
         //   'city'       => 'required',
            'password' => 'required|min:4|confirmed',
            // 'soundcloudLink'       => 'required',
            // 'facebookLink'       => 'required',
            // 'youtubeLink'       => 'required',
            // 'twitterLink'       => 'required',
            // 'instagramLink'       => 'required',
            ];
        }

        else {
            return [
                'name'       => 'required',
                'first_name'       => 'required',
                'last_name'      => 'required',
                'country'       => 'required',
              //  'city'       => 'required',
                // 'soundcloudLink'       => 'required',
                // 'facebookLink'       => 'required',
                // 'youtubeLink'       => 'required',
                // 'twitterLink'       => 'required',
                // 'instagramLink'       => 'required',
            ];
        }

    }
}
