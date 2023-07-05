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
    <div class="container-fluid body area-container">
      <form method="POST" action="{{route('profile.update')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
        <header class="panel-heading" style="border: none;">
          <div class="row panel-row" style="margin:0;">
            <div class="col-sm-6 col-xs-12 header-area" style="padding-top:8px;">
              <div class="page_header_class">
                <label id="page_header" name="page_header">
                  <i class="fa-solid fa-user"></i> {{__('User Account')}}: <?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : '';?>
                </label>        
              </div>
            </div>
            <div class="col-sm-6 col-xs-12 btn-area">
              <div class="pull-right btn-group">
                <!--<button type="submit" class="btn bg-info text-white save_button float-end" id="update_btn">
                  {{ __('Save')}}
                </button>-->
              </div>
            </div>    
          </div>                 
        </header>

        <?php if(!empty($subscription)) { ?>
            <img src="{{ asset('img/member_ship.png') }}" width="70" class="rounded-circle badgePremium">
        <?php } ?>
      
        <div class="col-lg-12 col-md-12 col-sm-12">
          @csrf
          
          <!-- Nav tabs -->
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <!--<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('User Account')}}</button>-->
              @if(!$AppUI->isStudent())
              <button class="nav-link active" id="nav-account-tab" data-bs-toggle="tab" data-bs-target="#tab_account" type="button" role="tab" aria-controls="nav-account" aria-selected="false">{{ __('My Account')}}</button>
              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('Logo')}}</button>
              @endif

              @if($AppUI->isStudent())
              <button class="nav-link active" id="nav-account-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="false">{{ __('My Account')}}</button>
              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('Profile Image')}}</button>
              @endif

            </div>
          </nav>
          <!-- Nav tabs -->
          <!-- Tabs content -->
          <div class="tab-content" id="ex1-content">

            @if($AppUI->isStudent())
            <div class="tab-pane fade" id="tab_account" role="tabpanel" aria-labelledby="tab_account">
            @endif
            @if(!$AppUI->isStudent())
            <div class="tab-pane fade show active" id="tab_account" role="tabpanel" aria-labelledby="tab_account">
            @endif

              <div class="row">
                <div class="col-sm-12 col-xs-12 header-area">
                  <div class="page_header_class">
                    @if($AppUI->isStudent())

                    @endif
                    @if(!$AppUI->isStudent())
                      <span id="page_header" class="page_title text-black"></span>
                      <?php if(!empty($subscription)) { ?>
                        <div class="alert alert-info  h6">
                          <i class="fa-solid fa-check"></i>  Premium Access activated.
                        </div>
                      <?php }else{ ?>
                        <div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Activate your Premium access and enable all features <a href="{{ route('subscription.upgradePlan') }}"> Choose a plan and upgrade now! </a></div>
                      <?php } ?>
                    @endif

