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
			'username' => 'filled|string',
			'email' => 'filled|string|email|max:255',
			'password' => 'nullable|min:8'
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
			'username.filled' => __('name.required'),
			'email.filled' => __('email.required'),
			'email.string' => __('email.string'),
			'email.email' => __('email.email'),
			'email.max' => __('email.max'),
			'password.min' => __('messages.password.min'),
		];
	}
}
