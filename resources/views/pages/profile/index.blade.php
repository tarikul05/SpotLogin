@extends('layouts.main')

@section('head_links')
  <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
  <style type="text/css">
    .text-end{
      margin-right: 20px;
    }
  </style>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content update_profile_page">
    <div class="container-fluid area-container">
      <form method="POST" action="{{route('profile.update')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
        <header class="panel-heading" style="border: none;">
          <div class="row panel-row" style="margin:0;">
            <div class="col-sm-6 col-xs-12 header-area">
              <div class="page_header_class">
                <label id="page_header" name="page_header">
                  {{__('User Account')}}: <?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : '';?>
                </label>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12 btn-area">
              <div class="pull-right btn-group">
                <button type="submit" class="btn bg-info text-white save_button float-end" id="update_btn">
                  {{ __('Save')}}
                </button>
              </div>
            </div>    
          </div>                 
        </header>
      
        <div class="col-lg-12 col-md-12 col-sm-12">
          @csrf
          
          <!-- Nav tabs -->
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('User Account')}}</button>
              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('Images')}}</button>
            </div>
          </nav>
          <!-- Nav tabs -->
          <!-- Tabs content -->
          <div class="tab-content" id="ex1-content">
            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">

              <div class="row">
                <div class="col-sm-12 col-xs-12 header-area">
                  <div class="page_header_class">
                    <label id="page_header" class="page_title text-black">{{ __('User Account')}}</label>
                  </div>
                </div>
              
                <div class="col-md-6 offset-md-2">
                  <div class="form-group">
                    <input type="hidden" id="user_id" name="user_id" value="{{!empty($AppUI['id']) ? $AppUI['id'] : '0'}}">
                  </div> 
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('ID')}}  </label>
                    <div class="col-sm-6">
                      <div class="form-group-data">
                        <input type="text" class="form-control" disabled="disabled" value="{{!empty($AppUI['username']) ? $AppUI['username'] : ''}}">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Name of User')}}  </label>
                    <div class="col-sm-6">
                      <div class="form-group-data">
                        <input type="text" class="form-control" id="firstname" name="firstname" value="{{!empty($AppUI['firstname']) ? old('firstname', $AppUI['firstname']) : old('firstname')}}">
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Email')}}  </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" readonly class="form-control" id="email" name="email" value="{{!empty($AppUI['email']) ? old('email', $AppUI['email']) : old('email')}}">
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('New Password')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="password" type="text" class="form-control" id="password" name="password" value="">
                      
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
              <div class="row">
                <div class="col-sm-12 col-xs-12 header-area">
                  <div class="page_header_class">
                    <label id="page_header" class="page_title text-black">{{ __('Profile picture')}}</label>
                  </div>
                </div>
              
                <div class="col-md-6">
                  <form enctype="multipart/form-data" role="form" id="form_images" class="form-horizontal" method="post" action="#">
                    <div class="form-group row">
                      <div class="col-sm-8">
                        <fieldset>
                          <div class="profile-image-cropper responsive">
                          <?php if (!empty($AppUI->profileImage->path_name)): ?>
                            <img id="profile_image_user_account" src="{{ $AppUI->profileImage->path_name }}"
                                height="128" width="128" class="img-circle"
                                style="margin-right:10px;">
                          <?php else: ?>
                            <img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}"
                                height="128" width="128" class="img-circle"
                                style="margin-right:10px;">
                          <?php endif; ?>

                            
                            <div style="display:flex;flex-direction: column;">
                              <div style="margin:5px;">
                                <span class="btn btn-theme-success">
                                  <i class="fa fa-picture-o"></i>
                                  <span id="select_image_button_caption" onclick="UploadImage()">{{ __('Choose an image ...')}}</span>
                                  <input onchange="ChangeImage()"
                                      class="custom-file-input" id="profile_image_file"
                                      type="file" name="profile_image_file"
                                      accept="image/*" style="display: none;">
                                </span>
                              </div>
                              <?php //if (!empty($AppUI->profile_image_id)): ?>
                                <div style="margin:5px;">
                                  <a id="delete_profile_image" name="delete_profile_image" class="btn btn-theme-warn" style="{{!empty($AppUI->profile_image_id) ? '' : 'display:none;'}}">
                                    <i class="fa fa-trash"></i>
                                    <span id="delete_image_button_caption">{{ __('Remove Image')}}</span>
                                  </a>
                                </div>
                              <?php //endif; ?>
                            </div>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Tabs content -->
          
        </div>
      </form>
    </div>
  </div>

@endsection

@section('footer_js')
<script>
	$(document).ready(function(){

	}); //ready

  function UploadImage() {
    document.getElementById("profile_image_file").value = "";
    $("#profile_image_file").trigger('click');
  }
  function ChangeImage() {
    var p_person_id = $("#user_id").val(),
        p_file_id = '', data = '';
    var file_data = $('#profile_image_file').prop('files')[0];
    var formData = new FormData();
    formData.append('profile_image_file', file_data);
    formData.append('type', 'upload_image');
    formData.append('p_person_id', p_person_id);
    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
    let loader = $('#pageloader');
    loader.show("fast");
    $.ajax({
      url: BASE_URL + '/admin/update-profile-photo',
      data: formData,
      type: 'POST',
      //dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function (xhr) {
        loader.show("fast");
      },
      success: function (result) {
        loader.hide("fast");
        var mfile = result.image_file + '?time=' + new Date().getTime();
        $("#profile_image_user_account").attr("src",mfile);
        $("#user_profile_image").attr("src",mfile);
        $("#admin_logo").attr("src",mfile);
        $("#delete_profile_image").show();
      },// success
      error: function (reject) {
        loader.hide("fast");
        let errors = $.parseJSON(reject.responseText);
        errors = errors.errors;
        $.each(errors, function (key, val) {
          //$("#" + key + "_error").text(val[0]);
          errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
        });
      },
      complete: function() {
        loader.hide("fast");
      }
    });
  }
  $('#delete_profile_image').click(function (e) {
    DeleteProfileImage();      // refresh lesson details for billing
  })
  function DeleteProfileImage() {
    //delete image
    document.getElementById("profile_image_file").value = "";
    var p_person_id = document.getElementById('user_id').value;
    let loader = $('#pageloader');
    $.ajax({
      url: BASE_URL + '/admin/delete-profile-photo',
      data: 'user_id=' + p_person_id,
      type: 'POST',
      dataType: 'json',
      beforeSend: function (xhr) {
        loader.show("fast");
      },
      success: function(response) {
        if (response.status == 'success'){
          loader.hide("fast");
          $("#profile_image_user_account").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#user_profile_image").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#admin_logo").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#delete_profile_image").hide();
          successModalCall(response.message);
        }
              
      },
      error: function (reject) {
        loader.hide("fast");
        let errors = $.parseJSON(reject.responseText);
        errors = errors.errors;
        $.each(errors, function (key, val) {
          //$("#" + key + "_error").text(val[0]);
          errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
        });
      },
      complete: function() {
        loader.hide("fast");
      }
    });

  }
</script>
@endsection