@if(!$AppUI->isStudent())
<div class="row">

  <div class="col-lg-5 mb-2">
        
    <div class="card">
      <div class="card-body bg-tertiary">

        <div class="h4 pt-2" style="color:#0075bf;">My Plan</div>

        <?php if($product_object){?>
          @if($subscriber->cancel_at_period_end)
          <?php
          if($subscription['status'] === 'trialing') {
            echo '<span class="text-danger">Your subscription is canceled and will stop the' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span>';
          }
          if($subscription['status'] === 'active') {
            echo '<span class="text-danger">Your subscription is canceled and will stop the' . date('M j, Y', $subscription['current_period_end']).'</span>';
          }
          ?>
            <span class="text-danger">Your subscription is canceled and will stop the <?php echo date('M j, Y', $subscription['current_period_end']); ?>.</span><br>
          @endif

          @if($subscriber->status === 'trialing')
            Your trial period is valid until <?= date('M j, Y', $subscriber->trial_end) ?>.
            <?php if($product_object){ ?>
              <br>
              <small>(you will not be charged until the end of your trial period)</small>
            <?php } ?>
            <hr>
          @endif
          @if($subscriber->status === 'active')
              Premium Plan is active on your account.<br>
              Period in process : <?php echo date('M j, Y', $subscription['current_period_start']); ?> - <?php echo date('M j, Y', $subscription['current_period_end']); ?>
          @endif
        <?php } ?>
        
        
        <table class="table table-stripped table-hover">

          <tr>
            <td><b>Plan Type</b></td>
            <?php 
                if($product_object){
                    echo '<td><span class="badge bg-success"><i class="fa-solid fa-check"></i> '.$product_object->name.'</span></td>';
                }else{
                    if($AppUI->isSchoolAdmin()){
                        echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Trial period</span></td>';
                    }else{
                      $today_date = new DateTime();
                      $trial_ends_at = new DateTime($user->trial_ends_at);
                      if (!empty($user->trial_ends_at) && $today_date <= $trial_ends_at) {
                        echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(Trial period)</small></td>';
                      } else {
                        echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic (Trial ended)</span></td>';
                      }
                    }
                }
            ?>
          </tr>

          <?php if(!empty($user->trial_ends_at)){ ?>
            <tr>
                <?php 
                  if($AppUI->isSchoolAdmin()){
                      echo '<td><b>Trail Valid Until</b></td>';
                  }else{
                      echo '<td><b>Basic Valid Until</b></td>';
                  }
                ?>
                <td>
                  <?= date('M j, Y', strtotime($user->trial_ends_at)) ?>
                </td>
            </tr>
          <?php } ?>

          <?php 
            if($subscription) { ?>
              <tr>
                <?php {
                  if($subscription['status'] === 'trialing') {
                    echo '<td><b>Next payment</b></td><td>' . date('M j, Y', $subscription['billing_cycle_anchor']).'</td>';
                  }
                  if($subscription['status'] === 'active') {
                    echo '<td><b>Next payment</b></td><td>' . date('M j, Y', $subscription['current_period_end']).'</td>';
                  }
                  }
                ?>
              </tr>
            <?php } ?>

            <tr>
              <td><b>Price</b></td>
                <?php if(!empty($subscription)) { ?>
                    <td><span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
                    <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span></td>
                <?php }else{ ?>
                    <td><span class="price">Free</span></td>
                <?php } ?>
            </tr>

            <?php if(!empty($subscription)) { ?>
              <tr>
                <td><b>Invoice</b></td><td>
                  <i class="fa-solid fa-download"></i> 
                  <a class="action_link" target="_blank" href="<?= $invoice_url['hosted_invoice_url'] ?>">
                  <span class="action_icon">Download</span>
              </a></td>
              </tr>
            <?php } ?>

        </table>

        <div class="mt-2 btns-plan">
          <?php if(!empty($subscriber)) { ?>
            <a class="btn btn-success btn-md disabled" href="{{ route('subscription.upgradePlan') }}">Choose a Plan</a>
            @if($subscriber->cancel_at_period_end === true || $subscriber->cancel_at_period_end === "true")
            <a class="btn btn-warning btn-md disabled" href="#"><i class="fa-solid fa-arrow-right"></i> Cancel my subscription !</a>
            @else
            <a class="btn btn-warning btn-md" href="#" id="buttonCancelSubscription"><i class="fa-solid fa-arrow-right"></i> Cancel my subscription !</a>
            @endif
          <?php } else { ?>
            <a class="btn btn-success" href="{{ route('subscription.upgradePlan') }}"><i class="fa-regular fa-bell fa-bounce"></i>  Choose a Plan & Upgrade Now !</a>
          <?php } ?>
        </div>

      </div>
    </div>

  </div>

  <div class="col-lg-1"></div>

  <div class="col-lg-6 mb-2">

    <div class="card" style="box-shadow: 0px 15px 10px -15px #111;">
      <div class="card-body bg-tertiary">
        <div class="h5 pt-2" style="color:#0075bf;">My account</div>                    
      <table class="table table-stripped table-hover">
        <tr><td width="250"><b>Connected to school</b></td> <td>{{  $AppUI->related_school->school_name }}</td></tr>
        <tr><td><b>Account created date</b></td> <td>{{  $AppUI->created_at }}</td></tr>
        <tr><td><b>School timezone</b></td> <td>{{  $AppUI->related_school->timezone }}</td></tr>
        <!--<tr><td><b>Acces</b></td> <td>{{  $AppUI->role_type }}</td></tr>-->
      </table>

      </div>
    </div>

  </div>


    </div>
  @endif

          <div class="my_subscription row gutters-sm" style="display: none;">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column">
      
                
                        <?php if(!empty($user->trial_ends_at)){ ?>
                            <p class="text-secondary mb-1">
                                <?php 
                                if($AppUI->isSchoolAdmin()){
                                    echo '<b>Trail Valid Until</b>';
                                }else{
                                    echo '<b>Basic Valid Until</b>';
                                }
                            ?>:  <?= date('M j, Y', strtotime($user->trial_ends_at)) ?></td>
                            </p>
                        <?php } ?>
                        <p class="text-secondary mb-1"><b>Next Payment:</b> 
                            <?php 
                                if($subscription){
                                    echo '<span class="">' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span>';
                                }else{
                                    echo '<span class="">--/--/----</span>';
                                }
                            ?>
                        </p>
                        <p class="text-muted font-size-sm mb-1">
                            <b>Plan Type : </b>
                            <?php 
                                if($product_object){
                                    echo '<span class="plan_type_p">'.$product_object->name.'</span>';
                                }else{
                                    if($AppUI->isSchoolAdmin()){
                                        echo '<span class="plan_type_f">Trial period</span>';
                                    }else{
                                        echo '<span class="plan_type_f">Basic</span>';
                                    }
                                }
                            ?>
                        </p>
                        <p class="text-muted font-size-sm">
                            <b>Price:</b>
                            <?php if(!empty($subscription)) { ?>
                                <span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
                                <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span>
                            <?php }else{ ?>
                                <span class="price">Free</span>
                            <?php } ?>
                        </p>
                        <a class="btn btn-success" href="{{ route('subscription.upgradePlan') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 17.0129L11.413 16.9979L21.045 7.4579C21.423 7.0799 21.631 6.5779 21.631 6.0439C21.631 5.5099 21.423 5.0079 21.045 4.6299L19.459 3.0439C18.703 2.2879 17.384 2.2919 16.634 3.0409L7 12.5829V17.0129ZM18.045 4.4579L19.634 6.0409L18.037 7.6229L16.451 6.0379L18.045 4.4579ZM9 13.4169L15.03 7.4439L16.616 9.0299L10.587 15.0009L9 15.0059V13.4169Z" fill="#FFFFFF"/>
                                <path d="M5 21H19C20.103 21 21 20.103 21 19V10.332L19 12.332V19H8.158C8.132 19 8.105 19.01 8.079 19.01C8.046 19.01 8.013 19.001 7.979 19H5V5H11.847L13.847 3H5C3.897 3 3 3.897 3 5V19C3 20.103 3.897 21 5 21Z" fill="#FFFFFF"/>
                            </svg>
                            <span class="action_icon">Change plan type</span>
                        </a>
                        <button class="btn btn-outline-success">Cancle</button>
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                    <table class="my_subscription">
                        <tbody>
                            <?php if($AppUI->isSchoolAdmin()){ ?> 
                                <!-- school first higher plan info -->
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_one_price_id')) {?>
                                    <tr>
                                        <td>Teachers Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } else if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_two_price_id')){ ?>
                                <!-- school 2nd higher plan info -->
                                    <tr>
                                        <td>Up 5 teachers</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } else{ ?>
                                    <tr>
                                        <td>Teachers Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Manage your student</td>
                                </tr>
                                <tr>
                                    <td>Manage your teacher</td>
                                </tr>
                                <tr>
                                    <td>Manage your schedule</td>
                                </tr>
                                <tr>
                                    <td>Share your schedule with your team and your students</td>
                                </tr>
                                <tr>
                                    <td>Automatic invoice based on the Schedule (students and teachers)</td>
                                </tr>
                                <tr>
                                    <td>Manual invoices</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_two')){ ?>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Access the mobile app</td>
                                </tr>
                            <?php } ?>
                            <?php if($AppUI->isTeacherAdmin()){ ?>
                                <tr>
                                    <td>Unlimited student</td>
                                </tr>
                                <tr>
                                    <td>Manage your student</td>
                                </tr>
                                <tr>
                                    <td>Manage your schedule</td>
                                </tr>
                                <tr>
                                    <td>Share your schedule with your team and your students</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_single_cocah_premium_plan_price_id')){ ?>
                                    <tr>
                                        <td>Automatic invoice based on the Schedule</td>
                                    </tr>
                                    <tr>
                                        <td>Manual invoices</td>
                                    </tr>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td>Access the mobile app</td>
                                    </tr>
                            <?php } ?>
                            <?php if($AppUI->isTeacherAll() || $AppUI->isTeacherMedium() || $AppUI->isTeacherMinimum()){ ?> 
                                <tr>
                                    <td>Access the school schedule </td>
                                </tr>
                                <tr>
                                    <td>Access the mobile app</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_teacher_premium_plan_price_id')){ ?>
                                <!-- teacher plan info -->
                                    <tr>
                                        <td>Automatic invoice based on your schedule</td>
                                    </tr>
                                    <tr>
                                        <td>Manal invoice</td>
                                    </tr>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>















          
                  </div>
                </div>
              </div>
            </div>

            @if($AppUI->isStudent())
            <div class="tab-pane fade active show" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
            @endif
            @if(!$AppUI->isStudent())
            <div class="tab-pane fade" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
            @endif
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
                    @if($AppUI->isStudent())
                    <label id="page_header" class="page_title text-black">{{ __('Profile picture')}}</label>
                    @endif
                    @if(!$AppUI->isStudent())
                    <span id="page_header" class="page_title text-black"></span>
                    <div class="mb-3">Your logo will be added to the invoices you can issue with premium access</div>
                    @endif
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



  <div class="modal" id="cancel_subscription">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Subscription</h5>
                <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
            </div>
            <div class="modal-body">
              <?php if(!empty($subscription)) { ?>
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
                </div>
                <p class="text-center mt-3">Do you really want to cancel your subscription?<br>
                <small>(Your premium access will be valid until 

                  @if($subscriber->status === 'active')
                  <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                  @else
                  <?php echo date('M j, Y', $subscription['billing_cycle_anchor']); ?>
                  @endif
                  
                  )</small>
                </p>
              <?php } ?>
            </div>
            <div class="modal-footer">
                <a href="<?= $BASE_URL;?>/admin/profile-update" class="btn btn-secondary close"  data-dismiss="modal">No</a>
                <a class="btn btn-danger" href="{{ route('subscription.cancelPlan') }}">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>


@endsection

@section('footer_js')
<script>
	$(document).ready(function(){
    $('#buttonCancelSubscription').on('click', function() {
      $('#cancel_subscription').modal("show")
    })
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
    loader.fadeIn();
    $.ajax({
      url: BASE_URL + '/admin/update-profile-photo',
      data: formData,
      type: 'POST',
      //dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function (xhr) {
        loader.fadeIn();
      },
      success: function (result) {
        loader.fadeOut();
        var mfile = result.image_file + '?time=' + new Date().getTime();
        $("#profile_image_user_account").attr("src",mfile);
        $("#user_profile_image").attr("src",mfile);
        $("#admin_logo").attr("src",mfile);
        $("#delete_profile_image").show();
        var isStudent = "{{ $AppUI->isStudent() }}";
        if(isStudent) {
          successModalCall("Your profile picture is added!");
        } else {
          successModalCall("Your logo is added!");
        }
      },// success
      error: function (reject) {
        loader.fadeOut();
        let errors = $.parseJSON(reject.responseText);
        errors = errors.errors;
        $.each(errors, function (key, val) {
          //$("#" + key + "_error").text(val[0]);
          errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text')); 
        });
      },
      complete: function() {
        loader.fadeOut();
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
        loader.fadeIn();
      },
      success: function(response) {
        if (response.status == 'success'){
          loader.fadeOut();
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
