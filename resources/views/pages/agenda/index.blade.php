@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<link href="{{ asset('css/datetimepicker-lang/bootstrap-datetimepicker.css')}}" rel="stylesheet">
<link href="{{ asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{ asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data-10-year-range.js" integrity="sha512-QSV7x6aYfVs/XXIrUoerB2a7Ea9M8CaX4rY5pK/jVV0CGhYiGSHaDCKx/EPRQ70hYHiaq/NaQp8GtK+05uoSOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.js"></script> -->
<script src="{{ asset('js/jquery.table2excel.js')}}"></script>
<link href="{{ asset('css/admin_main_style.css')}}" rel='stylesheet' />
<!-- add new assets for modal of add event,lesson -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- end the assets area -->
@endsection

@section('content')
<div class="content agenda_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="agendaForm" name="agendaForm" class="form-horizontal" role="form">
            @csrf
            <header class="panel-heading" style="border: none;">
				<div class="row panel-row pt-2" style="margin:0;">
					<div class="col-md-5 col-xs-12 header-area">

							<div class="page_header_class pt-1" style="position: static;">
                                <h1 for="calendar" class="titleCalendar text-dynamic" id="cal_title" style="display: block;">
                                  {{__('Agenda')}} :
                                </h1>
                                <i class="fa-solid fa-magnifying-glass search-icon searchForEvent"></i>
                                <i class="fa fa-close mt-2 searchForEvent close-icon" style="display:none; font-size:21px;"></i>
                                <div id="dateTime">
                                <span style="font-size:11px;">[ {{ $myCurrentTimeZone }} ] {{ \Carbon\Carbon::now()->format('M, d') }} <i class="ml-1 fa-regular fa-clock fa-flip-horizontal"></i> <span id="currentTimer"></span></span>
                                <em id="eventInProgress" class="text-success" style="font-size:11px; margin-left: 7px; display:none;">
                                    <i class="fa-solid fa-bell fa-beat"></i>
                                    Lesson in progress...
                                </em>
                                </div>
                                <div id="messageContainer" style="display: none;"></div>
							</div>

					</div>
                   <div class="col-md-4  btn-area pt-2 align-items-end">
                        <div class="d-flex justify-content-end btn-group cal_top">
                            <input type="hidden" name="school_id" id="school_id" value="{{$schoolId}}">
                            <input type="hidden" name="max_teachers" id="max_teachers" value="<?php if($school){ echo $school->max_teachers; } ?>">
                            <input type="hidden" name="edit_view_url" id="edit_view_url" value="">
							<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="">
							<input type="hidden" name="user_role" id="user_role" value="{{$user_role}}">
                            <input type="hidden" name="coach_user" id="coach_user" value="{{$coach_user}}">
                            <input type="hidden" name="person_id" id="person_id" value="">
							<input type="hidden" name="evt_t_id" id="evt_t_id" value="">
                            <input type="hidden" name="event_teacher_id" size="14px" id="event_teacher_id" value="0">
                            <input type="hidden" name="date_from" id="date_from" value="">
                            <input type="hidden" name="date_to" id="date_to" value="">
                            <input type="hidden" name="view_mode" size="14px" id="view_mode" value="">
                            <input type="hidden" name="week_day" id="week_day" value="">
                            <input type="hidden" name="month_day" id="month_day" value="">
                            <input type="hidden" name="prevnext" size="14px" id="prevnext" value="">
                            <input type="hidden" name="get_event_id" id="get_event_id" value="">
                            <input type="hidden" name="get_validate_event_id" id="get_validate_event_id" value="">
                            <input type="hidden" name="get_non_validate_event_id" id="get_non_validate_event_id" value="">
                            <input type="hidden" name="get_non_validate_event_delete_id" id="get_non_validate_event_delete_id" value="">
                            <input type="hidden" name="copy_date_from" id="copy_date_from" value="">
                            <input type="hidden" name="copy_date_to" id="copy_date_to" value="">
                            <input type="hidden" name="copy_school_id" id="copy_school_id" value="">
                            <input type="hidden" name="copy_event_id" id="copy_event_id" value="">
                            <input type="hidden" name="copy_student_id" id="copy_student_id" value="">
                            <input type="hidden" name="copy_teacher_id" id="copy_teacher_id" value="">
                            <input type="hidden" name="copy_view_mode" id="copy_view_mode" value="">
                            <input type="hidden" name="copy_week_day" id="copy_week_day" value="">
                            <input type="hidden" name="copy_month_day" id="copy_month_day" value="">
                            <input type="hidden" name="event_school_id" size="14px" id="event_school_id" value="{{$schoolId}}">
                            <input type="hidden" name="event_type_id" size="14px" id="event_type_id" value="0">
                            <input type="hidden" name="event_student_id" size="14px" id="event_student_id" value="0">
                            <input type="hidden" name="event_teacher_id" size="14px" id="event_teacher_id" value="0">
                            <input type="hidden" name="event_location_id" size="14px" id="event_location_id" value="0">
                            <input type="hidden" name="event_category_id" size="14px" id="event_category_id" value="0">
                            <input type="hidden" name="event_school_all_flag" size="14px" id="event_school_all_flag" value="0">
                            <input type="hidden" name="event_type_all_flag" size="14px" id="event_type_all_flag" value="1">
                            <input type="hidden" name="event_student_all_flag" size="14px" id="event_student_all_flag" value="1">
                            <input type="hidden" name="event_teacher_all_flag" size="14px" id="event_teacher_all_flag" value="1">
                            <input type="hidden" name="event_location_all_flag" size="14px" id="event_location_all_flag" value="1">
                            <input type="hidden" name="event_category_all_flag" size="14px" id="event_category_all_flag" value="0">

                            <div id="button_menu_div" class="btn-group buttons d-flex" onclick="SetEventCookies()">
                                @php
                                $icalPersonal = route('ical.personalEvents');
                                if(!empty($schoolId)){
                                    $icalPersonal = route('ical.personalEventss',[$schoolId]);
                                }
                                @endphp
                                <!-- <a href="{{ $icalPersonal }}"
                                id="personal_ics1"
                                target="_blank" class="btn btn-sm btn-theme-warn light-blue-txt">
                                    <em class="glyphicon glyphicon-remove"></em>
                                    <span id ="btn_validate_events_cap">Copy my schedule</span>
                                </a> -->



                                <div class="styledropdownActions d-none d-md-block" style="display:inline-block; z-index:999;">
                                    <div class="btn-group">
                                        <div class="dropdown" id="dropdownActions" style="margin-top:0; padding-top:0;">
                                        <span class="btn btn-theme-outline">Actions <i class="fa fa-caret-down"></i></span>
                                        <div class="dropdown-content">
                                            <a style="display: none; display:inline-block; min-width: 190px;" href="#" id="btn_validate_events" target="_blank" class="btn btn-sm btn-info m-1 mb-2"><i class="fa-solid fa-lock"></i> <span id ="btn_validate_events_cap">{{__('Validate All')}}</span></a>
                                            <a style="display: none; display:inline-block; min-width: 190px;" href="#" id="btn_delete_events" target="_blank" class="btn btn-sm btn-theme-warn m-1 mb-2"><i class="fas fa-trash"></i> <span id ="btn_delete_events_cap">{{__('Delete All')}}</span></a>

                                            <a style="display: none; display:inline-block; min-width: 190px;" href="#" id="btn_copy_events" target="_blank" class="btn btn-theme-outline m-1 mb-2"><i class="far fa-copy"></i> <span id ="btn_copy_events_cap">{{__('Copy All')}}</span></a>
                                            <a style="display: none; display:inline-block; min-width: 190px;" href="#" id="btn_goto_planning" target="_blank" class="btn btn-theme-outline m-1 mb-2"><em class="glyphicon glyphicon-fast-forward"></em> <span id ="btn_goto_planning_cap">{{__('Paste')}}</span></a>



                                            @if(!$AppUI->isStudent() && !$AppUI->isParent())
                                            <a style="display: none; display:inline-block; min-width: 190px;" href="#" id="btn_export_events" target="_blank" class="btn btn-theme-outline m-1"><img src="{{ asset('img/excel_icon.png') }}"  width="17" height="auto"/>
                                                <span id ="btn_export_events_cap">Excel</span></a>
                                            @endif

                                            @if($AppUI->isStudent() || $AppUI->isParent())
                                                <a style="display:inline-block; min-width: 190px;" href="../{{$schoolId}}/student-off" title="" class="btn btn-sm btn-theme-success m-1 mb-2"><i class="glyphicon glyphicon-plus"></i> {{ __("Add") }}</a>
                                                <!--<a style="display:inline-block; min-width: 190px;" href="{{ route('student.availabilities') }}" class="btn btn-sm btn-info m-1 mb-2"><i class="fa-solid fa-calendar"></i> <span id ="btn_validate_events_cap">{{__('Availability')}}</span></a>-->
                                            @endif

                                        </div>
                                        </div>
                                </div>
                                <div class="btn-group">
                                <!--<a style="display: none;" href="#" id="btn_validate_events" target="_blank" class="btn btn-sm btn-theme-warn"><em class="glyphicon glyphicon-remove"></em><span id ="btn_validate_events_cap">Validate All</span></a>
                                <a style="display: none;" href="#" id="btn_delete_events" target="_blank" class="btn btn-sm btn-theme-warn"><em class="glyphicon glyphicon-remove"></em><span id ="btn_delete_events_cap">Delete All</span></a>-->
                                <!--<button style="display: none; max-width:80px; display:inline-block;" href="#" id="btn_copy_events" target="_blank" class="btn btn-theme-outline"><i class="far fa-copy"></i> <span id ="btn_copy_events_cap">{{__('Copy')}}</span></button>
                                <button style="display: none; max-width:80px; display:inline-block;" href="#" id="btn_goto_planning" target="_blank" class="btn btn-theme-outline"><em class="glyphicon glyphicon-fast-forward"></em><span id ="btn_goto_planning_cap">{{__('Paste')}}</span></button>-->
                                @if(!$AppUI->isStudent())
                                    <!--<a href="#" id="btn_export_events" target="_blank" class="btn btn-theme-outline">
                                        <img src="{{ asset('img/excel_icon.png') }}"  width="17" height="auto"/>
                                        <span id ="btn_export_events_cap">Excel</span>
                                    </a>-->
                                @endif
                                </div>
                                </div>

                            </div>

                          </div>
                        </div>

                    <div class="col-md-3 text-right btn-area align-items-end text-center">
                        @if(!$AppUI->isStudent() && !$AppUI->isParent())
                        <input type="input" name="search_text" class="form-control search_text_box search_box_agenda" id="search_text" value="" placeholder="Search" style="margin-top:10px; margin-left:-1px; width:100%!important; border:1px solid #b3d6ec!important;">
                        @else
                        <input type="input" name="search_text" class="form-control search_text_box search_box_agenda" id="search_text" value="" placeholder="Search" style="margin-top:10px; margin-left:-1px; width:100%!important; border:1px solid #b3d6ec!important;">
                        @endif
                    </div>
				</div>

            <div class="clearfix"></div>
            <div class="row" style="margin:0;">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <section class="panel cal_area" style="border: 0;box-shadow: none;">
                        <label id="loading" style="display:none;">Loading....</label>
                        <form action="#" method="post">
                            <div class="clearfix"></div>
                            <div class="row" id="school_cal">
                                <div class="col-md-9">
                                    <!-- fullcalendar -->
                                    <div style="margin-bottom: 5px; margin:0 auto; width:100%; margin-top:6px;" class="agendaContent1 mb-2">
                                        <div style="display: flex; align-items: flex-end; justify-content: space-between;">
                                        <div class="btn-group" style="margin-right:10%; width:22%;">
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_prev"><i class="fa fa-chevron-left" style="color: #b3d6ec;"></i></button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_today" style="padding:2px;">{{__('Today')}}</button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_next"><i class="fa fa-chevron-right" style="color: #b3d6ec;"></i></button>
                                        </div>
                                    <div style="display: flex; align-items:flex-end; justify-content:end;">
                                    <div class="btn-group" id="calendar_dropdown" style="width:50%; align-items:flex-end; margin-right:10px;">
                                            <button type="button" class="btn btn-default w-100 btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa-regular fa-eye"></i>  {{__('Views')}} <i class="fa fa-caret-down"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="calendar_dropdown">
                                                <button class="dropdown-item calendar_buttons" id="btn_day" type="button" style="font-size:14px;"><i class="fa-solid fa-circle-arrow-right"></i> {{__('Day')}}</button>
                                                <button class="dropdown-item calendar_buttons" id="btn_week" type="button" style="font-size:14px;"><i class="fa-solid fa-circle-arrow-right"></i> {{__('Week')}}</button>
                                                <button class="dropdown-item calendar_buttons" id="btn_month" type="button" style="font-size:14px;"><i class="fa-solid fa-circle-arrow-right"></i> {{__('Month')}}</button>
                                                <button class="dropdown-item calendar_buttons" id="btn_list" type="button" style="font-size:14px;"><i class="fa-solid fa-circle-arrow-right"></i> {{__('Monthly schedule')}}</button>
                                                <button class="dropdown-item calendar_buttons" id="btn_current_list" type="button" style="font-size:14px;"><i class="fa-solid fa-circle-arrow-right"></i> {{__('Daily schedule')}}</button>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: flex-end; justify-content: flex-end; width: 50%;">
                                            @if(!$AppUI->isStudent() && !$AppUI->isParent())
                                                <button type="button" class="btn btn-default w-100 btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{__('Actions')}} <i class="fa fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="calendar_dropdown" style="padding-top:0; padding-bottom:0;">
                                                    <button class="dropdown-item calendar_buttons text-success" id="add_lesson_btn_mobile" type="button" style="font-size:14px;"><i class="fa-regular fa-plus"></i> Add new</button>
                                                    <button class="dropdown-item calendar_buttons" id="btn_copy_events_mobile" type="button" style="font-size:14px;"><i class="fa-regular fa-copy"></i> {{__('Copy')}}</button>
                                                    <button class="dropdown-item calendar_buttons" id="btn_goto_planning_mobile" type="button"><i class="fa-regular fa-paste"></i> {{__('Paste')}}</button>
                                                    <button class="dropdown-item calendar_buttons badge-info" id="btn_validate_events_mobile" type="button"><i class="fa-solid fa-lock"></i> {{__('Validate All')}}</button>
                                                    <button class="dropdown-item calendar_buttons badge-warning" id="btn_delete_events_mobile" type="button"><i class="fas fa-trash"></i> {{__('Delete All')}}</button>
                                                </div>
                                            @else
                                                <a href="../{{$schoolId}}/student-off" title="" class="btn btn-sm btn-theme-success" style="border-radius: 4px!important; max-width: 80px; height: 35px;"><i class="glyphicon glyphicon-plus"></i> {{ __("Add") }}</a>
                                                <div id="btn_copy_events_mobile" style="display: none;"></div>
                                                <div id="btn_goto_planning_mobile" style="display: none;"></div>
                                                <div id="btn_validate_events_mobile" style="display: none;"></div>
                                                <div id="btn_delete_events_mobile" style="display: none;"></div>
                                                @endif
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div id="calendar" class="calendarContent"></div>
                                    <div style="margin-top: 15px;" class="agendaContent2">
                                        <div class="btn-group" style="margin-right:5px;">
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_prev"><i class="fa fa-chevron-left" style="color: #3b75bf;"></i></button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_today">{{__('Today')}}</button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_next"><i class="fa fa-chevron-right" style="color: #3b75bf;"></i></button>
                                        </div>
                                        <button class="btn btn-sm calendar_buttons" id="btn_day2" type="button">{{__('Day')}}</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_week2" type="button">{{__('Week')}}</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_month2" type="button">{{__('Month')}}</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_list2" type="button">{{__('List')}}</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_current_list2" type="button">{{__('Current List')}}</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center pt-5" id="loaderFilters">
                                        <i style="color:#DDD;" class="fas fa-spinner fa-spin fa-2x"></i>
                                    </div>

                                    <div id="loadingSearch" class="text-center"></div>
                                    <div id="resultSearch" class="p-2"></div>
                                    <div id="listEventsSearch"></div>

                                 <div id="allFilters" style="display:none;">


                                    <div style="margin-bottom:40px;" id="datepicker_month"></div>

                                    <div id="event_type_div" name="event_type_div" class="selectdiv mt-0">
                                        <select class="form-control" multiple="multiple" id="event_type" name="event_type[]" >
                                            @foreach($event_types as $key => $event_type)
                                                <option value="{{ $key }}">{{ $event_type }}</option>
                                            @endforeach
                                        </select>
                                        <select style="display:none;" class="form-control" multiple="multiple" id="event_types_all" name="event_types_all[]" style="margin-bottom: 0px;" >
                                            @foreach($event_types_all as $key => $event_type)
                                                <option value="{{ $key }}">{{ $event_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="event_student_div" name="event_student_div" class="selectdiv">
                                        <select class="form-control" multiple="multiple" id="event_student" name="event_student[]" style="margin-bottom: 0px;">
                                            @foreach($students as $key => $student)
                                                <option value="{{ $student->id }}">{{ $student->firstname }} {{ $student->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>




                                    <div id="event_location_div" name="event_location_div" class="selectdiv">
                                        <select class="form-control" multiple="multiple" id="event_location" name="event_location[]" style="margin-bottom: 15px;">
                                            @foreach($locations as $key => $location)
                                                <option value="{{ $location->id }}">{{ $location->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div id="event_school_div" name="event_school_div" class="selectdiv" style="opacity: 0 !important; visibility: hidden !important;">
                                        <select class="form-control" multiple="multiple" id="event_school" name="event_school[]" style="margin-bottom: 15px;" >
                                            @foreach($schools as $key => $this_school)
                                                <option {{ ( !empty($schoolId) && $schoolId == $this_school->id ? 'selected' : '') }}
                                                    value="{{ $this_school->id }}">{{ $this_school->school_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div>
                                        <div class="btn-group btn-xs pull-left" style="padding:0;width:100%;">


                                            <div id="event_teacher_div" name="event_teacher_div" class="selectdiv" style="opacity: 0 !important; visibility: hidden !important;">
                                                <select class="form-control" multiple="multiple" id="event_teacher" name="event_teacher[]" style="margin-bottom: 0px;">
                                                    @foreach($teachers as $key => $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->firstname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div id="list-button" class="pull-right form-inline">
                                                <button id="list_button" style="height:27px;display: none;" class="btn btn-primary btn-sm" type="button">list</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div style="margin-top: 25px;">
                                <div id="agenda_list" width="350px" border="1" style="display:none;margin-top: auto;">
                                    <!-- class="display row-border" -->
                                    <table id="agenda_table" name="agenda_table" cellpadding="0" cellspacing="0" width="99%" class="table-responsive agenda_table_class tablesorter">
                                        <thead>
                                        <tr href="">
                                        <th width="8%"><label id="row_hdr_date">Date</label></th>
                                        <th width="10%">Heure de d`part</th>
                                        <th width="10%">Heure de fin</th>
                                        <th width="15%">Nombre d`tudiants</th>
                                        <th width="20%">Nom de l`tudiant (s)</th>
                                        <th width="17%">Cours</th>
                                        <th width="10%">Dur`e en minutes</th>
                                        <th width="10%">Professeur</th>
                                        </tr>
                                        </thead>
                                    <!--
                                    <tbody id="agenda_table_body" name="agenda_table_body">
                                    </tbody>
                                    -->
                                    </table>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </header>
		</form>
	</div>
</div>


<!-- Modal for add event,lesson,student and coach off -->
<div class="modal fade login-event-modal" id="addAgendaModal" name="addAgendaModal" tabindex="-1" aria-hidden="true" aria-labelledby="addAgendaModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="add_lesson" method="post"  name="add_lesson" role="form">
            @csrf
            <input id="save_btn_value" name="save_btn_more" type="hidden" class="form-control" value="0">

            <div class="modal-header text-white" style="background-color: #152245;">
                <h6 class="modal-title page_header_class">
                  <i class="fa-regular fa-calendar-plus"></i>  {{ __('Add an event / lesson') }}
                </h6>
                <button type="button" class="close" id="modalClose" class="btn btn-light" data-bs-dismiss="modal" style="margin-top:-11px;">
                    <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="addAgendaModalClass" id="addAgendaModalWin">


                    <div id="infoLesson" class="text-center alert alert-default">
                        <b>{{ __('Create a lesson') }}</b><br>
                        <h4 id="displayDate"></h4>
                        <i class="fa-solid fa-circle-info"></i> {{ __('Create a lesson with a minimum attendance of 1 student and a maximum duration of 1 day') }}.
                    </div>
                    <div id="infoLessonEvent" class="text-center alert alert-default">
                        <b>{{ __('Create an event') }}</b><br>
                        <h4 id="displayDateEvent"></h4>
                            <i class="fa-solid fa-circle-warning"></i> {{ __('Create an event for 1 or more complete days') }}.
                    </div>

                            <div class="col-md-10  offset-md-1 mt-4">

                                <div class="row">
                                    <label class="col-lg-3 col-sm-3 text-left">{{__('Agenda Type')}}</label>
                                    <div class="col-lg-9 col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa-solid fa-list-ul"></i>
                                                </span>
                                                <select class="form-control" id="agenda_select">
                                                    <option value="1">{{__('Lesson')}}</option>
                                                    <option value="2">{{__('Event')}}</option>
                                                    @if(!$AppUI->isTeacherMediumMinimum())
                                                    <option value="3">{{__('Student Off')}}</option>
                                                    @endif
                                                    <option value="4">{{__('Coach off')}}</option>
                                                </select>
                                            </div>
                                    </div>
                                </div>
                            </div>


                                <div class="col-md-10 offset-md-1 mt-4">
                                    <div class="row">
                                <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }}</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-regular fa-file-lines"></i>
                                            </span>
                                            <input id="Title" name="title" type="text" class="form-control" placeholder="{{ __('Title here') }}" value="">
                                        </div>
                                </div>
                                    </div>
                                </div>

                                <div class="col-md-10 offset-md-1">

                                @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                                <div class="row show_coach_off hide_on_off mt-4 mb-4">
                                <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher') }} :</label>
                                @endif
                                @if(!$AppUI->isSchoolAdmin() && !$AppUI->isTeacherSchoolAdmin())
                                <input style="opacity: 0 !important; visibility: hidden !important; height: 0 !important" type="text" name="teacher_select" value="{{ $AppUI->person_id }}" readonly>
                                @else
                                <div class="col-sm-9">
                                    <div class="selectdiv">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-solid fa-list-ul"></i>
                                            </span>
                                        <select class="form-control" id="teacher_select" name="teacher_select">
                                                <option value="">{{__('Select Professor') }}</option>
                                            @foreach($professors as $key => $professor)
                                                <option value="{{ $professor->teacher_id }}" {{ old('teacher_select') == $professor->teacher_id ? 'selected' : ''}}>{{ $professor->nickname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                @endif


                                @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                                <div class="row event show_coach_off hide_on_off mb-4">
                                    <label class="col-lg-3 col-sm-3 text-left" for="event_invoice_type" id="invoice_cat_type_id">{{__('Category type') }} :</label>
                                    <div class="col-sm-9">
                                        <div class="selectdiv">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa-solid fa-list-ul"></i>
                                                </span>
                                                <select class="form-control" id="event_invoice_type" name="event_invoice_type" disabled>
                                                    <option value="">{{__('Select Type') }}</option>
                                                    <option value="T">{{__('Teacher invoice')}}</option>
                                                    <option value="S">{{__('School invoice')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <input style="opacity: 0; visibility: hidden; height: 0 !important" type="text" id="event_invoice_type" name="event_invoice_type"  value="T">
                                @endif

                                </div>


                                <div class="col-md-10 offset-md-1 mt-1">



                                <div class="lesson hide_on_off row mb-4">
                                    <label class="col-lg-3 col-sm-3 text-left" for="category_select" id="category_label_id">{{__('Category') }}</label>
                                    <div class="col-sm-9">
                                        <div class="selectdiv">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa-solid fa-list-ul"></i>
                                            </span>
                                            <select class="form-control" id="category_select" name="category_select" @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()) disabled @endif>
                                                @foreach($eventCategoryList as $key => $eventcat)
                                                    <option s_thr_pay_type="{{ $eventcat->s_thr_pay_type }}" s_std_pay_type="{{  $eventcat->s_std_pay_type }}" t_std_pay_type="{{  $eventcat->t_std_pay_type }}" value="{{ $eventcat->id }}" category_type="{{ $eventcat->invoiced_type }}" @if(session('last_cat') == $eventcat->id) selected @endif>{{ $eventcat->title }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row hide_on_off">
                                    <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }}</label>
                                    <div class="col-sm-9">
                                        <div class="selectdiv">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa-solid fa-list-ul"></i>
                                                </span>
                                                <select class="form-control" id="location" name="location">
                                                    <option value="" selected>Select Location</option>
                                                    @foreach($locations as $key => $location)
                                                        <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : ''}}>{{ $location->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                </div>

                            <div class="tab-content" id="agenda_form_area" style="display:none">
                                <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">

                                            <div class="row m-2">
                                                <div class="col-md-10 offset-md-1 mt-4">

                                                    <div class="card bg-tertiary hide_coach_off p-3" style="border-color:#b3d6ec;"><!--card-->
                                                        <!--<label class="text-left col-lg-12 row col-sm-12 text-left" for="availability_select" id="visibility_label_id">
                                                           <b>{{__('Select student(s)') }}</b>
                                                            <input checked type="checkbox" name="check-students-availability" id="check-students-availability"> {{__('Show students availability') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('If checked, you will be able to show students\'s availability if student registered it.')}}"></i>
                                                        </label>-->

                                                        <!--<label class="text-left col-lg-12 col-sm-12 text-left" for="availability_select" id="visibility_label_id2">
                                                           <input type="checkbox" name="check-students-availability2" id="check-students-availability2"> {{__('Show only students with availabilty') }}
                                                        </label>-->
                                                      
                                                        <div id="studentListAvailabilities"></div>

                                                            <div class="student_list pt-4">
                                                            <label>  <b>{{__('Select student(s)') }}</b></label>
                                                                <div class="input-group">  
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-users"></i>
                                                                    </span>
                                                                <select class="multiselect" id="student" name="student[]" multiple="multiple">
                                                                    @foreach($studentsbySchool as $key => $student)
                                                                        <div>
                                                                            <option value="{{ $student->student_id }}" {{ old('student') == $student->id ? 'selected' : '' }}>
                                                                            @php
                                                                            $studentName = App\Models\Student::find($student->student_id);
                                                                            @endphp
                                                                            {{ $studentName->firstname }} {{ $studentName->lastname }}</option>
                                                                        </div>
                                                                    @endforeach
                                                                </select>
                                                                </div>
                                                                <br>
                                                                <div id="studentlistAway"></div>
                                                                <br>
                                                            </div>

                                                        <div class="col-sm-2 p-l-n p-r-n">
                                                           <span class="no_select" id="std-check-div"> <input type="checkbox" name="student_empty" id="student_empty"> {{__('do not select') }} <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('If you wish to not select any students for the lesson, for ’school invoiced’ lesson with a many students for example. Remember that if no students are selected, no invoice will be generated for them for that lesson.')}}"></i> </span>
                                                        </div>
                                                    </div>








                                                    <div class="mt-5 form-group row not-allday">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
                                                        <div class="col-sm-9 row">
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="start_date_div">
                                                                    <input id="start_date" name="start_date" type="text" class="form-control" value="{{old('start_date')}}" autocomplete="off">
                                                                    <input type="hidden" name="zone" id="zone" value="<?php echo $timezone; ?>">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 offset-md-1 lesson hide_on_off">
                                                                <div class="input-group">
                                                                    <input id="start_time" name="start_time" type="text" class="form-control timepicker_start" value="{{old('start_time')}}">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-clock"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row not-allday">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
                                                        <div class="col-sm-9 row">
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="end_date_div">
                                                                    <input type="text" class="form-control" readonly id="infoDateEnd" value="{{old('end_date')}}">
                                                                    <input id="end_date" name="end_date" type="text" class="form-control" value="{{old('end_date')}}" autocomplete="off" readonly>
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 offset-md-1 lesson hide_on_off">
                                                                <div class="input-group">
                                                                    <input id="end_time" name="end_time" type="text" class="form-control timepicker" value="{{old('end_time')}}">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-clock"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row lesson hide_on_off not-allday">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Duration') }} :</label>
                                                        <div class="col-sm-2">
                                                            <div class="input-group">
                                                                <input id="duration" name="duration" type="text" class="form-control" value="{{old('duration')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group row lesson" id="all_day">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="all_day" id="has_user_ac_label_id">{{__('All day') }} :</label>
                                                        <div class="col-sm-7">
                                                            <input id="all_day_input" name="fullday_flag" type="checkbox" value="Y">
                                                        </div>
                                                    </div> -->
                                                    <div class="form-group row lesson hide_on_off" id="teacher_type_billing">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher type of billing') }} :</label>
                                                        <div class="col-sm-9">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="sis_paying" name="sis_paying">
                                                                    <option value="0">{{__('Hourly rate') }}</option>
                                                                    <option value="1">{{__('Fixed price') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row lesson hide_on_off">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student type of billing') }} :</label>
                                                        <div class="col-sm-9">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="student_sis_paying" name="student_sis_paying">
                                                                    <option value="0">{{__('Hourly rate') }}</option>
                                                                    <option value="1">{{__('Fixed price') }}</option>
                                                                    <option value="2">{{__('Packaged') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="hourly" style="display:none">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
                                                        <div class="col-sm-9">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="sevent_price" name="sevent_price">
                                                                    @foreach($lessonPrice as $key => $lessprice)
                                                                        <option value="{{ $lessprice->lesson_price_student }}" {{ old('sevent_price') == $lessprice->lesson_price_student ? 'selected' : ''}}>
                                                                        @if($lessprice->lesson_price_student == 'price_1')
                                                                        {{__('Private Group')}}
                                                                        @else
                                                                        {{__('Group lessons for')}} {{ $lessprice->divider }} {{__('students')}}
                                                                        @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="priceEventOptions">
                                                    <div id="price_per_student">
                                                        <div class="row">
                                                            <hr class="col-lg-12 col-sm-12 text-left" style="font-size:10px; color:#EEE;">
                                                        </div>
                                                        <!--<div class="form-group row">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Currency') }} :</label>
                                                            <div class="col-sm-6">
                                                                <div class="selectdiv">
                                                                    <select class="form-control" id="sprice_currency" name="sprice_currency">
                                                                        @foreach($currency as $key => $curr)
                                                                            <option value="{{$curr->currency_code}}">{{$curr->currency_code}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>-->
                                                        <input type="hidden" value ="{{$school->default_currency_code}}" id="sprice_currency" name="sprice_currency">
                                                        <div class="form-group row not_teacher">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher price') }} <span class="lesson-text">({{__('class/hour') }})</span><span class="event-text">(per event)</span> :</label>
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="sprice_amount_buy_div">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-arrow-right"></i>
                                                                    </span>
                                                                    <input id="sprice_amount_buy" name="sprice_amount_buy" type="number" step="0.01" class="form-control" value="{{old('sprice_amount_buy')}}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student price (per student)') }} :</label>
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="sprice_amount_sell_div">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-arrow-right"></i>
                                                                    </span>
                                                                    <input id="sprice_amount_sell" name="sprice_amount_sell" type="number" step="0.01" class="form-control" value="{{old('sprice_amount_sell')}}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row event">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Extra Charges') }}{{ __('(per student)')}} : </label>
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="extra_charges_div">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa-solid fa-arrow-right"></i>
                                                                    </span>
                                                                    <input id="extra_charges" name="extra_charges" type="number" step="0.01" class="form-control" value="{{old('extra_charges')}}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <hr class="col-lg-12 col-sm-12 text-left" style="font-size:10px; color:#EEE;">
                                                        </div>
                                                    </div>
                                                    </div>


                                                </div>

                                                <div class="col-md-10 offset-md-1">
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
                                                        <div class="col-sm-7">
                                                            <p style="font-size:11px;">(the description will be added on the invoice)</p>
                                                            <div class="input-group">
                                                                <textarea class="form-control" cols="60" id="description" name="description" rows="3">{{old('description')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                </div>
                            </div>

                </div>
            </div>

                <div class="modal-footer pt-0" style="background-color: #152245;">
                    <button id="save_btn_more" class="btn btn-info">{{ __('Save & add more') }} </button>
                    <button id="save_btn" class="btn btn-theme-success">{{ __('Save') }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- success modal-->
<div class="modal modal_parameter" id="add_lesson_success">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:4px solid #97cc04;">
            <div class="modal-body text-center">
                <h1 class="text-success"><i class="fa-solid fa-check"></i></h1>
                <h3 class="text-success">{{__('Successfully added') }}</h3>
                <p>{{__('You can add now another lesson or event') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
            </div>
        </div>
    </div>
</div>





<!-- success modal-->
<div class="modal modal_parameter" id="modal_lesson_price">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <p id="modal_alert_body"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- success modal-->
<div class="modal" id="modal_free_trial">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-0">
            <a href="{{route('profile.plan')}}">
                <img src="{{ asset('img/freetrial.gif') }}" style="width:100%;">
            </a>
        </div>
    </div>
</div>

<!-- Modal on event click -->
<div class="modal fade login-event-modal" id="EventModal" name="EventModal" tabindex="-1" aria-hidden="true" aria-labelledby="EventModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #152245;">
                <h6 class="modal-title page_header_class">
                  <i class="fa-regular fa-calendar"></i>  {{ __('Event / lesson') }}
                </h6>
                <button type="button" class="close" id="modalClose" class="btn btn-light" data-bs-dismiss="modal" style="margin-top:-11px;">
                    <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="EventModalClass" id="EventModalWin">

                            <h4 class="light-blue-txt gilroy-bold" style="font-size: 17px; line-height: 2"><span id="event_modal_title">{{ __('Title') }}</span></h4>
                            <p style="font-size: 20px;"></p>

                            <button type="button" id="btn_confirm" onclick="confirm_event()" class="btn btn-sm btn-info button_lock_and_save" data-dismiss="modal" style="width:100px;">
                            <span id="event_btn_confirm_text"><i class="fa-solid fa-lock"></i> {{ __('Validate') }}<span>
                            </button>

                            <!-- <button type="button" id="btn_confirm_unlock" onclick="confirm_event(true)" class="btn btn-theme-success" data-dismiss="modal" style="width:100px;">
                                <span id="event_btn_confirm_unlock_text">Unlock<span>
                            </button> -->
                            <a type="button" id="btn_edit_view" onclick="view_edit_event()" class="btn btn-theme-warn" data-dismiss="modal" style="width:100px;">
                                <span id="event_btn_edit_text">{{ __('View') }}<span>
                            </a>

                            <div id="messageNoCategory" class="text-warning" style="display:none; border-top:1px solid #EEE; margin-top:20px; padding-top:10px;">
                               <i class="fa fa-solid fa-triangle-exclamation"></i> You need to choose a category for validate this lesson.
                            </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Tabs content -->
@endsection

@section('footer_js')
<!-- ================================= -->
<!-- starting calendar related jscript -->
<!-- ================================= -->

<script>
    $('#pageloader').show();
    let foundedSearchItems = [];
    $('#search_text').on('keydown', function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
    }
});
var windowWidth = $(window).width();
if (windowWidth < 768) {
    $('#search_text').hide();
    } else {
    $('#search_text').show();
}
</script>

@if(session('firstConnexion') === true)
    <script>
    $(document).ready(function() {
        //$('#modal_free_trial').modal('show');
    });
    </script>
    <?php session(['firstConnexion' => false]); ?>
@endif

<script>
$('.search-icon').on('click', function() {
    $('#search_text').toggle();
    $('.search-icon').toggle();
    $('.close-icon').toggle();
});
$('.close-icon').on('click', function() {
    $('#search_text').toggle();
    $('.search-icon').toggle();
    $('.close-icon').toggle();
});
</script>

<script>
    if($(window).width() < 1200) {
        toastr.options = {
            "positionClass": "toast-bottom-full-width",
            "timeOut": "4000",
        }

        toastr.info('Press and hold for 2 seconds on calendar, then release to add a lesson or event');
    }
</script>

<script>
    let recentFreshEventsList = [];
    let isOnlyAvailability = false;
    var no_of_teachers = document.getElementById("max_teachers").value;
    var resultHtml='';      //for populate list - agenda_table
    var resultHtml_cc='';      //for populate list - agenda_table
    var teachersList=[];
    var isTeacherHasPrices = false
    var firstload = '0';    // check first time loading or not
    var prevdt='';          // for rendering for heading
    // var prev_text=document.getElementById("prev_text").value;
    // var next_text=document.getElementById("next_text").value;
    //var listcalendar_text=document.getElementById("listcalendar_text").value;
	var stime='00:00',etime='00:00';
	var v_calc_height=((screen.height/100)*59.00);
	//var v_calc_height=((window.innerHeight/100)*30.00);

    var loading=1;
    var resultHtml_rows='';      //for populate list - agenda_table
    var resultHtml_rows_cc='';      //for populate list - agenda_table
    var lang_id='fr';
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var user_role=document.getElementById("user_role").value;
    var coach_user=document.getElementById("coach_user").value;
    var user_auth='';

    var currentTimezone = document.getElementById("zone").value;
    var zone = document.getElementById("zone").value;
    document.getElementById("zone").value = zone;

    var json_events = @json($events);

   if ($(window).width() < 1200) {
        var defview='timeGridThreeDay';
    }
    else {
        var defview='agendaWeek';
    }
      //'month';//'agendaWeek'
    try {
        if ((getCookie("cal_view_mode") != "") && (getCookie("cal_view_mode") !== undefined)){
            defview=getCookie("cal_view_mode");
        }
    } catch(err) {
        defview="agendaWeek";
    }


    var dt = new Date();
    // set default data
    // GET THE MONTH AND YEAR OF THE SELECTED DATE.
    var month = dt.getMonth(),year = dt.getFullYear();

    var FirstDay = new Date(year, month, 1);
    var LastDay = new Date(year, month+1, 1);

    let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days


    if (defview == 'month') {
        // GET THE FIRST AND LAST DATE OF THE MONTH.
        document.getElementById("date_from").value = formatDate(FirstDay);
        document.getElementById("date_to").value = formatDate(LastDay);

    }
    else if (defview == 'CurrentListView') {
        // GET THE FIRST AND LAST DATE OF THE MONTH.
        var dt = new Date();
        let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
        document.getElementById("date_from").value = formatDate(dt);
        document.getElementById("date_to").value = formatDate(CurrentListViewDate);
        //if (document.getElementById("view_mode").value == 'CurrentListView'){
            getCurrentListFreshEvents('CurrentListView','firstLoad');
        //}
    } else {

        document.getElementById("date_from").value=moment(startOfWeek(dt)).format('YYYY-MM-DD');
        //document.getElementById("date_from").value=startOfWeek(dt);
        document.getElementById("date_to").value = moment(endOfWeek(dt)).format('YYYY-MM-DD');
    }
    if (getCookie("date_from") != ""){
        document.getElementById("date_from").value = getCookie("date_from");
        document.getElementById("date_to").value = getCookie("date_to");
        FirstDay=document.getElementById("date_from").value;
        LastDay=document.getElementById("date_to").value;

    }
    if (getCookie("view_mode") != "CurrentListView"){
        // GET THE FIRST AND LAST DATE OF THE MONTH.
        var dt = new Date();
        let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
        document.getElementById("date_from").value = formatDate(dt);
        document.getElementById("date_to").value = formatDate(CurrentListViewDate);

    }


    document.getElementById("view_mode").value='';

    if (getCookie("view_mode") != "list"){
        document.getElementById("view_mode").value = getCookie("view_mode");
    }

    if (getCookie("prevnext") != ""){
        document.getElementById("prevnext").value = getCookie("prevnext");
    }


    var currentLangCode = 'fr';
    var foundRecords=0; // to store found valid records for rendering yes/no - default is 0.
    var lockRecords=1;

    if ((no_of_teachers == 1) || (user_role == "student") || (user_role == "parent")){
		document.getElementById('event_teacher_div').style.display="none";
	}
	$('#datepicker_month').datetimepicker({
        inline: true,
        //locale: lang_id,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            left: "",
            right: ""
        },
        format: "DD/MM/YYYY",
        autoclose: true,
        minView: 2,
        pickTime: false,
        weekStart: 1 // Start with Monday

    });
    moment.locale(lang_id, {
	  week: { dow: 1 } // Monday is the first day of the week
	});
    RenderCalendar();

    $(document).ready(function(){

        setTimeout(() => {

        if (user_role == "student" || user_role == "parent") {
            document.getElementById('event_school').style.display="none";
			document.getElementById('event_type').style.display="none";
			document.getElementById('event_student_div').style.display="none";
			document.getElementById('event_teacher').style.display="none";
		}
		$('#back_btn').click(function (e) {
	   	    window.history.back();
		});
        loading=0;

        RerenderEvents();
        PopulateSchoolDropdown();
        PopulateEventTypeDropdown();
        getFreshEvents();
        var selected_school_ids = [];
        $.each($("#event_school option:selected"), function(){
            selected_school_ids.push($(this).val());
        });
        if (selected_school_ids.length == 1) {
            PopulateLocationDropdown(document.getElementById("event_school_id").value);
            PopulateStudentDropdown(document.getElementById("event_school_id").value)
            PopulateTeacherDropdown(document.getElementById("event_school_id").value)
            PopulateEventCategoryDropdown(document.getElementById("event_school_id").value)
            PopulateSchoolCurrencyDropdown(document.getElementById("event_school_id").value)
        } else{
            PopulateLocationDropdown();
            PopulateStudentDropdown();
            PopulateTeacherDropdown();
        }


        DisplayCalendarTitle();

        document.getElementById("copy_school_id").value =getSchoolIDs();
        document.getElementById("copy_event_id").value =getEventIDs();
        document.getElementById("copy_student_id").value = getStudentIDs();
        document.getElementById("copy_teacher_id").value = getTeacherIDs();

        var menuHtml='';
        //user_role = 'student';
        //console.log(value.value);
        if (user_role == 'student'){
            //menuHtml+= '<a href="../{{$schoolId}}/student-off" title="" class="btn btn-sm btn-theme-success d-none d-md-block" style="border-radius: 4px!important; max-width: 80px; height: 35px;"><i class="glyphicon glyphicon-plus"></i> {{ __("Add") }}</a>';
        }
        $("#event_types_all option").each(function(key,value)
        {

            // cours - events - PopulateButtonMenuList
            if ((value.value == 10) && user_role != 'student' && user_role != 'parent'){
                menuHtml += '<button type="button" id="add_lesson_btn" class="btn btn-theme-success d-none d-md-block" style="border-radius: 4px!important; max-width: 80px; height: 35px;"><i class="fa-solid fa-plus"></i></button>';
                // menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                // menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                // menuHtml+='<ul class="dropdown-menu" role="menu">';
            }



            // Add $(this).val() to your list
        });
        // menuHtml+='</ul>';
        $('#button_menu_div').append(menuHtml);



        $('#personal_ics').click(function (e) {
            DownloadEventsICS('PersonnelEvents');
        })

        $(".fc-content-skeleton tbody tr:nth-child(n+4)").hide()

    });
	}); //ready

    function DownloadEventsICS(CalType) {
        var p_calendar_type = CalType,
          file_name = '', data = '',
          p_person_id = ReadSessionVariable('person_id');   //document.getElementById("sperson_id").value;

        //alert(p_person_id);
        var url = "../agenda/icalendar.php?type=ical&p_calendar_type=" + CalType + '&p_person_id=' + p_person_id;
        window.location = url;
    }

    function startOfWeek(date)
    {
        var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 1);
        return new Date(date.setDate(diff));
    }
    function endOfWeek(date)
    {
        var stdt=startOfWeek(date);
        var diff = stdt.getDate() + 6;
        return new Date(date.setDate(diff));
    }
    function view_edit_event(){
        var event_url=document.getElementById('edit_view_url').value;
        //alert(event_url);
        window.location=event_url;
    }
    function confirm_event(unlock=false){
        var p_event_auto_id=document.getElementById('confirm_event_id').value;
        //var school_id=document.getElementById('school_id').value;
        var data = 'p_event_auto_id=' + p_event_auto_id;
        if (unlock) {
            var data = 'unlock=1&p_event_auto_id=' + p_event_auto_id;
        }

        var status = '';
        $.ajax({
            url: BASE_URL + '/confirm_event',
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function( xhr ) {
                $("#pageloader").show();
            },
            success: function (result) {
                //console.log(result);
                status = result.status;
                if (status == 'success') {
                    if (unlock) {
                        successModalCall('{{ __("Event has been unlocked ")}}');
                    } else{
                        successModalCall('{{ __("Event has been validated ")}}');
                    }

                    //window.location.reload(false);
                    getFreshEvents();
                   // window.location.reload(false);
                   $('#EventModal').modal('hide')
                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
                }
            },   //success
            complete: function( xhr ) {
                $("#pageloader").hide();
            },
            error: function (ts) {
                ts.responseText+'-'+errorModalCall('{{ __("Event validation error ")}}');
            }
        }); //ajax-type

    }

    var typingTimer;
    var doneTypingInterval = 1000;

    $('#search_text').on('input', function() {
        var search_text = $(this).val();
        if (search_text.length > 0) {
            var loadingSearch = document.getElementById('loadingSearch');
            loadingSearch.innerHTML = '<i style="color:#DDD;" class="fas fa-spinner fa-spin fa-2x"></i>';
            $('#calendar').fullCalendar('rerenderEvents');
        }
        if (search_text.length == 0) {
            $('#calendar').fullCalendar('rerenderEvents');
        }

        if (search_text.length < 2) {
            $("#resultSearch").hide();
            $("#allFilters").show();
            $("#listEventsSearch").hide();
        }

        if (search_text.length > 0) {
            var eventIds = [];
            // Effacer le délai précédent s'il y en a un
            clearTimeout(typingTimer);
            // Définir un nouveau délai
            typingTimer = setTimeout(function() {
                toastr.options = {
                    "positionClass": "toast-bottom-full-width",
                    "timeOut": "2000",
                }

                var eventCount = $('.fc-event').length || $('.fc-list-item').length;
                if (eventCount > 0) {
                    if (search_text.length > 3) {
                        toastr.info(eventCount + ' results according to your criteria');
                    }
                    $("#allFilters").hide();
                    $("#listEventsSearch").show();
                    $("#resultSearch").show();
                    resultSearch.style.display = 'block';
                    resultSearch.innerHTML = '<b><i class="fa-solid fa-arrow-right"></i> Your search result:</b><br><div class="light-blue-txt"><b>' + eventCount + '</b> results for your search <b><i>' + search_text + '</i></b>.</div>';
                    var cal_view_mode = $('#calendar').fullCalendar('getView');
                    var ulElement = document.getElementById("listEventsSearch");
                    while (ulElement.firstChild) {
                    ulElement.removeChild(ulElement.firstChild);
                    }

                    let getClassForSearch = "";
                    if (cal_view_mode.name == 'listMonth' || cal_view_mode.name == 'timeGridThreeDay') {
                        getClassForSearch = '.fc-list-item';
                        $('.fc-list-item-title a').each(function() {
                            var text = $(this).text();
                            if (text.indexOf('all-day') > -1) {
                                text = text.replace('all-day', 'all-day ');
                            }
                           // $('#listEventsSearch').append('<li>' + text + '</li>');
                        });
                    }
                    else {
                        getClassForSearch = '.fc-event';
                        $('.fc-event').each(function() {
                            var text = $(this).text();
                           // $('#listEventsSearch').append('<li>' + text + '</li>');
                        });
                    }



                        //Get current IDs
                        $(getClassForSearch).each(function() {
                            var id = $(this).data('event-id');
                            eventIds.push(id);
                        });
                        //

                        var jsonEvents = recentFreshEventsList;

                        eventIds.forEach(function(eventId) {

                        var matchedEvent = jsonEvents.find(function(event) {
                            return event.id === eventId;
                        });


                        if (matchedEvent) {
                            const startDate = new Date(matchedEvent.start);
                            const endDate = new Date(matchedEvent.end);
                            const startHourMinute = startDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
                            const endHourMinute = endDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
                            let iconSearchResult = matchedEvent.action_type == "view" ? 'fa-solid fa-eye' : 'fa-solid fa-pen-to-square';
                            var html = `
                                <div style="padding:10px; background-color:#F5F5F5; border-radius:15px; margin-bottom:10px; font-size:12px; position:relative;">
                                <b><i class="fa solid fa-calendar"></i> ${startDate.toISOString().split('T')[0]}</b>
                                [ <i style="font-size:10px;" class="fa-regular fa-clock"></i> ${startHourMinute} - ${endHourMinute} ]
                                <div class="title"><span style="width:10px; height:10px; background-color:${matchedEvent.backgroundColor}; border-radius:50px; display:inline-block;"></span> ${matchedEvent.content}</div>
                                <div style="border-top:1px solid #EEE; padding-top:10px;"><table>${matchedEvent.title_for_modal}</table></div>
                                <a href="${matchedEvent.url}"><i class="fa ${iconSearchResult}" style="cursor:pointer; position:absolute; right:10px; top:10px; cursor:pointer;"></i></a>
                                </div>
                            `;

                                $('#listEventsSearch').append(html);
                        }

                        });

                        if(loadingSearch) {
                            loadingSearch.innerHTML = "";
                        }


                } else {
                    toastr.info('No results according to your criteria');
                    $("#allFilters").show();
                    $('#listEventsSearch').html("");
                    $("#resultSearch").html("No results according to your criteria");
                    if(loadingSearch) {
                        loadingSearch.innerHTML = "";
                    }
                }
                if (search_text.length < 2) {
                    toastr.info('No results according to your criteria');
                    $("#resultSearch").hide();
                    $("#allFilters").show();
                    $('#listEventsSearch').html("");
                    $("#resultSearch").html("No results according to your criteria");
                    if(loadingSearch) {
                        loadingSearch.innerHTML = "";
                    }
                }
            }, doneTypingInterval);
        }
    });
    $("#datepicker_month").datetimepicker()
    .on('changeDate', function(ev){

        var dt=$(this).datetimepicker('getDate');
        var jsDate = $(this).datetimepicker('getDate');

        if (dt !== null) { // if any date selected in datepicker
            // jsDate instanceof Date; // -> true
            // jsDate.getDate();
            // jsDate.getMonth();
            // var month = jsDate.getMonth() + 1;
            // jsDate.getFullYear();
            // dt=jsDate.getFullYear()+'-'+month+'-'+jsDate.getDate();
            dt = moment(dt).format("YYYY-MM-DD")
            $('#calendar').fullCalendar( 'gotoDate', dt);

        }
    });

    //right: 'prev,today,next month,agendaWeek,agendaDay MyListButton'
	$('#btn_prev, #btn_prev2').on('click', function() {
        $('#calendar').fullCalendar('prev');
	});

	$('#btn_today, #btn_today2').on('click', function() {
        $('#calendar').fullCalendar('today');
	});

	$('#btn_next, #btn_next2').on('click', function() {
        $('#calendar').fullCalendar('next');
	});

	$('#btn_month, #btn_month2').on('click', function() {
        $("#btn_prev").prop("disabled", false);
        $("#btn_next").prop("disabled", false);
        $("#btn_today").prop("disabled", false);
        $('#calendar').fullCalendar('changeView', 'month');
        hideExtraRowInMonthView()
	});

	$('#btn_week, #btn_week2').on('click', function() {
        $("#btn_prev").prop("disabled", false);
        $("#btn_next").prop("disabled", false);
        $("#btn_today").prop("disabled", false);
        $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});

	$('#btn_day, #btn_day2').on('click', function() {
        $("#btn_prev").prop("disabled", false);
        $("#btn_next").prop("disabled", false);
        $("#btn_today").prop("disabled", false);
        $('#calendar').fullCalendar('changeView', 'agendaDay');
	});
    $('#btn_list, #btn_list2').on('click', function() {
        //getFreshEvents('ListView');
        $("#btn_prev").prop("disabled", false);
        $("#btn_next").prop("disabled", false);
        $("#btn_today").prop("disabled", false);
		$('#calendar').fullCalendar('changeView', 'listMonth');
	});
    $('#btn_current_list, #btn_current_list2').on('click', function() {
        //getFreshEvents('CurrentListView');
       // CallListView();

       $("#btn_prev").prop("disabled", true);
       $("#btn_next").prop("disabled", true);
       $("#btn_today").prop("disabled", true);

       var aujourdHuiList = moment().startOf('day');
       $('#calendar').fullCalendar('gotoDate', aujourdHuiList);
		// console.log('lllll----------------')
        //getCurrentListFreshEvents2();
        $('#calendar').fullCalendar('changeView', 'timeGridThreeDay');
        // console.log('lllll----------------')
	});

    $('#list_button').on('click', function() {
        CallListView();
	});

    function hideExtraRowInMonthView() {
        setTimeout(function() {
          $("body").find(".fc-content-skeleton tbody tr:nth-child(n+4)").hide()
        }, 500);

    }

    $('body').on('click', 'button.fc-prev-button', function() {
        //alert('prev is clicked, do something');
        $('.fc-MyListButton-button').text('list');
        if (document.getElementById("view_mode").value == 'list')
        {
            document.getElementById("prevnext").value = 'yes';
        }
        else if (document.getElementById("view_mode").value == 'CurrentListView')
        {
            document.getElementById("prevnext").value = 'yes';
        }
        else
        {
            document.getElementById("prevnext").value == '';
        }
    });
    $('body').on('click', 'button.fc-next-button', function() {
        //alert('next is clicked, do something');
        $('.fc-MyListButton-button').text('list');
        if (document.getElementById("view_mode").value == 'list')
        {
            document.getElementById("prevnext").value = 'yes';
        }
        else if (document.getElementById("view_mode").value == 'CurrentListView')
        {
            document.getElementById("prevnext").value = 'yes';
        }
        else
        {
            document.getElementById("prevnext").value == '';
        }
    });

    if ((firstload == "0") && (getCookie("date_from") != "")) {
        var sdt = getCookie("date_from");
        $('#calendar').fullCalendar( 'gotoDate', sdt);
        firstload ='1';
    }


    // if ((user_auth == "MIN"))
    // {
    //     document.getElementById("event_teacher").disabled="disabled";
    // }

    // populate school
    function PopulateSchoolDropdown(){

        $('#event_school').multiselect({
            includeSelectAllOption:true,
            selectAllText: 'All Schools',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected School ',
            allSelectedText: 'All Schools',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: 'Search',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                    //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
                    //console.log('School changed triggered!');
                    document.getElementById("event_school_id").value=getSchoolIDs();
                    document.getElementById("event_school_all_flag").value='0';

                    var selected_ids = [];
                    $.each($("#event_school option:selected"), function(){
                        selected_ids.push($(this).val());
                    });
                    if (selected_ids.length == 1) {
                        PopulateLocationDropdown(document.getElementById("event_school_id").value);
                        PopulateStudentDropdown(document.getElementById("event_school_id").value)
                        PopulateTeacherDropdown(document.getElementById("event_school_id").value)
                        PopulateEventCategoryDropdown(document.getElementById("event_school_id").value)
                        PopulateSchoolCurrencyDropdown(document.getElementById("event_school_id").value)
                        $('#agenda_select').trigger('change');
                    }

                    SetEventCookies();
                    RerenderEvents();
                    //getFreshEvents();
            },
            onSelectAll: function (options,checked) {

                if (options){
                    //console.log('school onSelectAll triggered!'+options);
                    document.getElementById("event_school_id").value=getSchoolIDs();
                    document.getElementById("event_school_all_flag").value='1';
                }
                else {
                    //console.log('school onDeSelectAll triggered!');
                    document.getElementById("event_school_id").value='';
                    document.getElementById("event_school_all_flag").value='0';

                }
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onDeselectAll: function(option,checked) {
                //console.log('school onDeSelectAll triggered!');
                document.getElementById("event_school_id").value='';
                document.getElementById("event_school_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            selectAllValue: 0
        });
        if (document.getElementById("event_school_id").value ==0) {
            $('#event_school').multiselect('selectAll', false);
        }

        $('#event_school').multiselect('refresh');

    }

    // populate event type
    function PopulateEventTypeDropdown(){

        $('#event_type').multiselect({
            includeSelectAllOption:true,
            selectAllText: '{{__("All Events") }}',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : '{{__("Selected Event type") }} ',
            allSelectedText: '{{__("All Events") }}',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: '{{ __("Search") }}',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                    //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
                    //console.log('Event changed triggered!');
                    document.getElementById("event_type_id").value=getEventIDs();
                    document.getElementById("event_type_all_flag").value='0';
                    SetEventCookies();
                    RerenderEvents();
                    getFreshEvents();
            },
            onSelectAll: function (option,checked) {
                    document.getElementById("event_type_id").value='0';
                    document.getElementById("event_type_all_flag").value='1';
                    SetEventCookies();
                    RerenderEvents();
                    getFreshEvents();
            },
            onDeselectAll: function(option,checked) {
                //console.log('Event onDeSelectAll triggered!');
                    //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
                    document.getElementById("event_type_id").value=getEventIDs();
                    document.getElementById("event_type_all_flag").value='0';
                    SetEventCookies();
                    RerenderEvents();
                    getFreshEvents();
                },
            selectAllValue: 0
        });

        $('#event_type').multiselect('selectAll', false);
        $('#event_type').multiselect('refresh');

    }

    // populate location
    function PopulateEventCategoryDropdown(school_id=null){

        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#category_select').html('');

            $.ajax({
                url: BASE_URL + '/get_event_category',
                data: data,
                type: 'POST',
                dataType: 'json',
                //async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                  //  $("#pageloader").hide();
                    if (data.length >0) {
                        var resultHtml ="";
                        resultHtml+='<option value="">Select Category</option>';
                        var i='0';
                        $.each(data, function(key,value){
                            var isAdmin = "{{ $AppUI->isSchoolAdmin() }}";
                            let textAdmin = "";
                            if(isAdmin) {
                                textAdmin = "<span class='text-danger'>("+value.invoiced_type+")</span> ";
                            }

                            var lastCat = '{{ session('last_cat') }}';
                            resultHtml += '<option data-s_thr_pay_type="' + value.s_thr_pay_type + '" data-s_std_pay_type="' + value.s_std_pay_type + '" data-t_std_pay_type="' + value.t_std_pay_type + '" value="' + value.id + '" data-invoice="' + value.invoiced_type + '" ' + (value.prices.length !== 0 ? '' : 'disabled') + ' ' + ((lastCat && lastCat == value.id) ? 'selected' : '') + '>' + textAdmin + '' + value.title + (value.prices.length !== 0 ? '' : ' (setup prices for this category)') + '</option>';

                        
                        });
                        $('#category_select').html(resultHtml);
                        $('#category_select').change();

                    }

                },   //success
                complete: function( xhr ) {
                  //  $("#pageloader").hide();
                },
                error: function(ts) {
                    // alert(ts.responseText)
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }

    }

    // populate location
    function PopulateSchoolCurrencyDropdown(school_id=null){
        return null;
        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#sprice_currency').html('');

            $.ajax({
                url: BASE_URL + '/get_school_currency',
                data: data,
                type: 'GET',
                dataType: 'json',
                //async: false,
                beforeSend: function( xhr ) {
                   // $("#pageloader").show();
                },
                success: function(data) {
                  //  $("#pageloader").hide();
                    if (data.length >0) {
                        var resultHtml ="";
                        var i='0';
                        $.each(data, function(key,value){
                            resultHtml+='<option value="'+value.currency_code+'">'+value.currency_code+'</option>';
                        });
                        $('#sprice_currency').html(resultHtml);
                    }

                },   //success
                complete: function( xhr ) {
                    $("#pageloader").hide();
                },
                error: function(ts) {
                    // alert(ts.responseText)
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }

    }



    // populate location
    function PopulateLocationDropdown(school_id=null){

        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            //$('#event_location').html('');

            /*$.ajax({
                url: BASE_URL + '/get_locations',
                data: data,
                type: 'POST',
                dataType: 'json',
                //async: false,
                beforeSend: function( xhr ) {
                  //  $("#pageloader").show();
                },
                success: function(data) {
                   // $("#pageloader").hide();
                    if (data.length >0) {

                    }
                    var resultHtml ="";
                    var locHtml ="<option value=''>{{__('Select Location') }}</option>";
                    var i='0';
                    $.each(data, function(key,value){
                        resultHtml+='<option value="'+value.id+'">'+value.title+'</option>';
                    });
                    locHtml += resultHtml
                    $('#event_location').html(resultHtml);
                    $('#location').html(locHtml);
                    $("#event_location").multiselect('destroy');

                },   //success
                complete: function( xhr ) {
                   // $("#pageloader").hide();
                },
                error: function(ts) {
                    // alert(ts.responseText)
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); */
        }
        $('#event_location').multiselect({
            includeSelectAllOption:true,
            selectAllText: "{{ __('All Location') }}",
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Location',
            allSelectedText: "{{ __('All Location') }}",
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: 'Search',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                //('onChange location triggered!');
                document.getElementById("event_location_id").value=getLocationIDs();
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                    //console.log('location onSelectAll triggered!'+options);
                    document.getElementById("event_location_id").value='0';
                    document.getElementById("event_location_all_flag").value='1';
                }
                else {
                    //console.log('location onDeSelectAll triggered!');
                    document.getElementById("event_location_id").value='';
                    document.getElementById("event_location_all_flag").value='0';

                }
                //SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            onDeselectAll: function() {
                //console.log('NOT WORKING location onDeSelectAll triggered!');
                document.getElementById("event_location_id").value='';
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            selectAllValue: 0
        });
        $('#event_location').multiselect('selectAll', false);
        $('#event_location').multiselect('refresh');







    }
    // populate teacher
    function PopulateTeacherDropdown(school_id=null){

        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#event_teacher').html('');

            $.ajax({
                url: BASE_URL + '/get_teachers',
                data: data,
                type: 'POST',
                dataType: 'json',
                //async: false,
                beforeSend: function( xhr ) {
                   // $("#pageloader").show();
                },
                success: function(data) {
                   // $("#pageloader").hide();
                    teachersList = data
                    if (data.length >0) {

                    }
                    var resultHtml ='';
                    var EresultHtml ='';
                    var i='0';

                    resultHtml+='<option value="">{{__('Select Professor') }}</option>';
                    $.each(data, function(key,value){
                        resultHtml+='<option value="'+value.teacher_id+'">'+value.full_name+'</option>';
                    });

                    $.each(data, function(key,value){
                        EresultHtml+='<option value="'+value.teacher_id+'">'+value.full_name+'</option>';
                    });

                    $('#event_teacher').html(EresultHtml);
                    $('#teacher_select').html(resultHtml);
                    $("#event_teacher").multiselect('destroy');

                },   //success
                complete: function( xhr ) {
                   // $("#pageloader").hide();
                },
                error: function(ts) {
                    // alert(ts.responseText)
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }
        $('#event_teacher').multiselect({
            includeSelectAllOption:true,
            selectAllText: '{{__("All Teachers") }}',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : '{{__("Selected Teacher") }}',
            allSelectedText: '{{__("All Teachers") }}',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: '{{__("Search") }}',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
               // console.log('onChange teacher triggered!');
                document.getElementById("event_teacher_id").value=getTeacherIDs();
                document.getElementById("event_teacher_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                   // console.log('teacher onSelectAll triggered!'+options);
                    document.getElementById("event_teacher_id").value='0';
                    document.getElementById("event_teacher_all_flag").value='1';
                }
                else {
                   // console.log('teacher onDeSelectAll triggered!');
                    document.getElementById("event_teacher_id").value='';
                    document.getElementById("event_teacher_all_flag").value='0';

                }
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onDeselectAll: function() {
               // console.log('NOT WORKING teacher onDeSelectAll triggered!');
                document.getElementById("event_teacher_id").value='';
                document.getElementById("event_teacher_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();

            },
            selectAllValue: 0
        });

        $('#event_teacher').multiselect('selectAll', false);
        $('#event_teacher').multiselect('refresh');

    }
    // populate student
    function PopulateStudentDropdown(school_id=null){
        /*if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#event_student').html('');

            $.ajax({
                url: BASE_URL + '/get_students',
                data: data,
                type: 'POST',
                dataType: 'json',
                //async: false,
                beforeSend: function( xhr ) {
                  //  $("#pageloader").show();
                },
                success: function(data) {
                  //  $("#pageloader").hide();
                    if (data.length >0) {

                    }
                    var resultHtml = "";
                    var i='0';
                    $.each(data, function(key,value){
                        resultHtml+='<option value="'+value.id+'">'+value.firstname+' '+value.lastname+'</option>';
                    });

                    setTimeout(() => {
                        $('#event_student').html(resultHtml);
                        $("#event_student").multiselect('destroy');
                        $('#event_student').multiselect('refresh');
                        $('#event_student').change();
                    }, 500);

                   // $("#event_student").multiselect('destroy');
                    //$('#student').multiselect({ search: true })

                },   //success
                complete: function( xhr ) {
                   // $("#pageloader").hide();
                },
                error: function(ts) {
                    // alert(ts.responseText)
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }*/
        $('#event_student').multiselect({
            includeSelectAllOption:true,
            selectAllText: "{{__("All Students") }}",
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Student',
            allSelectedText: "{{__("All Students") }}",
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: '{{ __("Search") }}',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
               // console.log('onChange student triggered!');
                document.getElementById("event_student_id").value=getStudentIDs();
                document.getElementById("event_student_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                   //  console.log('student onSelectAll triggered!'+options);
                     document.getElementById("event_student_id").value='0';
                     document.getElementById("event_student_all_flag").value='1';
                }
                else {
                   // console.log('student onDeSelectAll triggered!');
                    document.getElementById("event_student_id").value='';
                    document.getElementById("event_student_all_flag").value='0';

                }
                SetEventCookies();
                RerenderEvents();
                getFreshEvents();

            },
            onDeselectAll: function() {
               // console.log('NOT WORKING student onDeSelectAll triggered!');
                document.getElementById("event_student_id").value='';
                document.getElementById("event_student_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            selectAllValue: 0
        });

        $('#event_student').multiselect('selectAll', false);
        $('#event_student').multiselect('refresh');

    }   // populate event type

    function RerenderEvents(){

	    if (loading == 0){

            //getFreshEvents();
            //console.log('sss');
           // $("#agenda_table tr:gt(0)").remove();
            //$("#agenda_table_current tr:gt(0)").remove();
            $('#calendar').fullCalendar('rerenderEvents');
        }
    }


    $("#btn_export_events").click(function () {

        $("#agenda_table").table2excel({
            // exclude CSS class
            //exclude: ".noExl",
            name: "Sheet",
            filename: "events.xls" //do not include extension
        });
        return false;


    })
    //Convert HTML Table to CSV Method : END
    //capture events criteria
        $(document).on('click', '#btn_copy_events,#btn_copy_events_mobile', function() {

        var cal_view_mode_for_copy=$('#calendar').fullCalendar('getView');

        if(cal_view_mode_for_copy.name === "agendaDay" || cal_view_mode_for_copy.name === "agendaWeek") {

            document.getElementById("copy_date_from").value = document.getElementById("date_from").value;
            document.getElementById("copy_date_to").value = document.getElementById("date_to").value;
            document.getElementById("copy_view_mode").value =document.getElementById("view_mode").value;


            document.getElementById("copy_week_day").value =document.getElementById("week_day").value;
            document.getElementById("copy_month_day").value =document.getElementById("month_day").value;

            document.getElementById("copy_school_id").value = getSchoolIDs();
            document.getElementById("copy_event_id").value = getEventIDs();
            document.getElementById("copy_student_id").value = getStudentIDs();
            document.getElementById("copy_teacher_id").value = getTeacherIDs();

          // console.log('copy start',  document.getElementById("copy_date_from").value)
          // console.log('copy end',  document.getElementById("copy_date_to").value)
          // console.log('list all copied events', getEventIDs())
          // console.log('list all students', getStudentIDs())

        }

		//console.log("current view for copy="+cal_view_mode_for_copy.name);
        if(cal_view_mode_for_copy.name === "agendaWeek") {
		    successModalCall('Copied with success', 'Schedule of current week view is copied ! You can past it in other week.');
            showMessage('Schedule of current week view is copied!', 'success')
        }
        if(cal_view_mode_for_copy.name === "agendaDay") {
		    successModalCall('Copied with success', 'Schedule of current day view is copied ! You can past it in other day.');
            showMessage('Schedule of current day view is copied!', 'success')
        }
        if(cal_view_mode_for_copy.name === "month") {
		    errorModalCall('You cannot copy your month view at this time. Please copy by day or week.');
            //showMessage('Schedule of current month view is copied!', 'success')
        }
        if(cal_view_mode_for_copy.name === "listMonth") {
		    errorModalCall('You cannot copy your monthly schedule view at this time. Please copy by day or week.');
            //showMessage('Schedule of current month view is copied!', 'success')
        }
        if(cal_view_mode_for_copy.name === "timeGridThreeDay") {
		    errorModalCall('You cannot copy your daily schedule view at this time. Please copy by day or week.');
            //showMessage('Schedule of current month view is copied!', 'success')
        }

        return false;
    })

    //delete multiple events based on date, events type, teacher and student etc
        $(document).on('click', '#btn_delete_events,#btn_delete_events_mobile', function(e) {


        var user_role=document.getElementById("user_role").value;
        if (user_role == 'student' || user_role == 'parent') {
            //alert("You don't have permission to delete events");
            errorModalCall('permission_issue_common_text');
            return false;
        }

        var p_from_date=document.getElementById("date_from").value,
        p_to_date=document.getElementById("date_to").value;
        var p_event_school_id=getSchoolIDs();
        var p_event_location_id=getLocationIDs();
        var p_event_type_id=getEventIDs();
        var p_student_id=getStudentIDs();
        var p_teacher_id=getTeacherIDs();

        //console.log('??', p_student_id);

        var p_event_id=document.getElementById("get_non_validate_event_delete_id").value;



        /** get events id **/
        var eventIds = [];
        var cal_view_mode = $('#calendar').fullCalendar('getView');
        let getClassForSearch = "";
        if (cal_view_mode.name == 'listMonth' || cal_view_mode.name == 'timeGridThreeDay') {
            getClassForSearch = '.fc-list-item';
        } else {
            getClassForSearch = '.fc-event';
        }
        $(getClassForSearch).each(function() {
            var id = $(this).data('event-id');
            var is_locked = $(this).data('locked');
            var allDay = $(this).data('allday');
            if(Number(is_locked) === 0 && !allDay) {
                eventIds.push(id);
            }
        });
        var jsonEvents = recentFreshEventsList;
        var html = '';
        eventIds.forEach(function(eventId) {
        var matchedEvent = jsonEvents.find(function(event) {
            return event.id === eventId;
        });

        if (matchedEvent) {
            //console.log(matchedEvent.content);
            const startDate = new Date(matchedEvent.start);
            const endDate = new Date(matchedEvent.end);
            const startHourMinute = startDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
            const endHourMinute = endDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
            let iconSearchResult = 'fa-solid fa-trash text-danger';
             html += `
                <div class="col-lg-6 col-xs-12"><div style="padding:10px; background-color:#F5F5F5; border-radius:15px; margin-bottom:10px; font-size:13px; position:relative;">
                <b><i class="fa solid fa-calendar"></i> ${startDate.toISOString().split('T')[0]}</b>
                [ <i style="font-size:10px;" class="fa-regular fa-clock"></i> ${startHourMinute} - ${endHourMinute} ]
                <div class="title"><span style="width:10px; height:10px; background-color:${matchedEvent.backgroundColor}; border-radius:50px; display:inline-block;"></span> ${matchedEvent.content}</div>
                <div style="border-top:1px solid #EEE; padding-top:10px;"><table>${matchedEvent.title_for_modal}</table></div>
                </div></div>
            `;
        }

        });
        //var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
        e.preventDefault();
        confirmDeleteLessonsModal(html,'Do you want to delete events',"delete_multiple_lessons('"+eventIds+"');");
        return false;
    })


    //validate multiple events based on date, events type, teacher and student etc
        $(document).on('click', '#btn_validate_events,#btn_validate_events_mobile', function(e) {

        var user_role=document.getElementById("user_role").value;
        if (user_role == 'student' || user_role == 'parent') {
            //alert("You don't have permission to delete events");
            errorModalCall('permission_issue_common_text');
            return false;
        }
        var curdate=new Date();
        var p_from_date=document.getElementById("date_from").value,
        p_to_date=document.getElementById("date_to").value;

       var currentMyView =  $('#calendar').fullCalendar('getView');
       if(currentMyView.name != 'agendaDay') {
            const date = moment(p_to_date);
            const newDate = date.subtract(1, 'days');
            p_to_date = newDate.format('YYYY-MM-DD');
           // console.log('new date-1', p_to_date); // Day-1
        }

        var p_event_school_id=getSchoolIDs();
        var p_event_type_id=getEventIDs();
        var p_student_id=getStudentIDs();
        var p_teacher_id=getTeacherIDs();
        var p_event_id=document.getElementById("get_event_id").value;
        var get_non_validate_event_id=document.getElementById("get_non_validate_event_id").value;


         /** get events id **/
         var eventIds = [];
        var cal_view_mode = $('#calendar').fullCalendar('getView');
        let getClassForSearch = "";
        if (cal_view_mode.name == 'listMonth' || cal_view_mode.name == 'timeGridThreeDay') {
            getClassForSearch = '.fc-list-item';
        } else {
            getClassForSearch = '.fc-event';
        }
        $(getClassForSearch).each(function() {
            var id = $(this).data('event-id');
            var is_locked = $(this).data('locked');
            var allDay = $(this).data('allday');
            var can_lock = $(this).data('canlock');
            if(Number(is_locked) === 0 && !allDay && can_lock === "Y") {
                eventIds.push(id);
            }
        });
        var jsonEvents = recentFreshEventsList;
        var html = '';
        eventIds.forEach(function(eventId) {
        var matchedEvent = jsonEvents.find(function(event) {
            return event.id === eventId;
        });

        if (matchedEvent) {
            //console.log(matchedEvent.content);
            const startDate = new Date(matchedEvent.start);
            const endDate = new Date(matchedEvent.end);
            const startHourMinute = startDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
            const endHourMinute = endDate.toTimeString().split(' ')[0].slice(0, 5); // HH:MM
            let iconSearchResult = 'fa-solid fa-lock text-primary';
             html += `
                <div class="col-lg-6 col-xs-12"><div style="padding:10px; background-color:#F5F5F5; border-radius:15px; margin-bottom:10px; font-size:13px; position:relative;">
                <b><i class="fa solid fa-calendar"></i> ${startDate.toISOString().split('T')[0]}</b>
                [ <i style="font-size:10px;" class="fa-regular fa-clock"></i> ${startHourMinute} - ${endHourMinute} ]
                <div class="title"><span style="width:10px; height:10px; background-color:${matchedEvent.backgroundColor}; border-radius:50px; display:inline-block;"></span> ${matchedEvent.content}</div>
                <div style="border-top:1px solid #EEE; padding-top:10px;"><table>${matchedEvent.title_for_modal}</table></div>
                </div></div>
            `;
        }

        });


        //var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
        e.preventDefault();
        confirmMultipleValidateModalCall(html,'Do you want to validate events',"validate_multiple_events('"+eventIds+"');");
        return false;
    })

    function validate_multiple_events(p_event_school_id){
        var p_event_location_id=getLocationIDs();
        var data='p_event_school_id='+p_event_school_id;

            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/validate_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                   // console.log(result);
                    document.getElementById("btn_validate_events").style.display = "none";
                    document.getElementById("btn_validate_events_mobile").style.display = "none";
                    var status =  result.status;
                    //$('#calendar').fullCalendar('removeEvents');
                    //$('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events) )
                    //alert(status);
                    getFreshEvents();      //refresh calendar
                    //window.location.reload(false);

                },   //success
                error: function(ts) {
                    errorModalCall('validate_multiple_events:'+ts.responseText+'-'+GetAppMessage('error_message_text'));
                    // alert(ts.responseText)
                }
            }); //ajax-type
    }

    function delete_multiple_events(p_event_school_id,p_from_date,p_to_date,p_event_type_id,p_student_id,p_teacher_id){
        var p_event_location_id=getLocationIDs();
        var p_event_student_id=getStudentIDs();
        var data='location_id='+p_event_location_id+'&student_id='+p_event_student_id+'&type=delete_multiple_events'+'&p_event_school_id='+p_event_school_id+'&p_from_date='+p_from_date+'&p_to_date='+p_to_date+'&p_event_type_id='+p_event_type_id+'&p_student_id='+p_student_id+'&p_teacher_id='+p_teacher_id;

            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/delete_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                    document.getElementById("btn_delete_events").style.display = "none";
                    document.getElementById("btn_delete_events_mobile").style.display = "none";
                    var status =  result.status;

                    //alert(status);
                    getFreshEvents();      //refresh calendar
                   // window.location.reload(false);

                },   //success
                error: function(ts) {
                    errorModalCall('delete_multiple_events:'+ts.responseText+'-'+GetAppMessage('error_message_text'));
                    // alert(ts.responseText)
                }
            }); //ajax-type
    }

    function delete_multiple_lessons(p_event_school_id){

        var data='p_event_school_id='+p_event_school_id;

            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/delete_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                    document.getElementById("btn_delete_events").style.display = "none";
                    document.getElementById("btn_delete_events_mobile").style.display = "none";
                    var status =  result.status;

                    //reload events/lessons
                    getFreshEvents();


                },   //success
                error: function(ts) {
                    errorModalCall('delete_multiple_events:'+ts.responseText+'-'+GetAppMessage('error_message_text'));
                    // alert(ts.responseText)
                }
            }); //ajax-type
    }


    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function isElemOverDiv() {
        var trashEl = jQuery('#trash');

        var ofs = trashEl.offset();

        var x1 = ofs.left;
        var x2 = ofs.left + trashEl.outerWidth(true);
        var y1 = ofs.top;
        var y2 = ofs.top + trashEl.outerHeight(true);

        if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
            currentMousePos.y >= y1 && currentMousePos.y <= y2) {
            return true;
        }
        return false;
    }

    function CallListView(){
      //  console.log(document.getElementById("view_mode").value);
        if (document.getElementById("view_mode").value != 'CurrentListView')
        {
            document.getElementById("view_mode").value = 'CurrentListView';
        SetEventCookies();

            $('.fc-MyListButton-button').text('Calender');
            //remove
            //$('#calendar').fullCalendar().find('.fc-day-header').parents('table').html('');
            $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
            $('#calendar').fullCalendar().find('.fc-day-header').hide();
            //$('#calendar').fullCalendar('option', 'contentHeight', 0);
            //document.getElementById("agenda_list_current").style.display = "block";

        }
        // else{
        //     //alert('refresh');
        //     $('.fc-MyListButton-button').text('list');
        //     document.getElementById("view_mode").value = '';
        //     document.getElementById("agenda_list").style.display = "none";
        //     $('#calendar').fullCalendar().find('.fc-day-header').parents('table').show();
        //     $('#calendar').fullCalendar().find('.fc-day-header').show();

        //     //getFreshEvents();   // scroll bar is not appearing hence refresh calendar - resolved hence commented

        //     //$('#calendar').fullCalendar(options).slideToggle();
        //     //$('#calendar').fullCalendar('rerenderEvents');
        // }
    }



    function RenderCalendar(){
		/* initialize the calendar
		-----------------------------------------------------------------*/
        let curdate=new Date();
        let p_from_date=moment(curdate).format("YYYY-MM-DD");

        let myTimezone = "{{ $myCurrentTimeZone }}";
        var mySetting = @json($settingUser);
        var timeFormat;
        var columnHeaderText;
        let weekends;
        let current;
        var europeanTimezones = ['Europe/Paris', 'Europe/Berlin', 'Europe/London', 'Europe/Moscow', 'Europe/Minsk', 'Europe/Warsaw', 'Europe/Rome',  'Europe/Brussels', 'Europe/Zurich'];
        var isEuropeanTimezone = europeanTimezones.includes(myTimezone);

        //Init Hours Format
        if (isEuropeanTimezone) {
        timeFormat = 'HH:mm';
        } else {
        timeFormat = 'h:mm A';
        }

        var dayNamesShortArray;
        if (isEuropeanTimezone) {
            dayNamesShortArray = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        } else {
            dayNamesShortArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        }
        if(mySetting && mySetting.timezone !== ""){
            myTimezone = mySetting.timezone;
            var isEuropeanTimezoneForSetting = europeanTimezones.includes(myTimezone);
            if (isEuropeanTimezoneForSetting) {
            dayNamesShortArray = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        } else {
            dayNamesShortArray = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        }
        }
        //weekends = true;
        if(mySetting && mySetting.weekends !== ""){
            weekends = mySetting.weekends == "0" ? false : true;
        }

        if(mySetting && mySetting.current !== ""){
            current = mySetting.current == "0" ? false : true;
        }

        afficherHeureActuelle(myTimezone);
        const scrollTimeInit = moment().tz(myTimezone).format("HH");
        $('#calendar').fullCalendar({
            eventLimit: 3, // If you set a number it will hide the itens
            eventLimitText: "More", // Default is `more` (or "more" in the lang you pick in the option)
            timeFormat: timeFormat,
            axisFormat: timeFormat,
            weekends: weekends,
			slotDuration: '00:15:00',
			slotLabelFormat: timeFormat,
            dayNamesShort: dayNamesShortArray,
            defaultView: defview,
            minTime: mySetting === null ? '00:00:01' : mySetting.min_time,
            maxTime: mySetting === null ? '23:59:59' : mySetting.max_time,
            scrollTime: scrollTimeInit + ':00',
            defaultDate: (getCookie("date_from")) ? getCookie("date_from") : p_from_date,
            utc: false,
            editable: false,
            selectable: true,
            buttonText: {
                prev: '<',
                next: '>'
            },
			header: false,
            views: {
                agenda: {
                    columnFormat: ($(window).width() < 1200) ? 'DD' : 'ddd MMM DD'
                },
                week: {
                    columnFormat: 'ddd MMM DD'
                },
                month: {
                    columnFormat: 'ddd'
                },
                day: {
                    columnFormat: 'ddd DD MMM'
                },
                timeGridThreeDay: {
                    type: 'listWeek',
                    duration: { days: 2 },
                    buttonText: '3 day'
                }
            },
            handleWindowResize: true,
            eventTextColor: '#000000',
            firstDay: '1',      //monday
            height: 'parent', // calendar content height excluding header
            contentHeight: v_calc_height, // calendar content height excluding header
            timezone: myTimezone,
            noEventsMessage:"No lessons yet",
            locale: currentLangCode,
			buttonIcons: true, // show the prev/next text
			allDayDefault: true,
			defaultTimedEventDuration: '00:15:00',
			forceEventDuration: true,
			nextDayThreshold: '00:00',
            nowIndicator: true,
            now: current ? moment().format('YYYY-MM-DDTHH:mm:00') : moment().tz(myTimezone).format('YYYY-MM-DDTHH:mm:00'),
            // events: function (start, end, tz, callback) {
            //     callback(JSON.parse(json_events));
            // },
            //events: JSON.parse(json_events),
            //allDaySlot: true,

            loading: function(bool) {
				$('#loading').toggle(bool)
                if (bool) {

                }else{
                    hideExtraRowInMonthView();
                }
			},

            // to customize cell text
            eventRender: function(event, el) {
                var flag=true;
                var event_found=1;
                var school_found=1;
                var student_found=1;
                var teacher_found=1;
                var search_found=1;
                var date_found=1;
                var location_found=1;
                /* Start datepicker - change date */
                var dt=moment(event.start).format('DD/MM/YYYY');


                var eventIdTest = event.id;
                var eventIsLockedTest = event.is_locked;
                var eventallDayTest = event.allDay;
                var eventCanLockTest = event.can_lock;
                // Ajouter l'ID à l'élément DOM
                el.attr('data-event-id', eventIdTest);
                el.attr('data-locked', eventIsLockedTest);
                el.attr('data-allday', eventallDayTest);
                el.attr('data-canlock', eventCanLockTest);

                //$('#datepicker_month').data("DateTimePicker").date(dt)
                /* END datepicker - change date */

                //ProgressIncrement(); //display progress bar
                if (document.getElementById("event_type").value != '0') {
                    event_found=0;
                    $.each($("#event_type option:selected"), function(){
                        var name=$(this).text();
                        if (event.event_type_name.indexOf(name) >= 0){
                            event_found=1;
                            //break;
                        }
                    });
                }
                // event_type=50 - teacher's vacation
                //incase teacher's vacation student will not be checked
                if (event.event_type != 50) {
                    if (document.getElementById("event_student_id").value == '') {
                        student_found=0;
                    }
                    else {
                        if (document.getElementById("event_student_id").value !='0') {
                            student_found=0;
                            $.each($("#event_student option:selected"), function(){
                                var id=$(this).val();
                                if (event.student_id_list.indexOf(id) >= 0){
                                    student_found=1;
                                    //break;
                                }
                            });
                        }
                    }
                }	//event_type <> 50

                // event_type=51 - student's vacation
                //incase student's vacation student will not be checked
                if (event.event_type != 51) {
                    if (document.getElementById("event_teacher_id").value == '') {
                        teacher_found=0;
                    }
                    else {
                        if (document.getElementById("event_teacher_id").value !='0') {
                            if (no_of_teachers != 1){
                            teacher_found=0;
                            $.each($("#event_teacher option:selected"), function(){
                                var id=$(this).val();
                                if (event.teacher_id == id){
                                    teacher_found=1;
                                }
                            });
                            }	//no_of_teachers
                        }
                    }
                }
                /* START listmonth view - display off past dated events */
                var view = $('#calendar').fullCalendar('getView');
                var viewname=view.name;

                if ((viewname=='CurrentListView') || (viewname == 'listMonth') || (viewname == 'listYear') || (viewname == 'listWeek')){
                    date_found=1;
                    var curdate=new Date();

                    // if (moment(event.start).format('YYYYMMDD') < moment(curdate).format('YYYYMMDD') ){

                    //     date_found = 0;
                    // }
                    // console.log('viewname----------------')
                    // console.log(moment(curdate).format('YYYYMMDD'))
                    // console.log('viewname----------------')
                }
                /* END listmonth view - display off past dated events */

                var loc_str=document.getElementById("event_location_id").value;
                if (loc_str == '') {
                    location_found=0;
                }
                else {
                    if (loc_str.substring(0, 1) !='0') {
                        location_found=0;
                        $.each($("#event_location option:selected"), function(){
                            var id=$(this).val();
                            var loc_id=event.location;
                            if (event.location == null){
                                location_found=0;
                            }
                            else {
                                try {
                                    if (loc_id = id){
                                        location_found=1;
                                    }
                                }
                                catch (e){
                                    location_found=0;
                                }
                            }
                        });
                    }
                }


                var event_school=document.getElementById("event_school_id").value;
                if (event_school == '') {
                    school_found=0;
                }
                else {
                    if (event_school.substring(0, 1) !='0') {
                        school_found=0;
                        $.each($("#event_school option:selected"), function(){
                            var id=$(this).val();
                            var event_school_id=event.event_school_id;
                            //console.log(id);
                            if (event.event_school_id == null){
                                school_found=0;
                            }
                            else {
                                // if (event.event_school_id.indexOf(id) >= 0){
                                //     school_found=1;
                                //     //break;
                                // }
                                try {
                                    //console.log();
                                    if (event.event_school_id == id){
                                        school_found=1;
                                        //break;
                                    }
                                    // if (event_school_id >= 0){
                                    //     school_found=1;
                                    // }
                                }
                                catch (e){
                                    school_found=0;
                                }
                            }
                        });
                    }

                }

                /* search START */
                var search_text = $('#search_text').val();
                if ((school_found == 1) && (event_found == 1) && (student_found == 1) && (teacher_found == 1) && (date_found == 1) && (location_found == 1) ) {
                    if (search_text.length > 2){
                        search_found=0;
                        var finalSearch = event.text_for_search + ' ' + event.text_for_search.toLowerCase() + ' ' + event.title + ' ' + event.title.toLowerCase();
                        //if ((event.tooltip.toLowerCase().indexOf(search_text) >= 0) || (event.tooltip.toLowerCase().indexOf(search_text) >= 0)) {
                        //if (event.tooltip.toLowerCase().indexOf(search_text) >= 0) {
                        if (finalSearch.indexOf(search_text) >= 0) {
                            //if (event.tooltip.indexOf(search_text) >= 0) {
                            search_found=1;
                            //flag=true;
                        } else {
                            search_found=0;
                            //flag=false;
                        }
                    }
                } //
                /* search END */
                //console.log('event_id='+event.id+';event_found='+event_found+';student_found='+student_found+';teacher_found='+teacher_found+';date_found='+date_found+';location_found='+location_found+';search_found='+search_found);

                if ((school_found == 1) &&
                    (event_found == 1) &&
                    (student_found == 1) &&
                    (teacher_found == 1) &&
                    (search_found == 1) &&
                    (date_found == 1) &&
                    (location_found == 1) )
                {
                    flag = true;
                } else {
                    flag = false;
                }


                //Check if students are not away
                var icon2 = '<i class="fa-solid fa-circle-info" style="position:absolute; right:2px; top:2px; color:orange;"></i>';
                var hasStudentWithFutureDate = false;
                for (var i = 0; i < event.studentsbySchool.length; i++) {
                    var student = event.studentsbySchool[i];
                    if (student.dates) {
                        hasStudentWithFutureDate = true;
                        break;
                    }
                }
                if (hasStudentWithFutureDate) {
                    $(el).find('.fc-time').after(icon2);
                }

                //if valid
                if (flag == true){
                    stime=moment(event.start).format('HH:mm');
                    etime=moment(event.end).format('HH:mm');
                    if (moment(event.end).isValid() == false){
                        etime=stime;
                    }
                    foundRecords=1; //found valid record;
                    //event.allDay = true;
                    //console.log(event)


                    if (event.allDay) {
                        $(el).find('div.fc-content').prepend(icon);
                    } else {
                        // $(el).find('.fc-time').prepend(icon);
                        $(el).find('.fc-time').html(icon);
                    }

                    if(event.event_category_name === "Temp") {
                        if(event.event_type == 10){
                            var icon ='<span class="fa fa-solid fa-triangle-exclamation txt-orange" style="padding:2px;"></span>';
                            $(el).find('div.fc-content').prepend(icon);
                        }
                    }

                    if (event.isInvoice){
                        var icon ='<span class="fa fa-solid fa-file-pdf txt-orange" style="padding:2px;"></span>';
                        $(el).find('div.fc-content').prepend(icon);
                    }

                    var icon ='<span class="fa fa-lock txt-orange" style="padding:2px;"></span>';
                    if (event.is_locked == '1'){
                        $(el).find('div.fc-content').prepend(icon);


                    }else if (event.is_locked == '0'){
                        icon='';
                        lockRecords=1;

                    } else if (event.event_mode == '0'){
                        icon ='<i class="fa fa-file"></i> ';
                    } else{
                        icon='';
                    }
                    if (document.getElementById("view_mode").value != 'month'){
                        if (event.duration_minutes > 60){
                            var ooo= event.title_extend;
                            $(el).find('div.fc-content').append(ooo);
                        }
                    }
                    prevdt = moment(event.start).format('DD-MM-YYYY');
                   // $(el).find('div.fc-title').prepend(event.event_type_name+':'+moment(event.start).format('DD-MM-YYYY')+' '+content_format);
                    resultHtml+='<tr class="agenda_event_row" href="'+event.url+'">';
                    resultHtml+='<td href="'+event.url+'">'+moment(event.start).format('DD-MM-YYYY')+'</td>';
                    resultHtml+='<td>'+stime+'</td>';
                    resultHtml+='<td>'+etime+'</td>';
                    if ( event.no_of_students <= 1 ){
                        resultHtml+='<td>'+event.no_of_students+' :</td>';
                    }else{
                        resultHtml+='<td>'+event.no_of_students+' :</td>';
                    }
                    resultHtml+='<td>'+event.title+'</td>';
                    resultHtml+='<td>'+event.cours_name+'</td>';
                    resultHtml+='<td>'+event.duration_minutes+' minutes</td>';
                    resultHtml+='<td>'+event.teacher_name+'</td>';
                    resultHtml+='</tr>';

                }

                resultHtml_rows=resultHtml;

                el.attr('title', event.tooltip);
                //el.attr('data-html', 'true');

                setTimeout(function() {
                    el.popover({
                        title: event.tooltip,
                        trigger: 'hover',
                        html: true,
                        placement: 'top',
                        container: 'body'
                    });
                }, 1500);

                //el.attr('timetext', event.title);
                //$('#timetext').text(event.cours_name);
                $(el).find('#timetext').append(' '+event.event_type_name);
                return flag;
            },

            eventClick: function(event, jsEvent, view) {
                let loggedId = <?= $AppUI->person_id ?>;
                let evnUsrId = event.teacher_id;
                let invoice_type = event.event_invoice_type

                if (event.url) {
                    SetEventCookies();
                    document.getElementById('edit_view_url').value=event.url;
                    document.getElementById('confirm_event_id').value=event.id;

                    if (event.action_type == 'edit') {
                        $('#event_btn_edit_text').text("{{__('Edit')}}");
                        if (event.can_lock == 'Y') {
                            const type_removed = [50, 51];
                            if(type_removed.includes(event.event_type) != true){
                                if(loggedId == evnUsrId){
                                    $('#btn_confirm').show();
                                }else if((user_role == 'admin_teacher' || user_role == 'school_admin_teacher') ){ /*&& invoice_type == 'S'*/
                                    $('#btn_confirm').show();
                                }else{
                                    $('#btn_confirm').hide();
                                }
                                //$('#btn_confirm_unlock').hide();

                            }else {
                                $('#btn_confirm').hide();
                                //$('#btn_confirm_unlock').show();
                            }
                        } else {
                            $('#btn_confirm').hide();
                            //$('#btn_confirm_unlock').show();
                        }

                        if (event.event_category == 0) {
                            $('#messageNoCategory').show();
                        } else {
                            $('#messageNoCategory').hide();
                        }

                    } else {
                        $('#event_btn_edit_text').text("{{__('View')}}");
                        $('#btn_confirm').hide();
                        //$('#btn_confirm_unlock').show();
                    }

                    stime=moment(event.start).format('HH:mm');
                    etime=moment(event.end).format('HH:mm');
                        if (moment(event.end).isValid() == false){
                            etime=stime;
                        }

                    var dayEvent = moment(event.start).format('DD/MM/YYYY');
                    var dayEventEnd = moment(event.end).format('DD/MM/YYYY');

                    var eventStart = moment.utc(event.start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
                    var eventEnd = moment.utc(event.end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone);
                    var now = moment().tz(myTimezone).format('YYYY-MM-DDTHH:mm:00');

                    const eventStartTimeStamp = moment.utc(event.start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
                    const eventEndTimeStamp = moment.utc(event.end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();
                    const nowTimeStamp =  moment.utc(now, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezone).valueOf();

                    if (eventEnd.isBefore(nowTimeStamp)) {
                    var timeBetween = timeDifference(nowTimeStamp, eventEndTimeStamp);
                    var phrase = "Completed since " + timeBetween;
                    }

                    if (eventStart.isAfter(nowTimeStamp)) {
                    var timeBetween = timeDifference(eventStartTimeStamp, nowTimeStamp);
                    var phrase = "Incoming in " + timeBetween;
                    }

                    if (eventStart.isBefore(nowTimeStamp) && eventEnd.isAfter(nowTimeStamp)) {
                    var timeBetween = timeDifference(eventEndTimeStamp, nowTimeStamp);
                    var phrase = "event in progress - ends in "+timeBetween+"";
                  //  console.log(phrase);
                    }

                    if (eventEnd.isSame(nowTimeStamp)) {
                    var phrase = "ends soon - in few seconds";
                  //  console.log(phrase);
                    }

                    var titleEvent = ''
                    if(event.title_event !== null && event.title_event !== "") {
                        var titleEvent = '<tr><td>Title :</td><td class="light-blue-txt gilroy-bold"> ' + event.title_event + '</td></tr>';
                    }

                    //document.getElementById('event_modal_title').text=stime+' - '+etime+':'+event.title;
                    if (stime == '00:00') {

                        var date1 = new Date(eventStartTimeStamp);
                        var date2 = new Date(eventEndTimeStamp);
                        var differenceInMillis = Math.abs(date2 - date1);
                        var hoursDifference = differenceInMillis / (1000 * 60 * 60);

                        if(Number(hoursDifference) == Number(24)) {
                            $('#event_modal_title').html('<span style="font-size: 22px; line-height: 2">' + event.event_type_name+' <p class="small">('+phrase+')</p></span><span style="color:#333;"></span><table class="table table-stripped table-hover">'+titleEvent+'<tr><td><i class="fa-solid fa-calendar-days"></i> Date :</td><td class="light-blue-txt gilroy-bold"> '+moment(event.start).format('DD/MM/YYYY')+'</td></tr>'+event.title_for_modal+'</table>');
                        } else {
                            $('#event_modal_title').html('<span style="font-size: 22px; line-height: 2">' + event.event_type_name+' <p class="small">('+phrase+')</p></span><span style="color:#333;"></span><table class="table table-stripped table-hover">'+titleEvent+'<tr><td><i class="fa-solid fa-calendar-days"></i> Date :</td><td class="light-blue-txt gilroy-bold"> '+moment(event.start).format('DD/MM/YYYY')+' au '+moment(event.end).subtract(1, 'days').format('DD/MM/YYYY')+'</td></tr>'+event.title_for_modal+'</table>');
                        }
                    }
                    else {
                        // $('#event_modal_title').text(event.event_type_name+':'+stime+'-'+etime+' '+event.title);
                        $('#event_modal_title').html('<span style="font-size: 22px; line-height: 2">' + event.event_type_name+'<p class="small">('+phrase+')</p></span><span style="color:#333;"></span><table style="width:100%;" class="table table-stripped table-hover">'+titleEvent+'<tr><td><i class="fa-solid fa-calendar-days"></i> Date :</td><td class="light-blue-txt gilroy-bold"> '+dayEvent+'</td></tr><tr><td><i class="fa-solid fa-clock"></i> Timer :</td><td class="light-blue-txt gilroy-bold"> '+stime+' - '+etime+'</td></tr>'+event.title_for_modal+'</table>');
                    }

                if((user_role == 'student' || user_role == 'parent') ){
                   if(event.event_type == 50) {
                    $("#btn_edit_view").fadeOut();
                   } else {
                    $("#btn_edit_view").fadeIn();
                    $("#btn_edit_view").attr("href", event.url);
                   }
                } else {
                    $("#btn_edit_view").attr("href", event.url);
                }
                    $("#EventModal").modal('show');
                    return false;
                }
            },

            eventAfterAllRender: function() {
                DisplayCalendarTitle();
                var resultHtmlHeader='';
                var resultHtmlHeader_cc='';
                //add header
                /* commened header by soumen */

                row_hdr_date='Date';
                row_hdr_start_time='Heure de d�part';
                row_hdr_end_time='Heure de fin';
                row_hdr_no_of_students='Nombre of studiants';
                row_hdr_student_name='Nom de student name';
                row_hdr_course='Cours';
                row_hdr_duration_id='Duration Minutes';
                row_hdr_teacher_id='Professeur';

                resultHtmlHeader+='<table id="agenda_table" name="agenda_table" cellpadding="0" cellspacing="0" width="99%" class="agenda_table_class tablesorter">';
                resultHtmlHeader+='<thead>';
                resultHtmlHeader+='<tr>';
                resultHtmlHeader+='<th width="12%">'+row_hdr_date+'</th>';
                resultHtmlHeader+='<th width="6%">'+row_hdr_start_time+'</th>';
                resultHtmlHeader+='<th width="6%">'+row_hdr_end_time+'</th>';
                resultHtmlHeader+='<th width="10%">'+row_hdr_no_of_students+'</th>';
                resultHtmlHeader+='<th width="19%">'+row_hdr_student_name+'</th>';
                resultHtmlHeader+='<th width="19%">'+row_hdr_course+'</th>';
                resultHtmlHeader+='<th width="10%">'+row_hdr_duration_id+'</th>';
                resultHtmlHeader+='<th width="8%">'+row_hdr_teacher_id+'</th>';
                resultHtmlHeader+='</tr>';
                resultHtmlHeader+='</thead>';

                //resultHtmlHeader+=resultHtml;
                resultHtmlHeader+=resultHtml_rows;

                resultHtml_rows='';

                resultHtmlHeader+="</table>";
                resultHtmlHeader+="<script>";
                resultHtmlHeader+="$('#agenda_table').DataTable({";
                resultHtmlHeader+='"stateSave": true,';
                resultHtmlHeader+='"paging":   false,';
                resultHtmlHeader+='"searching": false,';
                resultHtmlHeader+='"order": [[2, "asc"]],';
                resultHtmlHeader+='"bInfo":     false,';
                //resultHtmlHeader+="dom: 'Bfrtip',";       // uncomment to enable datatable export
                //resultHtmlHeader+="buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],"; // uncomment to enable datatable export
                resultHtmlHeader+='"bProcessing": true});';
                resultHtmlHeader+='<\/script>';
                $('#agenda_list').html(resultHtmlHeader);



                resultHtml='';
                document.getElementById("btn_copy_events").style.display = "none";
                document.getElementById("btn_copy_events_mobile").style.display = "none";
                document.getElementById("btn_goto_planning").style.display = "none";
                document.getElementById("btn_goto_planning_mobile").style.display = "none";
                document.getElementById("btn_delete_events").style.display = "none";
                document.getElementById("btn_delete_events_mobile").style.display = "none";
                document.getElementById("btn_validate_events").style.display = "none";
                document.getElementById("btn_validate_events_mobile").style.display = "none";

                var user_role=document.getElementById("user_role").value;
                let selected_non_validate_ids = document.getElementById("get_non_validate_event_id").value;
                let selected_non_validate_delete_ids = document.getElementById("get_non_validate_event_delete_id").value;
                if (foundRecords == 1)
                {
                    if (user_role == 'student' || user_role == 'parent') {
                        document.getElementById("btn_copy_events").style.display = "none";
                        document.getElementById("btn_copy_events_mobile").style.display = "none";
                    } else {
                        document.getElementById("btn_copy_events").style.display = "block";
                        document.getElementById("btn_copy_events_mobile").style.display = "block";
                        if (selected_non_validate_ids.length ==0) {
                            document.getElementById("btn_validate_events").style.display = "none";
                            document.getElementById("btn_validate_events_mobile").style.display = "none";
                        }else {
                            document.getElementById("btn_validate_events").style.display = "block";
                            document.getElementById("btn_validate_events_mobile").style.display = "block";
                        }
                    }
                }

                if (foundRecords > 0)
                {
                    if (user_role == 'student' || user_role == 'parent') {
                        document.getElementById("btn_validate_events").style.display = "none";
                        document.getElementById("btn_validate_events_mobile").style.display = "none";
                        document.getElementById("btn_delete_events").style.display = "none";
                        document.getElementById("btn_delete_events_mobile").style.display = "none";
                    }else if (user_role == 'admin_teacher' || user_role == 'school_admin_teacher') {
                        if (selected_non_validate_ids.length == 0) {
                            document.getElementById("btn_validate_events").style.display = "none";
                            document.getElementById("btn_validate_events_mobile").style.display = "none";
                            document.getElementById("btn_delete_events").style.display = "none";
                            document.getElementById("btn_delete_events_mobile").style.display = "none";
                        }else{
                            document.getElementById("btn_validate_events").style.display = "block";
                            document.getElementById("btn_validate_events_mobile").style.display = "block";
                            document.getElementById("btn_delete_events").style.display = "block";
                            document.getElementById("btn_delete_events_mobile").style.display = "block";
                        }
                        if (selected_non_validate_delete_ids.length > 0) {
                            document.getElementById("btn_delete_events").style.display = "block";
                            document.getElementById("btn_delete_events_mobile").style.display = "block";
                        }
                    } else {
                        //Delete button will be visible if events are available and all events are in unlock mode
                        if (selected_non_validate_ids.length ==0) {
                            document.getElementById("btn_delete_events").style.display = "none";
                            document.getElementById("btn_delete_events_mobile").style.display = "none";
                            document.getElementById("btn_validate_events").style.display = "none";
                            document.getElementById("btn_validate_events_mobile").style.display = "none";
                        }else {
                            document.getElementById("btn_delete_events").style.display = "block";
                            document.getElementById("btn_delete_events_mobile").style.display = "block";
                            document.getElementById("btn_validate_events").style.display = "block";
                            document.getElementById("btn_validate_events_mobile").style.display = "block";
                        }
                        if (selected_non_validate_delete_ids.length > 0) {
                            document.getElementById("btn_delete_events").style.display = "block";
                            document.getElementById("btn_delete_events_mobile").style.display = "block";
                        }

                    }
                    if (lockRecords == 0)
                    {
                        document.getElementById("btn_delete_events").style.display = "none";
                        document.getElementById("btn_delete_events_mobile").style.display = "none";
                        document.getElementById("btn_validate_events").style.display = "none";
                        document.getElementById("btn_validate_events_mobile").style.display = "none";

                    }
                } else{
                    document.getElementById("btn_delete_events").style.display = "none";
                    document.getElementById("btn_delete_events_mobile").style.display = "none";
                    document.getElementById("btn_validate_events").style.display = "none";
                    document.getElementById("btn_validate_events_mobile").style.display = "none";
                }


                lockRecords=0;

                var view = $('#calendar').fullCalendar('getView');
                if ( (document.getElementById("copy_date_from").value.length != 0)
                    && (document.getElementById("copy_view_mode").value == view.name) )
                {

                    var source_start_date = document.getElementById("date_from").value;
                    var source_end_date = document.getElementById("date_to").value;
                    var target_start_date = document.getElementById("copy_date_from").value;
                    var target_end_date = document.getElementById("copy_date_to").value;

                    var momentSourceStart = moment(source_start_date, "YYYY-MM-DD");
                    var momentSourceEnd = moment(source_end_date, "YYYY-MM-DD");
                    var momentTargetStart = moment(target_start_date, "YYYY-MM-DD");
                    var momentTargetEnd = moment(target_end_date, "YYYY-MM-DD");

                    if (momentSourceEnd.isBefore(momentTargetStart)) {
                        document.getElementById("btn_goto_planning").style.display = "none";
                        document.getElementById("btn_goto_planning_mobile").style.display = "none";
                        showMessage('Your cannot copy your events or lessons in the past.', 'danger');
                    } else {
                        document.getElementById("btn_goto_planning").style.display = "block";
                        document.getElementById("btn_goto_planning_mobile").style.display = "block";
                    }
                }

                target_start_date=document.getElementById("date_from").value;
                target_end_date=document.getElementById("date_to").value;

                foundRecords=0;

                CheckPermisson();

                // Récupérer les éléments btn_validate_events et btn_delete_events
                var btnValidateEvents = document.getElementById("btn_validate_events");
                var btnDeleteEvents = document.getElementById("btn_delete_events");

                // Récupérer l'élément dropdownActions
                //var dropdownActions = document.getElementById("dropdownActions");

                // Vérifier si l'un des boutons est affiché
                //if (btnValidateEvents.style.display !== "none" || btnDeleteEvents.style.display !== "none") {
                //dropdownActions.style.display = "block"; // Afficher le dropdown
                //} else {
                //dropdownActions.style.display = "none"; // Masquer le dropdown
                //}

                $('#agenda_table tr').click(function(){

                    //alert('agenda_table tr Render soumen');

                    //var x=$(this).attr('href');
                    if ((typeof $(this).attr('href') === "undefined")) {
                        return false;
                    }
                    //alert(x);
                    if ($(this).attr('href') != "") {
                        SetEventCookies();
                        window.location = $(this).attr('href');
                    }

                    return false;
                });

            },

            // Renders events onto the view and populates the View's segment array
            renderEvents: function(events) {
                var dayEvents = [];
                var timedEvents = [];
                var daySegs = [];
                var timedSegs;
                var i;

                // separate the events into all-day and timed
                for (i = 0; i < events.length; i++) {
                    if (events[i].allDay) {
                        dayEvents.push(events[i]);
                    }
                    else {
                        timedEvents.push(events[i]);
                    }
                }

                // render the events in the subcomponents
                timedSegs = this.timeGrid.renderEvents(timedEvents);
                if (this.dayGrid) {
                    daySegs = this.dayGrid.renderEvents(dayEvents);
                }

                // the all-day area is flexible and might have a lot of events, so shift the height
                this.updateHeight();
            },

            viewRender: function( view, el ) {
                //$("#agenda_table tr:gt(0)").remove();
                //$("#agenda_table_current tr:gt(0)").remove();
                resultHtml='';
                prevdt='';
                //view change event - here needs to refresh data
                if (view.name == 'listYear') {
                    document.getElementById("date_from").value = getCookie("date_from");
                    document.getElementById("date_to").value = getCookie("date_to");


                } else if (view.name=='CurrentListView') {
                    var dt = new Date();
                    let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
                    document.getElementById("date_from").value = formatDate(dt);
                    document.getElementById("date_to").value = formatDate(CurrentListViewDate);
                }
                else {
                    document.getElementById("date_from").value = view.intervalStart.format('YYYY-MM-DD');
                    document.getElementById("date_to").value = view.intervalEnd.format('YYYY-MM-DD');

                    var date = moment(document.getElementById("date_to").value);
                    var newDate = date.subtract(1, 'day').format('YYYY-MM-DD');
                    document.getElementById("date_to").value = newDate;
                }

                if (document.getElementById("prevnext").value == 'yes'){
                    if ($('#calendar').fullCalendar('getView').name == 'CurrentListView'){
                        document.getElementById("view_mode").value = 'CurrentListView';
                        $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                        $('#calendar').fullCalendar().find('.fc-day-header').hide();
                    } else {

                        document.getElementById("view_mode").value = 'list';
                        $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                        $('#calendar').fullCalendar().find('.fc-day-header').hide();
                    }
                }
                else
                {
                    if ($('#calendar').fullCalendar('getView').name == 'CurrentListView'){
                        document.getElementById("view_mode").value = 'CurrentListView';
                        $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                        $('#calendar').fullCalendar().find('.fc-day-header').hide();
                    } else {
                        document.getElementById("view_mode").value = view.name;
                    }
                }

                SetEventCookies();


                if  (firstload != '0'){
                    if($('#calendar').fullCalendar('getView').name !='CurrentListView'){
                        getFreshEvents();
                    } else {
                       getCurrentListFreshEvents();
                    }

                }
            },
            dayClick: function(date, jsEvent, view, resource) {

                var listAvailabilities = document.getElementById('displayDate');
                listAvailabilities.innerHTML = date.format('DD/MM/YYYY');

                var listEvents = document.getElementById('displayDateEvent');
                listEvents.innerHTML = date.format('DD/MM/YYYY');

                if (date._ambigTime) {
                $('#agenda_select').val('2');
                } else {
                   $('#agenda_select').val('1');
                }

                // $('#start_date').val('');
                // $('#end_date').val('');
                // $("#addAgendaModal").modal('show');
                // const [day, month, year] = date.format().split('-');
                // const result = [year, month, day].join('/');
                // $('#start_date').val(result);
                // $('#end_date').val('');
                // console.log('xxx',date,jsEvent,view,resource)
            },
            select: function(startDate, endDate, jsEvent, view, resource) {

                if (startDate._ambigTime) {
                $('#agenda_select').val('2');
                } else {
                   $('#agenda_select').val('1');
                }

                var listAvailabilities = document.getElementById('displayDate');
                listAvailabilities.innerHTML = startDate.format('DD/MM/YYYY');

                var listEvents = document.getElementById('displayDateEvent');
                listEvents.innerHTML = startDate.format('DD/MM/YYYY');


                @if(!$AppUI->isStudent() && !$AppUI->isParent())
                $('#start_date').val('');
                $('#end_date').val('');
                if (getSchoolIDs('is_multi')) {
                    $('#modal_lesson_price').modal('show');
                    $("#modal_alert_body").text("Please select One school for add event or lesson");
                }else{
                    $("#addAgendaModal").modal('show');
                    const startresult = startDate.format('DD/MM/YYYY');
                    const startTime = startDate.format('HH:mm');
                    $('#start_date').val(startresult);
                    $('#start_time').val(startTime);
                    const endTime = endDate.format('HH:mm');
                    const endresult = endDate.subtract(1, 'seconds').format('DD/MM/YYYY');
                    $('#end_date').val(endresult);
                    $('#end_time').val(endTime).trigger('change');
                    $('#agenda_select').trigger('change');
                    $('#Title').val('');
                    //resetStudentList();
                    getAwayStudent();
                }
                @endif

            }
        })    //full calendar initialization
        CheckPermisson();

    } //full calender - RenderCalendar


    function timeDifference(date1,date2) {
        var difference = date1 - date2

        var daysDifference = Math.floor(difference/1000/60/60/24);
        difference -= daysDifference*1000*60*60*24

        var hoursDifference = Math.floor(difference/1000/60/60);
        difference -= hoursDifference*1000*60*60

        var minutesDifference = Math.floor(difference/1000/60);
        difference -= minutesDifference*1000*60

        var secondsDifference = Math.floor(difference/1000);

        if(daysDifference > 0) {
            return daysDifference + (daysDifference === 1 ? ' day' : ' days');
        } else {
            if(hoursDifference > 0) {
                return hoursDifference + (hoursDifference === 1 ? ' hour ' : ' hours ') + minutesDifference + (minutesDifference === 1 ? ' minute' : ' minutes');
            } else {
                return minutesDifference + (minutesDifference > 1 ? ' minutes' : ' minute');
            }
        }
    }


    function getFreshEvents(p_view=getCookie("cal_view_mode")){
        $("#pageloader").fadeIn();
        if (document.getElementById("view_mode").value == 'CurrentListView'){
            return;
        }
        //console.log(getCookie("date_from"));
        var start_date=document.getElementById("date_from").value;
        var end_date=document.getElementById("date_to").value;
        if (getCookie("date_from") != ""){
            //alert('getrefresh..: Start');
            document.getElementById("date_from").value = getCookie("date_from");
            document.getElementById("date_to").value = getCookie("date_to");
            var start_date=getCookie("date_from");
            var end_date=getCookie("date_to");
        }
        if (p_view=='CurrentListView') {
            var dt = new Date();
            let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
            document.getElementById("date_from").value = formatDate(dt);
            document.getElementById("date_to").value = formatDate(CurrentListViewDate);

            var start_date=document.getElementById("date_from").value;
            var end_date=document.getElementById("date_to").value;
        }

       // console.log('get envent start date',start_date);
      //  console.log('get envent end date',end_date);

        var list_student_id = getStudentIDs();
        //console.log('list of students', list_student_id);
       // console.log('list des students for delete', list_student_id);
        var school_id=document.getElementById('school_id').value;
        var p_event_school_id=document.getElementById("event_school_id").value;
        var p_event_location_id= getLocationIDs();
        var p_event_type = getEventIDs();
       // console.log('p_event_type' + p_event_type + 'toutcollé');
        document.getElementById("prevnext").value = '';
        var json_events = @json($events);
        $("#pageloader").fadeIn();
        //console.log('typessss', p_event_type);
        //console.log('p_event_location_id', p_event_location_id);
        $.ajax({
            //url: BASE_URL + '/'+school_id+'/get_event',
            url: BASE_URL + '/get_event',
            type: 'GET',
            data: 'type=fetch&location_id='+p_event_location_id+'&event_type='+p_event_type+'&school_id='+p_event_school_id+'&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view+'&list_student_id='+list_student_id,
            async: true,
            success: function(s){
                //console.log(JSON.parse(s));
                recentFreshEventsList = JSON.parse(s);
                SetEventCookies();
                json_events = s;
                var selected_ids = [];
                var selected_validate_ids = [];
                var selected_non_validate_ids = [];
                var selected_non_validate_delete_ids = [];
                const type_removed = [50, 51, 100];
                Object.keys(JSON.parse(json_events)).forEach(function(key) {
                    if(type_removed.includes(JSON.parse(json_events)[key].event_type) != true){
                        let end = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY HH:mm");
                        let start = moment(JSON.parse(json_events)[key].start.toString()).format("DD/MM/YYYY HH:mm");
                        let end_date = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY HH:mm");
                        let teacher_name =JSON.parse(json_events)[key].teacher_name;
                        let cours_name = JSON.parse(json_events)[key].cours_name;
                        let cours_id = JSON.parse(json_events)[key].id;
                        let teacher_id = JSON.parse(json_events)[key].teacher_id;
                        let invoice_type = JSON.parse(json_events)[key].invoice_type;

                        let startDisplay = moment(JSON.parse(json_events)[key].start.toString()).format("DD/MM HH:mm");
                        let endDisplay = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM HH:mm");

                        if(moment(JSON.parse(json_events)[key].start.toString()).format("DD/MM/YYYY") == moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY")) {
                            endDisplay = '';
                        } else {
                            endDisplay = ' to ' + endDisplay;
                        }

                        let loggedin_teacher_id = <?= $AppUI->person_id; ?>

                        let duration_minutes = JSON.parse(json_events)[key].duration_minutes;
                        if (cours_name == null) {
                            cours_name = '';
                        }
                        if (duration_minutes == null) {
                            duration_minutes = 0;
                        }
                        if (teacher_name == null) {
                            teacher_name = '';
                        }


                        var curdate=new Date();
                        if (JSON.parse(json_events)[key].is_locked ==1) {
                            if((invoice_type == 'S') && ((user_role == 'admin_teacher') || user_role == ('school_admin_teacher'))){
                                    selected_validate_ids.push('<tr><td>Date</td><td><b>'+startDisplay+' '+endDisplay+'</b></td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr><tr><td>Teacher</td><td>with '+teacher_name + '</td></tr>');
                            }
                            if((invoice_type == 'T') && (loggedin_teacher_id == teacher_id)){
                                    selected_validate_ids.push('<tr><td><b>Date</b></td><td><b>'+startDisplay+' '+endDisplay+'</td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr>');
                            }
                        }
                        else if(moment(JSON.parse(json_events)[key].end) < moment(curdate)){

                            if((invoice_type == 'S') && (user_role == 'admin_teacher') ){
                                selected_non_validate_ids.push('<tr><td width="45"><b>Date</b></td><td><b>'+startDisplay+' '+endDisplay+'</b></td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr><tr><td>Teacher</td><td>with '+teacher_name + '</td></tr>');
                            }
                            if((invoice_type == 'T') && (loggedin_teacher_id == teacher_id)){
                                selected_non_validate_ids.push('<tr><td width="45"><b>Date</b></td><td><b>'+startDisplay+' '+endDisplay+'</b></td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr>');
                            }

                        }

                        if (JSON.parse(json_events)[key].is_locked ==0) {
                             if((invoice_type == 'S') && (user_role == 'admin_teacher') ){
                                selected_non_validate_delete_ids.push('<tr><td width="45"><b>Date</b></td><td><b>'+start+' to '+end+'</b></td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr>');
                            }
                            if((invoice_type == 'T') && (loggedin_teacher_id == teacher_id)){
                                selected_non_validate_delete_ids.push('<tr><td width="45"><b>Date</b></td><td><b>'+start+' to '+end+'</b></td></tr><tr><td>Title</td><td>'+JSON.parse(json_events)[key].title+'</td></tr><tr><td>Type</td><td>'+cours_name+'</td></tr><tr><td>Duration</td><td>'+duration_minutes+' Mn.</td></tr><tr><td>Teacher</td><td>with '+teacher_name + '</td></tr>');
                            }
                        }
                        // if((invoice_type == 'S') && ((user_role == 'schooladmin') || (user_role == 'admin_teacher'))){
                        //     selected_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+'   minutes '+teacher_name);
                        // }else if(invoice_type == 'T'){
                        //     if(loggedin_teacher_id == teacher_id){
                        //         selected_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+'   minutes '+teacher_name);
                        //     }
                        // }


                    }

                });
                selected_ids.join("|");

                //selected_non_validate_ids.join(",  ");
                selected_non_validate_ids = selected_non_validate_ids.map(e => JSON.stringify(e)).join("|");
                selected_non_validate_delete_ids = selected_non_validate_delete_ids.map(e => JSON.stringify(e)).join("|");

                document.getElementById("get_event_id").value = selected_ids;
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_validate_events").style.display = "none";
                    document.getElementById("btn_validate_events_mobile").style.display = "none";
                } else {
                    selected_validate_ids.join("|");
                }
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_delete_events").style.display = "none";
                    document.getElementById("btn_delete_events_mobile").style.display = "none";
                } else {
                    selected_validate_ids.join("|");
                }
                document.getElementById("get_validate_event_id").value = selected_validate_ids;
                document.getElementById("get_non_validate_event_id").value = selected_non_validate_ids;
                document.getElementById("get_non_validate_event_delete_id").value = selected_non_validate_delete_ids;

                $('#calendar').fullCalendar('removeEvents', function () { return true; });

                var eventsToPut = [];

                var myTimezoneDetect = "{{ $myCurrentTimeZone }}";
                var nowDetect = moment().tz(myTimezoneDetect).format('YYYY-MM-DDTHH:mm:00');

                $.each(JSON.parse(json_events), function(k, v)  {
                    var eventStartDetect = moment.utc(v.start, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezoneDetect);
                    var eventEndDetect = moment.utc(v.end, 'YYYY-MM-DDTHH:mm:00').subtract(2, 'hours').tz(myTimezoneDetect);
                    if (eventStartDetect.isBefore(nowDetect) && eventEndDetect.isAfter(nowDetect) && v.event_type == 10) {
                        $('#eventInProgress').css('display','inline-block');
                    }
                    // OBJECT is created when processing response
                    eventsToPut.push(v);
                });
                //console.log('test', json_events);



                $('#calendar').fullCalendar('addEventSource',JSON.parse(json_events), true);
                //$("#agenda_table tr:gt(0)").remove();
                //$("#agenda_table_current tr:gt(0)").remove();
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events));
                $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                $('#calendar').fullCalendar('refetchEventSources',JSON.parse(json_events))
                $('#calendar').fullCalendar('rerenderEvents' );
                $('#calendar').fullCalendar('refetchEvents');
                $('#calendar').fullCalendar('refetchEvents');
                $('#calendar').fullCalendar( 'renderEvent', JSON.parse(json_events) , 'stick');
                // $('#calendar').fullCalendar({ events: JSON.parse(json_events) });

               /* var myTimezone = "{{ $myCurrentTimeZone }}";
                const scrollTime = moment().tz(myTimezone).format("HH");
                $scrollTo = $('[data-time="'+scrollTime+':00:00"]');
                console.log('current hour', scrollTime)
                if ($scrollTo.length > 0) {
                    $(".fc-scroller").animate({
                        scrollTop: $scrollTo.offset().top
                    }, 1500);
                }
*/


                if (document.getElementById("view_mode").value == 'list'){
                    //remove

                    $('#calendar').fullCalendar().find('.fc-day-header').hide();
                    $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                    document.getElementById("agenda_list").style.display = "block";
                }else if (document.getElementById("view_mode").value == 'CurrentListView'){
                    //remove
                    $('#calendar').fullCalendar().find('.fc-day-header').hide();
                    $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                    document.getElementById("agenda_list").style.display = "block";
                }
                else
                {

                    resultHtml='';
                    prevdt='';
                    document.getElementById("prevnext").value='';
                    document.getElementById("agenda_list").style.display = "none";
                    $('#calendar').fullCalendar().find('.fc-day-header').show();
                    $('#calendar').fullCalendar().find('.fc-day-header').parents('table').show();
                }
                hideExtraRowInMonthView();
                $("#pageloader").fadeOut();
            },
            error: function(ts) {
                //errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
                // alert(ts.responseText)
                $("#pageloader").fadeOut();
                console.error(ts.responseText);
            }
        });
    }

    function first() {
        setTimeout(function() {

            SetEventCookies();

            second();
            //third();
        }, 100);
    }
    function second() {
        var url = window.location.href;
            window.location.href= url;
            return true;
        //$('#q').append('second <br>');
    }
    function third() {
        $('#q').append('third <br>');
    }
    function getCurrentListFreshEvents(p_view=getCookie("cal_view_mode"),firstLoad=''){

        document.getElementById("agenda_list").style.display = "none";

       // console.log('go to current view')
        $('#calendar').fullCalendar('changeView', 'CurrentListView');
        var dt = new Date();
        let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
        document.getElementById("date_from").value = formatDate(dt);
        document.getElementById("date_to").value = formatDate(CurrentListViewDate);

        var start_date=document.getElementById("date_from").value;
        var end_date=document.getElementById("date_to").value;

        var list_student_id = getStudentIDs();
       // console.log('first list student', list_student_id);

        var school_id=document.getElementById('school_id').value;
        var p_event_school_id=document.getElementById("event_school_id").value;
        var p_event_location_id=getLocationIDs();
        var p_event_type = getEventIDs();
        document.getElementById("prevnext").value = '';
        var json_events = @json($events);
        $.ajax({
            //url: BASE_URL + '/'+school_id+'/get_event',
            url: BASE_URL + '/get_event',
            type: 'GET',
            data: 'type=fetch&location_id='+p_event_location_id+'&event_type='+p_event_type+'&school_id='+p_event_school_id+'&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view + '&list_student_id='+list_student_id,
            //async: false,
            success: function(s){
                //SetEventCookies();
                if (firstLoad =='firstLoad') {
                    $("#agenda_table tr:gt(0)").remove();
                } else {
                    first();
                }
                json_events = s;
                var selected_ids = [];
                var selected_validate_ids = [];
                var selected_non_validate_ids = [];
                var selected_non_validate_delete_ids = [];
                const type_removed = [50, 51, 100];
                let resultHtml_cc ='';
                $('#agenda_list').html(resultHtml_cc);
                //$("#agenda_table tr:gt(0)").remove();
                Object.keys(JSON.parse(json_events)).forEach(function(key) {
                    if(type_removed.includes(JSON.parse(json_events)[key].event_type) != true){
                        let end = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY");
                        let start = moment(JSON.parse(json_events)[key].start.toString()).format("DD/MM/YYYY HH:mm");
                        let end_date = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY HH:mm");
                        let teacher_name =JSON.parse(json_events)[key].cours_name;
                        let cours_name = JSON.parse(json_events)[key].duration_minutes;
                        let duration_minutes = JSON.parse(json_events)[key].teacher_name;
                        let teacher_id = JSON.parse(json_events)[key].teacher_id;
                        let loggedin_teacher_id = <?= $AppUI->person_id; ?>

                        if (cours_name == null) {
                            cours_name = '';
                        }
                        if (duration_minutes == null) {
                            duration_minutes = 0;
                        }
                        if (teacher_name == null) {
                            teacher_name = '';
                        }
                        var curdate=new Date();
                        if (end<moment(curdate).format("DD/MM/YYYY HH:mm") && JSON.parse(json_events)[key].is_locked ==1) {
                            if(loggedin_teacher_id == teacher_id){
                                selected_validate_ids.push('Start: '+start+' End: '+end_date+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);
                            }
                        }
                        if (start < moment(curdate).format("DD/MM/YYYY HH:mm") && JSON.parse(json_events)[key].is_locked ==0) {
                            if(loggedin_teacher_id == teacher_id){
                                selected_non_validate_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);

                            }
                        }
                        if (start>moment(curdate).format("DD/MM/YYYY HH:mm") && JSON.parse(json_events)[key].is_locked ==0) {
                            if(loggedin_teacher_id == teacher_id){

                                selected_non_validate_delete_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);
                            }
                        }

                        if(loggedin_teacher_id == teacher_id){
                            selected_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);
                        }

                    }

                        stime=moment(JSON.parse(json_events)[key].start).format('HH:mm');
                        etime=moment(JSON.parse(json_events)[key].end).format('HH:mm');
                        if (moment(JSON.parse(json_events)[key].end).isValid() == false){
                            etime=stime;
                        }
                        foundRecords=1; //found valid record;
                        //event.allDay = true;
                        //console.log(event)
                        // if (JSON.parse(json_events)[key].allDay) {
                        //     $(el).find('div.fc-content').prepend(icon);
                        // } else {
                        //     $(el).find('.fc-time').prepend(icon);
                        // }
                        var icon ='<span class="fa fa-lock txt-orange"></span>';
                        if (JSON.parse(json_events)[key].is_locked == '1'){
                            //$(el).find('div.fc-content').prepend(icon);


                        }else if (JSON.parse(json_events)[key].is_locked == '0'){
                            icon='';
                            lockRecords=1;

                        } else if (JSON.parse(json_events)[key].event_mode == '0'){
                            icon ='<i class="fa fa-file"></i> ';
                        } else{
                            icon='';
                        }
                        if (document.getElementById("view_mode").value != 'month'){
                            if (JSON.parse(json_events)[key].duration_minutes > 60){
                                var ooo= JSON.parse(json_events)[key].title_extend;
                                //$(el).find('div.fc-content').append(ooo);
                            }
                        }
                        prevdt = moment(JSON.parse(json_events)[key].start).format('DD-MM-YYYY');

                        resultHtml_cc+='<tr class="agenda_event_row" href="'+JSON.parse(json_events)[key].url+'">';
                        resultHtml_cc+='<td href="'+JSON.parse(json_events)[key].url+'">'+icon+moment(JSON.parse(json_events)[key].start).format('DD-MM-YYYY')+'</td>';
                        resultHtml_cc+='<td>'+stime+'</td>';
                        resultHtml_cc+='<td>'+etime+'</td>';
                        if ( JSON.parse(json_events)[key].no_of_students <= 1 ){
                            resultHtml_cc+='<td>'+JSON.parse(json_events)[key].no_of_students+' :</td>';
                        }else{
                            resultHtml_cc+='<td>'+JSON.parse(json_events)[key].no_of_students+' :</td>';
                        }
                        resultHtml_cc+='<td>'+JSON.parse(json_events)[key].title+'</td>';
                        resultHtml_cc+='<td>'+JSON.parse(json_events)[key].cours_name+'</td>';
                        resultHtml_cc+='<td>'+JSON.parse(json_events)[key].duration_minutes+' minutes</td>';
                        var coach_user=document.getElementById("coach_user").value;
                        if (coach_user =='') {
                            resultHtml_cc+='<td>'+JSON.parse(json_events)[key].teacher_name+'</td>';
                        }
                        resultHtml_cc+='</tr>';

                });
                let resultHtml_rows_cc=resultHtml_cc;
               // console.log(resultHtml_rows_cc);
                let resultHtmlHeader_cc = '';
                resultHtmlHeader_cc+='<table id="agenda_table" name="agenda_table" cellpadding="0" cellspacing="0" width="99%" class="agenda_table_class tablesorter">';
                resultHtmlHeader_cc+='<thead>';
                resultHtmlHeader_cc+='<tr>';
                resultHtmlHeader_cc+='<th width="12%">'+row_hdr_date+'</th>';
                resultHtmlHeader_cc+='<th width="6%">'+row_hdr_start_time+'</th>';
                resultHtmlHeader_cc+='<th width="6%">'+row_hdr_end_time+'</th>';
                resultHtmlHeader_cc+='<th width="10%">'+row_hdr_no_of_students+'</th>';
                resultHtmlHeader_cc+='<th width="19%">'+row_hdr_student_name+'</th>';
                resultHtmlHeader_cc+='<th width="19%">'+row_hdr_course+'</th>';
                resultHtmlHeader_cc+='<th width="10%">'+row_hdr_duration_id+'</th>';
                resultHtmlHeader_cc+='<th width="8%">'+row_hdr_teacher_id+'</th>';
                resultHtmlHeader_cc+='</tr>';
                resultHtmlHeader_cc+='</thead>';

                //resultHtmlHeader_cc+=resultHtml;
                resultHtmlHeader_cc+=resultHtml_rows_cc;


                //resultHtml_rows_cc = '';
                resultHtmlHeader_cc+="</table>";
                resultHtmlHeader_cc+="<script>";
                resultHtmlHeader_cc+="$('#agenda_table').DataTable({";
                resultHtmlHeader_cc+='"stateSave": true,';
                resultHtmlHeader_cc+='"paging":   false,';
                resultHtmlHeader_cc+='"searching": false,';
                resultHtmlHeader_cc+='"order": [[2, "asc"]],';
                resultHtmlHeader_cc+='"bInfo":     false,';
                //resultHtmlHeader_cc+="dom: 'Bfrtip',";       // uncomment to enable datatable export
                //resultHtmlHeader_cc+="buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],"; // uncomment to enable datatable export
                resultHtmlHeader_cc+='"bProcessing": true});';
                resultHtmlHeader_cc+='<\/script>';

                $('#agenda_list').html(resultHtmlHeader_cc);

                selected_ids.join("|");

                //selected_non_validate_ids.join("|");
                selected_non_validate_ids = selected_non_validate_ids.map(e => JSON.stringify(e)).join("|");
                selected_non_validate_delete_ids = selected_non_validate_delete_ids.map(e => JSON.stringify(e)).join("|");

                document.getElementById("get_event_id").value = selected_ids;
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_validate_events").style.display = "none";
                    document.getElementById("btn_validate_events_mobile").style.display = "none";
                } else {
                    selected_validate_ids.join("|");
                }
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_delete_events").style.display = "none";
                    document.getElementById("btn_delete_events_mobile").style.display = "none";
                } else {
                    selected_validate_ids.join("|");
                }
                document.getElementById("get_validate_event_id").value = selected_validate_ids;
                document.getElementById("get_non_validate_event_id").value = selected_non_validate_ids;
                document.getElementById("get_non_validate_event_delete_id").value = selected_non_validate_delete_ids;

               // get_non_validate_event_delete_id
                //$('#calendar').fullCalendar('removeEvents', function () { return true; });

                // var eventsToPut = [];

                // $.each(JSON.parse(json_events), function(k, v)  {
                //     // OBJECT is created when processing response
                //     eventsToPut.push(v);
                // });
               // console.log(JSON.parse(json_events));
                //


                if (firstLoad =='firstLoad') {
                    $('#calendar').fullCalendar('addEventSource',JSON.parse(json_events), true);
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events));
                    $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                    $('#calendar').fullCalendar('refetchEventSources', JSON.parse(json_events));
                    $('#calendar').fullCalendar('rerenderEvents' );
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#calendar').fullCalendar({ events: JSON.parse(json_events) });
                    $('#calendar').fullCalendar( 'renderEvent', JSON.parse(json_events) , 'stick');


                }

                //remove
               $('#calendar').fullCalendar().find('.fc-day-header').hide();
                $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                //document.getElementById("agenda_list_current").style.display = "block";
                document.getElementById("agenda_list").style.display = "none";
                $("#pageloader").fadeOut();
            },
            error: function(ts) {
                $("#pageloader").fadeOut();
                //errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
                // alert(ts.responseText)
               // console.log(ts.responseText);
            }
        });
    }



    function DisplayCalendarTitle() {
        var view = $('#calendar').fullCalendar('getView');
        if (view.name=='timeGridThreeDay') {
            var dt = new Date();
            var CurrentListViewDate = new Date(new Date().getTime()+(1*24*60*60*1000)) //2 days
            const options1 = {month: 'short',day: 'numeric' };
            const options = { day: 'numeric', year: 'numeric'};
            view.title = moment(dt).format("MMM DD")+' - '+moment(CurrentListViewDate).format("DD, YYYY");
            //$('#calendar').fullCalendar( 'renderEvent', JSON.parse(json_events) , 'stick');
            //$('#calendar').fullCalendar('rerenderEvents');


            document.getElementById("date_from").value = formatDate(dt);
            document.getElementById("date_to").value = formatDate(CurrentListViewDate);

        }
        $('#cal_title').text("{{__('Agenda')}} : "+view.title);
    };

    function getSchoolIDs(count=null){
		var selected_ids = [];
        $.each($("#event_school option:selected"), function(){
            selected_ids.push($(this).val());
        });

        if (count == 'count') return selected_ids.length
        if (count == 'is_multi') return !!(selected_ids.length > 1)

        if (selected_ids.length > 0) {
            $('#event_location_div').show();
            $('#event_teacher_div').hide();
            //$('#event_student_div').show();
        } else {
            if ($("#event_location option").length > 0 ) {
                $('#event_location_div').show();
            }else{
               $('#event_location_div').hide();
            }

            $('#event_teacher_div').show();
            $('#event_student_div').show();
        }
		return selected_ids.join("|");
	}
    function getEventIDs(){
		var selected_ids = [];
        $.each($("#event_type option:selected"), function(){
            selected_ids.push($(this).val());
        });
		//console.log('selected='+selected_ids.join("|"));
		return selected_ids.join("|");
	}



    function getStudentIDs(){
        var selected_ids = [];
        $.each($("#event_student option:selected"), function(){
            selected_ids.push($(this).val());
        });
        //console.log('selected='+selected_ids.join("|"));
        return selected_ids.join("|");
    }

    function getTeacherIDs(){
        var selected_ids = [];
        $.each($("#event_teacher option:selected"), function(){
            selected_ids.push($(this).val());
        });
        //console.log('selected='+selected_ids.join("|"));
        return selected_ids.join("|");
    }

    function getLocationIDs	(){
        var selected_ids = [];
        var all_locations = [];
        $.each($("#event_location option:selected"), function(){
            selected_ids.push($(this).val());
        });
        $.each($("#event_location option"), function(){

            all_locations.push($(this).val());
        });
        if (all_locations.length == selected_ids.length) {
            selected_ids.push(0);
        }
        return selected_ids.join("|");
    }


    function setSelectedItems(obj){
        var x=document.getElementById(obj);
            for (var j = 0; j < x.options.length; j++)
        {
            if(x.options[j].selected)
            {
                if ( (x.options[j]).value = '0') {
                        break;
                    }

            }
        }
    }






    function CheckPermisson(){
        var user_role=document.getElementById("user_role").value;
        //event_type
        //event_student
        //event_teacher

        // if (getCookie('v_t_cnt') == '1') {
        //     document.getElementById("event_teacher").style.display="none";
        // }
        if (user_role =='student' || user_role =='parent'){
            document.getElementById("btn_copy_events").style.display="none";
            document.getElementById("btn_copy_events_mobile").style.display="none";
            document.getElementById("btn_goto_planning").style.display="none";
            document.getElementById("event_teacher").style.display="none";
			document.getElementById("event_student_div").style.display="none";

        }
    }


    //creating events
    $(document).on('click', '#btn_goto_planning,#btn_goto_planning_mobile', function(e) {

        var school_id=document.getElementById('school_id').value;
        var source_start_date=document.getElementById("copy_date_from").value,
        source_end_date=document.getElementById("copy_date_to").value,
        event_school=document.getElementById("copy_school_id").value,
        event_type= document.getElementById("copy_event_id").value,
        student_id=document.getElementById("copy_student_id").value,
        teacher_id=document.getElementById("copy_teacher_id").value,
        target_start_date=document.getElementById("date_from").value,
        target_end_date=document.getElementById("date_to").value,
        view_mode = document.getElementById("view_mode").value;
        var p_event_location_id=getLocationIDs();

       // console.log('view_mode', view_mode);

        var data='location_id='+p_event_location_id+'&view_mode='+view_mode+'&source_start_date='+source_start_date+'&source_end_date='+source_end_date+'&target_start_date='+target_start_date+'&target_end_date='+target_end_date+'&school_id='+event_school+'&event_type='+event_type+'&student_id='+student_id+'&teacher_id='+teacher_id+'&zone='+zone;
        //console.log(data);
        //return false;
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: BASE_URL + '/copy_paste_events',
            data: data,
            dataType: "JSON",
            async: false,
            success:function(result){
                var status =  result.status;
                if(status == 0)
                {
                    document.getElementById("copy_date_from").value = '';
                    document.getElementById("copy_date_to").value = '';
                    document.getElementById("copy_school_id").value = '';
                    document.getElementById("copy_event_id").value = '';
                    document.getElementById("copy_student_id").value ='';
                    document.getElementById("copy_teacher_id").value = '';
                    document.getElementById("copy_view_mode").value = '';
                    document.getElementById("copy_week_day").value = '';
                    document.getElementById("copy_month_day").value = '';
                    //window.location.reload(false);

                    getFreshEvents();      //refresh calendar
                }
                else
                {
                    alert('failed.. ');
                }
            },   //success
            error: function(ts) {
                // alert(ts.responseText)
                errorModalCall('Someting went wrong on copy events' + GetAppMessage('error_message_text'));
                }
        }); //ajax-type
        return false;
    })


    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length,c.length);
            }
        }
        return "";
    }


    function SetEventCookies(){
		//if (loading == 0) {


			//document.getElementById("event_type_id").value=getEventIDs();
			//document.getElementById("event_student_id").value=getStudentIDs();
			//document.getElementById("event_teacher_id").value=getTeacherIDs();
			//document.getElementById("event_location_id").value=getLocationIDs();
			//console.log("event_location_id="+document.getElementById("event_location_id").value);
			//if (document.getElementById("event_location_id").value == ''){
			//	document.getElementById("event_location_all_flag").value='0';
			//}
			//console.log("LOCATION: event_location_id="+document.getElementById("event_location_id").value);

			document.cookie = "event_type_id="+document.getElementById("event_type_id").value+";path=/";
			document.cookie = "event_student_id="+document.getElementById("event_student_id").value+";path=/";
			document.cookie = "event_teacher_id="+document.getElementById("event_teacher_id").value+";path=/";
			document.cookie = "date_from="+document.getElementById("date_from").value+";path=/";
			document.cookie = "date_to="+document.getElementById("date_to").value+";path=/";

			document.cookie = "view_mode="+document.getElementById("view_mode").value+";path=/";

            //document.cookie = "timezone_user="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
			document.cookie = "timezone_user="+document.getElementById("zone").value+";path=/";

			var cal_view_mode=$('#calendar').fullCalendar('getView');
			//console.log("cal_view_mode="+cal_view_mode.name);

          /*  if(cal_view_mode.name === "agendaWeek") {
                const scrollTime = moment().format("HH:mm");
                console.log(scrollTime)
                $(".fc-scroller").animate({
                    scrollTop: $('[data-time="18:00:00"]').position().top // Scroll to 01:00 pm
                }, 1000);
            }*/

			if (cal_view_mode.name === undefined) {
                document.cookie = "cal_view_mode="+"month"+";path=/";
            }
			else {
				document.cookie = "cal_view_mode="+cal_view_mode.name+";path=/";
            }

			document.cookie = "prevnext="+document.getElementById("prevnext").value+";path=/";

		//}
    }


   /* window.addEventListener( "pageshow", function ( event ) {
        var historyTraversal = event.persisted ||
                            ( typeof window.performance != "undefined" &&
                                window.performance.navigation.type === 2 );

        if ( historyTraversal ) {
            // Handle page restore.
            //RerenderEvents();
            var isFirefox = typeof InstallTrigger !== 'undefined';
            //if (isFirefox) {
                console.log('before firefox.. reload.');
                //alert('firefox.. reload.');
                //getFreshEvents();

                window.location.reload(false);
                //}
        }
    });*/

    // window.addEventListener('focus', function (event) {
    //     console.log('has focus');
    //     //RerenderEvents();
    // });

    // window.addEventListener('blur', function (event) {
    //     console.log('lost focus');
    // });





