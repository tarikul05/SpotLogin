<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePhotoUpdateRequest extends FormRequest
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
			'profile_image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
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
			'profile_image_file.required' => __('image required'),
			'profile_image_file.image' => __('Please upload image file'),
			'profile_image_file.mimes' => __('jpeg,png,jpg,gif,svg needed'),
			'profile_image_file.max' => __('Maximum 20mb image file will support'),
		];
	}
}
