<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Support\Facades\URL;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as InterventionImageManager;


class ProfileController extends Controller
{
  
  public function __construct()
  {
    parent::__construct();
    $this->img_config = [
      'target_path' => [
        'UserImage' => 'photo/user_photo'
      ],
      'target_url' => [
        'UserImage' => URL::to('').'/uploads/photo/user_photo/'
      ],
    ];
    
    // create the folder if it does not exist
    foreach ($this->img_config['target_path'] as $img_dir) {
      if (!is_dir($img_dir)) {
        if (!mkdir($img_dir, 0777, true)) {
          die('Failed to create folders...');
        }
      }
    }
    
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
      return back()->withInput($request->all())->with('success', __('Profile updated successfully!'));
    } catch (\Exception $e) {
      //return error message
      return redirect()->back()->withInput($request->all())->with('error', __('Internal server error'));
      
    }
    
  }
  
  public function __processImg($file,$type,$authUser,$shouldCrop=false,$shouldResize=false) {
    
    $imageNewName = 'user_'.$authUser->id.'_dp.'.$file->getClientOriginalExtension();
    $uploadedPath = $this->img_config['target_path'][$type];
    
    $filePath = $uploadedPath.'/'.date('Y/m/d') . '/'. $imageNewName;
    $fileContent = null;
    
    if ($shouldCrop) {
      $interventionImage = InterventionImageManager::make($file->getRealPath());
      
      if (!$width && !$height) {
        $height = $interventionImage->height();
        $width = $interventionImage->width();
        
        if ($height <= $width) {
          $width = $height;
        } else {
          $height = $width;
        }
      } else {
        if (!$width) {
          $width = $interventionImage->width();
        }
        
        if (!$height) {
          $height = $interventionImage->height();
        }
      }
      
      $croppedImage = $interventionImage->fit($width, $height);
      // $croppedImage = $interventionImage->fit($width, $height, function ($constraint) {
      //     $constraint->upsize();
      // });
      $croppedImageStream = $croppedImage->stream();
      
      $fileContent = $croppedImageStream->__toString();
    } else if($shouldResize){
      $interventionImage = InterventionImageManager::make($file->getRealPath());
      if (!isset($width) && !isset($height)) {
        $height = $interventionImage->height();
        $width = $interventionImage->width();
        
        if ($height <= $width) {
          $width = $height;
        } else {
          $height = $width;
        }
      } else {
        if (!isset($width)) {
          $width = $interventionImage->width();
        }
        
        if (!isset($height)) {
          $height = $interventionImage->height();
        }
      }
      $resizedImage = $interventionImage->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
      });
      $resizedImageStream = $resizedImage->stream();
      $fileContent = $resizedImageStream->__toString();
    } else {
      $fileContent = file_get_contents($file);
    }
    $result = Storage::disk('local')->put($filePath, $fileContent);
    if (!$result) {
      throw new HttpResponseException(response()->error('Image could not be uploaded', Response::HTTP_BAD_REQUEST));
    }
    return [$this->img_config['target_url'][$type] .date('Y/m/d') . '/'. $imageNewName, $imageNewName];
  }
  
  /**
  * Update the specified resource in storage.
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


  public function profilePhotoDelete(Request $request)
  {
    $authUser = $request->user();
    $result = array(
      'status' => 'failed',   
      'message' => __('failed to remove image'),
    );
    $path_name =  $request->user()->profileImage->path_name;
    $file = str_replace(URL::to('').'/uploads/','',$path_name);

    $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
    if(file_exists($storagePath.$file)) unlink($storagePath.$file);
    AttachedFile::find($authUser->profileImage->id)->delete();
    $data['profile_image_id'] ='';
    if ($authUser->update($data)) {
      $result = array(
        "status"     => 'success',
        'message' => __('Successfully Changed Profile image')
      );
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
    return view('pages.profile.index');
  }
  
  
  
  
}