$(function() {
	$("#start_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
        weekStart: 1,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
    $('#end_date').datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
        weekStart: 1,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
        minDate: $("#start_date").val()
	});
});



  $('#student').multiselect({
    maxHeight: 400,  // Augmentez cette valeur selon vos besoins
    buttonWidth: '100%',
    dropRight: false,
    enableFiltering: true,
    includeSelectAllOption: true,
    includeFilterClearBtn: true,
    search: true,
    noneSelected: "{{__("None selected") }}",
    selectAllText: "{{__("All Students") }}",
    enableCaseInsensitiveFiltering: true,
    enableFullValueFiltering: false,
  });


  $('#start_date').on('change', function(e) {
   // resetStudentList();
    getAwayStudent();
});


$('#student').on('change', function(event) {
	var cnt = $('#student option:selected').length;
	var price=document.getElementById("sis_paying").value;

    if (cnt >= 10) {
        document.getElementById("sevent_price").value='price_10';
    }
    else
    {
        document.getElementById("sevent_price").value='price_'+cnt;
    }


})
$( document ).ready(function() {
    // var datainvoiced = $("#category_select option:selected").data('invoice');
    // var s_thr_pay_type = $("#category_select option:selected").data('s_thr_pay_type');
    // var s_std_pay_type = $("#category_select option:selected").data('s_std_pay_type');
    // var t_std_pay_type = $("#category_select option:selected").data('t_std_pay_type');
    // if (datainvoiced == 'S') {
    //     $("#student_sis_paying").val(s_std_pay_type);
    //     $("#sis_paying").val(s_thr_pay_type);
    //     $("#teacher_type_billing").show();
    // }else{
    //     $("#teacher_type_billing").hide();
    //     $("#student_sis_paying").val(t_std_pay_type);
    // }

    // if(s_thr_pay_type == 0){
    //     $('#hourly').show();
    //     $('#price_per_student').hide();
    // }else if(s_thr_pay_type == 1){
    //     $('#hourly').hide();
    //     $('#price_per_student').show();
    // }

	$('#sprice_amount_buy').val(0);
	$('#sprice_amount_sell').val(0);
    $('#extra_charges').val(0);



	$('.timepicker_start').timepicker({
		timeFormat: 'HH:mm',
		interval: 15,
		minTime: '0',
		maxTime: '23:59',
		defaultTime: '11',
		startTime: '00:00',
		dynamic: false,
		dropdown: true,
		scrollbar: true,
		change:function(time){
            $('#end_time').val(recalculate_end_time(moment(time).format('HH:mm'),15));
			CalcDuration();
		}
	});

    $('.timepicker').timepicker({
        timeFormat: 'HH:mm',
        interval: 15,
        minTime: '0',
        maxTime: '23:59',
        defaultTime: '11',
        startTime: '00:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        change:function(time){
            CalcDuration();
        }
    });

	function CalcDuration(){
		var el_start = $('#start_time'),
		el_end = $('#end_time'),
		el_duration = $('#duration');

            if (el_end.val() == el_start.val()) {
                $('#start_time').addClass('error');
                $('#end_time').addClass('error');
             } else {
                $('#start_time').removeClass('error');
                $('#end_time').removeClass('error');
            }

			if (el_end.val() < el_start.val()) {
				$('#end_time').val(recalculate_end_time(el_start.val(),15));
				el_duration.val(recalculate_duration(el_start.val(), el_end.val()));
			}
			else{
				el_duration.val(recalculate_duration(el_start.val(), el_end.val()));
			}
		}
	function recalculate_end_time(start_value, duration) {
		if (validateStringHours(start_value) && parseInt(duration, 10) == duration) {
			var start_minutes = +(parseInt(string_left(start_value, 2), 10) * 60) + parseInt(string_right(start_value, 2), 10) + parseInt(duration, 10),
				start_hours_number = parseInt((start_minutes / 60).toString(), 10),
				start_hours = start_hours_number;
				if (start_hours > 23) {start_hours = start_hours - 24;}
				return string_right('00' + start_hours.toString(), 2) + ':' + string_right('00' + (start_minutes - (start_hours_number * 60)).toString(), 2);
		}
		return 0;
	}
	function recalculate_duration(start_value, end_value) {
		if (validateStringHours(start_value) && validateStringHours(end_value)) {
			return -(parseInt(string_left(start_value, 2), 10) * 60)
					- parseInt(string_right(start_value, 2), 10)
					+ (parseInt(string_left(end_value, 2), 10) * 60)
					+ (parseInt(string_right(end_value, 2), 10));
		}
		return 0;
	}
	function validateStringHours(s_hours) {
            if (s_hours == '24:00') {return true;}
            var re = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
            return re.test(s_hours);
	}
	function string_left(str, n){
		if (n <= 0)
			return "";
		else if (n > String(str).length)
			return str;
		else
			return String(str).substring(0,n);
	}
	function string_right(str, n){
		if (n <= 0)
			return "";
		else if (n > String(str).length)
			return str;
		else {
			var iLen = String(str).length;
			return String(str).substring(iLen, iLen - n);
		}
	}
	function filterParseDigits(str) {
		return str.replace(/[^\d]/g, '');
	}
	function validateStringHours(s_hours) {
		if (s_hours == '24:00') {return true;}
		var re = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
		return re.test(s_hours);
	}
	$('#start_time, #end_time, #duration').on('change.datetimepicker', function(e){
    var event_source = $(this).attr('id');
	var el_duration = $('#duration');
	if (event_source === 'start_time'){
		if(!el_duration.val()){el_duration.val('15');}
		$('#end_time').val(recalculate_end_time($('#start_time').val(), '15'));
	}

	var el_start = $('#start_time'),
		el_end = $('#end_time');
		if (event_source === 'end_time' || event_source === 'start_time') {
			if (el_end.val() < el_start.val()) {
				$('#end_time').val(el_start.val());
			};
			el_duration.val(recalculate_duration(el_start.val(), el_end.val()));
		} else {
			if (!(parseInt(el_duration.val(), 10) == el_duration.val())) {
				el_duration.val(20);
				$('#end_time').val(el_start.val());
			} else {
				if (parseInt(el_duration.val(), 10) >= (60*24)) {
					el_duration.val(((60*24) - 1));
				}
				$('#end_time').val(recalculate_end_time(el_start.val(), el_duration.val()));
			}
		}
	});
})
// $('#sis_paying').on('change', function() {
// 	$('#hourly').hide();
// 	$('#price_per_student').hide();
// 	$('#sprice_amount_buy').val(0);
// 	$('#sprice_amount_sell').val(0);
// 	if(this.value == 1){
// 		$('#hourly').show();
// 	}else if(this.value == 2){
// 		$('#price_per_student').show();
// 	}
// });
$("body").on('click', '#all_day', function(event) {
    if ($(this).prop('checked')) {
        $(".not-allday").hide();
    }else{
        $(".not-allday").show();
    }
});

