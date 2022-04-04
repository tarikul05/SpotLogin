@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<link href="{{ asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{ asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
@endsection

@section('content')
<div class="content agenda_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="agendaForm" name="agendaForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
							<div class="page_header_class">
									<label id="page_header" name="page_header">
										{{__('Agenda')}}: Apr 4 – 10, 2022
									</label>
							</div>
					</div>
					<div class="col-sm-6 col-xs-12 btn-area">
							<div class="pull-right btn-group">
                <input type="input" name="search_text" class="form-control search_text_box" id="search_text" value="" placeholder="Search">
                <a href="#" id="btn_export_events" target="_blank" class="btn btn-theme-outline">
                  <img src="{{ asset('img/excel_icon.png') }}"  width="17" height="auto"/>
                  <span id ="btn_export_events_cap">Excel</span>
                </a>
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
                      <div id="datepicker_month" style1="height:30%;padding:0;width:100%;"></div>
                      <div>
                          <div class="btn-group btn-xs pull-left" style="padding:0;width:100%;"> 
                              
                              <div id="event_location_div" name="event_location_div" class="selectdiv">
                                <select class="form-control" multiple="multiple" id="event_location" name="event_location[]" style="margin-bottom: 15px;" ></select>
                              </div>                                                    


                              <div id="event_type_div" name="event_type_div" class="selectdiv">
                                <select class="form-control" multiple="multiple" id="event_type" name="event_type[]" style="margin-bottom: 15px;" ></select>
                              </div>                                                    
                          
                              <div id="event_student_div" name="event_student_div" class="selectdiv">
                                  <!--<select class="multiple-control" name="event_student" id="event_student" style="margin-bottom: 15px;"></select>-->
                                  <select class="form-control" multiple="multiple" id="event_student" name="event_student[]" style="margin-bottom: 15px;"></select>
                              </div>
                          
                              <div id="event_teacher_div" name="event_teacher_div" class="selectdiv">
                                  <!--<select class="form-control" name="event_teacher" id="event_teacher" style="margin-bottom: 15px;"></select>-->
                                  <select class="form-control" multiple="multiple" id="event_teacher" name="event_teacher[]" style="margin-bottom: 15px;"></select>
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
	$('#datepicker_month').datetimepicker({            
    inline: true,
    locale: lang_id,
    //format: 'DD/MM/YYYY',
    icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    },


    format: "DD/MM/YYYY",
    autoclose: true,
    todayBtn: true,
		minuteStep: 10,
		minView: 2,
		// maxView: 3,
		//viewSelect: 3,
		// todayBtn:false,
    pickTime: false
      
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
    console.log('a) loading='+loading);
    RerenderEvents();
    
		
		
	}); //ready

  function RerenderEvents(){
	  if (loading == 0){ 
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

	
</script>
@endsection