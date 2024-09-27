
<form class="form-horizontal mb-4" id="add_teacher" action="{{ route('updateTeacherAction') }}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form" style="padding-bottom:5px!important;">
    @csrf

    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('Coach information') }}</div>
            <div class="card-body">

                    <input type="hidden" id="user_id" name="user_id" value="{{$teacher->user->id}}">
					<input type="hidden" id="school_id" name="school_id" value="{{$schoolId}}">
					<input type="hidden" id="school_name" name="school_name" value="{{$schoolName}}">
                    <input class="form-control" disabled="disabled" id="nickname" maxlength="50" name="nickname" placeholder="Pseudo" type="hidden"
                    value="{{!empty($relationalData->nickname) ? old('nickname', $relationalData->nickname) : old('nickname')}}"
                    >
                    @if ($errors->has('nickname'))
                        <span id="" class="error">
                                <strong>{{ $errors->first('nickname') }}.</strong>
                        </span>
                    @endif

                    <div class="row">
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group custom-form-group">
                                <label for="lastname" id="family_name_label_id" class="titleFieldPage">{{__('Family Name') }}<span style="color:red; font-size:13px;">*</span></label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input class="form-control require" value="{{ !empty($teacher->lastname) ? old('lastname', $teacher->lastname) : old('lastname') }}" id="lastname" name="lastname" type="text">
                                </div>
                                @if ($errors->has('lastname'))
                                    <span id="" class="error">
                                        <strong>{{ $errors->first('lastname') }}.</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group custom-form-group">
                                <label for="firstname" id="first_name_label_id" class="titleFieldPage">{{__('First Name') }}<span style="color:red; font-size:13px;">*</span></label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input class="form-control require" value="{{ !empty($teacher->firstname) ? old('firstname', $teacher->firstname) : old('firstname') }}" id="firstname" name="firstname" type="text">
                                </div>
                                @if ($errors->has('firstname'))
                                    <span id="" class="error">
                                        <strong>{{ $errors->first('firstname') }}.</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12"></div>
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group custom-form-group">
                                <label id="slicence_js_caption" class="titleFieldPage">{{__('License number') }}</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa-regular fa-id-card"></i></span>
                                <input class="form-control" placeholder="{{__('Add a license number') }}" value="{{ !empty($teacher->licence_js) ? old('licence_js', $teacher->licence_js) : old('licence_js') }}" id="licence_js" name="licence_js" type="text">
                                </div>
                            </div>

                            <div class="form-group custom-form-group">
                                <label for="email" id="email_caption" class="titleFieldPage">{{__('Email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input class="form-control" value="{{ !empty($teacher->email) ? old('email', $teacher->email) : old('email') }}" id="email" name="email" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
    </div>

    <div class="row justify-content-center pt-4">
        <div class="col-md-12">
            <div class="card2">
                <div class="card-header titleCardPage">{{ __('Coach Address') }} <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span></div>
                <div class="card-body">

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="street" id="street_caption" class="titleFieldPage">{{__('Street') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->street) ? old('street', $teacher->street) : old('street')}}" id="street" name="street" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="street_number" id="street_number_caption" class="titleFieldPage">{{__('Street No') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->street_number) ? old('street_number', $teacher->street_number) : old('street_number')}}" id="street_number" name="street_number" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="zip_code" id="postal_code_caption" class="titleFieldPage">{{__('Postal Code') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->zip_code) ? old('zip_code', $teacher->zip_code) : old('zip_code')}}" id="zip_code" name="zip_code" type="text">
                                            </div>

									</div>
								</div>
                                <div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="place" id="locality_caption" class="titleFieldPage">{{__('City') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->place) ? old('place', $teacher->place) : old('place')}}" id="place" name="place" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="country_code" id="pays_caption" class="titleFieldPage">{{__('Country') }}</label>
											<div class="selectdiv">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<select class="form-control select_two_defult_class" id="country_code" name="country_code">
												@foreach($countries as $country)
														<option value="{{ $country->code }}" {{!empty($teacher->country_code) ? (old('country_code', $teacher->country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }} ({{ $country->code }})</option>
												@endforeach
											</select>
                                                </div>

										</div>
									</div>
									<div id="province_id_div" class="form-group custom-form-group" style="display:none">
										<label id="province_caption" for="province_id" class="col-lg-3 col-sm-3 text-left titleFieldPage">Province: </label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control select_two_defult_class" id="province_id" name="province_id">
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>

                        </div>
                    </div>
                </div>
                </div>

                <div class="row justify-content-center pt-4">
                    <div class="col-md-12">
                        <div class="card2">
                            <div class="card-header titleCardPage">{{ __('Contact information') }} <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span></div>
                            <div class="card-body">

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label  for="phone" id="phone_caption" class="titleFieldPage">{{__('Phone') }}</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
												<input class="form-control" value="{{!empty($teacher->phone) ? old('phone', $teacher->phone) : old('phone')}}" id="phone" name="phone" type="text">
											</div>
									</div>

									<div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption" class="titleFieldPage">{{__('Mobile') }}</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-mobile-screen-button"></i></span>
												<input class="form-control" value="{{!empty($teacher->mobile) ? old('mobile', $teacher->mobile) : old('mobile')}}" id="mobile" name="mobile" type="text">
											</div>
									</div>
								</div>
                                <div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="email2" id="email_caption" class="titleFieldPage">{{__('Email') }}</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
												<input class="form-control" value="{{!empty($teacher->email2) ? old('email2', $teacher->email2) : old('email2')}}" id="email2" name="email2" type="text">
											</div>
									</div>
								</div>
							</div>

                        </div>
                    </div>
                </div>
                </div>

  
                <div class="row justify-content-center pt-4">
                    <div class="col-md-12">
                        <div class="card2">
                            <div class="card-header titleCardPage">
                                @if ($isInEurope)
                                <input type="radio" id="payment_info_checkbox" name="payment_info_checkbox" value="1" {{!empty($teacher->payment_info_checkbox) ? (old('payment_info_checkbox', $teacher->payment_info_checkbox) == 1 ? 'checked' : '') : 'checked'}}>
                                @endif
                                {{ $isInEurope ? __('Coach Bank Information') :  __('Payment Information') }} 
                                <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> 
                                <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span>
                            </div>
                            <div class="card-body" id="payment_info_div">

							@if ($isInEurope)

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('Bank Name')}}</label>
											<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('Address')}}</label>
											<input class="form-control" id="bank_address" name="bank_address" type="text"
											value="{{!empty($teacher->bank_address) ? old('bank_address', $teacher->bank_address) : old('bank_address')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('Postal Code')}}</label>
											<input class="form-control" id="bank_zipcode" name="bank_zipcode" type="text"
												value="{{!empty($teacher->bank_zipcode) ? old('bank_zipcode', $teacher->bank_zipcode) : old('bank_zipcode')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('City')}}</label>
											<input class="form-control" id="bank_place" name="bank_place" type="text"
											value="{{!empty($teacher->bank_place) ? old('bank_place', $teacher->bank_place) : old('bank_place')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('Country')}}</label>
											<div class="selectdiv">
												<select class="form-control" name="bank_country_code" id="bank_country_code">
													<option value="">{{ __('Select')}}</option>
													@foreach($countries as $country)
															<option value="{{ $country->code }}" {{!empty($teacher->bank_country_code) ? (old('country_code', $teacher->bank_country_code) == $country->code ? 'selected' : '') : (old('country_code') == $country->code ? 'selected' : '')}}>{{ $country->name }}</option>
													@endforeach
												</select>
											</div>

									</div>
								</div>
                                <div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">

									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('Account No')}}</label>
											<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('IBAN No')}}</label>
											<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption" class="titleFieldPage">{{ __('SWIFT A/c No')}}</label>
											<input class="form-control" id="bank_swift" name="bank_swift" type="text"
												value="{{!empty($teacher->bank_swift) ? old('bank_swift', $teacher->bank_swift) : old('bank_swift')}}">

									</div>
								</div>
							</div>

							@else

									<div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption" class="titleFieldPage">{{__('Payment preference') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">
											</div>

									</div>

                                    <div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption" class="titleFieldPage">{{__('Payment preference 2') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">
											</div>

									</div>

                                    <div class="form-group custom-form-group">
										<label class="col-lg-3 col-sm-3 text-left titleFieldPage" for="mobile" id="mobile_caption">{{__('Payment preference 3') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">
											</div>

									</div>

									@if($teacher == 'CA')
									<div class="form-group row">
										<label for="mobile" id="mobile_caption">{{__('E-transfer email') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-brands fa-canadian-maple-leaf"></i></span>
												<input class="form-control" id="etransfer_acc" name="etransfer_acc" type="text"
												value="{{!empty($teacher->etransfer_acc) ? old('etransfer_acc', $teacher->etransfer_acc) : old('etransfer_acc')}}">
												<span class="etransfer_acc"></span>
											</div>

									</div>
									@endif

							@endif

            </div>
        </div>
    </div>
    


    @if ($isInEurope)
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card2">
                <div class="card-header titleCardPage">
                    <input type="radio" id="payment_info_checkbox2" name="payment_info_checkbox" value="2" {{!empty($teacher->payment_info_checkbox) ? (old('payment_info_checkbox', $teacher->payment_info_checkbox) == 2 ? 'checked' : '') : ''}}>
                    Other payment method
                </div>
                <div class="card-body" style="display:none;" id="payment_info_div2">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
                        <input class="form-control" id="bank_name2" name="bank_name" type="text"
                        value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
                        <input class="form-control" id="bank_account2" name="bank_account" type="text"
                        value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
                        <input class="form-control" id="bank_iban2" name="bank_iban" type="text"
                        value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>  
    @endif


    <div class="row justify-content-center pt-4">
        <div class="col-md-12">
            <div class="card2">
                @if($AppUI->isStudent())
                <div class="card-header titleCardPage">{{ __('Profile picture') }}</div>
                @else
                <div class="card-header titleCardPage">{{ __('Coach Logo') }}</div>
                @endif
                <div class="card-body">

                    @if(!$AppUI->isStudent())
                    <span id="page_header" class="page_title text-black"></span>
                    <div class="mb-3">{{ __('Your logo will be added to the invoices you can issue with premium access') }}</div>
                    @endif

                        <div style="position:relative; width:150px;">
                            <?php if (!empty($AppUI->profileImage->path_name)): ?>
                                <img id="profile_image_user_account" src="{{ $AppUI->profileImage->path_name }}" onclick="UploadImage()"
                                    height="128" width="128" class="img-thumbnail"
                                    style="margin-right:10px;">
                            <?php else: ?>
                                <img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}" onclick="UploadImage()"
                                    height="128" width="128" class="img-thumbnail"
                                    style="margin-right:10px;">
                            <?php endif; ?>

                            <div style="position: absolute; top:1px; right:1px;">
                      
                                <span id="select_image_button_caption" onclick="UploadImage()"><i class="fa-solid fa-camera"></i></span>
                                <input onchange="ChangeImage()"
                                    class="custom-file-input" id="profile_image_file"
                                    type="file" name="profile_image_file"
                                    accept="image/*" style="display: none;">
                          
                          
                                <?php if (!empty($AppUI->profile_image_id)): ?>
                                <div style="margin-top:5px;">
                                    <a id="delete_profile_image" name="delete_profile_image" style="{{!empty($AppUI->profile_image_id) ? '' : 'display:none;'}}">
                                    <span id="delete_image_button_caption text-danger"><i class="fa fa-trash text-danger"></i></span>
                                    </a>
                                </div>
                                <?php endif; ?>
                          
                            </div>

                        </div>
                        
             
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
        <button type="submit" class="btn btn-success">{{ __('Update coach informations') }}</button>
        </div>
    </div>

</div>
</form>


<div id="fakeModalOverlay" class="overlay"></div>
<div id="cropContainer" class="cropContainer">
  <img id="image" src="" alt="Image to Crop">
  <button id="closeModal" class="close-btn">Cancel</button>
  <button id="cropImage" class="crop-btn">Crop</button>
</div>