$('#add_lesson').on('submit', function(e) {
    e.preventDefault();

    $('#pageloader').fadeIn();

    setTimeout(() => {

	var title = $('#Title').val();
	var professor = $('#teacher_select').val();
    var invoice_cat_type_id = $('#event_invoice_type option:selected').val();
    var agendaSelect = +$("#agenda_select").val();
    var evetCat = $('#category_select option:selected').val();
    var evetLoc = $('#location option:selected').val();
    var emptyStdchecked = $("#student_empty").prop('checked')
	var selected = $("#student :selected").map((_, e) => e.value).get();
	var startDate = $('#start_date').val();
	var endDate = $('#end_date').val();
    var startTime = $('#start_time').val();
	var endTime = $('#end_time').val();
	var errMssg = '';
	var type = $("#agenda_select").val();
    var isAdmin = "{{ $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() }}";


    var selected_school_ids = [];
    $.each($("#event_school option:selected"), function(){
        selected_school_ids.push($(this).val());
    });

    if(type == 1){
        var page_action = BASE_URL+'/'+selected_school_ids+'/'+'add-lesson';
    }else if(type == 2){
        var page_action = BASE_URL+'/'+selected_school_ids+'/'+'add-event';
    }else if(type == 3){
        var page_action = BASE_URL+'/'+selected_school_ids+'/'+'student-off';
    }else if(type == 4){
        var page_action = BASE_URL+'/'+selected_school_ids+'/'+'coach-off';
    }

    var formData = $('#add_lesson').serializeArray();
    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
    formData.push({
        "name": "_token",
        "value": csrfToken,
    });

    
    var errMssg = '';

    if(type == 1 || type == 2){
        if(type==1){
            var bill_type = $('#sis_paying').val();

            // if(bill_type == 1){
            //     $.ajax({
            //         url: BASE_URL + '/check-lesson-price',
            //         async: false,
            //         data: formData,
            //         type: 'POST',
            //         dataType: 'json',
            //         success: function(response){
            //             if(response.status == 1){
            //                 var errMssg = '';
            //             }else{
            //                 var errMssg = 'error';
            //                 $('#modal_lesson_price').modal('show');
            //                 $("#modal_alert_body").text("Price setup is not available for this event category and coach. please check and update.");
            //                 e.preventDefault();
            //             }
            //         }
            //     })
            // }
        }

        if(professor == ''){
            var errMssg = 'professor required';
            $('#teacher_select').addClass('error');
        }else{
            $('#teacher_select').removeClass('error');
        }

        if(invoice_cat_type_id == ''){
            var errMssg = 'Category type is required';
            $('#event_invoice_type').addClass('error');
        }else{
            $('#event_invoice_type').removeClass('error');
        }

        //
        if(isAdmin) {
            if( evetCat == undefined || evetCat == '' || evetCat == 0){
                if(invoice_cat_type_id == 'S'){
                    var errMssg = '{{ __("Select a category") }}';
                    $('#category_select').addClass('error');
                }
            }else{
                    $('#category_select').removeClass('error');
            }
        } else {
            if( evetCat == undefined || evetCat == ''){
            if(agendaSelect == "1")
                var errMssg = '{{ __("Select a category") }}';
                $('#category_select').addClass('error');
            }else{
                $('#category_select').removeClass('error');
            }
        }
        //
        /*if(evetLoc == ''){
            var errMssg = 'Location required';
            $('#location').addClass('error');
        }else{
            $('#location').removeClass('error');
        }*/

        if(isAdmin) {

            if ($("#student_empty").prop('checked') == false){
                if (!emptyStdchecked) {
                    if( selected < 1){
                        if(invoice_cat_type_id == 'T'){
                            var errMssg = 'Please select at least one student to continue';
                            $('.student_list').addClass('error');
                        }
                    }else{
                        //var errMssg = '';
                        $('.student_list').removeClass('error');
                    }
                }
            }else{
                // var errMssg = '';
                $('.student_list').removeClass('error');
            }

        } else {

            if ($("#student_empty").prop('checked') == false){
                if (!emptyStdchecked) {
                    if( selected < 1){
                        var errMssg = 'Please select at least one student to continue';
                        $('.student_list').addClass('error');
                    }else{
                        //var errMssg = '';
                        $('.student_list').removeClass('error');
                    }
                }
            }else{
                // var errMssg = '';
                $('.student_list').removeClass('error');
            }

        }


        if((agendaSelect == 1) && (startTime == endTime)){
            var errMssg = 'Please select a start and end time to continue';
            $('#start_time').addClass('error');
            $('#end_time').addClass('error');
        }

        if(startDate == ''){
            var errMssg = 'Start date required';
            $('#start_date').addClass('error');
        }else{
            $('#start_date').removeClass('error');
        }
        if(endDate == ''){
            var errMssg = 'End date required';
            $('#end_date').addClass('error');
        }else{
            $('#end_date').removeClass('error');
        }
    }else if(type == 3){
        if ($("#student_empty").prop('checked') == false){
            if( selected < 1){
                var errMssg = 'Please select at least one student to continue';
                $('.student_list').addClass('error');
            }else{
                var errMssg = '';
                $('.student_list').removeClass('error');
            }
        }else{
            var errMssg = '';
            $('.student_list').removeClass('error');
        }
    }else if(type == 4){
        if(professor == ''){
            var errMssg = 'professor required';
            $('#teacher_select').addClass('error');
        }else{
            $('#teacher_select').removeClass('error');
        }
    }



    if(errMssg == ""){


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        // console.log("hello");
        $.ajax({
            url: page_action,
            async: false,
            data: formData,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                if(response.status == 1){

                    //$('#add_lesson_success').modal('show');
                    $('#pageloader').fadeOut(100);

                    Swal.fire({
                    title: "{{__('Successfully added') }}",
                    text: "{{__('You can add now another lesson or event') }}",
                    icon: "success"
                    });

                   // $('#student').multiselect('deselectAll', false);
                    $('#student').multiselect('updateButtonText');

                    // $("#add_lesson")[0].reset();
                    // $('#student').val([]).multiselect('refresh');
                    // const startresult = moment().format('DD/MM/YYYY');
                    // const startTime = moment().format('HH:mm');
                    // $('#start_date').val(startresult);
                    // $('#start_time').val(startTime);
                    // const endTime = moment().add(15, 'm').format('HH:mm');
                    // const endresult = moment().subtract(1, 'seconds').format('DD/MM/YYYY');
                    // $('#end_date').val(endresult);
                    // $('#end_time').val(endTime).trigger('change');


                }else{

                    $('#pageloader').fadeOut(100);
                    $('#addAgendaModal').hide();

                    let timerInterval;
                    Swal.fire({
                    icon: "success",
                    title: agendaSelect === 1 ? "New Lesson Added" : "New Event Added",
                    html: "Please wait few seconds...",
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                        //timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        location.reload();
                    }
                    });


                }
            }
        })
    }else{
        $('#pageloader').fadeOut(100);
        Swal.fire({
        icon: "error",
        title: "Oops...",
        text: errMssg,
        });
        return false;
    }

    }, 1000);
});


