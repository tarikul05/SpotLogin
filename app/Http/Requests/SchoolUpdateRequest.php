<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolUpdateRequest extends FormRequest
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
        'school_name' => 'required',
        'contact_firstname' => 'required',
        'contact_lastname' => 'required'
      ];
    }

    public function messages()
    {
        return[
            
            'school_name.required' => __('School name required'),
            'contact_firstname.required' => __('contact_firstname required'),
            'contact_lastname.required' => __('contact_lastname required')
           
           
        ];
    }
}
