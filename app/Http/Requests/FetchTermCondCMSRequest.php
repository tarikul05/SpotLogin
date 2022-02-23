<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchTermCondCMSRequest extends FormRequest
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
            'language_id' => 'required'
        ];
    }

    public function messages()
    {
        return[
            
            'template_code.required' => _('template_code required'),
            'language_id.required' => _('language_id required')
           
           
        ];
    }
}
