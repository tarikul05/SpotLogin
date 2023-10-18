@extends('layouts.navbar')


@section('head_links')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
<script>


    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'UTC',
        initialView: 'timeGridFourDay',
        height: 500,
        selectable: true,
        initialDate: '<?php echo date("Y-m-d");?>',
        navLinks: true,
        editable: true,
        headerToolbar: {
          left: 'prev,next',
          center: 'title',
          right: 'timeGridDay,timeGridFourDay'
        },
        views: {
          timeGridFourDay: {
            type: 'timeGrid',
            duration: { days: 4 },
            buttonText: '4 day'
          }
        },

      });

      calendar.render();
    });





  </script>
@endsection


@section('content')
<main role="main" class="container-fluid">
<br><br><br>
 <div class="row">
    <div class="col-lg-9">
        <div id='calendar'></div>
    </div>
    <div class="col-lg-3">
        coucou
    </div>
 </div>


  </main>
@endsection


@section('footer_js')

  @endsection
