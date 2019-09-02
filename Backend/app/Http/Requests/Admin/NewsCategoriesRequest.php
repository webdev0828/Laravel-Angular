<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class NewsCategoriesRequest extends Request
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
        // echo '<pre>'; print_r($id); die; 
        $rules = [];
        if(!$id) {
            $rules = [
                        'name' => 'required|unique:news_categories,name'
                    ];
        } else{
            $rules = [
                        'name' => 'required|unique:news_categories,name,'.$id,
                    ];
        }
        return $rules;
    }
}