$(document).ready(function() {
    $('#agenda_select').trigger('change');

    var agenda_select = $("#agenda_select").val();
    var selected_school_ids = [];
    $.each($("#event_school option:selected"), function(){
        selected_school_ids.push($(this).val());
    });

    if (selected_school_ids.length == 1) {
        var page_action = BASE_URL+'/'+selected_school_ids+'/'+'add-lesson';
    }else{
        var page_action = 'javascript:void(0)';
    }

    //const endTime = endDate.format('HH:mm');
    //const endresult = endDate.subtract(1, 'seconds').format('DD/MM/YYYY');

/*$('#end_date').val("15/01/2023");
    $('#end_time').val("15:20").trigger('change');
    $('#agenda_select').trigger('change');*/

    if(agenda_select != ''){
		$('#agenda_form_area').show();
        if(agenda_select == 1){
            $('#start_date').on('change', function(e){
                $("#end_date").val($("#start_date").val());
            });
            $( "#end_date" ).attr("readonly", "readonly");
            $('.lesson').show();
            $('.event').hide();
            //$('#sis_paying').val(1);
            //$('#price_per_student').hide();
            $('.hide_on_off').show();
            $('.event.hide_on_off').hide();
            $("form.form-horizontal").attr("action", page_action);
            $('.hide_coach_off').show();
            $('.show_coach_off.hide_on_off').show();
        }
	}else{
        $('#agenda_form_area').hide();
    }
});

