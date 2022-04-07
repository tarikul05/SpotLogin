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


<script src="{{ asset('js/fullcalendar.js')}}"></script>
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
            <input type="hidden" name="user_role" id="user_role" value="{{$user_role}}">                                        
              

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
                      <div id="datepicker_month" style1="height:30%;padding:0;width:100%;"></div>
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

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<!-- ================================= -->
<!-- starting calendar related jscript -->
<!-- ================================= -->
<script>
    var loading=1;
    var lang_id='fr';
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var user_role=document.getElementById("user_role").value;

    var json_events = @json($events);
   
    var defview='agendaWeek';   //'month';//'agendaWeek'
    var v_calc_height=((screen.height/100)*50.00);
    var currentTimezone = 'local';
    var currentLangCode = 'fr';
	$('#datepicker_month').datetimepicker({            
        inline: false,
        locale: lang_id,
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
        todayBtn: true,
        minuteStep: 10,
        minView: 2,
        pickTime: false,
        todayBtn: false 
      
    });
    moment.locale(lang_id, {
	  week: { dow: 1 } // Monday is the first day of the week
	});
    $('#search_text').on('input', function(){
		var search_text=$(this).val();
		if (search_text.length > 0){
			$('#calendar').fullCalendar('rerenderEvents');
		}
    });
    // $("#datepicker_month").on("dp.change", function() {
    //     var dt=$('#datepicker_month').data('DateTimePicker').date();
    //     dt=dt.format('YYYY-MM-DD');
    //     $('#calendar').fullCalendar( 'gotoDate', dt);
    //     //alert(dt);
    // });
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

	$(document).ready(function(){
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

        var menuHtml='';
        $("#event_type option").each(function(key,value)
        {
            
            if ( (value.value == 51) && (user_role == 'student') ){
                menuHtml+='<a title="" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px 0 0 4px!important;" href="../admin/events_entry.html?event_type='+value.value+'&action=new"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a>';
                menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }
            
            // cours - events - PopulateButtonMenuList
            if ( (value.value == 10) && (user_role != 'student') ){
                menuHtml+='<a title="" class="btn btn-theme-success dropdown-toggle btn-add-event" style="border-radius:4px 0 0 4px!important;" href="../admin/events_entry.html?event_type='+value.value+'&action=new"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a>';
                menuHtml+='<button title="" type="button" class="btn btn-theme-success dropdown-toggle" style="margin-left:0!important;height:35px;border-radius:0 4px 4px 0!important;" data-toggle="dropdown">';
                menuHtml+='<span class="caret"></span><span class="sr-only">Plus...</span></button>' ;
                menuHtml+='<ul class="dropdown-menu" role="menu">';                            
            }        
            if ( (user_role == 'schooladmin') || (user_role == 'superadmin') || (user_role == 'webmaster') || (user_auth == "ALL") ) {
                if (value.id != 10) {
                    menuHtml+='<li><a  href="../admin/events_entry.html?event_type='+value.value+'&action=new"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
                }
                
            }else if ( (user_role == 'teacher') && ((user_auth == "MED") || (user_auth == "MIN")) && ((value.value == 100) || (value.value == 50)) ) {
                menuHtml+='<li><a  href="../admin/events_entry.html?event_type='+value.value+'&action=new"><i class="glyphicon glyphicon-plus"></i>Add '+value.text+'</a></li>';
            }
            
             
            // Add $(this).val() to your list
        });
        menuHtml+='</ul>';
        $('#button_menu_div').append(menuHtml); 
    
		
		
	}); //ready

    function RerenderEvents(){
	    if (loading == 0){ 
            console.log('sss');
            $("#agenda_table tr:gt(0)").remove();
            $('#calendar').fullCalendar('rerenderEvents');								
        }
    }


    function getFreshEvents(p_view=''){
        //alert('getrefresh..: Start');
        var start_date=document.getElementById("date_from").value;
        var end_date=document.getElementById("date_to").value;
        
        
        document.getElementById("prevnext").value = '';
        $.ajax({
        url: 'agenda_data.php',
        type: 'POST', 
        data: 'type=fetch&start_date='+start_date+'&end_date='+end_date+'&zone='+zone+'&p_view='+p_view,
        async: false,
        success: function(s){
                json_events = s;
        },
        error: function(ts) { 
            errorModalCall('getFreshEvents:'+ts.responseText+' '+GetAppMessage('error_message_text'));
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
            //events: json_events,	  
			events: JSON.parse(json_events),
			utc: false,            
            defaultView: defview,
            
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
			defaultTimedEventDuration: '00:30:00',
			forceEventDuration: true,
			nextDayThreshold: '00:00',
            nowIndicator: true,
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
                
                // //$('#datepicker_month').data("DateTimePicker").date(dt)
                // /* END datepicker - change date */
            
                // //ProgressIncrement(); //display progress bar
                // if (document.getElementById("event_type").value != '0') {
                //     event_found=0;
                //     $.each($("#event_type option:selected"), function(){ 
                //         var name=$(this).text();
                //         if (event.event_type_name.indexOf(name) >= 0){
                //             event_found=1;
                //             //break;
                //         }                      
                        
                //     });
                // }                
                // // event_type=50 - teacher's vacation
                // //incase teacher's vacation student will not be checked
                // if (event.event_type != 50) {
                //     if (document.getElementById("event_student_id").value == '') { 
                //         student_found=0;
                //     }
                //     else {
                        
                //             if (document.getElementById("event_student_id").value !='0') {
                //                 student_found=0;
                //                 $.each($("#event_student option:selected"), function(){ 
                //                     var id=$(this).val();
                //                     if (event.student_id_list.indexOf(id) >= 0){
                //                         student_found=1;
                //                         //break;
                //                     }
                //                     });
                //             }
                        
                //     }
                // }	//event_type <> 50
                
                // // event_type=51 - student's vacation
                // //incase student's vacation student will not be checked
                // if (event.event_type != 51) {
                //     if (document.getElementById("event_teacher_id").value == '') { 
                //         teacher_found=0;
                //     }
                //     else {
                //         if (document.getElementById("event_teacher_id").value !='0') {
                //             if (no_of_teachers != 1){ 
                //             teacher_found=0;
                //             $.each($("#event_teacher option:selected"), function(){ 
                //                 var id=$(this).val();
                //                 if (event.teacher_id.indexOf(id) >= 0){
                //                     teacher_found=1;
                //                     //break;
                //                 }                      
                //             });
                //             }	//no_of_teachers		
                //         }
                //     }
                // }
                // /* START listmonth view - display off past dated events */
                // var view = $('#calendar').fullCalendar('getView');
                // var viewname=view.name;
                // if ((viewname == 'listMonth') || (viewname == 'listYear') || (viewname == 'listWeek')){
                //     date_found=1;
                //     var curdate=new Date();
                //     if (moment(event.start).format('YYYYMMDD') < moment(curdate).format('YYYYMMDD') ){
                        
                //         date_found = 0;
                //     } 
                // }		  
                // /* END listmonth view - display off past dated events */
            
                // var loc_str=document.getElementById("event_location_id").value;
                // console.log('event.location='+event.location+' loc_str='+loc_str);
                // if (loc_str == '') {
                //     location_found=0;
                // }
                // else {
                //         if (loc_str.substring(0, 1) !='0') {
                //             //if (no_of_teachers != 1){ 
                //             location_found=0;
                //             $.each($("#event_location option:selected"), function(){ 
                //                 var id=$(this).val();
                //                 var loc_id=event.location;
                //                 if (event.location == null){
                //                     location_found=0;
                //                 }
                //                 else {
                //                     try {
                //                         if (loc_id.indexOf(id) >= 0){
                //                             location_found=1;
                //                         }
                //                     }
                //                     catch (e){
                //                         location_found=0;
                //                         }
                //                 }
                //             });
                //             //}	//no_of_teachers		
                //         }		
                // }



            
                // /* search START */ 
                // var search_text = $('#search_text').val();
                // if ((event_found == 1) && (student_found == 1) && (teacher_found == 1) && (date_found == 1) && (location_found == 1) ) {
                //     if (search_text.length > 2){
                //         search_found=0;
                //         //if ((event.tooltip.toLowerCase().indexOf(search_text) >= 0) || (event.tooltip.toLowerCase().indexOf(search_text) >= 0)) {
                //         //if (event.tooltip.toLowerCase().indexOf(search_text) >= 0) {
                //         if (event.text_for_search.indexOf(search_text) >= 0) {
                //             //if (event.tooltip.indexOf(search_text) >= 0) {
                //         search_found=1;
                //         //flag=true; 
                //         } else {
                //             search_found=0;
                //             //flag=false;
                //         }
                //     }
                // } // 
                // /* search END */
                // console.log('event_id='+event.id+';event_found='+event_found+';student_found='+student_found+';teacher_found='+teacher_found+';date_found='+date_found+';location_found='+location_found+';search_found='+search_found);

                // if ((event_found == 1) && (student_found == 1) && (teacher_found == 1) && (search_found == 1) && (date_found == 1) && (location_found == 1) ) 
                // {
                //     flag = true;
                // } else {
                //     flag = false;
                // }

                // if (flag == true){
                    
                //     stime=moment(event.start).format('HH:mm');
                //     etime=moment(event.end).format('HH:mm');
                //         if (moment(event.end).isValid() == false){
                //             etime=stime;
                //         }
                //     foundRecords=1; //found valid record;
                //     //lockRecords=0;
                    
                //     //locked event icon
                //     //add icon first line of events
                //     //var icon ='<img src="../images/icons/locked.gif" width="12" height="12"/>';
                    
                //     var icon ='<span class="fa fa-lock txt-orange"></span>';
                //     //$(el).find('.fc-title').append("<br/>" + event.description); 
                //     if (event.is_locked == '1'){        
                //     //icon ='<img src="../images/icons/locked.gif" width="12" height="12"/>';
                //     $(el).find('div.fc-content').prepend(icon);
                //     //$(el).find('div.fc-title').append("<br/>"); 
                //     /*
                //     if (event.allDay) {
                //             $(el).find('div.fc-content').prepend(icon);
                //     } else {
                //             $(el).find('.fc-time').prepend(icon);
                //     }*/
                //         lockRecords=1;
                //         //$(el).find('.fc-time').prepend(icon);
                //     } else if (event.event_mode == '0'){
                        
                //         //icon ='<img src="../images/icons/draft.png" width="12" height="12"/>';
                //         icon ='<i class="fa fa-file"></i> ';
                //         //$(el).find('.fc-time').prepend(icon);
                //     } else{
                //         icon='';
                //     }
                //     //group header
                //     /* commented group header as requested by Matt on 02-Aug issue log# 10.2                        
                //     if (prevdt != moment(event.start).format('DD-MM-YYYY') )
                //     {
                //         //class="form-group"
                //         resultHtml+='<b><tr class="agenda_list_header"><td colspan="7">Date: '+moment(event.start).format('dddd DD-MMMM-YYYY',currentLangCode)+'</tr>';
                //     }
                //     */
                //     prevdt = moment(event.start).format('DD-MM-YYYY');
                //     //event_img_id="event_img_"+moment(event.start).format('YYYYMMDD');
                //     //$("#"+event_img_id).show();
                //     //$("#"+event_img_id).css('display','block');
                    
                //     //populate agenda_table - soumen
                //     //resultHtml+='<tr onClick="OpenEvent()" class="agenda_event_row" href="'+event.url+'">';
                    
                //     resultHtml+='<tr class="agenda_event_row" href="'+event.url+'">';
                //     //onClick="OpenEvent()"
                //     resultHtml+='<td href="'+event.url+'">'+icon+moment(event.start).format('DD-MM-YYYY')+'</td>';
                //     //resultHtml+='<td>'+stime+' - '+etime+'</td>';
                //     resultHtml+='<td>'+stime+'</td>';
                //     resultHtml+='<td>'+etime+'</td>';
                //     if ( event.no_of_students <= 1 ){
                //         resultHtml+='<td>'+event.no_of_students+' :</td>';
                //     }else{
                //         resultHtml+='<td>'+event.no_of_students+' :</td>';
                //     }
                //     resultHtml+='<td>'+event.title+'</td>';
                //     resultHtml+='<td>'+event.cours_name+'</td>';
                //     resultHtml+='<td>'+event.duration_minutes+' minutes</td>';
                //     resultHtml+='<td>'+event.teacher_name+'</td>';
                //     resultHtml+='</tr>';
                
                // }
                // resultHtml_rows=resultHtml;
                // el.attr('title', event.tooltip);
                // //el.attr('timetext', event.title);
                // //$('#timetext').text(event.cours_name);
                // $(el).find('#timetext').append(' '+event.event_type_name);
                
                // return flag;                
                    
            },           

            eventClick: function(event, jsEvent, view) {
                if (event.url) {
                    //alert(event.url);
                    SetEventCookies();
                    //commented by soumen on 15-Jun fr phase 2 changes
                    //window.location(event.url);
                    
                    //console.log($(this).getBoundingClientRect());
                    document.getElementById('edit_view_url').value=event.url;
                    document.getElementById('confirm_event_id').value=event.event_auto_id;
                    //if (event.is_locked == 0) {
                    if (event.action_type == 'edit') {
                        $('#event_btn_edit_text').text(GetAppMessage('event_btn_edit_text'));
                        if (event.can_lock == 'Y') {
                            $('#btn_confirm').show();
                        } else {
                            $('#btn_confirm').hide();
                        }
                        
                    } else {
                        $('#event_btn_edit_text').text(GetAppMessage('event_btn_view_text'));
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

            // eventAfterAllRender: function() {
            //     DisplayCalendarTitle();
            //     var resultHtmlHeader='';
            //     //add header
            //     /* commened header by soumen */
                
            //     row_hdr_date=$("#row_hdr_date").text();
            //     row_hdr_start_time=$("#row_hdr_start_time").text();
            //     row_hdr_end_time=$("#row_hdr_end_time").text();
            //     row_hdr_no_of_students=$("#row_hdr_no_of_students").text();
            //     row_hdr_student_name=$("#row_hdr_student_name").text();
            //     row_hdr_course=$("#row_hdr_course").text();
            //     row_hdr_duration_id=$("#row_hdr_duration_id").text();
            //     row_hdr_teacher_id=$("#row_hdr_teacher_id").text();
                
            //     resultHtmlHeader+='<table id="agenda_table" name="agenda_table" cellpadding="0" cellspacing="0" width="99%" class="agenda_table_class tablesorter">';
            //     resultHtmlHeader+='<thead>';
            //     resultHtmlHeader+='<tr>';
            //     resultHtmlHeader+='<th width="12%">'+row_hdr_date+'</th>';
            //     resultHtmlHeader+='<th width="6%">'+row_hdr_start_time+'</th>';
            //     resultHtmlHeader+='<th width="6%">'+row_hdr_end_time+'</th>';
            //     resultHtmlHeader+='<th width="10%">'+row_hdr_no_of_students+'</th>';
            //     resultHtmlHeader+='<th width="19%">'+row_hdr_student_name+'</th>';
            //     resultHtmlHeader+='<th width="19%">'+row_hdr_course+'</th>';
            //     resultHtmlHeader+='<th width="10%">'+row_hdr_duration_id+'</th>';                
            //     resultHtmlHeader+='<th width="8%">'+row_hdr_teacher_id+'</th>';
            //     resultHtmlHeader+='</tr>';
            //     resultHtmlHeader+='</thead>';
                
            //     //resultHtmlHeader+=resultHtml;
            //     resultHtmlHeader+=resultHtml_rows;
            //     resultHtml_rows='';
                
            //     resultHtmlHeader+="</table>";
            //     resultHtmlHeader+="<script>";
            //     resultHtmlHeader+="$('#agenda_table').DataTable({";
            //     resultHtmlHeader+='"stateSave": true,';
            //     resultHtmlHeader+='"paging":   false,';
            //     resultHtmlHeader+='"searching": false,';
            //     resultHtmlHeader+='"ordering": true,';
            //     resultHtmlHeader+='"info":     false,';
            //     //resultHtmlHeader+="dom: 'Bfrtip',";       // uncomment to enable datatable export
            //     //resultHtmlHeader+="buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],"; // uncomment to enable datatable export
            //     resultHtmlHeader+='"processing": true});';
            //     resultHtmlHeader+='<\/script>';
                
            //     $('#agenda_list').html(resultHtmlHeader);
            //     //$('#agenda_table').append(resultHtmlHeader); //Fill Events
            //     //$("#agenda_table").children().html(resultHtmlHeader);
            //     //$("#agenda_table > tbody").html(resultHtmlHeader);
                
            //     resultHtml='';
            //     document.getElementById("btn_copy_events").style.display = "none";
            //     document.getElementById("btn_goto_planning").style.display = "none";
            //     document.getElementById("btn_delete_events").style.display = "none";
                
            //     var user_role=document.getElementById("user_role").value;
                
            //     if (foundRecords == 1)
            //         {
            //             if (user_role == 'student') {
            //                 document.getElementById("btn_copy_events").style.display = "none";        
            //             } else {
            //                 document.getElementById("btn_copy_events").style.display = "block";
            //             }
            //         }

            //     if ((foundRecords == 1) && (lockRecords == 0))
            //         {
            //             if (user_role == 'student') {
            //                 document.getElementById("btn_delete_events").style.display = "none";    
            //             } else {
            //                 //Delete button will be visible if events are available and all events are in unlock mode
            //                 //alert('delete button will visible');
            //                 document.getElementById("btn_delete_events").style.display = "block";
            //             }
            //         }
            //     else
            //     {
            //         document.getElementById("btn_delete_events").style.display = "none";
            //     }
            //     lockRecords=0;
                    
            //     var view = $('#calendar').fullCalendar('getView'); 
            //     if ((foundRecords == 0) && (document.getElementById("copy_date_from").value.length != 0) 
            //             && (document.getElementById("copy_view_mode").value == view.name) )
            //         {
            //         document.getElementById("btn_goto_planning").style.display = "block";
            //         }
                    
            //     foundRecords=0;
                
            //     CheckPermisson();
                
            //     $('#agenda_table tr').click(function(){
                    
            //         //alert('agenda_table tr Render soumen');
                    
            //         //var x=$(this).attr('href');
            //         if ((typeof $(this).attr('href') === "undefined")) {
            //             return false;
            //         }
            //         //alert(x);
            //         if ($(this).attr('href') != "") {
            //             SetEventCookies();
            //             window.location = $(this).attr('href');    
            //         }
                    
            //         return false;
            //     });
                                    
            // },
                
            // viewRender: function( view, el ) {
            //     //alert('RerenderEvents events');
            //     $("#agenda_table tr:gt(0)").remove();
            //     resultHtml='';
            //     prevdt='';
            //     //view change event - here needs to refresh data
            //     document.getElementById("date_from").value = view.intervalStart.format('YYYY-MM-DD');
            //     document.getElementById("date_to").value = view.intervalEnd.format('YYYY-MM-DD');
                
            //     if (document.getElementById("prevnext").value == 'yes'){
            //         document.getElementById("view_mode").value = 'list';
            //         $('#calendar').fullCalendar().find('.fc-day-header').parents('table').hide();
            //         $('#calendar').fullCalendar().find('.fc-day-header').hide();
            //     }
            //     else
            //     {
            //         document.getElementById("view_mode").value = view.name;
            //     }
            //     if  (firstload != '0'){
            //         getFreshEvents();
            //     }
            // },
            eventDidMount: info => {
                info.el.addEventListener('contextmenu', (ev) => {
                    ev.preventDefault();
                    this.$refs.copyMenu.open(ev, info.event)
                    return false;
                }, false);
            }
            

        })    //full calendar initialization
        
    } //full calender - RenderCalendar



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
            // onChange: function(option, checked) {
            //         //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
            //         console.log('Event changed triggered!');
            //         document.getElementById("event_type_id").value=getEventIDs();
            //         document.getElementById("event_type_all_flag").value='0';
            //         SetEventCookies();
            //         RerenderEvents();
            // },
            // onSelectAll: function (option,checked) {
            //         document.getElementById("event_type_id").value='0';
            //         document.getElementById("event_type_all_flag").value='1';
            //         SetEventCookies();
            //         RerenderEvents();
            // },
            // onDeselectAll: function(option,checked) {
            //     console.log('Event onDeSelectAll triggered!');
            //         //alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
            //         document.getElementById("event_type_id").value=getEventIDs();
            //         document.getElementById("event_type_all_flag").value='0';
            //         SetEventCookies();
            //         RerenderEvents();
            //     },
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
            SetEventCookies();
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
            SetEventCookies();
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
            // onChange: function(option, checked) {
            //     console.log('onChange location triggered!');
            //     document.getElementById("event_location_id").value=getLocationIDs();
            //     document.getElementById("event_location_all_flag").value='0';
            //     SetEventCookies();
            //     RerenderEvents();
            // },
            // onSelectAll: function (options,checked) {
            //     if (options){
            //         console.log('location onSelectAll triggered!'+options);
            //         document.getElementById("event_location_id").value='0';
            //         document.getElementById("event_location_all_flag").value='1';
            //         }
            //     else {
            //         console.log('location onDeSelectAll triggered!');
            //         document.getElementById("event_location_id").value='';
            //         document.getElementById("event_location_all_flag").value='0';
                
            //         }
            //     //SetEventCookies();
            //     RerenderEvents();
            // },
            // onDeselectAll: function() {
            //     console.log('NOT WORKING location onDeSelectAll triggered!');
            //     document.getElementById("event_location_id").value='';
            //     document.getElementById("event_location_all_flag").value='0';
            //     SetEventCookies();
            //     RerenderEvents();
            // },
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
            // onChange: function(option, checked) {
            //     console.log('onChange location triggered!');
            //     document.getElementById("event_location_id").value=getLocationIDs();
            //     document.getElementById("event_location_all_flag").value='0';
            //     SetEventCookies();
            //     RerenderEvents();
            // },
            // onSelectAll: function (options,checked) {
            //     if (options){
            //         console.log('location onSelectAll triggered!'+options);
            //         document.getElementById("event_location_id").value='0';
            //         document.getElementById("event_location_all_flag").value='1';
            //         }
            //     else {
            //         console.log('location onDeSelectAll triggered!');
            //         document.getElementById("event_location_id").value='';
            //         document.getElementById("event_location_all_flag").value='0';
                
            //         }
            //     //SetEventCookies();
            //     RerenderEvents();
            // },
            // onDeselectAll: function() {
            //     console.log('NOT WORKING location onDeSelectAll triggered!');
            //     document.getElementById("event_location_id").value='';
            //     document.getElementById("event_location_all_flag").value='0';
            //     SetEventCookies();
            //     RerenderEvents();
            // },
             selectAllValue: 0
        });
 
         $('#event_student').multiselect('selectAll', false);   
         $('#event_student').multiselect('refresh');	
                  
    }   // populate event type


    function DisplayCalendarTitle() {
        var view = $('#calendar').fullCalendar('getView');
        $('#cal_title').text("{{__('Agenda')}} : "+view.title);            
    };
	
</script>
@endsection