@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/> 
<link href="{{ asset('css/datetimepicker-lang/bootstrap-datetimepicker.css')}}" rel="stylesheet">
<link href="{{ asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{ asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.js"></script> -->
<script src="{{ asset('js/jquery.table2excel.js')}}"></script>
<link href="{{ asset('css/admin_main_style.css')}}" rel='stylesheet' />
<!-- add new assets for modal of add event,lesson -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<!-- end the assets area -->
admin_main_style.css
@endsection

@section('content')
<div class="content agenda_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="agendaForm" name="agendaForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-4 col-xs-12 header-area">
							<div class="page_header_class">
                                <label for="calendar" id="cal_title" style="display: block;">
                                    {{__('Agenda')}}: 
                                </label>
							</div>
					</div>
                    
					<div class="col-sm-8 col-xs-12 btn-area">
                        <div class="pull-right btn-group cal_top">
                            <input type="hidden" name="school_id" id="school_id" value="{{$schoolId}}">
                            <input type="hidden" name="max_teachers" id="max_teachers" value="<?php if($school){ echo $school->max_teachers; } ?>">
                            <input type="hidden" name="edit_view_url" id="edit_view_url" value="">
							<input type="hidden" name="confirm_event_id" id="confirm_event_id" value="">
							<input type="hidden" name="user_role" id="user_role" value="{{$user_role}}">                                        
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
                            <input type="input" name="search_text" class="form-control search_text_box" id="search_text" value="" placeholder="Search">
                            <div id="button_menu_div" class="btn-group buttons pull-right" onclick="SetEventCookies()">
                                <!-- <div class="btn-group"> -->
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
                                <a style="display: none;" href="#" id="btn_validate_events" target="_blank" class="btn btn-sm btn-theme-warn"><em class="glyphicon glyphicon-remove"></em><span id ="btn_validate_events_cap">Validate All</span></a>
                                <a style="display: none;" href="#" id="btn_delete_events" target="_blank" class="btn btn-sm btn-theme-warn"><em class="glyphicon glyphicon-remove"></em><span id ="btn_delete_events_cap">Delete All</span></a>
                                <button style="display: none;" href="#" id="btn_copy_events" target="_blank" class="btn btn-theme-outline"><em class="glyphicon glyphicon-plus"></em><span id ="btn_copy_events_cap">Copy</span></button>
                                <button style="display: none;" href="#" id="btn_goto_planning" target="_blank" class="btn btn-theme-outline"><em class="glyphicon glyphicon-fast-forward"></em><span id ="btn_goto_planning_cap">Paste</span></button>
                                @if(!$AppUI->isStudent())
                                    <a href="#" id="btn_export_events" target="_blank" class="btn btn-theme-outline">
                                        <img src="{{ asset('img/excel_icon.png') }}"  width="17" height="auto"/>
                                        <span id ="btn_export_events_cap">Excel</span>
                                    </a>
                                @endif
                                <!-- </div> -->
                            </div>
                        </div>
					</div>    
				</div>                 
			</header>
            <div class="clearfix"></div>
            <div class="row" style="margin:0;">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @csrf
                    <section class="panel cal_area" style="border: 0;box-shadow: none;">
                        <label id="loading" style="display:none;">Loading....</label> 
                        <form action="#" method="post">
                            <div class="clearfix"></div>
                            <div class="row" id="school_cal">
                                <div class="col-md-9">
                                    <!-- fullcalendar -->
                                    <div id="calendar"></div>
                                    <div style="margin-top: 15px;">
                                        <div class="btn-group" style="margin-right:5px;">
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_prev"><i class="fa fa-chevron-left" style="color: #3b75bf;"></i></button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_today">Today</button>
                                            <button type="button" class="btn btn-sm calendar_buttons" id="btn_next"><i class="fa fa-chevron-right" style="color: #3b75bf;"></i></button>
                                        </div>
                                        <button class="btn btn-sm calendar_buttons" id="btn_day" type="button">Day</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_week" type="button">Week</button> 
                                        <button class="btn btn-sm calendar_buttons" id="btn_month" type="button">Month</button>
                                        <button class="btn btn-sm calendar_buttons" id="btn_list" type="button">List</button> 
                                        <button class="btn btn-sm calendar_buttons" id="btn_current_list" type="button">Current List</button> 
                                    </div>   
                                </div>
                                <div class="col-md-3">
                                    <div id="event_school_div" name="event_school_div" class="selectdiv">
                                        <select class="form-control" multiple="multiple" id="event_school" name="event_school[]" style="margin-bottom: 15px;" >
                                            @foreach($schools as $key => $this_school)
                                                <option {{ ( !empty($schoolId) && $schoolId == $this_school->id ? 'selected' : '') }} 
                                                    value="{{ $this_school->id }}">{{ $this_school->school_name }}</option>
                                            @endforeach    
                                        </select>
                                    </div>  
                                    <!-- Datepicker -->
                                    <div id="datepicker_month"></div>
                                    <div>
                                        <div class="btn-group btn-xs pull-left" style="padding:0;width:100%;"> 
                                            <div id="event_location_div" name="event_location_div" class="selectdiv">
                                                <select class="form-control" multiple="multiple" id="event_location" name="event_location[]" style="margin-bottom: 15px;" >
                                                    @foreach($locations as $key => $location)
                                                        <option value="{{ $location->id }}">{{ $location->title }}</option>
                                                    @endforeach    
                                                </select>
                                            </div>
                                            <div id="event_type_div" name="event_type_div" class="selectdiv">
                                                <select class="form-control" multiple="multiple" id="event_type" name="event_type[]" style="margin-bottom: 15px;" >
                                                    @foreach($event_types as $key => $event_type)
                                                        <option value="{{ $key }}">{{ $event_type }}</option>
                                                    @endforeach
                                                </select>
                                                <select style="display:none;" class="form-control" multiple="multiple" id="event_types_all" name="event_types_all[]" style="margin-bottom: 15px;" >
                                                    @foreach($event_types_all as $key => $event_type)
                                                        <option value="{{ $key }}">{{ $event_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="event_student_div" name="event_student_div" class="selectdiv">
                                                <select class="form-control" multiple="multiple" id="event_student" name="event_student[]" style="margin-bottom: 15px;">
                                                    @foreach($students as $key => $student)
                                                        <option value="{{ $student->id }}">{{ $student->firstname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="event_teacher_div" name="event_teacher_div" class="selectdiv">
                                                <select class="form-control" multiple="multiple" id="event_teacher" name="event_teacher[]" style="margin-bottom: 15px;">
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
		</form>
	</div>
</div>


<!-- Modal for add event,lesson,student and coach off -->	
<div class="modal fade login-event-modal" id="addAgendaModal" name="addAgendaModal" tabindex="-1" aria-hidden="true" aria-labelledby="addAgendaModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-dialog addAgendaModalClass" id="addAgendaModalWin">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="col-md-10 offset-md-1 p-l-n p-r-n"> 
                                <div class="form-group row">
                                    <label class="col-lg-3 col-sm-3 text-left">Agenda Type :</label>
                                    <div class="col-sm-7">
                                        <div class="selectdiv">
                                            <select class="form-control" id="agenda_select">
                                                <option value="1">Lesson</option>
                                                <option value="2">Event</option>
                                                <option value="3">Student Off</option>
                                                <option value="4">Coach off</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>                   
                            <div class="tab-content" id="agenda_form_area" style="display:none">
                                <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                                    <form class="form-horizontal" id="add_lesson" method="post" action="{{ route('lesson.createAction',[$schoolId]) }}"  name="add_lesson" role="form">
                                        @csrf
                                        <input id="save_btn_value" name="save_btn_more" type="hidden" class="form-control" value="0">
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-10 offset-md-1">
                                                    <div class="form-group row lesson hide_on_off">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Type') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="category_select" name="category_select">
                                                                    @foreach($eventCategoryList as $key => $eventcat)
                                                                        <option category_type="{{ $eventcat->invoiced_type }}" value="{{ $eventcat->id }}" {{ old('category_select') == $eventcat->id ? 'selected' : ''}}>{{ $eventcat->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hide_on_off">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Location') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="location" name="location">
                                                                    
                                                                    @foreach($locations as $key => $location)
                                                                        <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : ''}}>{{ $location->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Title') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group"> 
                                                                <input id="Title" name="title" type="text" class="form-control" value="{{old('title')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row show_coach_off hide_on_off">
                                                    @if(!$AppUI->isTeacherAdmin())
                                                    <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Professor') }} :</label>
                                                    @endif
                                                    @if($AppUI->isTeacherAdmin())
                                                        <input style="display:none" type="text" name="teacher_select" class="form-control" value="{{ $AppUI->person_id; }}" readonly>
                                                    @else	
                                                    <div class="col-sm-7">
                                                        <div class="selectdiv">
                                                            <select class="form-control" id="teacher_select" name="teacher_select">
                                                                    <option value="">{{__('Select Professor') }}</option>
                                                                @foreach($professors as $key => $professor)
                                                                    <option value="{{ $professor->teacher_id }}" {{ old('teacher_select') == $professor->teacher_id ? 'selected' : ''}}>{{ $professor->nickname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    </div>
                                                    <div class="form-group row hide_coach_off">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="selectdiv student_list">
                                                                <select class="form-control" id="student" name="student[]" multiple="multiple">
                                                                    @foreach($studentsbySchool as $key => $student)
                                                                        <option value="{{ $student->student_id }}" {{ old('student') == $student->student_id ? 'selected' : ''}}>{{ $student->nickname }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 p-l-n p-r-n">
                                                           <span class="no_select"> <input type="checkbox" name="student_empty" id="student_empty"> {{__('No selected') }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group row not-allday">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Start date') }} :</label>
                                                        <div class="col-sm-7 row">
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="start_date_div"> 
                                                                    <input id="start_date" name="start_date" type="text" class="form-control" value="{{old('start_date')}}" autocomplete="off">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>	
                                                            <div class="col-sm-4 offset-md-1 hide_on_off">
                                                                <div class="input-group"> 
                                                                    <input id="start_time" name="start_time" type="text" class="form-control timepicker" value="{{old('start_time')}}">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </span>
                                                                </div>
                                                            </div>	
                                                        </div>
                                                    </div>
                                                    <div class="form-group row not-allday">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('End date') }} :</label>
                                                        <div class="col-sm-7 row">
                                                            <div class="col-sm-6">
                                                                <div class="input-group" id="end_date_div"> 
                                                                    <input id="end_date" name="end_date" type="text" class="form-control" value="{{old('end_date')}}" autocomplete="off" readonly>
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>	
                                                            <div class="col-sm-4 offset-md-1 hide_on_off">
                                                                <div class="input-group"> 
                                                                    <input id="end_time" name="end_time" type="text" class="form-control timepicker" value="{{old('end_time')}}">
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-clock-o"></i>
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
                                                    <div class="form-group row" id="all_day">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="all_day" id="has_user_ac_label_id">{{__('All day') }} :</label>
                                                        <div class="col-sm-7">
                                                            <input id="all_day" name="fullday_flag" type="checkbox" value="Y">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row lesson hide_on_off">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Type of billing') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="sis_paying" name="sis_paying">
                                                                    <option value="0">Coming Soon</option>
                                                                    <!-- <option value="1">Hourly rate</option>
                                                                    <option value="2">Price per student</option> -->
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="hourly" style="display:none">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Number of students') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="selectdiv">
                                                                <select class="form-control" id="sevent_price" name="sevent_price">
                                                                    @foreach($lessonPrice as $key => $lessprice)
                                                                        <option value="{{ $lessprice->lesson_price_student }}" {{ old('sevent_price') == $lessprice->lesson_price_student ? 'selected' : ''}}>    
                                                                        @if($lessprice->lesson_price_student == 'price_1')
                                                                            Private Group
                                                                        @else
                                                                            Group lessons for {{ $lessprice->divider }} students
                                                                        @endif	
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="price_per_student" style="display:none;">
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Currency') }} :</label>
                                                            <div class="col-sm-7">
                                                                <div class="selectdiv">
                                                                    <select class="form-control" id="sprice_currency" name="sprice_currency">
                                                                        @foreach($currency as $key => $curr)
                                                                            <option value="{{$curr->currency_code}}">{{$curr->currency_code}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Teacher price (per class)') }} :</label>
                                                            <div class="col-sm-4">
                                                                <div class="input-group" id="sprice_amount_buy_div">
                                                                    <p>COMING SOON</p> 
                                                                    <!-- <span class="input-group-addon">
                                                                        <i class="fa fa-calendar1"></i>
                                                                    </span> -->
                                                                    <input id="sprice_amount_buy" name="sprice_amount_buy" type="hidden" class="form-control" value="{{old('sprice_amount_buy')}}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Student price (per student)') }} :</label>
                                                            <div class="col-sm-4">
                                                                <div class="input-group" id="sprice_amount_sell_div"> 
                                                                <p>COMING SOON</p>
                                                                    <!-- <span class="input-group-addon">
                                                                        <i class="fa fa-calendar1"></i>
                                                                    </span> -->
                                                                    <input id="sprice_amount_sell" name="sprice_amount_sell" type="hidden" class="form-control" value="{{old('sprice_amount_sell')}}" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row event hide_on_off">
                                                    <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Extra Charges:') }} :</label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group" id="extra_charges_div"> 
                                                            <p>COMING SOON</p>
                                                            <!-- <span class="input-group-addon">
                                                                <i class="fa fa-calendar1"></i>
                                                            </span> -->
                                                            <input id="extra_charges" name="extra_charges" type="hidden" class="form-control" value="{{old('extra_charges')}}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                                
                                                <div class="col-md-10 offset-md-1">
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">{{__('Description') }} :</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group"> 
                                                                <textarea class="form-control" cols="60" id="description" name="description" rows="3">{{old('description')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <button id="save_btn" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save') }} </button>
                                        <button id="save_btn_more" class="btn btn-theme-success"><i class="fa fa-save"></i>{{ __('Save & add more') }} </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- success modal-->
<div class="modal modal_parameter" id="modal_lesson_price">
    <div class="modal-dialog">
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

<!-- Modal on event click -->	
<div class="modal fade login-event-modal" id="EventModal" name="EventModal" tabindex="-1" aria-hidden="true" aria-labelledby="EventModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        
            <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
                <div class="modal-dialog EventModalClass" id="EventModalWin">
                    <div class="modal-content">
                        <div class="modal-body text-center p-4">                    
                            <h4 class="light-blue-txt gilroy-bold"><span id="event_modal_title">Title</span></h4>
                            <p style="font-size: 20px;"></p>
                            <button type="button" id="btn_confirm" onclick="confirm_event()" class="btn btn-theme-success" data-dismiss="modal" style="width:100px;">
                            <span id="event_btn_confirm_text">Validate<span>

                            </button>
                            <!-- <button type="button" id="btn_confirm_unlock" onclick="confirm_event(true)" class="btn btn-theme-success" data-dismiss="modal" style="width:100px;">
                                <span id="event_btn_confirm_unlock_text">Unlock<span>
                            </button> -->
                            <a type="button" id="btn_edit_view" onclick="view_edit_event()" class="btn btn-theme-warn" data-dismiss="modal" style="width:100px;">
                                <span id="event_btn_edit_text">View<span>
                            </a>
                        </div>
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

    // set default data
    var no_of_teachers = document.getElementById("max_teachers").value;
    var resultHtml='';      //for populate list - agenda_table
    var resultHtml_cc='';      //for populate list - agenda_table
    
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
    var user_auth='';

    var json_events = @json($events);

   

   if ($(window).width() < 768) {
        var defview='agendaDay'; 
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
    


    var currentTimezone = 'local';
    var currentLangCode = 'fr';
    var foundRecords=0; // to store found valid records for rendering yes/no - default is 0.
    var lockRecords=1;
    var zone =getTimeZone();
    if ((no_of_teachers == 1) || (user_role == "student")){
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
        pickTime: false
      
    });
    moment.locale(lang_id, {
	  week: { dow: 1 } // Monday is the first day of the week
	});
    RenderCalendar();
    $(document).ready(function(){
        if (user_role == "student") {
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
        getFreshEvents();
        PopulateSchoolDropdown();
        PopulateEventTypeDropdown();
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
        $("#event_types_all option").each(function(key,value)
        {
            //console.log(value.value);
            if ( (value.value == 51) && (user_role == 'student') ){
                // menuHtml+='<a title="" id="add_lesson_btn" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px!important;"><i class="glyphicon glyphicon-plus"></i>Add </a>';
                // menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                // menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                // menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }
            
            // cours - events - PopulateButtonMenuList
            if ((value.value == 10) && user_role != 'student'){
                menuHtml+='<a title="" id="add_lesson_btn" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px!important;"><i class="glyphicon glyphicon-plus"></i>Add </a>';
                // menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                // menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                // menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }        
            if ( (user_role == 'schooladmin') || (user_role == 'superadmin') || (user_role == 'webmaster') || (user_auth == "ALL") ) {
                // if (value.id != 10) {
                //     menuHtml+='<li><a  href="../admin/'+{{$schoolId}}+'/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.value == 10) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/add-lesson"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.value == 50) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/coach-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.value == 51) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/student-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.value == 100) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }
                
            }
            else if ( (user_role == 'teacher') && ((user_auth == "MED") || (user_auth == "MIN")) && ((value.value == 100) || (value.value == 50)) ) {
                // if (value.value == 50) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/coach-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.value == 100) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }
            }
            
             
            // Add $(this).val() to your list
        });
        // menuHtml+='</ul>';
        $('#button_menu_div').append(menuHtml); 
        


        $('#personal_ics').click(function (e) {
            DownloadEventsICS('PersonnelEvents');
        })
		
		
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
    $('#search_text').on('input', function(){
		var search_text=$(this).val();
        

		if (search_text.length > 0){
			$('#calendar').fullCalendar('rerenderEvents');
		}
        if (search_text.length == 0){
			$('#calendar').fullCalendar('rerenderEvents');
		}
    });
    $("#datepicker_month").datetimepicker()
    .on('changeDate', function(ev){
        
        var dt=$(this).datetimepicker('getDate');
        
        var jsDate = $(this).datetimepicker('getDate');
        if (jsDate !== null) { // if any date selected in datepicker
            jsDate instanceof Date; // -> true
            jsDate.getDate();
            jsDate.getMonth();
            var month = jsDate.getMonth() + 1;   
            jsDate.getFullYear();
            dt=jsDate.getFullYear()+'-'+month+'-'+jsDate.getDate();
            $('#calendar').fullCalendar( 'gotoDate', dt);
            
        }
    });

    //right: 'prev,today,next month,agendaWeek,agendaDay MyListButton'
	$('#btn_prev').on('click', function() {
        $('#calendar').fullCalendar('prev');
	});

	$('#btn_today').on('click', function() {
        $('#calendar').fullCalendar('today');        
	});

	$('#btn_next').on('click', function() {
        $('#calendar').fullCalendar('next');
	});

	$('#btn_month').on('click', function() {
        $('#calendar').fullCalendar('changeView', 'month');
	});

	$('#btn_week').on('click', function() {
        $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});

	$('#btn_day').on('click', function() {
        $('#calendar').fullCalendar('changeView', 'agendaDay');
	});
    $('#btn_list').on('click', function() {
        //getFreshEvents('ListView');	  
		$('#calendar').fullCalendar('changeView', 'listYear');	
	});
    $('#btn_current_list').on('click', function() {
        //getFreshEvents('CurrentListView');
       // CallListView();	  
        
		console.log('lllll----------------')
          
        getCurrentListFreshEvents();
        console.log('lllll----------------')
	});
    
    $('#list_button').on('click', function() {
        CallListView();
	});

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
    }
    firstload ='1';

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
                    console.log('School changed triggered!');
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
                    console.log('school onSelectAll triggered!'+options);
                    document.getElementById("event_school_id").value=getSchoolIDs();
                    document.getElementById("event_school_all_flag").value='1';
                }
                else {
                    console.log('school onDeSelectAll triggered!');
                    document.getElementById("event_school_id").value='';
                    document.getElementById("event_school_all_flag").value='0';
                 
                }
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onDeselectAll: function(option,checked) {
                console.log('school onDeSelectAll triggered!');
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
            selectAllText: 'All Events',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Event type ',
            allSelectedText: 'All Events',
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
                    console.log('Event changed triggered!');
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
                console.log('Event onDeSelectAll triggered!');
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
                async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                    $("#pageloader").hide();
                    if (data.length >0) {
                        var resultHtml ="";
                        var i='0';
                        $.each(data, function(key,value){
                            resultHtml+='<option value="'+value.id+'" data-invoice="'+value.invoiced_type+'">'+value.title+'</option>'; 
                        });
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

    // populate location
    function PopulateSchoolCurrencyDropdown(school_id=null){

        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#sprice_currency').html('');
        
            $.ajax({
                url: BASE_URL + '/get_school_currency',
                data: data,
                type: 'POST',                     
                dataType: 'json',
                async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                    $("#pageloader").hide();
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
            $('#event_location').html('');
        
            $.ajax({
                url: BASE_URL + '/get_locations',
                data: data,
                type: 'POST',                     
                dataType: 'json',
                async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                    $("#pageloader").hide();
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
                    $("#pageloader").hide();
                }, 
                error: function(ts) { 
                    // alert(ts.responseText) 
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }
        $('#event_location').multiselect({
            includeSelectAllOption:true,
            selectAllText: 'All Location',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Location',
            allSelectedText: 'All Location',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: 'Search',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                console.log('onChange location triggered!');
                document.getElementById("event_location_id").value=getLocationIDs();
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                    console.log('location onSelectAll triggered!'+options);
                    document.getElementById("event_location_id").value='0';
                    document.getElementById("event_location_all_flag").value='1';
                }
                else {
                    console.log('location onDeSelectAll triggered!');
                    document.getElementById("event_location_id").value='';
                    document.getElementById("event_location_all_flag").value='0';
                
                }
                //SetEventCookies();
                RerenderEvents();
                getFreshEvents();
            },
            onDeselectAll: function() {
                console.log('NOT WORKING location onDeSelectAll triggered!');
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
                async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                    $("#pageloader").hide();
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
                    $("#pageloader").hide();
                }, 
                error: function(ts) { 
                    // alert(ts.responseText) 
                    errorModalCall('Populate Event Type:'+GetAppMessage('error_message_text'));
                }
            }); // Ajax
        }
        $('#event_teacher').multiselect({
            includeSelectAllOption:true,
            selectAllText: 'All Teachers',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Teacher',
            allSelectedText: 'All Teachers',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: 'Search',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                console.log('onChange teacher triggered!');
                document.getElementById("event_teacher_id").value=getTeacherIDs();
                document.getElementById("event_teacher_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                    console.log('teacher onSelectAll triggered!'+options);
                    document.getElementById("event_teacher_id").value='0';
                    document.getElementById("event_teacher_all_flag").value='1';
                }
                else {
                    console.log('teacher onDeSelectAll triggered!');
                    document.getElementById("event_teacher_id").value='';
                    document.getElementById("event_teacher_all_flag").value='0';
                 
                }
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onDeselectAll: function() {
                console.log('NOT WORKING teacher onDeSelectAll triggered!');
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
        if (school_id !=null) {
            var menuHtml='';
            var data = 'school_id='+school_id;
            $('#event_student').html('');
        
            $.ajax({
                url: BASE_URL + '/get_students',
                data: data,
                type: 'POST',                     
                dataType: 'json',
                async: false,
                beforeSend: function( xhr ) {
                    $("#pageloader").show();
                },
                success: function(data) {
                    $("#pageloader").hide();
                    if (data.length >0) {
                        
                    }
                    var resultHtml ='';
                    var i='0';
                    $.each(data, function(key,value){
                        resultHtml+='<option value="'+value.student_id+'">'+value.nickname+'</option>'; 
                    });
                    $('#event_student, #student').html(resultHtml);
                    $("#event_student").multiselect('destroy');
                    $('#student').multiselect({ search: true })
                    
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
        $('#event_student').multiselect({
            includeSelectAllOption:true,
            selectAllText: 'All Students',
            maxHeight:true,
            enableFiltering:false,
            nSelectedText  : 'Selected Student',
            allSelectedText: 'All Students',
            enableCaseInsensitiveFiltering:false,
            // enables full value filtering
            enableFullValueFiltering:false,
            filterPlaceholder: 'Search',
            numberDisplayed: 3,
            buttonWidth: '100%',
            // possible options: 'text', 'value', 'both'
            filterBehavior: 'text',
            onChange: function(option, checked) {
                console.log('onChange student triggered!');
                document.getElementById("event_student_id").value=getStudentIDs();
                document.getElementById("event_student_all_flag").value='0';
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
            },
            onSelectAll: function (options,checked) {
                if (options){
                     console.log('student onSelectAll triggered!'+options);
                     document.getElementById("event_student_id").value='0';
                     document.getElementById("event_student_all_flag").value='1';
                }
                else {
                    console.log('student onDeSelectAll triggered!');
                    document.getElementById("event_student_id").value='';
                    document.getElementById("event_student_all_flag").value='0';
                 
                }
                SetEventCookies();
                RerenderEvents();
                //getFreshEvents();
                
            },
            onDeselectAll: function() {
                console.log('NOT WORKING student onDeSelectAll triggered!');
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
    $('#btn_copy_events').click(function (e) {
        document.getElementById("copy_date_from").value = document.getElementById("date_from").value;
        document.getElementById("copy_date_to").value = document.getElementById("date_to").value;
		document.getElementById("copy_view_mode").value =document.getElementById("view_mode").value;


        document.getElementById("copy_week_day").value =document.getElementById("week_day").value;
        document.getElementById("copy_month_day").value =document.getElementById("month_day").value;
		
		document.getElementById("copy_school_id").value =getSchoolIDs();
        document.getElementById("copy_event_id").value =getEventIDs();
		document.getElementById("copy_student_id").value = getStudentIDs();
        document.getElementById("copy_teacher_id").value = getTeacherIDs();
		
        return false;
    })

    //delete multiple events based on date, events type, teacher and student etc
    $('#btn_delete_events').click(function (e) {
	
        
        var user_role=document.getElementById("user_role").value;
        if (user_role == 'student') {
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
        
        var p_event_id=document.getElementById("get_non_validate_event_id").value;

        //var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
        e.preventDefault();
        confirmDeleteModalCall(p_event_id,'Do you want to delete events',"delete_multiple_events('"+p_event_school_id+"','"+p_from_date+"','"+p_to_date+"','"+p_event_type_id+"','"+p_student_id+"','"+p_teacher_id+"');");
        return false;
    })


    //validate multiple events based on date, events type, teacher and student etc
    $('#btn_validate_events').click(function (e) {
	
        
        var user_role=document.getElementById("user_role").value;
        if (user_role == 'student') {
            //alert("You don't have permission to delete events");
            errorModalCall('permission_issue_common_text');
            return false;
        }
        var curdate=new Date();
        var p_from_date=document.getElementById("date_from").value,
        p_to_date=moment(curdate).format("YYYY-MM-DD");
        var p_event_school_id=getSchoolIDs();
        var p_event_type_id=getEventIDs();
        var p_student_id=getStudentIDs();
        var p_teacher_id=getTeacherIDs();
        var p_event_id=document.getElementById("get_event_id").value;
        var get_non_validate_event_id=document.getElementById("get_non_validate_event_id").value;
        

        //var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
        e.preventDefault();
        confirmMultipleValidateModalCall(get_non_validate_event_id,'Do you want to validate events',"validate_multiple_events('"+p_event_school_id+"','"+p_from_date+"','"+p_to_date+"','"+p_event_type_id+"','"+p_student_id+"','"+p_teacher_id+"','"+p_event_id+"');");
        return false;
    })

    function validate_multiple_events(p_event_school_id,p_from_date,p_to_date,p_event_type_id,p_student_id,p_teacher_id,p_event_id){
        var data='p_event_school_id='+p_event_school_id+'&p_from_date='+p_from_date+'&p_to_date='+p_to_date+'&p_event_type_id='+p_event_type_id+'&p_student_id='+p_student_id+'&p_teacher_id='+p_teacher_id+'&p_event_id='+p_event_id;
        
            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/validate_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                    document.getElementById("btn_validate_events").style.display = "none";
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
        var data='type=delete_multiple_events'+'&p_event_school_id='+p_event_school_id+'&p_from_date='+p_from_date+'&p_to_date='+p_to_date+'&p_event_type_id='+p_event_type_id+'&p_student_id='+p_student_id+'&p_teacher_id='+p_teacher_id;
        
            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/delete_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                    document.getElementById("btn_delete_events").style.display = "none";
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


    function getTimeZone() {
        var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
        return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
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
        console.log(document.getElementById("view_mode").value);
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
        console.log('RenderCalendar: defview'+defview);
		/* initialize the calendar
		-----------------------------------------------------------------*/
        let curdate=new Date();
        let p_from_date=moment(curdate).format("YYYY-MM-DD");
        // if (getCookie("date_from")){
        //     p_from_date = getCookie("date_from");
        // } 
        
        $('#calendar').fullCalendar({
            timeFormat: 'HH(:mm)',   
            axisFormat: 'HH(:mm)',            
			slotDuration: '00:15:00',
			slotLabelFormat: 'H:mm',
            defaultView: defview,
            // minTime: '05:00:00',
            scrollTime: '05:00:00',
            maxTime: '24:00:00',
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
                    columnFormat: 'ddd MMM DD'
                },
                week: {
                    columnFormat: 'ddd MMM DD'
                },
                month: {
                    columnFormat: 'ddd'
                },
                day: {
                    columnFormat: 'ddd DD MMM'
                }
            }, 
            handleWindowResize: true,
            eventTextColor: '#000000',
            firstDay: '1',      //monday
            height: 'parent', // calendar content height excluding header
            contentHeight: v_calc_height, // calendar content height excluding header
            timezone: currentTimezone, 
            locale: currentLangCode,
			buttonIcons: true, // show the prev/next text
			allDayDefault: true,
			defaultTimedEventDuration: '00:15:00',
			forceEventDuration: true,
			nextDayThreshold: '00:00',
            nowIndicator: true,
            // events: function (start, end, tz, callback) {
            //     callback(JSON.parse(json_events));
            // },
            //events: JSON.parse(json_events),
            //allDaySlot: true,
            loading: function(bool) {
				$('#loading').toggle(bool)
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
                        //if ((event.tooltip.toLowerCase().indexOf(search_text) >= 0) || (event.tooltip.toLowerCase().indexOf(search_text) >= 0)) {
                        //if (event.tooltip.toLowerCase().indexOf(search_text) >= 0) {
                        if (event.text_for_search.indexOf(search_text) >= 0) {
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
                        $(el).find('.fc-time').prepend(icon);
                    }
                    var icon ='<span class="fa fa-lock txt-orange"></span>';
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
                            var ooo= icon+''+event.title_extend;
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

                el.popover({
                    title: event.tooltip,
                    trigger: 'hover',
                    html: true,
                    placement: 'top',
                    container: 'body'
                });
                //el.attr('timetext', event.title);
                //$('#timetext').text(event.cours_name);
                $(el).find('#timetext').append(' '+event.event_type_name);
                return flag;  
            },           

            eventClick: function(event, jsEvent, view) {
                if (event.url) {
                    SetEventCookies();
                    document.getElementById('edit_view_url').value=event.url;
                    document.getElementById('confirm_event_id').value=event.id;
                    
                    if (event.action_type == 'edit') {
                        $('#event_btn_edit_text').text("{{__('Edit')}}");
                        if (event.can_lock == 'Y') {
                            const type_removed = [50, 51];
                            if(type_removed.includes(event.event_type) != true){ 
                                $('#btn_confirm').show();
                                //$('#btn_confirm_unlock').hide();
                                
                            } else {
                                $('#btn_confirm').hide(); 
                                //$('#btn_confirm_unlock').show();
                            }
                        } else {
                            $('#btn_confirm').hide();
                            //$('#btn_confirm_unlock').show();
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
                    
                    //document.getElementById('event_modal_title').text=stime+' - '+etime+':'+event.title;
                    if (stime == '00:00') {
                        $('#event_modal_title').text(event.event_type_name+' : '+event.title); 
                    }
                    else {
                        $('#event_modal_title').text(event.event_type_name+':'+stime+'-'+etime+' '+event.title); 
                    }
                    
                    
                    $("#btn_edit_view").attr("href", event.url);
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
                document.getElementById("btn_goto_planning").style.display = "none";
                document.getElementById("btn_delete_events").style.display = "none";
                document.getElementById("btn_validate_events").style.display = "none";
                
                var user_role=document.getElementById("user_role").value;
                
                if (foundRecords == 1)
                {
                    if (user_role == 'student') {
                        document.getElementById("btn_copy_events").style.display = "none";        
                    } else {
                        document.getElementById("btn_copy_events").style.display = "block";
                        let selected_validate_ids = document.getElementById("get_validate_event_id").value;
                        if (selected_validate_ids.length ==0) {
                            document.getElementById("btn_validate_events").style.display = "none";
                        }else {
                            document.getElementById("btn_validate_events").style.display = "block";
                        }
                    }
                }

                if (foundRecords > 0)
                {
                    if (user_role == 'student') {
                        document.getElementById("btn_validate_events").style.display = "none"; 
                        document.getElementById("btn_delete_events").style.display = "none"; 
                    } else {
                        //Delete button will be visible if events are available and all events are in unlock mode
                        document.getElementById("btn_delete_events").style.display = "block";
                        document.getElementById("btn_validate_events").style.display = "block";
                    }
                    if (lockRecords == 0)
                    {
                        document.getElementById("btn_delete_events").style.display = "none";
                        document.getElementById("btn_validate_events").style.display = "none"; 
                        
                    }
                } else{
                    document.getElementById("btn_delete_events").style.display = "none";  
                    document.getElementById("btn_validate_events").style.display = "none";  
                }

                
                lockRecords=0;
                    
                var view = $('#calendar').fullCalendar('getView'); 
                if ((foundRecords == 0) && (document.getElementById("copy_date_from").value.length != 0) 
                    && (document.getElementById("copy_view_mode").value == view.name) )
                {
                    document.getElementById("btn_goto_planning").style.display = "block";
                }
                
                    
                foundRecords=0;
                
                CheckPermisson();
                
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
                @if(!$AppUI->isStudent())
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
                }
                @endif
                    
            }
        })    //full calendar initialization
        CheckPermisson();
        
    } //full calender - RenderCalendar


    function getFreshEvents(p_view=getCookie("cal_view_mode")){
        
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

        var school_id=document.getElementById('school_id').value;
        var p_event_school_id=document.getElementById("event_school_id").value;
        var p_event_location_id=getLocationIDs();
        document.getElementById("prevnext").value = '';
        var json_events = @json($events);
        $.ajax({
            //url: BASE_URL + '/'+school_id+'/get_event',
            url: BASE_URL + '/get_event',
            type: 'POST', 
            data: 'type=fetch&location_id='+p_event_location_id+'&school_id='+p_event_school_id+'&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view,
            // async: false,
            success: function(s){
                SetEventCookies();
                json_events = s;
                var selected_ids = [];
                var selected_validate_ids = [];
                var selected_non_validate_ids = [];
                const type_removed = [50, 51];
                Object.keys(JSON.parse(json_events)).forEach(function(key) {
                    if(type_removed.includes(JSON.parse(json_events)[key].event_type) != true){ 
                        let end = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY");
                        let start = moment(JSON.parse(json_events)[key].start.toString()).format("DD/MM/YYYY HH:mm");
                        let end_date = moment(JSON.parse(json_events)[key].end.toString()).format("DD/MM/YYYY HH:mm");
                        let teacher_name =JSON.parse(json_events)[key].cours_name; 
                        let cours_name = JSON.parse(json_events)[key].duration_minutes; 
                        let duration_minutes = JSON.parse(json_events)[key].teacher_name; 
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
                        if (end<moment(curdate).format("DD/MM/YYYY") && JSON.parse(json_events)[key].is_locked !=1) {
                            selected_validate_ids.push('Start: '+start+' End: '+end_date+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);	  
                        } 
                        else{
                            selected_non_validate_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);
                        }   
                        selected_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);	
                        
                        
                    }
                    
                });
                selected_ids.join("|");
                selected_validate_ids.join("|");
                selected_non_validate_ids.join("|");
                document.getElementById("get_event_id").value = selected_ids;
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_validate_events").style.display = "none";
                }
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_delete_events").style.display = "none";
                }
                document.getElementById("get_validate_event_id").value = selected_validate_ids;
                document.getElementById("get_non_validate_event_id").value = selected_non_validate_ids;
                
                
                $('#calendar').fullCalendar('removeEvents', function () { return true; });

                var eventsToPut = []; 

                $.each(JSON.parse(json_events), function(k, v)  {
                    // OBJECT is created when processing response
                    eventsToPut.push(v);
                });
                console.log(eventsToPut);
                
                $('#calendar').fullCalendar('addEventSource',JSON.parse(json_events), true);
                //$("#agenda_table tr:gt(0)").remove();
                //$("#agenda_table_current tr:gt(0)").remove();
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events) )
                $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                $('#calendar').fullCalendar('refetchEventSources',JSON.parse(json_events))
                $('#calendar').fullCalendar('rerenderEvents' );
                $('#calendar').fullCalendar('refetchEvents');
                $('#calendar').fullCalendar('refetchEvents');
                $('#calendar').fullCalendar( 'renderEvent', JSON.parse(json_events) , 'stick');
                // $('#calendar').fullCalendar({ events: JSON.parse(json_events) });
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
            },
            error: function(ts) { 
                //errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
                // alert(ts.responseText) 
                console.log(ts.responseText);
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
        
        $('#calendar').fullCalendar('changeView', 'CurrentListView');
        var dt = new Date();
        let CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
        document.getElementById("date_from").value = formatDate(dt);
        document.getElementById("date_to").value = formatDate(CurrentListViewDate);
    
        var start_date=document.getElementById("date_from").value;
        var end_date=document.getElementById("date_to").value;
    

        var school_id=document.getElementById('school_id').value;
        var p_event_school_id=document.getElementById("event_school_id").value;
        var p_event_location_id=getLocationIDs();
        document.getElementById("prevnext").value = '';
        var json_events = @json($events);
        $.ajax({
            //url: BASE_URL + '/'+school_id+'/get_event',
            url: BASE_URL + '/get_event',
            type: 'POST', 
            data: 'type=fetch&location_id='+p_event_location_id+'&school_id='+p_event_school_id+'&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view,
            // async: false,
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
                const type_removed = [50, 51];
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
                        if (end<moment(curdate).format("DD/MM/YYYY") && JSON.parse(json_events)[key].is_locked !=1) {
                            selected_validate_ids.push('Start: '+start+' End: '+end_date+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);	  
                        } 
                        else{
                            selected_non_validate_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);
                        }   
                        selected_ids.push('Start:'+start+' End:'+end+' '+JSON.parse(json_events)[key].title+' '+cours_name+' '+duration_minutes+' minutes '+teacher_name);	
                        


                        
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
                        resultHtml_cc+='<td>'+JSON.parse(json_events)[key].teacher_name+'</td>';
                        resultHtml_cc+='</tr>';






                    
                });
                let resultHtml_rows_cc=resultHtml_cc;
                console.log(resultHtml_rows_cc);
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
                selected_validate_ids.join("|");
                selected_non_validate_ids.join("|");
                document.getElementById("get_event_id").value = selected_ids;
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_validate_events").style.display = "none";
                }
                if (selected_validate_ids.length ==0) {
                    document.getElementById("btn_delete_events").style.display = "none";
                }
                document.getElementById("get_validate_event_id").value = selected_validate_ids;
                document.getElementById("get_non_validate_event_id").value = selected_non_validate_ids;
                
                
                //$('#calendar').fullCalendar('removeEvents', function () { return true; });

                // var eventsToPut = []; 

                // $.each(JSON.parse(json_events), function(k, v)  {
                //     // OBJECT is created when processing response
                //     eventsToPut.push(v);
                // });
                console.log(JSON.parse(json_events));
                //
               
                
                if (firstLoad =='firstLoad') {
                    $('#calendar').fullCalendar('addEventSource',JSON.parse(json_events), true);
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events) )
                    $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
                    $('#calendar').fullCalendar('refetchEventSources',JSON.parse(json_events))
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
                
            },
            error: function(ts) { 
                //errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
                // alert(ts.responseText) 
                console.log(ts.responseText);
            }
        }); 
    } 


   

    function DisplayCalendarTitle() {
        var view = $('#calendar').fullCalendar('getView');
        if (view.name=='CurrentListView') {
            var dt = new Date();
            var CurrentListViewDate = new Date(new Date().getTime()+(2*24*60*60*1000)) //2 days
            const options1 = {month: 'short',day: 'numeric' };
            const options = { day: 'numeric', year: 'numeric'};
            view.title = moment(dt).format("MMM DD")+' - '+moment(CurrentListViewDate).format("DD,YYYY");
            //$('#calendar').fullCalendar( 'renderEvent', JSON.parse(json_events) , 'stick');
            //$('#calendar').fullCalendar('rerenderEvents');
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

        if (selected_ids.length > 1) {
            $('#event_location_div').hide();
            $('#event_teacher_div').hide();
            $('#event_student_div').hide();
        } else {
            $('#event_location_div').show();
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
        $.each($("#event_location option:selected"), function(){            
            selected_ids.push($(this).val());
        });		
        //console.log('selected='+selected_ids.join("|"));
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
        if (user_role =='student'){
            document.getElementById("btn_copy_events").style.display="none";
            document.getElementById("btn_goto_planning").style.display="none";
            document.getElementById("event_teacher").style.display="none";
			document.getElementById("event_student_div").style.display="none";
			
        }
    } 


    //creating events
    $('#btn_goto_planning').click(function (e) {
        var school_id=document.getElementById('school_id').value;
        var source_start_date=document.getElementById("copy_date_from").value,
        source_end_date=document.getElementById("copy_date_to").value,
        event_school=document.getElementById("copy_school_id").value,
        event_type=document.getElementById("copy_event_id").value,
        student_id=document.getElementById("copy_student_id").value,
        teacher_id=document.getElementById("copy_teacher_id").value,
        target_start_date=document.getElementById("date_from").value,
        target_end_date=document.getElementById("date_to").value,
        view_mode = document.getElementById("view_mode").value;
        
        var data='view_mode='+view_mode+'&source_start_date='+source_start_date+'&source_end_date='+source_end_date+'&target_start_date='+target_start_date+'&target_end_date='+target_end_date+'&school_id='+event_school+'&event_type='+event_type+'&student_id='+student_id+'&teacher_id='+teacher_id;
        //console.log(data);
        //return false;
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: BASE_URL + '/copy_paste_events',
            //url: "copy_paste_events.php",
            data: data,
            dataType: "JSON",
            async: false,
            success:function(result){
                var status =  result.status;
                //alert(status);
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
                errorModalCall('btn_goto_planning:' + GetAppMessage('error_message_text'));
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
			
			var cal_view_mode=$('#calendar').fullCalendar('getView');
			console.log("cal_view_mode="+cal_view_mode.name);
			
			if (cal_view_mode.name === undefined) {
                document.cookie = "cal_view_mode="+"month"+";path=/";
            }
			else {
				document.cookie = "cal_view_mode="+cal_view_mode.name+";path=/";
            }
			
			document.cookie = "prevnext="+document.getElementById("prevnext").value+";path=/";
		//}    
    }


    window.addEventListener( "pageshow", function ( event ) {
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
    });

    // window.addEventListener('focus', function (event) {
    //     console.log('has focus');
    //     //RerenderEvents();
    // });

    // window.addEventListener('blur', function (event) {
    //     console.log('lost focus');
    // });

</script>

<!-- add lesson,event,school and coach off js start here -->
<script type="text/javascript">
$(function() {
	$("#start_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
    $('#end_date').datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
});
// $('#student').multiselect({
// 	search: true
// });
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
	var value = $('#sis_paying').val();
	$('#hourly').hide();
	$('#price_per_student').hide();
	$('#sprice_amount_buy').val(0);
	$('#sprice_amount_sell').val(0);
	if(value == 1){
		$('#hourly').show();
	}else if(value == 2){
		$('#price_per_student').show();
	}
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
$('#sis_paying').on('change', function() {
	$('#hourly').hide();
	$('#price_per_student').hide();
	$('#sprice_amount_buy').val(0);
	$('#sprice_amount_sell').val(0);
	if(this.value == 1){
		$('#hourly').show();
	}else if(this.value == 2){
		$('#price_per_student').show();
	}
});
$("body").on('click', '#all_day', function(event) {
    if ($(this).prop('checked')) {
        $(".not-allday").hide();
    }else{
        $(".not-allday").show();
    }
});

$('#add_lesson').on('submit', function(e) {
    e.preventDefault();
	var title = $('#Title').val();
	var professor = $('#teacher_select').val();
    var evetCat = $('#category_select option:selected').val();
    var evetLoc = $('#location option:selected').val();
    var emptyStdchecked = $("#student_empty").prop('checked')
	var selected = $("#student :selected").map((_, e) => e.value).get();
	var startDate = $('#start_date').val();
	var endDate = $('#end_date').val();
	var errMssg = '';
	var type = $("#agenda_select").val();

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

    if(type == 1 || type == 2){
        if(type==1){
            var bill_type = $('#sis_paying').val();
            
            if(bill_type == 1){
                $.ajax({
                    url: BASE_URL + '/check-lesson-price',
                    async: false, 
                    data: formData,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        if(response.status == 1){
                            var errMssg = '';	
                        }else{
                            var errMssg = 'error';
                            $('#modal_lesson_price').modal('show');
                            $("#modal_alert_body").text("Price setup is not available for this event category and coach. please check and update.");
                            e.preventDefault();
                        }
                    }
                })
            }
        }
        
        if(professor == ''){
            var errMssg = 'professor required';
            $('#teacher_select').addClass('error');
        }else{
            $('#teacher_select').removeClass('error');
        }

        if( evetCat == undefined || evetCat == ''){
            var errMssg = '{{ __("Select event category") }}';
            $('#category_select').addClass('error');
        }else{
            $('#category_select').removeClass('error');
        }

        if (!emptyStdchecked) {
            if( selected < 1){
                var errMssg = 'Select student';
                $('.student_list').addClass('error');
            }else{
                $('.student_list').removeClass('error');
            }
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
        if( selected < 1){
            var errMssg = 'Select student';
            $('.student_list').addClass('error');
        }else{
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
        $.ajax({
            url: page_action,
            async: false, 
            data: formData,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                if(response.status == 1){
                    $("#add_lesson")[0].reset();
                    $('#student').val([]).multiselect('refresh');
                    const startresult = moment().format('DD/MM/YYYY');
                    const startTime = moment().format('HH:mm');
                    $('#start_date').val(startresult);
                    $('#start_time').val(startTime);
                    const endTime = moment().add(15, 'm').format('HH:mm');
                    const endresult = moment().subtract(1, 'seconds').format('DD/MM/YYYY');
                    $('#end_date').val(endresult);
                    $('#end_time').val(endTime).trigger('change');
                }else{
                    location.reload();
                }
            }
        })
    }else{
        return false;
    }
});


$(document).ready(function() {
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
    
    if(agenda_select != ''){
		$('#agenda_form_area').show();
        if(agenda_select == 1){
            $('#start_date').on('change', function(e){  
                $("#end_date").val($("#start_date").val());  
            });
            $( "#end_date" ).attr("readonly", "readonly");;	
            $('.lesson').show();
            $('.event').hide();
            $('#sis_paying').val(0);
            $('#price_per_student').hide();
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
$("body").on('change', '#category_select', function(event) {
    var datainvoiced = $("#category_select option:selected").data('invoice');
    if (datainvoiced == 'S') {
        $("#std-check-div").css('display', 'block');
    }else{
        $("#std-check-div").css('display', 'none');
        $("#student_empty").prop('checked', false)
    }
});
$("body").on('click', '#student_empty', function(event) {
    if ($("#student_empty").prop('checked')) {
        $('#student').val([]).multiselect('refresh');
    }else{
        $('#student').val([]).multiselect('refresh');
    }
})

$('#agenda_select').on('change', function() {
    if(this.value != ''){
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
            $('#sis_paying').val(0);
            $('#price_per_student').hide();
            $('.hide_on_off').show();
            $('.event.hide_on_off').hide();
            $("form.form-horizontal").attr("action", page_action);
            $('.hide_coach_off').show();
            $('.show_coach_off.hide_on_off').show();
        }else if(this.value == 2){
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
            $('.show_coach_off.hide_on_off').show();
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
        }
	}else{
        $('#agenda_form_area').hide();
    }
});

$( document ).ready(function() {

    $('#add_lesson_btn').on('click', function() {

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
        }

    });
});



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
