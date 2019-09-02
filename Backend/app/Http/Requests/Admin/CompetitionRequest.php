<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CompetitionRequest extends Request
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
        $id = $this->input('id');
        $rules = [];
        if(!$id) {
            $rules = [
                // 'name'              => 'required',
                // 'track_name'        => 'required',
                'start_date'        => 'required',
                'end_date'          => 'required',
                'price_1'           => 'required',
                'price_2'           => 'required',
                'price_3'           => 'required',
                'announcement_date' => 'required',
                // 'original_track'    => 'required',
                'stem_file'         => 'required'
            ];
        } else{
            $rules = [
                // 'name'              => 'required',
                // 'track_name'        => 'required',
                'start_date'        => 'required',
                'end_date'          => 'required',
                'price_1'           => 'required',
                'price_2'           => 'required',
                'price_3'           => 'required',
                'announcement_date' => 'required'
            ];
        }
        return $rules;
    }
}
