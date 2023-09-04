<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subscription;

use App\Models\School;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{

  protected $stripe;

  public function __construct()
    {
        parent::__construct();
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
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
    try{
      $authUser = $request->user();
      if (array_key_exists('password', $data) && empty($data['password'])) {
        unset($data['password']);
      }
      if (!$authUser->update($data)) {
        return back()->withInput($request->all())->with('error', __('Profile failed to update'));
      }
        $userData = [
            'email' => $data['email'],
        ];
        if ($authUser->person_type == 'App\Models\Teacher') {
            if ($authUser->isSchoolAdmin() || $authUser->isTeacherSchoolAdmin() || $authUser->isTeacherAdmin() ) {
                School::where('id', $authUser->selectedSchoolId())->update($userData);
            }
            Teacher::where('id', $authUser->person_id)->update($userData);
        }
        if ($authUser->person_type == 'App\Models\Student') {
            Student::where('id', $authUser->person_id)->update($userData);
        }

      return back()->withInput($request->all())->with('success', __('Profile updated successfully!'));
    } catch (\Exception $e) {
      //return error message
      return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));

    }

  }




  /**
  * AJAX Action Update the specified resource in storage.
  *
  * @param  \App\Http\Requests\ProfilePhotoUpdateRequest  $request
  * @return \Illuminate\Http\Response
  */
  public function profilePhotoUpdate(ProfilePhotoUpdateRequest $request)
  {
    $data = $request->all();
    $result = array(
      'status' => 0,
      "file_id" => '0',
      "image_file" => '',
      'message' => __('failed to change image'),
    );
    try{
      $authUser = $request->user();
      if($request->file('profile_image_file'))
      {

        $image = $request->file('profile_image_file');
        $mime_type = $image->getMimeType();
        $extension = $image->getClientOriginalExtension();
        if($image->getSize()>0)
        {
          list($path, $imageNewName) = $this->__processImg($image,'UserImage',$authUser);

          if (!empty($path)) {
            $fileData = [
              'visibility' => 1,
              'file_type' =>'image',
              'title' => $authUser->username,
              'path_name' =>$path,
              'file_name' => $imageNewName,
              'extension'=>$extension,
              'mime_type'=>$mime_type
            ];

            $attachedImage = AttachedFile::create($fileData);

            $data['profile_image_id'] = $attachedImage->id;

          }
        }
      }

      if ($authUser->update($data)) {
        $result = array(
          "status"     => 1,
          "file_id" => $authUser->profile_image_id,
          "image_file" => $path,
          'message' => __('Successfully Changed Profile image')
        );
      }

    } catch (\Exception $e) {
      //return error message
      $result['message'] = __('Internal server error');
    }
    return response()->json($result);
  }


  /**
   *  AJAX action image delete and unlink
   *
   * @return json
   * @author Mamun <lemonpstu09@gmail.com>
   * @version 0.1 written in 2022-03-10
   */
  public function profilePhotoDelete(Request $request)
  {
    $authUser = $request->user();
    $result = array(
      'status' => 'failed',
      'message' => __('failed to remove image'),
    );
    try{
      $path_name =  $request->user()->profileImage->path_name;
      $file = str_replace(URL::to('').'/uploads/','',$path_name);

      $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
      if(file_exists($storagePath.$file)) unlink($storagePath.$file);
      AttachedFile::find($authUser->profileImage->id)->delete();
      $data['profile_image_id'] =null;
      if ($authUser->update($data)) {
        $result = array(
          "status"     => 'success',
          'message' => __('Successfully Changed Profile image')
        );
      }
    }
    catch (\Exception $e) {
      //return error message
      $result['message'] = __('Internal server error');
    }
    return response()->json($result);
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
    $response = [];
    $params = $request->all();

    $user = auth()->user();
    $subscription = null;
    $invoice_url = '';
    $product_object = null;
    $subscriber = null;
    if($user->stripe_id){
        $subscription_info = $this->stripe->subscriptions->all(['customer' => $user->stripe_id])->toArray();
        if(!empty($subscription_info['data'])){
            $subscription = $subscription_info['data'][0];
            $subscriber = (object) $subscription_info['data'][0];
            $product_object = $this->stripe->products->retrieve(
                $subscription['plan']['product'],
                []
            );
            $invoice_url = $this->stripe->invoices->retrieve(
              $subscriber->latest_invoice,
              []
            );
        }

        $invoices = $this->stripe->invoices->all(
        ['customer' => $user->stripe_id],
        );

    }

    return view('pages.profile.index', compact('subscription', 'product_object', 'subscriber', 'invoice_url', 'user', 'invoices'));
}

}
