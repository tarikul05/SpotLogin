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
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.js"></script>
<script src="{{ asset('js/jquery.table2excel.js')}}"></script>

<link href="{{ asset('css/admin_main_style.css')}}" rel='stylesheet' />
admin_main_style.css
@endsection

@section('content')
<div class="content agenda_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="agendaForm" name="agendaForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
							<div class="page_header_class">
                                <label for="calendar" id="cal_title" style="display: block;">
                                    {{__('Agenda')}}: 
                                </label>
							</div>
					</div>
					<div class="col-sm-6 col-xs-12 btn-area">
                        <div class="pull-right btn-group">

                            <input type="hidden" name="school_id" id="school_id" value="{{$schoolId}}">
                            <input type="hidden" name="max_teachers" id="max_teachers" value="{{$school->max_teachers}}">
                            
                            
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
                            

                            <input type="hidden" name="copy_date_from" id="copy_date_from" value="">
                            <input type="hidden" name="copy_date_to" id="copy_date_to" value="">
                            <input type="hidden" name="copy_event_id" id="copy_event_id" value="">
                            <input type="hidden" name="copy_student_id" id="copy_student_id" value="">
                            <input type="hidden" name="copy_teacher_id" id="copy_teacher_id" value="">
                            <input type="hidden" name="copy_view_mode" id="copy_view_mode" value="">
                            <input type="hidden" name="copy_week_day" id="copy_week_day" value="">
                            <input type="hidden" name="copy_month_day" id="copy_month_day" value="">
                            

                            <input type="hidden" name="event_type_id" size="14px" id="event_type_id" value="0">
                            <input type="hidden" name="event_student_id" size="14px" id="event_student_id" value="0">
                            <input type="hidden" name="event_teacher_id" size="14px" id="event_teacher_id" value="0">
                            <input type="hidden" name="event_location_id" size="14px" id="event_location_id" value="0">
                            <input type="hidden" name="event_category_id" size="14px" id="event_category_id" value="0">


                            <input type="hidden" name="event_type_all_flag" size="14px" id="event_type_all_flag" value="1">
                            <input type="hidden" name="event_student_all_flag" size="14px" id="event_student_all_flag" value="1">
                            <input type="hidden" name="event_teacher_all_flag" size="14px" id="event_teacher_all_flag" value="1">
                            <input type="hidden" name="event_location_all_flag" size="14px" id="event_location_all_flag" value="1">
                            <input type="hidden" name="event_category_all_flag" size="14px" id="event_category_all_flag" value="0">

                            <input type="input" name="search_text" class="form-control search_text_box" id="search_text" value="" placeholder="Search">
                            	
                            <div id="button_menu_div" class="btn-group buttons pull-right" >
                                <!-- <div class="btn-group"> -->
                                    <a style="display: none;" href="#" id="btn_delete_events" target="_blank" class="btn btn-sm btn-theme-warn"><em class="glyphicon glyphicon-remove"></em><span id ="btn_delete_events_cap">Delete</span></a>
							        <button style="display: none;" href="#" id="btn_copy_events" target="_blank" class="btn btn-theme-outline"><em class="glyphicon glyphicon-plus"></em><span id ="btn_copy_events_cap">Copy</span></button>
                                    <button style="display: none;" href="#" id="btn_goto_planning" target="_blank" class="btn btn-theme-outline"><em class="glyphicon glyphicon-fast-forward"></em><span id ="btn_goto_planning_cap">Paste</span></button>
                                    <a href="#" id="btn_export_events" target="_blank" class="btn btn-theme-outline">
                                        <img src="{{ asset('img/excel_icon.png') }}"  width="17" height="auto"/>
                                        <span id ="btn_export_events_cap">Excel</span>
                                    </a>
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
          <section class="panel" style="border: 0;box-shadow: none;">
            <label id="loading" style="display:none;">Loading....</label> 
            <form action="#" method="post">
            
              <div class="clearfix"></div>
              <div class="row">
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
                      </div>   
                  </div>
                  <div class="col-md-3">
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
                    <button type="button" id="btn_confirm" onclick="confirm_event()" class="btn btn-theme-success" data-dismiss="modal" style="width:100px;"><span id="event_btn_confirm_text">Confirm<span></button>
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
    var firstload = '0';    // check first time loading or not
    var prevdt='';          // for rendering for heading
    // var prev_text=document.getElementById("prev_text").value;
    // var next_text=document.getElementById("next_text").value;
    //var listcalendar_text=document.getElementById("listcalendar_text").value;
	var stime='00:00',etime='00:00';
	var v_calc_height=((screen.height/100)*50.00);
	//var v_calc_height=((window.innerHeight/100)*30.00);

    var loading=1;
    var resultHtml_rows='';      //for populate list - agenda_table
    var lang_id='fr';
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var user_role=document.getElementById("user_role").value;

    var json_events = @json($events);

   

   
    var defview='month';   //'month';//'agendaWeek'
    try {
        if ((getCookie("cal_view_mode") != "") && (getCookie("cal_view_mode") !== undefined)){
            defview=getCookie("cal_view_mode");
        }
    } catch(err) {
        defview="month";
    }
    var dt = new Date();
    // set default data    
    // GET THE MONTH AND YEAR OF THE SELECTED DATE.
    var month = dt.getMonth(),year = dt.getFullYear();

    var FirstDay = new Date(year, month, 1);
    var LastDay = new Date(year, month+1, 1);

    

    if (defview == 'month') {
        // GET THE FIRST AND LAST DATE OF THE MONTH.
        document.getElementById("date_from").value = formatDate(FirstDay);
        document.getElementById("date_to").value = formatDate(LastDay);
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
    var lockRecords=0;
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

    $(document).ready(function(){
        if (user_role == "student") {
			document.getElementById('event_type').style.display="none";
			document.getElementById('event_student_div').style.display="none";
			document.getElementById('event_teacher').style.display="none";
		}
		$('#back_btn').click(function (e) {							
	   	    window.history.back();
		});
        loading=0;
        //console.log('a) loading='+loading);
        RerenderEvents();
        RenderCalendar();
        PopulateEventTypeDropdown();
        PopulateLocationDropdown();
        PopulateStudentDropdown();
        PopulateTeacherDropdown();
        DisplayCalendarTitle();
        document.getElementById("copy_event_id").value =getEventIDs();
        document.getElementById("copy_student_id").value = getStudentIDs();
        document.getElementById("copy_teacher_id").value = getTeacherIDs();

        var menuHtml='';
        $("#event_type option").each(function(key,value)
        {
            
            if ( (value.value == 51) && (user_role == 'student') ){
                menuHtml+='<a title="" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px 0 0 4px!important;" href="../{{$schoolId}}/student-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a>';
                menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }
            
            // cours - events - PopulateButtonMenuList
            if ( (value.value == 10) && (user_role != 'student') ){
                menuHtml+='<a title="" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px 0 0 4px!important;" href="../{{$schoolId}}/add-lesson"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a>';
                menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }        
            if ( (user_role == 'schooladmin') || (user_role == 'superadmin') || (user_role == 'webmaster') || (user_auth == "ALL") ) {
                // if (value.id != 10) {
                //     menuHtml+='<li><a  href="../admin/'+{{$schoolId}}+'/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                // if (value.id == 10) {
                //     menuHtml+='<li><a  href="../{{$schoolId}}/add-lesson"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                // }

                if (value.value == 50) {
                    menuHtml+='<li><a  href="../{{$schoolId}}/coach-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }

                if (value.value == 51) {
                    menuHtml+='<li><a  href="../{{$schoolId}}/student-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }

                if (value.value == 100) {
                    menuHtml+='<li><a  href="../{{$schoolId}}/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }
                
            }else if ( (user_role == 'teacher') && ((user_auth == "MED") || (user_auth == "MIN")) && ((value.value == 100) || (value.value == 50)) ) {
                if (value.value == 50) {
                    menuHtml+='<li><a  href="../{{$schoolId}}/coach-off"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }

                if (value.value == 100) {
                    menuHtml+='<li><a  href="../{{$schoolId}}/add-event"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }
            }
            
             
            // Add $(this).val() to your list
        });
        menuHtml+='</ul>';
        $('#button_menu_div').append(menuHtml); 
    
		
		
	}); //ready

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
    function confirm_event(){
        var p_event_auto_id=document.getElementById('confirm_event_id').value;
        var school_id=document.getElementById('school_id').value;
        var data = 'school_id='+school_id+'&p_event_auto_id=' + p_event_auto_id;
        console.log(data);
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
                    successModalCall('event_confirm_message');
                    getFreshEvents();
                }
                else {
                    errorModalCall('error_message_text');
                }
            },   //success
            complete: function( xhr ) {
                $("#pageloader").hide();
            },
            error: function (ts) { 
                ts.responseText+'-'+errorModalCall('error_message_text');
            }
        }); //ajax-type            

    }
    $('#search_text').on('input', function(){
		var search_text=$(this).val();
        

		if (search_text.length > 0){
            console.log(search_text);
			$('#calendar').fullCalendar('rerenderEvents');
		}
        if (search_text.length == 0){
            //console.log(search_text);
			$('#calendar').fullCalendar('rerenderEvents');
		}
    });
    $("#datepicker_month").datetimepicker()
    .on('changeDate', function(ev){
        
        var dt=$(this).datetimepicker('getDate');
        
        var jsDate = $(this).datetimepicker('getDate');
        console.log(jsDate.getMonth());
        if (jsDate !== null) { // if any date selected in datepicker
            jsDate instanceof Date; // -> true
            jsDate.getDate();
            jsDate.getMonth();
            var month = jsDate.getMonth() + 1;   
            jsDate.getFullYear();
            console.log(jsDate.getFullYear()+'-'+month+'-'+jsDate.getDate());
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
        getFreshEvents('ListView');	   
		$('#calendar').fullCalendar('changeView', 'listYear');	   
	   
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
                     //SetEventCookies();
                     RerenderEvents();
             },
             onSelectAll: function (option,checked) {
                     document.getElementById("event_type_id").value='0';
                     document.getElementById("event_type_all_flag").value='1';
                     //SetEventCookies();
                     RerenderEvents();
             },
             onDeselectAll: function(option,checked) {
                 console.log('Event onDeSelectAll triggered!');
                     //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
                     document.getElementById("event_type_id").value=getEventIDs();
                     document.getElementById("event_type_all_flag").value='0';
                     //SetEventCookies();
                     RerenderEvents();
                 },
             selectAllValue: 0
         });
 
         $('#event_type').multiselect('selectAll', false);   
         $('#event_type').multiselect('refresh');	
                 
     }   // populate event type
    function PopulateLocationDropdown(){
          
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
            },
            onDeselectAll: function() {
                console.log('NOT WORKING location onDeSelectAll triggered!');
                document.getElementById("event_location_id").value='';
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
            },
            selectAllValue: 0
        });
 
        $('#event_location').multiselect('selectAll', false);   
        $('#event_location').multiselect('refresh');	
                  
    }   // populate event type
    function PopulateTeacherDropdown(){
          
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
                console.log('onChange location triggered!');
                document.getElementById("event_location_id").value=getLocationIDs();
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
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
            },
            onDeselectAll: function() {
                console.log('NOT WORKING location onDeSelectAll triggered!');
                document.getElementById("event_location_id").value='';
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
            },
            selectAllValue: 0
        });
  
        $('#event_teacher').multiselect('selectAll', false);   
        $('#event_teacher').multiselect('refresh');	
                   
    }   // populate event type
    function PopulateStudentDropdown(){
          
        $('#event_student').multiselect({
            includeSelectAllOption:true,
            selectAllText: 'All Location',
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
                console.log('onChange location triggered!');
                document.getElementById("event_location_id").value=getLocationIDs();
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
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
            },
            onDeselectAll: function() {
                console.log('NOT WORKING location onDeSelectAll triggered!');
                document.getElementById("event_location_id").value='';
                document.getElementById("event_location_all_flag").value='0';
                //SetEventCookies();
                RerenderEvents();
            },
            selectAllValue: 0
        });
  
        $('#event_student').multiselect('selectAll', false);   
        $('#event_student').multiselect('refresh');	
                   
    }   // populate event type

    function RerenderEvents(){
	    if (loading == 0){ 
            $("#agenda_table tr:gt(0)").remove();
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
        var p_event_type_id=getEventIDs();
        var p_student_id=getStudentIDs();
        var p_teacher_id=getTeacherIDs();

        //var retVal = confirm("Tous les événements affichés seront supprimés. Voulez-vous supprimer ?");
        e.preventDefault();
        confirmModalCall('confirm_event_delete_text',"delete_multiple_events('"+p_from_date+"','"+p_to_date+"','"+p_event_type_id+"','"+p_student_id+"','"+p_teacher_id+"');");
        return false;
    })

    function delete_multiple_events(p_from_date,p_to_date,p_event_type_id,p_student_id,p_teacher_id){
        var data='type=delete_multiple_events'+'&p_from_date='+p_from_date+'&p_to_date='+p_to_date+'&p_event_type_id='+p_event_type_id+'&p_student_id='+p_student_id+'&p_teacher_id='+p_teacher_id;
        
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
    


    function getFreshEvents(p_view=''){
        //alert('getrefresh..: Start');
        var start_date=document.getElementById("date_from").value;
        var end_date=document.getElementById("date_to").value;
        var school_id=document.getElementById('school_id').value;
        document.getElementById("prevnext").value = '';
        var json_events = @json($events);
        $.ajax({
            url: BASE_URL + '/'+school_id+'/get_event',
            type: 'POST', 
            data: 'type=fetch&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view,
            // async: false,
            success: function(s){
                
                json_events = s;
                console.log("aaaa------------------");
                console.log(json_events);
                console.log("aaaa------------------");
            },
            error: function(ts) { 
                //errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
                // alert(ts.responseText) 
            }
        });
        
        //alert('get refresh');
        $("#agenda_table tr:gt(0)").remove();
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
        if (document.getElementById("view_mode").value == 'list'){
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
        if (document.getElementById("view_mode").value != 'list')
        {
            document.getElementById("view_mode").value = 'list';
            $('.fc-MyListButton-button').text('Calender');
            //remove 
            //$('#calendar').fullCalendar().find('.fc-day-header').parents('table').html('');
            $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
            $('#calendar').fullCalendar().find('.fc-day-header').hide();
            //$('#calendar').fullCalendar('option', 'contentHeight', 0);
            document.getElementById("agenda_list").style.display = "block";
        
        }
        else{
            //alert('refresh');
            $('.fc-MyListButton-button').text('list');
            document.getElementById("view_mode").value = '';
            document.getElementById("agenda_list").style.display = "none";
            $('#calendar').fullCalendar().find('.fc-day-header').parents('table').show();
            $('#calendar').fullCalendar().find('.fc-day-header').show();
            
            //getFreshEvents();   // scroll bar is not appearing hence refresh calendar - resolved hence commented
            
            //$('#calendar').fullCalendar(options).slideToggle();
            //$('#calendar').fullCalendar('rerenderEvents');
        }       
    }


    
    function RenderCalendar(){    
        //console.log('RenderCalendar: defview'+defview);
		/* initialize the calendar
		-----------------------------------------------------------------*/

        $('#calendar').fullCalendar({
            timeFormat: 'HH(:mm)',   
            axisFormat: 'HH(:mm)',            
			slotDuration: '00:30:00',
			slotLabelFormat: 'H:mm',
            defaultView: 'agendaWeek',
            defaultDate: '2022-04-12',
            utc: false,  
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
			// allDayDefault: true,
			defaultTimedEventDuration: '00:30:00',
			forceEventDuration: true,
			nextDayThreshold: '00:00',
            nowIndicator: true,
            loading: function(bool) {
				$('#loading').toggle(bool)
			},

            // eventRender: function(eventObj, $el) {
            //     $el.popover({
            //         title: eventObj.title,
            //         content: eventObj.description,
            //         trigger: 'hover',
            //         placement: 'top',
            //         container: 'body'
            //     });
            // },
            events: JSON.parse(json_events),

            // events: [
            //     {
            //         title: 'Meeting',
            //         description: 'description for Meeting',
            //         start: '2022-04-12T14:30:00'
            //     },
            //     {
            //         title: 'Birthday Party',
            //         description: 'description for Birthday Party',
            //         start: '2022-04-13T07:00:00',
            //         end: '2022-04-13T09:00:00'
            //     }
            // ]
        // });


		// $('#calendar1').fullCalendar({
		// 	timeFormat: 'HH(:mm)',   
        //     axisFormat: 'HH(:mm)',            
		// 	slotDuration: '00:30:00',
		// 	slotLabelFormat: 'H:mm',
        //     //events: json_events,	  
		// 	events: JSON.parse(json_events),
		// 	utc: false,            
        //     defaultView: defview,
            
        //     buttonText: {
        //         prev: '<',
        //         next: '>'
        //     },       
		// 	header: false,
            
        //     views: {
        //         agenda: {
        //             columnFormat: 'ddd MMM DD'
        //         },
        //         week: {
        //             columnFormat: 'ddd MMM DD'
        //         },
        //         month: {
        //             columnFormat: 'ddd'
        //         },
        //         day: {
        //             columnFormat: 'ddd DD MMM'
        //         }
        //     },
            
        //     handleWindowResize: true,
        //     eventTextColor: '#000000',
        //     firstDay: '1',      //monday
        //     height: 'parent', // calendar content height excluding header
        //     contentHeight: v_calc_height, // calendar content height excluding header
            
        //     timezone: currentTimezone, 
        //     locale: currentLangCode,
		// 	buttonIcons: true, // show the prev/next text
		// 	allDayDefault: true,
		// 	defaultTimedEventDuration: '00:30:00',
		// 	forceEventDuration: true,
		// 	nextDayThreshold: '00:00',
        //     nowIndicator: true,
            loading: function(bool) {
				$('#loading').toggle(bool)
			},
  
            // to customize cell text
            eventRender: function(event, el) {
        
                var flag=true;
                var event_found=1;
                var student_found=1;
                var teacher_found=1;
                var search_found=1;
                var date_found=1;
                var location_found=1;
                /* Start datepicker - change date */    
                console.log('rendering...event_id='+event.id);
                var dt=moment(event.start).format('DD/MM/YYYY');
                
                
                //$('#datepicker_month').data("DateTimePicker").date(dt)
                /* END datepicker - change date */
            
                //ProgressIncrement(); //display progress bar
                if (document.getElementById("event_type").value != '0') {
                    console.log('rendering...event_id='+dt);
                    event_found=0;
                    
                    $.each($("#event_type option:selected"), function(){ 
                        var name=$(this).text();
                        console.log('dasda----------------')
                        console.log($(this).text())
                        console.log('adsda----------------')

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
                                if (event.teacher_id.indexOf(id) >= 0){
                                    teacher_found=1;
                                    //break;
                                }                      
                            });
                            }	//no_of_teachers		
                        }
                    }
                }
                /* START listmonth view - display off past dated events */
                var view = $('#calendar').fullCalendar('getView');
                var viewname=view.name;
                if ((viewname == 'listMonth') || (viewname == 'listYear') || (viewname == 'listWeek')){
                    date_found=1;
                    var curdate=new Date();
                    if (moment(event.start).format('YYYYMMDD') < moment(curdate).format('YYYYMMDD') ){
                        
                        date_found = 0;
                    } 
                }		  
                /* END listmonth view - display off past dated events */
            
                var loc_str=document.getElementById("event_location_id").value;
                console.log('event.location='+event.location+' loc_str='+loc_str);
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
                                    if (loc_id.indexOf(id) >= 0){
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



            
                /* search START */ 
                var search_text = $('#search_text').val();
                if ((event_found == 1) && (student_found == 1) && (teacher_found == 1) && (date_found == 1) && (location_found == 1) ) {
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
                console.log('event_id='+event.id+';event_found='+event_found+';student_found='+student_found+';teacher_found='+teacher_found+';date_found='+date_found+';location_found='+location_found+';search_found='+search_found);

                if ((event_found == 1) && (student_found == 1) && (teacher_found == 1) && (search_found == 1) && (date_found == 1) && (location_found == 1) ) 
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
                    
                    var icon ='<span class="fa fa-lock txt-orange"></span>';
                    if (event.is_locked == '1'){        
                        $(el).find('div.fc-content').prepend(icon);
                        
                        lockRecords=1;
                    } else if (event.event_mode == '0'){
                        icon ='<i class="fa fa-file"></i> ';
                    } else{
                        icon='';
                    }
                    prevdt = moment(event.start).format('DD-MM-YYYY');
                   
                    resultHtml+='<tr class="agenda_event_row" href="'+event.url+'">';
                    resultHtml+='<td href="'+event.url+'">'+icon+moment(event.start).format('DD-MM-YYYY')+'</td>';
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
                //el.attr('timetext', event.title);
                //$('#timetext').text(event.cours_name);
                $(el).find('#timetext').append(' '+event.event_type_name);
                return flag;  
            },           

            eventClick: function(event, jsEvent, view) {
                if (event.url) {
                    //SetEventCookies();
                    document.getElementById('edit_view_url').value=event.url;
                    document.getElementById('confirm_event_id').value=event.id;
                    
                    if (event.action_type == 'edit') {
                        $('#event_btn_edit_text').text("{{__('Edit')}}");
                        if (event.can_lock == 'Y') {
                            $('#btn_confirm').show();
                        } else {
                            $('#btn_confirm').hide();
                        }
                        
                    } else {
                        $('#event_btn_edit_text').text("{{__('View')}}");
                        $('#btn_confirm').hide();
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
                console.log('lllll----------------')
                console.log(resultHtml_rows)
                console.log('lllll----------------')
                resultHtml_rows='';
                
                resultHtmlHeader+="</table>";
                resultHtmlHeader+="<script>";
                resultHtmlHeader+="$('#agenda_table').DataTable({";
                resultHtmlHeader+='"stateSave": true,';
                resultHtmlHeader+='"paging":   false,';
                resultHtmlHeader+='"searching": false,';
                resultHtmlHeader+='"ordering": true,';
                resultHtmlHeader+='"info":     false,';
                //resultHtmlHeader+="dom: 'Bfrtip',";       // uncomment to enable datatable export
                //resultHtmlHeader+="buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],"; // uncomment to enable datatable export
                resultHtmlHeader+='"processing": true});';
                resultHtmlHeader+='<\/script>';
                
                $('#agenda_list').html(resultHtmlHeader);


                
                

                resultHtml='';
                document.getElementById("btn_copy_events").style.display = "none";
                document.getElementById("btn_goto_planning").style.display = "none";
                document.getElementById("btn_delete_events").style.display = "none";
                
                var user_role=document.getElementById("user_role").value;
                
                if (foundRecords == 1)
                {
                    if (user_role == 'student') {
                        document.getElementById("btn_copy_events").style.display = "none";        
                    } else {
                        document.getElementById("btn_copy_events").style.display = "block";
                    }
                }

                if ((foundRecords == 1) && (lockRecords == 0))
                {
                    if (user_role == 'student') {
                        document.getElementById("btn_delete_events").style.display = "none";    
                    } else {
                        //Delete button will be visible if events are available and all events are in unlock mode
                        //alert('delete button will visible');
                        document.getElementById("btn_delete_events").style.display = "block";
                    }
                }
                else
                {
                    document.getElementById("btn_delete_events").style.display = "none";
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
                        //SetEventCookies();
                        window.location = $(this).attr('href');    
                    }
                    
                    return false;
                });
                                    
            },
                
            viewRender: function( view, el ) {
                //alert('RerenderEvents events');
                $("#agenda_table tr:gt(0)").remove();
                resultHtml='';
                prevdt='';
                //view change event - here needs to refresh data
                document.getElementById("date_from").value = view.intervalStart.format('YYYY-MM-DD');
                document.getElementById("date_to").value = view.intervalEnd.format('YYYY-MM-DD');
                
                if (document.getElementById("prevnext").value == 'yes'){
                    document.getElementById("view_mode").value = 'list';
                    $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
                    $('#calendar').fullCalendar().find('.fc-day-header').hide();
                }
                else
                {
                    document.getElementById("view_mode").value = view.name;
                }
                if  (firstload != '0'){
                    getFreshEvents();
                }
            }
        })    //full calendar initialization
        CheckPermisson();
        
    } //full calender - RenderCalendar



   

    function DisplayCalendarTitle() {
        var view = $('#calendar').fullCalendar('getView');
        $('#cal_title').text("{{__('Agenda')}} : "+view.title);            
    };

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
        event_type=document.getElementById("copy_event_id").value,
        student_id=document.getElementById("copy_student_id").value,
        teacher_id=document.getElementById("copy_teacher_id").value,
        target_start_date=document.getElementById("date_from").value,
        target_end_date=document.getElementById("date_to").value,
        view_mode = document.getElementById("view_mode").value;
        
        var data='view_mode='+view_mode+'&source_start_date='+source_start_date+'&source_end_date='+source_end_date+'&target_start_date='+target_start_date+'&target_end_date='+target_end_date+'&event_type='+event_type+'&student_id='+student_id+'&teacher_id='+teacher_id;
        //console.log(data);
        //return false;
            e.preventDefault();
			$.ajax({
                type: "POST",
                url: BASE_URL + '/'+school_id+'/copy_paste_events',
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
                        document.getElementById("copy_event_id").value = '';
                        document.getElementById("copy_student_id").value ='';
                        document.getElementById("copy_teacher_id").value = '';					   
                        document.getElementById("copy_view_mode").value = '';
                        document.getElementById("copy_week_day").value = '';
                        document.getElementById("copy_month_day").value = '';
					       
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
		if (loading == 0) {
	
			//document.getElementById("event_type_id").value=getEventIDs();
			//document.getElementById("event_student_id").value=getStudentIDs();
			//document.getElementById("event_teacher_id").value=getTeacherIDs();
			//document.getElementById("event_location_id").value=getLocationIDs();
			//console.log("event_location_id="+document.getElementById("event_location_id").value);
			//if (document.getElementById("event_location_id").value == ''){
			//	document.getElementById("event_location_all_flag").value='0';
			//}
			console.log("LOCATION: event_location_id="+document.getElementById("event_location_id").value);
			
			document.cookie = "event_type_id="+document.getElementById("event_type_id").value+";path=/";
			document.cookie = "event_student_id="+document.getElementById("event_student_id").value+";path=/";
			document.cookie = "event_teacher_id="+document.getElementById("event_teacher_id").value+";path=/";
			document.cookie = "date_from="+document.getElementById("date_from").value+";path=/";
			document.cookie = "date_to="+document.getElementById("date_to").value+";path=/";

			document.cookie = "view_mode="+document.getElementById("view_mode").value+";path=/";        
			
			var cal_view_mode=$('#calendar').fullCalendar('getView');
			console.log("cal_view_mode="+cal_view_mode.name);
			
			if (cal_view_mode.name === undefined) {
					document.cookie = "cal_view_mode="+"agendaWeek"+";path=/";
				}
			else {
				document.cookie = "cal_view_mode="+cal_view_mode.name+";path=/";
				}
			
			document.cookie = "prevnext="+document.getElementById("prevnext").value+";path=/";
		}    
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

window.addEventListener('focus', function (event) {
    console.log('has focus');
	//RerenderEvents();
});

window.addEventListener('blur', function (event) {
    console.log('lost focus');
});






	
</script>
@endsection