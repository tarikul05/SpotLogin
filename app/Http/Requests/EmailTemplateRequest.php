<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
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
        'template_code' => 'required',
        'subject_text' => 'required',
        'body_text' => 'required',
        'language_id' => 'required',
      ];
    }

    public function messages()
    {
        return[
            
            'template_code.required' => __('template_code required'),
            'language_id.required' => __('language_id required'),
            'body_text.required' => __('body_text required'),
            'subject_text.required' => __('subject_text required')
           
           
        ];
    }
}
