

<form enctype="multipart/form-data" class="form-horizontal" id="add_student" method="post" action="{{ route('student.createAction') }}"  name="add_student" role="form">
    @csrf

    <div class="row justify-content-center pt-5">
    <div class="col-md-9">

        <div class="card">
            <div class="card-header">Add new student</div>
            <div class="card-body">

                        <input type="hidden" name="school_id" value="{{ $schoolId }}">
                        <input type="hidden" name="user_id" value="{{ !empty($exUser) ? $exUser->id : '' }}">

						<div class="row">

                            <div class="col-md-5 col-xs-12">

								@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherAll())
                                <div class="form-group custom-form-group">
                                    <label class="text-left" for="is_active" id="visibility_label_id">{{__('Status') }} :</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa-regular fa-square-check"></i></span>
                                        <select class="form-control" name="is_active" id="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
								</div>
								@endif

								<div class="form-group custom-form-group">
									<label class="text-left" for="gender_id" id="gender_label_id">{{__('Gender') }} : *</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa-solid fa-venus-mars"></i></span>
											<select class="form-control require" require id="gender_id" name="gender_id">
												@foreach($genders as $key => $gender)
													<option value="{{ $key }}" {{ old('gender_id') == $key ? 'selected' : ''}}>{{ $gender }}</option>
												@endforeach
											</select>
                                    </div>
								</div>
                                <input type="hidden" id="billing_method" name="billing_method" value="E">

								<div class="form-group custom-form-group">
									<label class="text-left" id="birth_date_label_id">{{__('Birth date') }}:</label>

										<div class="input-group" id="birth_date_div">
											<input id="birth_date" value="{{ $exStudent ? $exStudent->birth_date : '' }}" {{ $exStudent ? 'readonly' : '' }} name="birth_date" type="text" class="form-control" value="{{old('birth_date')}}">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>

									</div>
								</div>

								<div class="form-group" id="shas_user_account_div">
									<div id="shas_user_account_div111">
										<label class="text-left" for="shas_user_account" id="has_user_ac_label_id"><i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('If you enter an email, the student will receive an email with instructions to connect to his student account.')}}"></i> {{__('Send account invite') }} : </label>
											<input id="shas_user_account" name="is_sent_invite" type="checkbox" value="1">
									</div>
								</div>
							</div>

                            <div class="col-md-2"></div>

							<div class="col-md-5 col-xs-12">

                                <div class="form-group custom-form-group">
									<label class="text-left" for="nickname" id="nickname_label_id">{{__('Login ID') }} : *</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa-solid fa-lock"></i></span>
                                        <input class="form-control require" required="true" id="nickname" maxlength="50" name="nickname" type="text" value="{{old('nickname')}}">
                                        </div>
                                        @if ($errors->has('nickname'))
											<span id="" class="error">
													<strong>{{ $errors->first('nickname') }}.</strong>
											</span>
										@endif
								</div>

								<div class="form-group custom-form-group">
									<label class="text-left" for="lastname" id="family_name_label_id">{{__('Family Name') }} : *</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
										    <input class="form-control require" required="true" value="{{ $exStudent ? $exStudent->lastname : '' }}" {{ $exStudent ? 'readonly' : '' }} id="lastname" name="lastname" type="text" value="{{old('lastname')}}">
                                        </div>
                                        @if ($errors->has('lastname'))
											<span id="" class="error">
													<strong>{{ $errors->first('lastname') }}.</strong>
											</span>
										@endif
								</div>
								<div class="form-group custom-form-group">
									<label class="text-left" for="firstname" id="first_name_label_id">{{__('First Name') }} : *</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
										    <input class="form-control require" required="true" value="{{ $exStudent ? $exStudent->firstname : '' }}" {{ $exStudent ? 'readonly' : '' }} id="firstname" name="firstname" type="text" value="{{old('firstname')}}">
                                        </div>
                                        @if ($errors->has('firstname'))
											<span id="" class="error">
													<strong>{{ $errors->first('firstname') }}.</strong>
											</span>
										@endif

								</div>


                                <div class="form-group custom-form-group">
									<label class="text-left" for="email" id="email_caption">{{__('Email') }} :</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<input class="form-control" id="email" value="{{$searchEmail}}" name="email" type="text">
									</div>
								</div>



							</div>
						</div>

            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Student Informations</div>
            <div class="card-body">

							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="form-group ">
										<label class="text-left" for="level_id">{{__('Level') }} :</label>
                                        <select class="form-control m-bot15" id="level_id" name="level_id">
                                            <option selected value="">Select level</option>
                                            @foreach($levels as $key => $level)
                                                <option value="{{ $level->id }}">{{ $level->title }}</option>
                                            @endforeach
                                        </select>
									</div>
									@if($school->country_code == 'CH')
									<div class="form-group ">
									<label class="text-left">{{__('Date last level ASP') }}:</label>
											<div class="input-group">
												<input id="level_date_arp" value="{{ $exStudent ? $exStudent->level_date_arp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_date_arp" type="text" class="form-control" value="{{old('level_date_arp')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>
									</div>
									<div class="form-group ">
										<label class="text-left" for="licence_arp" id="postal_code_caption">{{__('ARP license') }} :</label>
											<input class="form-control" id="licence_arp" value="{{ $exStudent ? $exStudent->licence_arp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="licence_arp" type="text" value="{{old('licence_arp')}}">
									</div>
									@endif

									@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherAll())
										<div class="form-group pt-3">
											<label class="text-left">{{__('Private comment') }} :</label>
											<textarea class="form-control" cols="60" id="comment" name="comment" rows="5">{{old('comment')}}</textarea>
										</div>
									@endif

								</div>
								<div class="col-md-2 col-xs-12"></div>
								<div class="col-md-5 col-xs-12">
									<div class="form-group ">
										<label class="text-left" for="licence_usp" id="locality_caption">{{__('License number') }} :</label>
											<input class="form-control" id="licence_usp" value="{{ $exStudent ? $exStudent->licence_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="licence_usp" type="text" value="{{old('licence_usp')}}">
									</div>
									@if($school->country_code == 'CH')
									<div class="form-group ">
										<label class="text-left" for="level_skating_usp" id="locality_caption">{{__('USP Level') }} :</label>

											<input class="form-control" id="level_skating_usp" value="{{ $exStudent ? $exStudent->level_skating_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_skating_usp" type="text" value="{{old('level_skating_usp')}}">

									</div>
									<div class="form-group ">
									<label class="text-left">{{__('Date last level USP') }}:</label>

											<div class="input-group" id="date_last_level_usp_div">
												<input id="level_date_usp" value="{{ $exStudent ? $exStudent->level_date_usp : '' }}" {{ $exStudent ? 'readonly' : '' }} name="level_date_usp" type="text" class="form-control" value="{{old('level_date_usp')}}">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
											</div>

									</div>
									@endif

									<div class="form-group pt-3 text-center" id="profile_image">
										<label class="text-left">{{__('Profile Image') }}</label><br>

											<input class="form-control" type="file" accept="image/*" id="profile_image_file" name="profile_image_file" style="display:none">
											<span class="box_img" style="width: 60px; height: 60px">
												<label for="profile_image_file" class="profile_img_area">
												<img src="{{ asset('img/photo_blank.jpeg') }}"  id="frame" width="50px" alt="SpotLogin">
												<i class="fa fa-plus"></i>
												</label>
												<i class="fa fa-close" style="display:none"></i>
											</span>

									</div>

								</div>
							</div>


			</div>
		</div>

		<div class="card mt-4">
            <div class="card-header">Student Address</div>
            <div class="card-body">


					<div class="row">
						<div class="col-md-5 col-xs-12">
							<div class="form-group ">
								<label class="text-left" for="street" id="street_caption">{{__('Street') }} :</label>

									<input class="form-control" id="street" value="{{ $exStudent ? $exStudent->street : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street" value="{{old('street')}}" type="text">

							</div>
							<div class="form-group ">
								<label class="text-left" for="street_number" id="street_number_caption">{{__('Street No') }} :</label>

									<input class="form-control" id="street_number" value="{{ $exStudent ? $exStudent->street_number : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street_number" value="{{old('street_number')}}" type="text">

							</div>
							<!-- <div class="form-group ">
								<label class="text-left" for="street2" id="street_caption">{{__('Street2') }} :</label>
								<div class="col-sm-7">
									<input class="form-control" id="street2" value="{{ $exStudent ? $exStudent->street2 : '' }}" {{ $exStudent ? 'readonly' : '' }} name="street2" type="text">
								</div>
							</div> -->
							<div class="form-group ">
								<label class="text-left" for="zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>

									<input class="form-control" id="zip_code" value="{{ $exStudent ? $exStudent->zip_code : '' }}" {{ $exStudent ? 'readonly' : '' }} name="zip_code" value="{{old('zip_code')}}" type="text">

							</div>
						</div>
						<div class="col-md-2 col-xs-12"></div>
						<div class="col-md-5 col-xs-12">
							<div class="form-group ">
								<label class="text-left" for="place" id="locality_caption">{{__('City') }} :</label>
									<input class="form-control" id="place" value="{{ $exStudent ? $exStudent->place : '' }}" {{ $exStudent ? 'readonly' : '' }} name="place" value="{{old('place')}}" type="text">
							</div>
							<div class="form-group ">
								<label class="text-left" for="country_code" id="pays_caption">{{__('Country') }} :</label>
										<select class="form-control select_two_defult_class" id="country_code" name="country_code">
											<option value="">{{ 'Select Country' }}</option>
											@foreach($countries as $country)
												<option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
											@endforeach
										</select>
							</div>
							<div class="form-group row" id="province_id_div" style="display: none;">
								<label class="text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<select class="form-control select_two_defult_class" id="province_id" name="province_id">
								</select>
							</div>


					</div>
					<div class="section_header_class">
						<label id="address_caption">{{__('Billing address - Same as above') }} <input type="checkbox" name="bill_address_same_as" id="bill_address_same_as"></label>
					</div>

					<div class="row">
						<div class="col-md-5 col-xs-12">
							<div class="form-group">
								<label class="text-left" for="billing_street" id="street_caption">{{__('Street') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
								<input class="form-control" id="billing_street" value="{{ $exStudent ? $exStudent->billing_street : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_street" value="{{old('billing_street')}}" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="text-left" for="billing_street_number" id="street_number_caption">{{__('Street No') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<input class="form-control" id="billing_street_number" value="{{ $exStudent ? $exStudent->billing_street_number : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_street_number" type="text">
								</div>
								</div>
							<div class="form-group">
								<label class="text-left" for="billing_zip_code" id="postal_code_caption">{{__('Postal Code') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<input class="form-control" id="billing_zip_code" value="{{ $exStudent ? $exStudent->billing_zip_code : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_zip_code" type="text">
								</div>
								</div>
						</div>
						<div class="col-md-2 col-xs-12"></div>
						<div class="col-md-5 col-xs-12">
							<div class="form-group">
								<label class="text-left" for="billing_place" id="locality_caption">{{__('City') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<input class="form-control" id="billing_place" value="{{ $exStudent ? $exStudent->billing_place : '' }}" {{ $exStudent ? 'readonly' : '' }} name="billing_place" type="text">
								</div>
								</div>
							<div class="form-group">
								<label class="text-left" for="billing_country_code" id="pays_caption">{{__('Country') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<select class="form-control select_two_defult_class" id="billing_country_code" name="billing_country_code">
											<option value="">{{ 'Select Country' }}</option>
										@foreach($countries as $country)
											<option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
										@endforeach
									</select>
								</div>

							</div>
							<div class="form-group" id="billing_province_id_div">
								<label class="text-left" for="province_id" id="pays_caption">{{__('Province') }} :</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>

										<select class="form-control select_two_defult_class" id="billing_province_id" name="billing_province_id">
										</select>
									</div>

							</div>
						</div>
					</div>
				</div>

				</div>
			</div>
			<div class="card mt-4">
				<div class="card-header">{{__('Contact Information') }}</div>
				<div class="card-body">
					<small>{{__('At least one email needs to be selected to receive invoices') }}</small>
					<hr>

					<div class="row">
						<div class="col-md-5 col-xs-12">
							<div class="form-group">
								<label class="text-left" for="father_phone" id="father_phone">{{__("Father’s phone") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="father_phone" value="{{ $exStudent ? $exStudent->father_phone : '' }}" {{ $exStudent ? 'readonly' : '' }} name="father_phone"  type="text">
									</div>

							</div>
							<div class="form-group">
							<label class="text-left" for="mother_phone" id="mother_phone">{{__("Mother's phone") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="mother_phone" name="mother_phone" value="{{ $exStudent ? $exStudent->mother_phone : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text">
									</div>

							</div>
							<div class="form-group">
								<label class="text-left" for="student_phone" id="student_phone">{{__("Student's phone:") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="mobile" name="mobile" value="{{ $exStudent ? $exStudent->mobile : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text">
									</div>

							</div>
						</div>
						<div class="col-md-2 col-xs-12"></div>
						<div class="col-md-5 col-xs-12">
							<div class="form-group">
								<label class="text-left" for="father_email" id="father_email">{{__("Father’s email") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox"value="1" name="father_notify"></span> <input class="form-control" id="father_email" name="father_email" value="{{ $exStudent ? $exStudent->father_email : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>

							</div>
							<div class="form-group">
								<label class="text-left" for="mother_email">{{__("Mother’s email") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" value="1" name="mother_notify"></span> <input class="form-control" id="mother_email" name="mother_email" value="{{ $exStudent ? $exStudent->mother_email : '' }}" {{ $exStudent ? 'readonly' : '' }} type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>

							</div>
							<div class="form-group">
								<label class="text-left" for="email2" >{{__("Student's email") }} :</label>

									<div class="input-group">
										<span class="input-group-addon"><input type="checkbox" value="1" name="student_notify"></span>
										<input class="form-control" id="email2" name="email2" value="{{$searchEmail}}" type="text"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									</div>

							</div>
						</div>





				</div>





            </div>
        </div>


		<div class="col-lg-12 mt-4">
			<button type="submit" id="save_btn" name="save_btn" class="btn btn-success"><i class="fa fa-save"></i> {{ __('Save') }}</button>
			</div>

    </div>
</div>


</form>
