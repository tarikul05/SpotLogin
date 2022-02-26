<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\ProfileUpdateRequest;


use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;



class ProfileController extends Controller
{

  public function __construct()
  {
    parent::__construct();
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\ProfileUpdateRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function profileUpdate(ProfileUpdateRequest $request)
  {
      $data = $request->all();
      $authUser = $request->user();
      if (!$authUser) {
        return back();
      }
      if ($request->isMethod('post')) {

        $rules = array(
          'name'   => 'filled|string|max:50',
          'avatar_image_path' => 'nullable',
        );    
        $messages = array(
          'name.filled' => trans('messages.name.required'),
          'name.max' => trans('messages.name.max')
          
        );
        if (array_key_exists('authority', $data)) {
            unset($data['authority']);
        }
        
        if (array_key_exists('email', $data)) {
            unset($data['email']);
        }

          
        if (array_key_exists('current_password', $data)) {
            if(!Hash::check($data['current_password'], $authUser->password)){
                throw new HttpResponseException(response()->error(trans('messages.password.current'), Response::HTTP_BAD_REQUEST));
            }            
        }
        $validator = Validator::make( $data, $rules, $messages );

        if ( $validator->fails() ) 
        {
            
          Toastr::warning('Error occured',$validator->errors()->all()[0]);
          return redirect()->back()->withInput()->withErrors($validator);
        }
        else
        {

          if (!$authUser->update($data)) {
            return redirect()->back()->withInput()->withErrors(trans('messages.error_message'));
          }

          Toastr::success(trans('oauth.success_message'),'Success');
          return back();
        }
      }
      $rink_all = Rink::all()->pluck("name", "id")->sortBy("name");
      $experience_all = Experience::all()->pluck("name", "id")->sortBy("name");
      $certificate_all = Certificate::all()->pluck("name", "id")->sortBy("name");
      $language_all = Language::all()->pluck("name", "id")->sortBy("name");
      $price_all = Price::all()->pluck("name", "id")->sortBy("name");
      $speciality_all = Speciality::all()->pluck("name", "id")->sortBy("name");
      
    

      $response = [];

      
      if (!$authUser->isSuperAdmin()) { 
          $filterParams = [];
      }

      $breadcrumb = array(
        array(
           'name'=>trans('global.Profile'),
           'link'=>''
        )
      );

      return view('admin.profile.update', [
        'pageInfo'=>
         [
          'siteTitle'        =>'Manage Users',
          'pageHeading'      =>'Manage Users',
          'pageHeadingSlogan'=>'Here the section to manage all registered users'
          ]
          ,
          'data'=>
          [
             'user'      =>  $authUser,
             'breadcrumb' =>  $breadcrumb,
             'Title' =>  trans('global.Profile')
          ]
        ])->with(compact('rink_all','experience_all','speciality_all','language_all','price_all','certificate_all'));
  }



  /**
   * Display the specified resource.
   * 
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function userDetailUpdate(Request $request)
  {
    $user = $request->user();
    if (!$user) {
      return back();
    }

    $response = [];
    $params = $request->all();

    
    
    $response['data'] = $user;


    return view('pages.profile.index', [
      'pageInfo'=>
       [
        'siteTitle'        =>'Manage Users',
        'pageHeading'      =>'Manage Users',
        'pageHeadingSlogan'=>'Here the section to manage all registered users'
        ]
        ,
        'data'=>
        [
           'user'      =>  $user,
           'Title' =>  trans('global.Profile')
        ]
      ]);

  }

 

 
}
