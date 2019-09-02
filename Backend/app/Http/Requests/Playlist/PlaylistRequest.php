<?php

namespace App\Http\Requests\Playlist;

use App\Http\Requests\Request;

class PlaylistRequest extends Request
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
        if(\Auth::check()) {
            $this->user = \Auth::getUser();
            $userId = $this->user->id;
        }
        
        $id = $this->input('id');
        $rules = [];
        if(!$id) {
            $rules = [
                'playlist_name' => 'unique:playlists,name,NULL,id,user_id,'.$userId
            ];
        } else{
            $rules = [
                'name' => 'required|unique:playlists,name,'.$id.',id,user_id,'.$userId
            ];
        }
        return $rules;
    }
}
