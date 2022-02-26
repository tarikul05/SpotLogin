@extends('layouts.main')

@section('head_links')
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content update_profile_page">
	<div class="container-fluid area-container">

    <form method="POST" action="{{route('add.email_template')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
							<div class="page_header_class">
									<label id="page_header" name="page_header">
										{{__('User Account')}}: {{ $data['user']->username }}
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
                @csrf
              
                <div class="col-md-6 offset-md-2">
                  <div class="form-group">
                    <input type="hidden" id="user_id" name="user_id" value="0">
                  </div> 
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Name of User')}}: </label>
                    <div class="col-sm-6">
                      <div class="selectdiv form-group-data">
                        <input type="text" class="form-control" id="language_code" name="language_code">
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Email')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" class="form-control" id="language_title" name="title">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('New Password')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" class="form-control" id="abbr_name" name="abbr_name">
                      
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
                @csrf
              
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="hidden" id="user_id" name="user_id" value="0">
                  </div> 
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Name of User')}}: </label>
                    <div class="col-sm-6">
                      <div class="selectdiv form-group-data">
                        <input type="text" class="form-control" id="language_code" name="language_code">
                        
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('Email')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" class="form-control" id="language_title" name="title">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-sm-4 text-end">{{ __('New Password')}}: </label>
                    <div class="col-sm-6 form-group-data">
                      <input type="text" class="form-control" id="abbr_name" name="abbr_name">
                      
                    </div>
                  </div>
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