$("body").on('change', '#teacher_select', function(event) {
    var isAdmin = "{{ $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() }}";
    if(isAdmin)
    {
    var teacherSelect = +$("#teacher_select").val();
        if(teacherSelect == ''){
            $("#event_invoice_type").prop('disabled', true);
        } else {
            $("#event_invoice_type").prop('disabled', false);
        }
    } else {
            $("#event_invoice_type").prop('disabled', false);
    }

});

$("body").on('change', '#event_invoice_type', function(event) {
    var isAdmin = "{{ $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() }}";
    if(isAdmin)
    {
    var event_invoice_type = +$("#teacher_select").val();
        if(event_invoice_type == ''){
            $("#category_select").prop('disabled', true);
        } else {
            $("#category_select").prop('disabled', false);
        }
    } else {
            $("#category_select").prop('disabled', false);
    }
});

$("#category_select, #teacher_select").change();
$("body").on('change', '#category_select, #teacher_select', function(event) {


    var agendaSelect = +$("#agenda_select").val();
    var categoryId = +$("#category_select").val();
    var teacherSelect = +$("#teacher_select").val();
    var datainvoiced = $("#category_select option:selected").data('invoice');

    var s_thr_pay_type = $("#category_select option:selected").data('s_thr_pay_type');
    var s_std_pay_type = $("#category_select option:selected").data('s_std_pay_type');
    var t_std_pay_type = $("#category_select option:selected").data('t_std_pay_type');

    // Search Teacher ID
    var selectedTeacher = teachersList.find(function(teacher) {
    return teacher.teacher_id === teacherSelect;
    });

    //console.log('teachers', teachersList)

    if (selectedTeacher) {
    //console.log('Teacher ID:', selectedTeacher.id);
    if(selectedTeacher.lesson_price_teachers.length > 0) {
        isTeacherHasPrices = true
    } else {
        if(teacherSelect !== 0) {
            isTeacherHasPrices = false
            errorModalCall('This teacher has not yet saved any rates');
        }
    }
    } else {
    //console.log('No teacher found with ID ', teacherSelect, '.');
    }

    var isSchoolAdmin = +"{{$AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()}}";
    var isTeacherAdmin = +"{{$AppUI->isTeacherAdmin()}}";
    var isTeacher = +"{{$AppUI->isTeacher()}}";

    if (agendaSelect != 1 ) { return }
    if (datainvoiced == 'S') {
        if (s_std_pay_type == 2) {
            $("#std-check-div").css('display', 'block');
        }else{
            $("#std-check-div").css('display', 'none');
         }

        $("#teacher_type_billing").show();
        $("#student_sis_paying").val(s_std_pay_type);
        $("#sis_paying").val(s_thr_pay_type);
    }else{
        $("#student_sis_paying").val(t_std_pay_type);
        $("#std-check-div").css('display', 'none');
        $("#teacher_type_billing").hide();
        $("#student_empty").prop('checked', false)
    }
    if(s_thr_pay_type == 0){
		// $('#hourly').show();
        //$('#price_per_student').hide();
	}else if(s_thr_pay_type == 1 || s_std_pay_type == 1 ){
        $('#hourly').hide();
		$('#price_per_student').fadeIn();
	}

    if($('#sis_paying').val() == 0){
        $('#sprice_amount_buy').prop('disabled', true);
    }else if($('#sis_paying').val() == 1){
        $('#sprice_amount_buy').prop('disabled', false);
    }

    if($('#student_sis_paying').val() == 0){
        $('#sprice_amount_sell').prop('disabled', true);
    }else if($('#student_sis_paying').val() == 1){
        $('#sprice_amount_sell').prop('disabled', false);
    }else if($('#student_sis_paying').val() == 2){
        $('#sprice_amount_sell').prop('disabled', true);
    }

    if( ((isSchoolAdmin || isTeacherAdmin) && datainvoiced == 'S') || (isTeacher &&  datainvoiced == 'T') ){
       $("#price_per_student").fadeIn();
    }else{
        $("#price_per_student").fadeOut();
    }
   // getLatestPrice();


});
$("#student").on('change', function(event) {
   // getLatestPrice()
});

