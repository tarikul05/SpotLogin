<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'reset_password_pass' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                'same:reset_password_confirm_pass'
            ],
            'reset_password_confirm_pass' => 'filled',
        ];
    }

    public function messages()
    {
        return[
			      'reset_password_pass.min' => __('password must be minimum 8 character'),
            'reset_password_pass.required' => _('password required'),
            'reset_password_pass.regex' => _('The password format is invalid.'),
            'reset_password_pass.same' => __('The password not matched.')
           
        ];
    }
}
