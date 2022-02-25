<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermCondRequest extends FormRequest
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
        'language_id' => 'required',
        'tc_text' => 'required',
        'spp_text' => 'required',
      ];
    }

    public function messages()
    {
        return[
            
            'tc_text.required' => __('tc_text required'),
            'language_id.required' => __('language_id required'),
            'spp_text.required' => __('spp_text required'),
           
           
        ];
    }
}