function getLatestPrice() {
    var agendaSelect = +$("#agenda_select").val();
    var categoryId = +$("#category_select").val();
    var teacherSelect = +$("#teacher_select").val();
    var stdSelected = $("#student :selected").map((_, e) => e.value).get().length;
   // if (agendaSelect != 1) {
        //$("#sprice_amount_buy").val(0)
        //$("#sprice_amount_sell").val(0)
        //return
   // }
    var formData = $('#edit_lesson').serializeArray();
    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
    formData.push({
        "name": "_token",
        "value": csrfToken,
    });

    formData.push({
        "name": "event_category_id",
        "value": categoryId,
    });
    formData.push({
        "name": "teacher_select",
        "value": teacherSelect,
    });
    formData.push({
        "name": "no_of_students",
        "value": stdSelected,
    });

    if (categoryId > 0 && teacherSelect > 0) {
        $.ajax({
            url: BASE_URL + '/check-lesson-fixed-price',
            //async: false,
            data: formData,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                if(response.status == 1){
                    if (response.data) {
                        $("#sprice_amount_buy").val(response.data.price_buy)
                        $("#sprice_amount_sell").val(response.data.price_sell)
                    }
                }
            }
        })
    }

}

$('#student').multiselect({
	search: true
});


$("body").on('click', '#student_empty', function(event) {
    if ($("#student_empty").prop('checked')) {
        $('#student').val([]).multiselect('refresh');
    }else{
        $('#student').val([]).multiselect('refresh');
    }
});




    $('#agenda_select').on('change', function(e) {
        var agendaSelectdates = $("#agenda_select").val();
        if(agendaSelectdates == 1) {
            $("#end_date").val($("#start_date").val());
            $("#end_date").prop("hidden", true);
            $("#infoLesson").fadeIn();
            $("#infoLessonEvent").fadeOut();
            $("#infoDateEnd").fadeIn();
            $("#infoDateEnd").val($("#start_date").val());
        } else {
            $("#end_date").prop("hidden", false);
            $("#infoLesson").fadeOut();
                if(agendaSelectdates == 2) {
                    $("#infoLessonEvent").fadeIn();
                } else {
                    $("#infoLessonEvent").fadeIn();
                }
            $("#infoDateEnd").fadeOut();
        }
    });


    $('#start_date').on('change', function(e) {
    var agendaSelectdates = $("#agenda_select").val();
            if(agendaSelectdates == 1) {
                $("#infoDateEnd").val($("#start_date").val());
            }
        })

        $('#end_date').on('change', function(e) {
           // console.log('alors ?', $("#start_date").val());
            var agendaSelectdates2 = $("#agenda_select").val();
        if ($("#end_date").val() < $("#start_date").val() && agendaSelectdates2 == 1) {
            $("#end_date").val($("#start_date").val());
            errorModalCall('Information', '{{ __("Please ensure that the end date comes after the start date")}}');
            setTimeout(() => {
                $("#end_date").val($("#start_date").val());
            }, "200")
        }
        });



