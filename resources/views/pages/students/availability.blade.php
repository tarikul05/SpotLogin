@extends('layouts.main')
@section('head_links')
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
<link href="{{ asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
<link href="{{ asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
<style>
    #calendar .fc-header-toolbar .fc-left h2 {
    font-size: 18px;
    padding:8px;
    font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="container">

<div class="row justify-content-center pt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Availabilities</div>
            <div class="card-body">


                <div id="calendar"></div>

                <!--
                    <form action="{{ route('student.availability.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="day_of_week">Choose a Day:</label>
                        <select name="day_of_week" class="form-control">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                @if(!$availabilities->contains('day_of_week', $day))
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="time_of_day">Choose Time of Day:</label>
                        <select name="time_of_day" class="form-control">
                            <option value="AM">Morning</option>
                            <option value="PM">Afternoon</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            -->

            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card">
            <div class="card-header">My Current Availabilities</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($availabilities->isEmpty())
                    <tr>
                        <td colspan="3">No availabilities found</td>
                    </tr>
                    @endif
                    @foreach($availabilities as $availability)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($availability->day_of_week)->format('d M, Y') }}</td>
                        <td class="text-center">{{ date('H:i', strtotime($availability->start_time)) }} - {{ date('H:i', strtotime($availability->end_time)) }}</td>
                        <td width="60px" class="text-center">
                            <form action="{{ route('student.availability.destroy', $availability) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-md"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</div>




</div>

@endsection

@section('footer_js')
<script type="text/javascript">
    $(function() {


        var events = [];

@foreach($availabilities as $availability)
    events.push({
        title: '{{ $availability->title }}',
        start: moment('{{ $availability->day_of_week }} {{ $availability->time_of_day }}').format(),
        end: moment('{{ $availability->day_of_week }} {{ $availability->end_time }}').format(),
    });
@endforeach

        $('#calendar').fullCalendar({
            eventLimit: 3, // If you set a number it will hide the itens
            eventLimitText: "More", // Default is `more` (or "more" in the lang you pick in the option)
			slotDuration: '00:15:00',
            defaultDate: (getCookie("date_from")) ? getCookie("date_from") : moment(curdate).format("YYYY-MM-DD"),
            utc: false,
            editable: false,
            selectable: true,
            events: events,


            eventRender: function(event, element) {
                var startTime = moment(event.start).format('h:mm A');
                var endTime = moment(event.end).format('h:mm A');
                element.find('.fc-title').html(startTime + ' - ' + endTime);
            },

            dayClick: function(date, jsEvent, view, resource) {


    const timeOptions = {
        '06:00': '6:00 AM',
        '07:00': '7:00 AM',
        '08:00': '8:00 AM',
        '09:00': '9:00 AM',
        '10:00': '10:00 AM',
        '11:00': '11:00 AM',
        '12:00': '12:00 PM',
        '13:00': '1:00 PM',
        '14:00': '2:00 PM',
        '15:00': '3:00 PM',
        '16:00': '4:00 PM',
        '17:00': '5:00 PM',
        '18:00': '6:00 PM',
        '19:00': '7:00 PM',
        '20:00': '8:00 PM',
        '21:00': '9:00 PM',
        '22:00': '10:00 PM',
    };

    Swal.fire({
        title: 'Add Availability for ' + date.format('DD/MM/YYYY'),
        html: `
            <div class="swal-container">
                <div class="swal-column">
                    <label for="start-time">Choose Start time:</label>
                    <select id="start-time" class="swal-select">
                        ${generateSelectOptions(timeOptions)}
                    </select>
                </div>
                <div class="swal-column">
                    <label for="end-time">Choose End time:</label>
                    <select id="end-time" class="swal-select">
                        ${generateSelectOptions(timeOptions)}
                    </select>
                </div>
            </div>
        `,
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const startTime = $('#start-time').val();
            const endTime = $('#end-time').val();

            if (!startTime || !endTime) {
                Swal.showValidationMessage('Please choose both start and end times');
            }

            return { startTime, endTime };
        },
    }).then((result) => {
        if (!result.dismiss) {
            const { startTime, endTime } = result.value;

            $.ajax({
                url: "{{ route('student.availability.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    day_of_week: date.format('YYYY-MM-DD'),
                    start_time: startTime,
                    end_time: endTime,
                },
                success: function(data) {
                    console.log('Success', data);
                    if(data.success){
                        //reload page
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log('Error', error);
                },
            });
        }
    });
}

        });


    });


    function generateSelectOptions(options) {
    let html = '';
    for (const key in options) {
        if (options.hasOwnProperty(key)) {
            html += `<option value="${key}">${options[key]}</option>`;
        }
    }
    return html;
}

        </script>
@endsection
