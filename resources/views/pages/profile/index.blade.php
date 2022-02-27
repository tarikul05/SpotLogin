@extends('layouts.main')

@section('head_links')
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
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
										{{__('User Account')}}: <?php echo !empty($AppUI['username']) ? $AppUI['username'] : '';?>
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
        <div>
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
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Name of User')}}: </label>
                    <div class="col-sm-6">
                      <div class="selectdiv form-group-data">
                        <input type="text" class="form-control" id="username" name="username" value="{{!empty($AppUI['username']) ? old('username', $AppUI['username']) : old('username')}}">
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Email')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" class="form-control" id="language_title" name="title" value="{{!empty($AppUI['email']) ? old('email', $AppUI['email']) : old('email')}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('New Password')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="password" type="text" class="form-control" id="password" name="password" value="">
                      
                    </div>
                  </div>
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
                  <form enctype="multipart/form-data" role="form" id="form_images"
                                            class="form-horizontal" method="post" action="#">
                    <div class="form-group row">
                      <div class="col-sm-8">
                        <fieldset>
                          <div class="profile-image-cropper responsive">
                            <img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}"
                                height="128" width="128" class="img-circle"
                                style="margin-right:10px;">
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

                              <div style="margin:5px;">
                                <a id="delete_profile_image" name="delete_profile_image" class="btn btn-theme-warn">
                                  <i class="fa fa-trash"></i>
                                  <span id="delete_image_button_caption">{{ __('Remove Image')}}</span>
                                </a>
                              </div>
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
    $("#profile_image_file").trigger('click');
  }
  function ChangeImage() {
    //$("#form_images").submit();

    var p_person_id = $("#user_id").val(),
        p_file_id = '', data = '';

    
    var file_data = $('#profile_image_file').prop('files')[0];
    
    //var formdata = new FormData(); 
    var formElem = $("#form_images");
    //var formElem = document.querySelector('#form_images');
    var formData = new FormData();
    formData.append('profile_image_file', file_data);
    formData.append('type', 'upload_image');
    formData.append('p_person_id', p_person_id);
    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

    $.ajax({
        url: BASE_URL + '/admin/update-profile-photo',
        data: formData,
        type: 'POST',
        //dataType: 'json',
        processData: false,
        contentType: false,
        success: function (result) {
          console.log(result);
            var mfile = result.image_file + '?time=' + new Date().getTime();
            $("#profile_image_user_account").attr("src",mfile);
            $("#user_profile_image").attr("src",mfile);
            $("#admin_logo").attr("src",mfile);
        },// success
        error: function (ts) {
          errorModalCall(GetAppMessage('error_message_text'));
          // alert(ts.responseText+' failed: UploadImage') 
        }
    });


  }
</script>
@endsection