$('#agenda_select').on('change', function() {
    var agendaSelectpriceEventOptions = $("#agenda_select").val();
    if(agendaSelectpriceEventOptions == 2) {
        $('#priceEventOptions').fadeIn();
    } else {
        $('#priceEventOptions').fadeOut();
    }


    $('#all_day_input').prop( "checked", false)
    $('#student_empty').prop( "checked", false)
    $("#hourly").hide()

    if(this.value != ''){
        $('.not_teacher').show();
		$('#agenda_form_area').show();
        var selected_school_ids = [];
        $.each($("#event_school option:selected"), function(){
            selected_school_ids.push($(this).val());
        });
        if(this.value == 1){
            if (selected_school_ids.length == 1) {
                var page_action = BASE_URL+'/'+selected_school_ids+'/'+'add-lesson';
            }else{
                var page_action = 'javascript:void(0)';
            }

            $('#start_date').on('change', function(e){
                $("#end_date").val($("#start_date").val());
            });
            $('#all_day').show();
            $( "#end_date" ).attr("readonly", "readonly");;
            $('.lesson').show();
            $('.event').hide();
            //$('#').val(1);
            //$('#price_per_student').hide();
            $('.hide_on_off').show();
            $('.event.hide_on_off').hide();
            $("form.form-horizontal").attr("action", page_action);
            $('.hide_coach_off').show();
            $('.show_coach_off.hide_on_off').show();
             // $("#std-check-div").css('display', 'block');
            $('#category_select').trigger('change');
            $(".lesson-text").show()
            $(".event-text").hide()
        }else if(this.value == 2){
            var isTeacher = +"{{$AppUI->isTeacher()}}";
            if(isTeacher){
                $('.not_teacher').hide();
            }else{
                $('.not_teacher').show();
            }
            if (selected_school_ids.length == 1) {
                var page_action = BASE_URL+'/'+selected_school_ids+'/'+'add-event';
            }else{
                var page_action = 'javascript:void(0)';
            }
            $('#all_day').show();
            $( "#end_date" ).attr("disabled", false );
            $('.lesson').hide();
            $('.event').show();
            $('#price_per_student').show();
            $('.hide_on_off').show();
            $('.lesson.hide_on_off').hide();
            $("form.form-horizontal").attr("action", page_action);
            $('.hide_coach_off').show();
          //  $('.show_coach_off.hide_on_off').show();
             $("#std-check-div").css('display', 'none');
            $(".lesson-text").hide();
            $('.show_coach_off.hide_on_off').hide();
            $(".event-text").show()
            // $('#event_invoice_type').trigger('change');
        }else if(this.value == 3){
            if (selected_school_ids.length == 1) {
                var page_action = BASE_URL+'/'+selected_school_ids+'/'+'student-off';
            }else{
                var page_action = 'javascript:void(0)';
            }
            $('#all_day').hide();
            $('.hide_on_off').hide();
            $('#price_per_student').hide();
            $( "#end_date" ).attr("disabled", false );
            $("form.form-horizontal").attr("action", page_action);
            $('.hide_coach_off').show();
            $('.show_coach_off.hide_on_off').hide();
             $("#std-check-div").css('display', 'none');
            // $('#category_select').trigger('change');
        }else if(this.value == 4){
            if (selected_school_ids.length == 1) {
                var page_action = BASE_URL+'/'+selected_school_ids+'/'+'coach-off';
            }else{
                var page_action = 'javascript:void(0)';
            }
            $('#all_day').hide();
            $('.hide_on_off').hide();
            $('.hide_coach_off').hide();
            $('#price_per_student').hide();
            $( "#end_date" ).attr("disabled", false );
            $("form.form-horizontal").attr("action", page_action);
            $('.show_coach_off.hide_on_off').show();
             $("#std-check-div").css('display', 'none');
            // $('#category_select').trigger('change');
        }

	}else{
        $('#agenda_form_area').hide();
    }

    $('#EventModal').on('shown.bs.modal', function(event) {
        $('body').find(".popover.show").removeClass("show")
    });

    if(this.value == 1){
        if($('#sis_paying').val() == 0){
            $('#sprice_amount_buy').prop('disabled', true);
        }else if($('#sis_paying').val() == 1){
            $('#sprice_amount_buy').prop('disabled', false);
        }

        if($('#student_sis_paying').val() == 0){
            $('#sprice_amount_sell').prop('disabled', true);
        }else if($('#student_sis_paying').val() == 1){
            $('#sprice_amount_sell').prop('disabled', false);
        }
    }if(this.value == 2){
        $('#sprice_amount_buy').prop('disabled', false);
        $('#sprice_amount_sell').prop('disabled', false);
    }else{
        if($('#sis_paying').val() == 0){
            $('#sprice_amount_buy').prop('disabled', false);
        }else if($('#sis_paying').val() == 1){
            $('#sprice_amount_buy').prop('disabled', false);
        }

        if($('#student_sis_paying').val() == 0){
            $('#sprice_amount_sell').prop('disabled', false);
        }else if($('#student_sis_paying').val() == 1){
            $('#sprice_amount_sell').prop('disabled', false);
        }
    }

    var isSchoolAdmin = +"{{$AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()}}";
    var isTeacherAdmin = +"{{$AppUI->isTeacherAdmin()}}";
    var isTeacher = +"{{$AppUI->isTeacher()}}";
    var datainvoiced = $("#category_select option:selected").data('invoice');
    var event_invoice_type = "";

    @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
    event_invoice_type = $("#event_invoice_type option:selected").val();
    @endif
    @if($AppUI->isTeacherAdmin())
    event_invoice_type = $("#event_invoice_type").val();
    @endif

    if(this.value == 1){
        if( ((isSchoolAdmin || isTeacherAdmin) && datainvoiced == 'S') || (isTeacher &&  datainvoiced == 'T') ){
            $("#price_per_student").show();
        }else{
            $("#price_per_student").hide();
        }
    }else if(this.value == 2){
        $('#event_invoice_type').trigger('change');
        // if( ((isSchoolAdmin || isTeacherAdmin) && event_invoice_type == 'S') || (isTeacher &&  event_invoice_type == 'T') ){
        //     $("#price_per_student").show();
        // }else{
        //     $("#price_per_student").hide();
        // }
    }

   // getLatestPrice();
});

