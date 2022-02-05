<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            
            'login_username' => 'required',
            'login_password' => 'required|string|min:4|max:255'
        ];
    }

    public function messages()
    {
        return[
            
            'login_username.required' => _('username required'),
            'login_password.required' => _('password required'),
            'login_password.min' => _('pinimum 4 character required')
           
           
        ];
    }
}
