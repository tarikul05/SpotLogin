<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
			'firstname' => 'filled|string',
			'email' => 'filled|string|email|max:255',
			'password' => [
								'nullable',
								'string',
								'min:8',             // must be at least 8 characters in length
								'regex:/[a-z]/',      // must contain at least one lowercase letter
								'regex:/[A-Z]/',      // must contain at least one uppercase letter
								'regex:/[0-9]/',      // must contain at least one digit
								'regex:/[@$!%*#?&]/', // must contain a special character
						],
		];
	}
	
	/**
	* Get the error messages for the defined validation rules.
	*
	* @return array
	*/
	public function messages()
	{
		return [
			'firstname.filled' => __('firstname required'),
			'email.filled' => __('email required'),
			'email.email' => __('email format wrong'),
			'email.max' => __('max 255 character will support'),
			'password.min' => __('password must be minimum 8 character'),
			'password.regex' => _('The password format is invalid.')
		];
	}
}
