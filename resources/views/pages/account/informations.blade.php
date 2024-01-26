<form class="form-horizontal" id="add_teacher" action="{{ route('updateTeacherAction') }}"  method="POST" enctype="multipart/form-data" name="add_teacher" role="form">
    @csrf

    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('Coach information') }}</div>
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
                                <label for="lastname" id="family_name_label_id">{{__('Family Name') }}<span style="color:red; font-size:13px;">*</span></label>
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
                                <label for="firstname" id="first_name_label_id">{{__('First Name') }}<span style="color:red; font-size:13px;">*</span></label>
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
                                <label id="slicence_js_caption">{{__('License number') }}</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa-regular fa-id-card"></i></span>
                                <input class="form-control" value="{{ !empty($teacher->licence_js) ? old('licence_js', $teacher->licence_js) : old('licence_js') }}" id="licence_js" name="licence_js" type="text">
                                </div>
                            </div>

                            <div class="form-group custom-form-group">
                                <label for="email" id="email_caption">{{__('Email') }}</label>
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
            <div class="card">
                <div class="card-header">{{ __('Coach Address') }} <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span></div>
                <div class="card-body">

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="street" id="street_caption">{{__('Street') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->street) ? old('street', $teacher->street) : old('street')}}" id="street" name="street" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="street_number" id="street_number_caption">{{__('Street No') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->street_number) ? old('street_number', $teacher->street_number) : old('street_number')}}" id="street_number" name="street_number" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="zip_code" id="postal_code_caption">{{__('Postal Code') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->zip_code) ? old('zip_code', $teacher->zip_code) : old('zip_code')}}" id="zip_code" name="zip_code" type="text">
                                            </div>

									</div>
								</div>
                                <div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="place" id="locality_caption">{{__('City') }}</label>
                                            <div class="input-group">
                                            <span class="input-group-addon"><i class="fa-solid fa-location-dot"></i></span>
											<input class="form-control" value="{{!empty($teacher->place) ? old('place', $teacher->place) : old('place')}}" id="place" name="place" type="text">
                                            </div>

									</div>
									<div class="form-group custom-form-group">
										<label for="country_code" id="pays_caption">{{__('Country') }}</label>
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
										<label id="province_caption" for="province_id" class="col-lg-3 col-sm-3 text-left">Province: </label>
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
                        <div class="card">
                            <div class="card-header">{{ __('Contact information') }} <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span></div>
                            <div class="card-body">

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label  for="phone" id="phone_caption">{{__('Phone') }}</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
												<input class="form-control" value="{{!empty($teacher->phone) ? old('phone', $teacher->phone) : old('phone')}}" id="phone" name="phone" type="text">
											</div>
									</div>

									<div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption">{{__('Mobile') }}</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-mobile-screen-button"></i></span>
												<input class="form-control" value="{{!empty($teacher->mobile) ? old('mobile', $teacher->mobile) : old('mobile')}}" id="mobile" name="mobile" type="text">
											</div>
									</div>
								</div>
                                <div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label  for="email2" id="email_caption">{{__('Email') }}</label>
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
                        <div class="card">
                            <div class="card-header">{{ $isInEurope ? __('Coach Bank Information') :  __('Payment Information') }} <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span></div>
                            <div class="card-body">

							@if ($isInEurope)

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('Bank Name')}}</label>
											<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('Address')}}</label>
											<input class="form-control" id="bank_address" name="bank_address" type="text"
											value="{{!empty($teacher->bank_address) ? old('bank_address', $teacher->bank_address) : old('bank_address')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('Postal Code')}}</label>
											<input class="form-control" id="bank_zipcode" name="bank_zipcode" type="text"
												value="{{!empty($teacher->bank_zipcode) ? old('bank_zipcode', $teacher->bank_zipcode) : old('bank_zipcode')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('City')}}</label>
											<input class="form-control" id="bank_place" name="bank_place" type="text"
											value="{{!empty($teacher->bank_place) ? old('bank_place', $teacher->bank_place) : old('bank_place')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('Country')}}</label>
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
										<label for="sstreet" id="street_caption">{{ __('Account No')}}</label>
											<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('IBAN No')}}</label>
											<input class="form-control" id="bank_iban" name="bank_iban" type="text"
												value="{{!empty($teacher->bank_iban) ? old('bank_iban', $teacher->bank_iban) : old('bank_iban')}}">

									</div>
									<div class="form-group custom-form-group">
										<label for="sstreet" id="street_caption">{{ __('SWIFT A/c No')}}</label>
											<input class="form-control" id="bank_swift" name="bank_swift" type="text"
												value="{{!empty($teacher->bank_swift) ? old('bank_swift', $teacher->bank_swift) : old('bank_swift')}}">

									</div>
								</div>
							</div>

							@else

									<div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption">{{__('Payment preference') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_name" name="bank_name" type="text"
												value="{{!empty($teacher->bank_name) ? old('bank_name', $teacher->bank_name) : old('bank_name')}}">
											</div>

									</div>

                                    <div class="form-group custom-form-group">
										<label for="mobile" id="mobile_caption">{{__('Payment preference 2') }}</label>

											<div class="input-group">
												<span class="input-group-addon"><i class="fa-solid fa-money-check-dollar"></i></span>
												<input class="form-control" id="bank_account" name="bank_account" type="text"
												value="{{!empty($teacher->bank_account) ? old('bank_account', $teacher->bank_account) : old('bank_account')}}">
											</div>

									</div>

                                    <div class="form-group custom-form-group">
										<label class="col-lg-3 col-sm-3 text-left" for="mobile" id="mobile_caption">{{__('Payment preference 3') }}</label>

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


    <div class="row justify-content-center pt-4">
        <div class="col-md-12">
            <div class="card">
                @if($AppUI->isStudent())
                <div class="card-header">{{ __('Profile picture') }}</div>
                @else
                <div class="card-header">{{ __('Coach Logo') }}</div>
                @endif
                <div class="card-body">

                    @if(!$AppUI->isStudent())
                    <span id="page_header" class="page_title text-black"></span>
                    <div class="mb-3">{{ __('Your logo will be added to the invoices you can issue with premium access') }}</div>
                    @endif


                    <div class="row">
                        <div class="col-2">
                            <?php if (!empty($AppUI->profileImage->path_name)): ?>
                                <img id="profile_image_user_account" src="{{ $AppUI->profileImage->path_name }}"
                                    height="128" width="128" class="img-thumbnail"
                                    style="margin-right:10px;">
                            <?php else: ?>
                                <img id="profile_image_user_account" src="{{ asset('img/photo_blank.jpg') }}"
                                    height="128" width="128" class="img-thumbnail"
                                    style="margin-right:10px;">
                            <?php endif; ?>
                        </div>
                        <div class="col-10 text-left">
                            <div class="center-block">
                                <div style="margin:5px;">
                                  <span class="btn btn-theme-success">
                                    <span id="select_image_button_caption" onclick="UploadImage()"><i class="fa-solid fa-camera"></i></span>
                                    <input onchange="ChangeImage()"
                                        class="custom-file-input" id="profile_image_file"
                                        type="file" name="profile_image_file"
                                        accept="image/*" style="display: none;">
                                  </span>
                                </div>
                                <?php if (!empty($AppUI->profile_image_id)): ?>
                                  <div style="margin:5px;">
                                    <a id="delete_profile_image" name="delete_profile_image" class="btn btn-theme-warn" style="{{!empty($AppUI->profile_image_id) ? '' : 'display:none;'}}">
                                      <span id="delete_image_button_caption"><i class="fa fa-trash"></i></span>
                                    </a>
                                  </div>
                                <?php endif; ?>
                              </div>
                        </div>
                    </div>











                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center pt-2">
        <div class="col-md-12">
        <br>
        <button type="submit" class="btn btn-primary">{{ __('Update coach informations') }}</button>
        </div>
    </div>

</div>
</form>

