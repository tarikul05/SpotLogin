<div class="section_header_class">
	<label id="teacher_personal_data_caption">{{__('Settings') }}</label>
</div>
<div class="row">
    <!-- Tabs navs -->
	<div class="card-body">
    <nav class="col-lg-10">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-link active" id="clickCategory" data-bs-toggle="tab" data-bs-target="#tab_inner_part1" type="button" role="tab" aria-controls="nav-tab_inner_part1" aria-selected="false" href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_category.index',['school'=> $schoolId]) : route('event_category.index') }}">{{ __('Event Category') }}</a>
            <a class="nav-link" id="clickLocation" data-bs-toggle="tab" data-bs-target="#tab_inner_part2" type="button" role="tab" aria-controls="nav-tab_inner_part2" aria-selected="false"  href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_location.index',['school'=> $schoolId]) : route('event_location.index') }}">{{ __('Locations') }}</a>
            <a class="nav-link" id="clickLevel" data-bs-toggle="tab" data-bs-target="#tab_inner_part3" type="button" role="tab" aria-controls="nav-tab_inner_part3" aria-selected="false"  href="{{ auth()->user()->isSuperAdmin() ? route('admin_event_level.index',['school'=> $schoolId]) : route('event_level.index') }}">{{ __('Level') }}</a>
        </div>
    </nav>
	</div>
    <!-- Tabs navs -->
    <!-- Tabs content -->
	<div class="card col-lg-10">
		<div class="card-body bg-tertiary" style="z-index:999;">
    <form role="form" id="location_form" class="form-horizontal" method="post" action="{{route('event_location.create')}}">
        <div class="tab-content" id="tab_inner_part">
            <div id="tab_inner_part1" class="tab_inner tab-pane fade show active">
                <div class="tab-pane fade show active" id="tab_category" role="tabpanel" aria-labelledby="tab_category">
                    @csrf
                    <div class="section_header_class row">
                        <div class="col-md-2 col-3 cat_name d-none d-sm-block">
                            <label>{{ __('Category Name') }}</label>
                        </div>
                        <div class="col-md-2 col-3 inv_type d-none d-sm-block">
							@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                            <label class="invoice_type_label">{{ __('Invoice Type') }}</label>
							@endif
                        </div>
                        <div class="col-md-3 col-3 inv_type d-none d-sm-block">
							@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                            <label class="invoice_type_label">{{ __('Billing') }}</label>
							@endif
                        </div>
                    </div>
					<?php //echo '<pre>';print_r($eventCat); ?>
                    <div class="row">
                        <div id="add_more_event_category_div" class="col-md-10" style="z-index:98;">
                        @php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp
                            @foreach($eventCat as $cat)
                                <div class="col-md-12 add_more_event_category_row row border-top pb-2 pt-2 mb-5">
                                    <div class="col-md-3 col-5">
                                        <div class="form-group row">
                                            <div class="md-12 pt-2">
                                                <input type="hidden" name="category[{{$count}}][id]" value="<?= $cat->id; ?>">
                                                <div class="row">
                                                    <div class="col-lg-9">
                                                        <input class="form-control category_name" name="category[{{$count}}][name]" placeholder="{{ __('Category Name') }}" value="<?= $cat->title; ?>" type="text">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group ml-1" style="padding-left:10px;">
                                                            <input type="text" name="category[{{$count}}][bg_color_agenda]" value="{{!empty($cat->bg_color_agenda) ? $cat->bg_color_agenda : old('bg_color_agenda')}}"  class="colorpicker dot category_bg_color_agenda" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                    <div class="col-md-8 col-6">
                                        <div class="form-group row invoice_part">
                                            <div class="col-sm-3">
												@if($AppUI->isTeacherAdmin())
												<input type="hidden" class="invcat_name" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>>
												@endif
												@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
												<div>
                                                	<input type="radio" class="invcat_name" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>> <label> {{ __('School Invoiced') }}</label>
                                                </div>

												<div>
													<input type="radio" class="invcat_name" name="category[{{$count}}][invoice]" value="T" <?php if($cat->invoiced_type == 'T'){ echo 'checked'; }  ?>> <label> {{ __('Teacher Invoiced') }}</label>
												</div>
                                                @endif
                                            </div>
											<div class="col-sm-8">
												<div class="pack_invoice_area student form-group row" <?php if($cat->invoiced_type == 'T'){ echo 'style="display:none"'; }  ?>>
                                                    @if(!$AppUI->isTeacherAdmin())
													<div class="col-md-6">
														<label class="titl">Teachers</label>
														<div class="form-check">
															<label class="form-check-label" for="radio2{{$count}}">
																<input type="radio" class="form-check-input" id="radio2{{$count}}" name="category[{{$count}}][s_thr_pay_type]" value="0" <?php if($cat->s_thr_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label" for="radio{{$count}}">
																<input type="radio" class="form-check-input" id="radio{{$count}}" name="category[{{$count}}][s_thr_pay_type]" value="1" <?php if($cat->s_thr_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
															</label>
														</div>
													</div>
                                                    @endif
													<div class="col-md-6">
														@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
														<label class="titl">Students</label>
														@endif
														<div class="form-check">
															<label class="form-check-label" for="sradio2{{$count}}">
																<input type="radio" class="form-check-input" id="sradio2{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="0" <?php if($cat->s_std_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label" for="sradio{{$count}}">
																<input type="radio" class="form-check-input" id="sradio{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="1" <?php if($cat->s_std_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
															</label>
														</div>
														@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
														<div class="form-check">
															<label class="form-check-label" for="sradio3{{$count}}">
																<input type="radio" class="form-check-input" id="sradio3{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="2" <?php if($cat->s_std_pay_type == 2){ echo 'checked'; }  ?>>Packaged
															</label>
														</div>
														@endif
													</div>
												</div>
												<div class="pack_invoice_area teacher form-group row" <?php if($cat->invoiced_type == 'S'){ echo 'style="display:none"'; }  ?> >
													<div class="col-md-6">
														@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
														<label class="titl">Students</label>
														@endif
														<div class="form-check">
															<label class="form-check-label" for="tradio2{{$count}}">
																<input type="radio" class="form-check-input" id="tradio2{{$count}}" name="category[{{$count}}][t_std_pay_type]" value="0" <?php if($cat->t_std_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label" for="tradio{{$count}}">
																<input type="radio" class="form-check-input" id="tradio{{$count}}" name="category[{{$count}}][t_std_pay_type]" value="1" <?php if($cat->t_std_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
															</label>
														</div>
													</div>
												</div>
											</div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-1 col-1">
                                        @can('parameters-delete')
                                        <div class="form-group row">
                                            <div class="col-sm-5 pt-2">
                                                <button type="button" class="btn btn-theme-warn delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            @php $count++; endforeach @endphp
                        </div>

                    </div>

                </div>
            </div>
            <!-- End Tabs content -->
            <!-- Tabs content -->
            <div id="tab_inner_part2" class="tab_inner tab-pane tab-content">
                <div class="tab-pane fade show active" id="tab_location" role="tabpanel" aria-labelledby="tab_location">


                    <div class="section_header_class row">
                        <div class="col-md-3 col-9">
                            <label>{{ __('Location Name') }}</label>
                        </div>
                        <div class="col-md-2 col-2">
                            <label></label>
                        </div>
                    </div>
                    <div class="row">
                        <div id="add_more_location_div" class="col-md-8">
                            @php $countLocation= isset($eventLastLocaId->id) ? ($eventLastLocaId->id) : 1; @endphp
                            @foreach($locations as $loca)
                                <div class="col-md-12 add_more_location_row row">
                                    <div class="col-md-5 col-9">
                                        <div class="form-group row">
                                            <div class="col-sm-11">
                                                <input type="hidden" name="location[{{$countLocation}}][id]" value="<?= $loca->id; ?>">
                                                <input class="form-control location_name" name="location[{{$countLocation}}][name]" placeholder="{{ __('Location Name') }}" value="<?= $loca->title; ?>" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="offset-1 col-2">
                                        @can('parameters-delete')
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-theme-warn delete_location" data-location_id="<?= $loca->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            @php $countLocation++; endforeach @endphp
                        </div>
                        <div class="col-md-12">
                        @can('parameters-create-udpate')
                            <!--<button id="add_more_location_btn" data-last_location_id="{{$countLocation}}" type="button" class="btn bg-info text-white save_button"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Location') }}</button>-->
                        @endcan
                        </div>
                    </div>

                </div>
            </div>

            <!-- End Tabs content -->
            <!-- Tabs content -->
            <div id="tab_inner_part3" class="tab_inner tab-pane tab-content">
                <div class="tab-pane fade show active" id="tab_level" role="tabpanel" aria-labelledby="tab_level">

                    <div class="section_header_class row">
                        <div class="col-md-3 col-9">
                            <label>{{ __('Level Name') }}</label>
                        </div>
                        <div class="col-md-2 col-2">
                            <label></label>
                        </div>
                    </div>
                    <div class="row">
                        <div id="add_more_level_div" class="col-md-8">
                        @php $countLevel= isset($eventLastLevelId->id) ? ($eventLastLevelId->id) : 1; @endphp
                        @foreach($levels as $lvl)
                                <div class="col-md-12 add_more_level_row row">
                                    <div class="col-md-5 col-9">
                                        <div class="form-group row">
                                            <div class="col-sm-11">
                                                <input type="hidden" name="level[{{$countLevel}}][id]" value="<?= $lvl->id; ?>">
                                                <input class="form-control level_name" name="level[{{$countLevel}}][name]" placeholder="{{ __('Level Name') }}" value="<?= $lvl->title; ?>" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 offset-1 col-2">
                                        @can('parameters-delete')
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-theme-warn delete_level" data-level_id="{{ $lvl->id; }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            @php $countLevel++; endforeach @endphp
                        </div>
                        <div class="col-md-12">
                        @can('parameters-create-udpate')
                            <!--<button id="add_more_level_btn" type="button" data-last_level_id="{{$countLevel}}"  class="btn bg-info save_button text-white"><i class="fa fa-plus" aria-hidden="true"></i>{{ __('Add Another Level') }}</button>-->
                        @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
		</div>
	</div>


	@can('parameters-create-udpate')
	<div class="col-lg-2 btn_actions" style="position:fixed; right:0; z-index:9999;">
		<br><br><p></p>
		<div class="card" style="border-radius:8px 0 0 8px; background-color:#EEE;">
			<div class="card-body">
				<button id="add_more_event_category_btn" data-last_event_cat_id="{{$count}}" type="button" class="btn bg-info text-white save_button w-100"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add a category') }}</button>
				<button style="display: none;" id="add_more_location_btn" data-last_location_id="{{$countLocation}}" type="button" class="mt-1 btn bg-info text-white save_button w-100"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add a location') }}</button>
				<button style="display: none;" id="add_more_level_btn" type="button" data-last_level_id="{{$countLevel}}"  class="mt-1 btn bg-info save_button text-white w-100"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add a level') }}</button>
				<button id="save_btn_param" name="save_btn_param" class="mt-1 btn btn-success save_button w-100"><i class="fa fa-save"></i> Save settings</button>
			</div>
		</div>
	</div>
@endcan


    <!-- End Tabs content -->
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('.colorpicker').each(function() {
        var colorValue = $(this).val() || "{{ old('bg_color_agenda') }}";
        $(this).wheelColorPicker({
            sliders: "whsvp",
            preview: true,
            format: "css"
        }).wheelColorPicker('value', colorValue);
        console.log('couleur', colorValue)
    });
});

$(document).ready(function(){
    $('.colorpicker').on('colorpickerChange', function(event) {
        $(this).val(event.color.toString());
        console.log('new color', event.color.toString())
    });
});

function initializeColorpicker(element) {
    element.wheelColorPicker({
        sliders: "whsvp",
        preview: true,
        format: "css"
    });
}

$(document).ready(function(){
	$(document).on('click','#add_more_event_category_btn',function(){
		var lst_id = $(this).attr('data-last_event_cat_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_event_cat_id',incre);
		var resultHtml = `<span class="badge bg-info">new</span><div class="col-md-12 add_more_event_category_row row border-top mb-5">
			<div class="col-md-3 col-5">
				<div class="form-group row">
					<div class="col-sm-11 pt-2">
                        <div class="row">
                            <div class="col-lg-9">
						<input class="form-control category_name" name="category[`+lst_id+`][name]" placeholder="Category Name" type="text">
                        </div>
                        <div class="col-lg-3">
                        <div class="form-group ml-1" style="padding-left:10px;">
                            <input type="text" name="category[`+lst_id+`][bg_color_agenda]"  class="colorpicker dot category_bg_color_agenda" />
                        </div>
                    </div>
                    </div>
					</div>
				</div>
			</div>
			@if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
			<div class="col-md-8 col-6">
				<div class="form-group row invoice_part">
					<div class="col-sm-3">
						@if($AppUI->isTeacherAdmin())
						<input class="invcat_name" name="category[`+lst_id+`][invoice]" type="hidden" value="S" checked>
						@endif
						@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                        <div>
						  <input class="invcat_name" name="category[`+lst_id+`][invoice]" type="radio" value="S" checked> <label> School Invoiced</label>
					    </div>
					   <div>
						  <input class="invcat_name" name="category[`+lst_id+`][invoice]" type="radio" value="T"> <label> Teacher Invoiced </label>
					   </div>
                       @endif
                    </div>
					<div class="col-sm-8">
						<div class="pack_invoice_area student form-group row">
							@if(!$AppUI->isTeacherAdmin())
                            <div class="col-md-6">
								<label class="titl">Teachers</label>
								<div class="form-check">
									<label class="form-check-label" for="radio2`+lst_id+`">
										<input type="radio" class="form-check-input" id="radio2`+lst_id+`" name="category[`+lst_id+`][s_thr_pay_type]" value="0">Hourly rate
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label" for="radio`+lst_id+`">
										<input type="radio" class="form-check-input" id="radio`+lst_id+`" name="category[`+lst_id+`][s_thr_pay_type]" value="1">Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
									</label>
								</div>
							</div>
                            @endif
							<div class="col-md-6">
								@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
								<label class="titl">Students</label>
								@endif
								<div class="form-check">
									<label class="form-check-label" for="sradio2`+lst_id+`">
										<input type="radio" class="form-check-input" id="sradio2`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="0">Hourly rate
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label" for="sradio`+lst_id+`">
										<input type="radio" class="form-check-input" id="sradio`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="1">Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
									</label>
								</div>
								@if($AppUI->isSchoolAdmin())
								<div class="form-check">
									<label class="form-check-label" for="sradio3`+lst_id+`">
										<input type="radio" class="form-check-input" id="sradio3`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="2">Packaged
									</label>
								</div>
								@endif
							</div>
						</div>
						<div class="pack_invoice_area teacher form-group row" style="display:none">
							<div class="col-md-6">
								@if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
								<label class="titl">Students</label>
								@endif
								<div class="form-check">
									<label class="form-check-label" for="tradio2`+lst_id+`">
										<input type="radio" class="form-check-input" id="tradio2`+lst_id+`" name="category[`+lst_id+`][t_std_pay_type]" value="0">Hourly rate
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label" for="tradio`+lst_id+`">
										<input type="radio" class="form-check-input" id="tradio`+lst_id+`" name="category[`+lst_id+`][t_std_pay_type]" value="1">Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			<div class="col-md-1 col-1">
				<div class="form-group row">
					<div class="col-sm-5 pt-3">
						<button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_event_category_div").append(resultHtml);

        initializeColorpicker($("#add_more_event_category_div .colorpicker").last());

		window.scrollTo(0, document.body.scrollHeight);
	})
	$(document).on('click','.delete_event',function(){
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_event_cat_id',incre);


		if (!confirm('{{ __("Are you want to delete?") }}')) return
		var lst_id = $('#add_more_event_category_btn').attr('data-last_event_cat_id');
		var incre = (parseInt(lst_id)-1);
		$('#add_more_event_category_btn').attr('data-last_event_cat_id',incre);
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('category_id');
		var current_obj = $(this);
		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-category/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){

					if(response.status == 1){
						current_obj.parents('.add_more_event_category_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_event_category_row').remove();
		}
	});


	// level part
	$(document).on('click','#add_more_level_btn',function(){
		var lst_id = $(this).attr('data-last_level_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_level_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_level_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control level_name" name="level[`+lst_id+`][name]" placeholder="Level Name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_level"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_level_div").append(resultHtml);
		window.scrollTo(0, document.body.scrollHeight);
	});


	$(document).on('click','#clickCategory',function(){
		$('#add_more_event_category_btn').fadeIn();
		$('#add_more_location_btn').fadeOut();
		$('#add_more_level_btn').fadeOut();
	});
	$(document).on('click','#clickLocation',function(){
		$('#add_more_event_category_btn').fadeOut();
		$('#add_more_location_btn').fadeIn();
		$('#add_more_level_btn').fadeOut();
	});
	$(document).on('click','#clickLevel',function(){
		$('#add_more_event_category_btn').fadeOut();
		$('#add_more_location_btn').fadeOut();
		$('#add_more_level_btn').fadeIn();
	});


	$(document).on('click','.delete_level',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('level_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_level_id',incre);

		if (!confirm('{{ __("Are you want to delete?") }}')) return
		var lst_id = $('#add_more_level_btn').attr('data-last_level_id');
		var incre = (parseInt(lst_id)-1);
		$('#add_more_level_btn').attr('data-last_level_id',incre);
		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-level/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){
					if(response.status == 1){
						current_obj.parents('.add_more_level_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_level_row').remove();
		}
	});


	// location part
	$(document).on('click','#add_more_location_btn',function(){
		var lst_id = $(this).attr('data-last_location_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_location_id',incre);

		var resultHtml = `<div class="col-md-12 add_more_location_row row">
			<div class="col-md-5 col-9">
				<div class="form-group row">
					<div class="col-sm-11">
						<input class="form-control location_name" name="location[`+lst_id+`][name]" placeholder="location name" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-2 offset-1 col-2">
				<div class="form-group row">
					<div class="col-sm-5">
						<button type="button" class="btn btn-theme-warn delete_location"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>`;

		$("#add_more_location_div").append(resultHtml);
		window.scrollTo(0, document.body.scrollHeight);
	})

	$(document).on('click','.delete_location',function(){
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var id = $(this).data('location_id');
		var current_obj = $(this);
		var lst_id = $(this).attr('data-r_id');
		var incre = parseInt(lst_id);
		$(this).attr('data-last_location_id',incre);

		if (!confirm('{{ __("Are you want to delete?") }}')) return
		var lst_id = $('#add_more_location_btn').attr('data-last_location_id');
		var incre = (parseInt(lst_id)-1);
		$('#add_more_location_btn').attr('data-last_location_id',incre);

		if(id){
			$.ajax({
				url: BASE_URL + '/remove-event-location/'+id,
				type: 'DELETE',
				dataType: 'json',
				data: {
					"id": id,
					"_token": csrfToken,
				},
				success: function(response){
					if(response.status == 1){
						current_obj.parents('.add_more_location_row').remove();
					}
				}
			})
		}else{
			current_obj.parents('.add_more_location_row').remove();
		}

	});


	// save functionality
	$('#save_btn_param').click(function (e) {
		var formData = $('#location_form').serializeArray();
		// var eventFormData = $('#event_form').serializeArray();
		// var levelFormData = $('#level_form').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

		var error = '';

		$( ".location_name" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
		$( ".level_name").each(function( key, value ) {
			var lvname = $(this).val();
			if(lvname=='' || lvname==null || lvname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
		$( ".category_name" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
        formData.push({
			"name": "school_id",
			"value": $("#school_id").val(),
		});

		//console.log(formData);
		if(error < 1){
			$.ajax({
				url: BASE_URL + '/add-school-parameters',
				data: formData,
				type: 'POST',
				dataType: 'json',
				success: function(response){
					console.log(response);
					if(response.status == 1){
						$('#modal_parameter').modal('show');
						$("#modal_alert_body").text('{{ __('Successfully registered') }}');
						//window.location.reload();
						var url = window.location.href;
						//const url = "http://testing.com/path?empty&value1=test&id=3";

						setTimeout(() => {
							url = addOrChangeParameters( url, {tab:'tab_5'} )
							window.location.href = url;
						}, "1000")
					}
				}
			})
		}else{
			$('#modal_parameter').modal('show');
			$("#modal_alert_body").text('{{ __('Required field is empty') }}');
		}
	});


	function addOrChangeParameters( url, params )
	{
		let splitParams = {};
		let splitPath = (/(.*)[?](.*)/).exec(url);
		if ( splitPath && splitPath[2] )
			splitPath[2].split("&").forEach( k => { let d = k.split("="); splitParams[d[0]] = d[1]; } );
		let newParams = Object.assign( splitParams, params );
		let finalParams = Object.keys(newParams).map( (a) => a+"="+newParams[a] ).join("&");
		return splitPath ? (splitPath[1] + "?" + finalParams) : (url + "?" + finalParams);
	}

//if student package select it will automatic select fix price for teacher
    $(document).on('click', "input[name$=\'[s_std_pay_type]\'][value='2']", function(event) {
        if ($(this).prop("checked")) {
            $(this).closest('.pack_invoice_area').find("input[name$=\'[s_thr_pay_type]\'][value='1']").prop('checked', true)
        }
    });

    //if student package selected, teacher can't be move on hourly rate
    $(document).on('click', "input[name$=\'[s_thr_pay_type]\'][value='0']", function(event) {
        var dd = $(this).closest('.pack_invoice_area').find("input[name$=\'[s_std_pay_type]\'][value='2']").prop('checked')
        if (dd) {
            alert("If the student is packaged the teacher can not be paid hourly")
            event.preventDefault();
        }
    });


})

	$('#add_more_event_category_div').on('click', '.invcat_name', function() {
		var type = $(this).val();
		if(type == 'T'){
			$(this).closest(".invoice_part").find('.pack_invoice_area.student').hide();
			$(this).closest(".invoice_part").find('.pack_invoice_area.teacher').show();
		}else if(type == 'S'){
			$(this).closest(".invoice_part").find('.pack_invoice_area.teacher').hide();
			$(this).closest(".invoice_part").find('.pack_invoice_area.student').show();
		}
	});

</script>