$('#teacher_select').on('change', function() {
    var resultHtml ="";
    resultHtml+='<option value="">Select Category</option>';
    $('#category_select').html(resultHtml);
    $('#category_select').change();

    var resultHtml ="";
    resultHtml+='<option value="">Select Type</option>';
    resultHtml+='<option value="T">Teacher invoice</option>';
    resultHtml+='<option value="S">School invoice</option>';
    $('#event_invoice_type').html(resultHtml);
    $('#event_invoice_type').change();

    $("#category_select").prop('disabled', true);
    $("#event_invoice_type").prop('disabled', true);
});

$('#event_invoice_type').on('change', function() {

    var isSchoolAdmin = +"{{$AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()}}";
    var isTeacherAdmin = +"{{$AppUI->isTeacherAdmin()}}";
    var isTeacherSchoolAdmin = +"{{$AppUI->isTeacherSchoolAdmin()}}";
    var isTeacher = +"{{$AppUI->isTeacher()}}";
    var event_invoice_type = "";
    var teacher =  $("#teacher_select option:selected").val();


    @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
    event_invoice_type = $("#event_invoice_type option:selected").val();
    @endif
    @if($AppUI->isTeacherAdmin())
    event_invoice_type = $("#event_invoice_type").val();
    @endif

    if(event_invoice_type !== "") {

        if(isSchoolAdmin || isTeacherSchoolAdmin) {
            getCategoryByType('{{ $schoolId }}', event_invoice_type, teacher);
        }

        if( ((isSchoolAdmin || isTeacherAdmin) && event_invoice_type == 'S') || (isTeacher &&  event_invoice_type == 'T') ){
            $("#price_per_student").show();
        }else{
            $("#price_per_student").hide();
        }

    } else {
            var resultHtml ="";
            resultHtml+='<option value="">Select Category</option>';
            $('#category_select').html(resultHtml);
            $('#category_select').change();
    }

});


function getCategoryByType(school_id=null, type=null, teacher=null) {

if (school_id !=null) {
    var menuHtml='';
    var data = 'school_id='+school_id+'&type='+type+'&teacher='+teacher+'';
    $('#category_select').html('');

    $.ajax({
        url: BASE_URL + '/get_event_category_by_type',
        data: data,
        type: 'POST',
        dataType: 'json',
        //async: false,
        beforeSend: function( xhr ) {
            $("#pageloader").show();
        },
        success: function(data) {
          $("#pageloader").hide();
            if (data.length >0) {
                var resultHtml ="";
                resultHtml+='<option data-s_thr_pay_type="0" data-s_std_pay_type="0" data-t_std_pay_type="0" data-invoice="T" value="0">Select Category</option>';
                var i='0';
                $.each(data, function(key,value){
                    var isAdmin = "{{ $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin() }}";
                    let textAdmin = "";
                    if(isAdmin) {
                        textAdmin = "<span class='text-danger'>("+value.invoiced_type+")</span> ";
                    }
                    resultHtml+='<option data-s_thr_pay_type="'+value.s_thr_pay_type+'" data-s_std_pay_type="'+value.s_std_pay_type+'" data-t_std_pay_type="'+value.t_std_pay_type+'" value="'+value.id+'" data-invoice="'+value.invoiced_type+'">'+textAdmin+''+value.title+'</option>';
                });
                $('#category_select').html(resultHtml);
                $('#category_select').change();

            } else {

                var resultHtml ="";
                resultHtml+='<option value="">No category found</option>';
                $('#category_select').html(resultHtml);
                $('#category_select').change();

            }

        },   //success
        complete: function( xhr ) {
          $("#pageloader").hide();
        },
        error: function(ts) {
            // alert(ts.responseText)
            errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
        }
    }); // Ajax
}

}



$( document ).ready(function() {

    $(document).on('click', '#add_lesson_btn,#add_lesson_btn_mobile', function() {

        var listAvailabilities = document.getElementById('displayDate');
        listAvailabilities.innerHTML = moment().format('DD/MM/YYYY')

        if (getSchoolIDs('is_multi')) {
                $('#modal_lesson_price').modal('show');
                $("#modal_alert_body").text("Please select One school for add event or lesson");
        }else{
            $("#addAgendaModal").modal('show');
            const startresult = moment().format('DD/MM/YYYY');
            const startTime = moment().format('HH:mm');
            $('#start_date').val(startresult);
            $('#start_time').val(startTime);
            const endTime = moment().add(15, 'm').format('HH:mm');
            const endresult = moment().subtract(1, 'seconds').format('DD/MM/YYYY');
            $('#end_date').val(endresult);
            $('#end_time').val(endTime).trigger('change');
            $('#agenda_select').trigger('change');
            $('#Title').val('');
           // resetStudentList();
            getAwayStudent();
        }

    });
});

$( document ).ready(function() {
// if checked check-students-availability show all class .studentAvailabilities if not hide it
    $("#check-students-availability2").click(function() {
        if (this.checked) {
            isOnlyAvailability = true;
           // resetStudentList();
        } else {
            isOnlyAvailability = false;
           // resetStudentList();
        }
    })
});

$( document ).ready(function() {
// if checked check-students-availability show all class .studentAvailabilities if not hide it
    $("#check-students-availability").click(function() {
        if (this.checked) {
            $(".studentAvailabilities").fadeIn();
            $('#studentListAvailabilities').fadeIn();
        } else {
            $(".studentAvailabilities").hide();
            $('#studentListAvailabilities').hide();
        }
    })
});


function getAwayStudent() {

    var startDate = $('#start_date').val();
    var days = ['sunday', 'monday','tuesday','wednesday','thursday','friday','saturday'];
    var momentDate = moment(startDate, 'DD/MM/YYYY');
    var verifDate = moment(startDate, 'DD/MM/YYYY');

    var formattedDate = momentDate.format('YYYY-MM-DD');
    var date = new Date(formattedDate);
    var dayName = days[date.getDay()];

    var listAway = document.getElementById('studentlistAway');
    listAway.innerHTML = '';

        $.ajax({
        type: "POST",
        url: "/admin/getAbsentStudent",
        data: {
            startDate: startDate
        },
        success: function(response) {
        response.forEach(function (item) {
            var studentId = item.student_id;
           // console.log(item);
            var studentButton = $('button.multiselect-option:has(input.form-check-input[value="' + studentId + '"])');
            if (studentButton.length > 0) {
                var labelElement = studentButton.find('.form-check-label');

                if (labelElement.length > 0) {
                    var labelText = labelElement.text();

                    labelElement.html(item.full_name + ' <span class="text-warning"><i class="fa-solid fa-circle-info"></i> is away this day</span>');
                    listAway.innerHTML += '<span class="text-warning"><i class="fa-solid fa-circle-info"></i> <span class="text-warning">' + item.full_name + ' is away this day</span><br>';
                } else {
                   // console.log('Étiquette de texte non trouvée pour studentId=' + studentId);
                    labelElement.html(item.full_name);
                }
            } else {
               // console.log('Élément <button> non trouvé pour studentId=' + studentId);
            }

            if(item.availabilities.length > 0) {
                //faire une boucle savoir si startDate est monday ou tuesday etc... et voir si il est dans la liste des availabilities day_of_week
                item.availabilities.forEach(function (availability) {
                   // console.log('test');
                    if(moment(availability.day_of_week).format('YYYY-MM-DD') === verifDate.format('YYYY-MM-DD')) {
                        //var $time = availability.time_of_day === "AM" ? "morning" : "afternoon";
                        //console.log('available on ' + availability.day_of_week + " on " +  $time);
                    labelElement.append(' <span class="text-success studentAvailabilities" style="font-size: 11px!important;padding-left: 4px!important;"> (Available ' + availability.start_time + ' and ' + availability.end_time + ')</span>');
                    }
                });
            }

        });
        }
    });

}

function resetStudentList() {
    isOnlyAvailability = document.getElementById("check-students-availability2").checked;
  // Générer un tableau JSON des étudiants côté serveur
  var students = @json($studentsbySchool);
   // console.log('students', students)
  var studentSelect = $('#student');

  var startDate = $('#start_date').val();
    var days = ['sunday', 'monday','tuesday','wednesday','thursday','friday','saturday'];
    var momentDate = moment(startDate, 'DD/MM/YYYY');
    var verifDate = moment(startDate, 'DD/MM/YYYY');

    var formattedDate = momentDate.format('YYYY-MM-DD');
    var date = new Date(formattedDate);
    var dayName = days[date.getDay()];
    var countAvailabilityThisDay = 0;

    var listAvailabilities = document.getElementById('studentListAvailabilities');
    listAvailabilities.innerHTML = '';

    // Parcourir le tableau JSON côté client
    students.forEach(function(student) {
        var studentName = student.full_name;
        var studentButton = $('button.multiselect-option:has(input.form-check-input[value="' + student.student_id + '"])');
        if (studentButton.length > 0) {
        studentButton.show();
        var labelElement = studentButton.find('.form-check-label');

        if (labelElement.length > 0) {
            var labelText = labelElement.text();
            labelElement.html(student.full_name);
            studentButton.show();
        } else {
            if(isOnlyAvailability) {
                studentButton.hide();
            }
          //  console.log('Étiquette de texte non trouvée pour studentId=' + studentId);
        }
        } else {
       // console.log('Élément <button> non trouvé pour studentId=' + studentId);
        if(isOnlyAvailability) {
                studentButton.hide();
            }
        }

        if(student.availabilities.length > 0) {

                student.availabilities.forEach(function (availability) {

                    if(dayName === availability.day_of_week) {
                        countAvailabilityThisDay++;
                        labelElement.append('<span class="badge bg-success studentAvailabilities" style="margin:1px!important; font-size: 11px!important;padding-left: 4px!important;">' + moment(availability.start_time, 'HH:mm:ss').format('h:mm A') + ' and ' + moment(availability.end_time, 'HH:mm:ss').format('h:mm A') + '</span>');
                        listAvailabilities.innerHTML += '<span class="text-success studentAvailabilities" style="font-size: 12px!important;padding-left: 4px!important;"> <b>'+studentName+'</b> is usually available the '+dayName+' betw. ' + moment(availability.start_time, 'HH:mm:ss').format('h:mm A') + ' and ' + moment(availability.end_time, 'HH:mm:ss').format('h:mm A') + '</span><br>';
                    }

                  //  console.log(moment(availability.day_of_week).format('YYYY-MM-DD'));
                   // console.log('the day', moment(startDate).format('YYYY-MM-DD'));
                    if(availability.is_special === 1 && moment(availability.day_special).format('YYYY-MM-DD') === verifDate.format('YYYY-MM-DD')) {
                        countAvailabilityThisDay++;
                        //var $time = availability.time_of_day === "AM" ? "morning" : "afternoon";
                        //console.log('available on ' + availability.day_of_week + " on " +  $time);
                        labelElement.append('<span class="badge bg-success studentAvailabilities" style="margin:1px!important; font-size: 11px!important;padding-left: 4px!important;">' + moment(availability.start_time, 'HH:mm:ss').format('h:mm A') + ' and ' + moment(availability.end_time, 'HH:mm:ss').format('h:mm A') + '</span>');
                        listAvailabilities.innerHTML += '<span class="text-success studentAvailabilities" style="font-size: 12px!important;padding-left: 4px!important;"> <b>'+studentName+'</b> is available this day betw. ' + moment(availability.start_time, 'HH:mm:ss').format('h:mm A') + ' and ' + moment(availability.end_time, 'HH:mm:ss').format('h:mm A') + '</span><br>';

                    }
                });
            } else {
                if(isOnlyAvailability) {
                studentButton.hide();
            }
            }

    });

    if(countAvailabilityThisDay === 0) {
        listAvailabilities.innerHTML += '<span class="text-black studentAvailabilities" style="font-size: 11px!important;padding-left: 4px!important;"> No availability registered for this day</span><br>';
    }
}

function showMessage(messageText, messageType) {

    var messageClass = "";
    switch (messageType) {
        case "success":
            messageClass = "text-success";
            break;
        case "danger":
            messageClass = "text-danger";
            break;
        case "info":
            messageClass = "text-info";
            break;
        default:
            messageClass = ""; // ou une autre classe par défaut si vous le souhaitez
    }

    var dateTimeDiv = document.getElementById("dateTime");
    var messageDiv = document.getElementById("messageContainer");

    // Crée l'élément icône
    const iconElement = document.createElement("i");
    iconElement.className = "fa-solid fa-bell fa-beat";

    // Vide le contenu précédent du messageDiv
    messageDiv.innerHTML = '';

    // Ajoute l'icône au messageDiv
    messageDiv.appendChild(iconElement);

    // Ajoute le texte du message après l'icône
    messageDiv.appendChild(document.createTextNode(' ' + messageText));

    //Add class
    messageDiv.classList.add(messageClass);

    // Cachez la date/heure et affichez le message
    dateTimeDiv.style.display = "none";
    messageDiv.style.display = "block";

    // Après 5 secondes, cachez le message et affichez à nouveau la date/heure
    setTimeout(function() {
        messageDiv.style.display = "none";
        dateTimeDiv.style.display = "block";
    }, 5000);  // 5000 millisecondes = 5 secondes
}


$( document ).ready(function() {

    $(function() {
        $("#save_btn_more").click(function(){
           $("#save_btn_value"). val(1);
        });
        $("#save_btn").click(function(){
           $("#save_btn_value"). val(0);
        });
    });

});
</script>

@endsection
