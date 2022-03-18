<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'email' => 'filled|string|email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ];
    }

    public function messages()
    {
        return[
            'email.filled' => __('email required'),
			'email.email' => __('email format wrong'),
			'email.max' => __('max 255 character will support'),
			'password.min' => __('password must be minimum 8 character'),
            'username.required' => _('username required'),
            'password.required' => _('password required'),
            'password.regex' => _('The password format is invalid.')
           
           
        ];
    }
}
