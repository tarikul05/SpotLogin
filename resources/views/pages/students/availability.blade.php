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

    <h3>Availability : <small>Coming soon</small></h3>

<div class="row justify-content-center pt-5" style="display: none;">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Weekly Availabilities</div>
            <div class="card-body">


                    <form action="{{ route('student.availability.store') }}" method="post">
                    @csrf

                    <input type="hidden" name="is_special" id="is_special" value="0">
                    <input type="hidden" name="day_special" id="day_special" value="">

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

                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="time_of_day">Choose Start time:</label>
                                <select name="start_time" id="start_time" class="form-control">
                                    <option value="6:00 AM">6:00 AM</option>
                                    <option value="7:00 AM">7:00 AM</option>
                                    <option value="8:00 AM">8:00 AM</option>
                                    <option value="9:00 AM">9:00 AM</option>
                                    <option value="10:00 AM">10:00 AM</option>
                                    <option value="11:00 AM">11:00 AM</option>
                                    <option value="12:00 PM">12:00 PM</option>
                                    <option value="1:00 PM">1:00 PM</option>
                                    <option value="2:00 PM">2:00 PM</option>
                                    <option value="3:00 PM">3:00 PM</option>
                                    <option value="4:00 PM">4:00 PM</option>
                                    <option value="5:00 PM">5:00 PM</option>
                                    <option value="6:00 PM">6:00 PM</option>
                                    <option value="7:00 PM">7:00 PM</option>
                                    <option value="8:00 PM">8:00 PM</option>
                                    <option value="9:00 PM">9:00 PM</option>
                                    <option value="10:00 PM">10:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xs-12">
                            <div class="form-group">
                                <label for="time_of_day">Choose End time:</label>
                                <select name="end_time" id="end_time" class="form-control">
                                    <option value="6:00 AM">6:00 AM</option>
                                    <option value="7:00 AM">7:00 AM</option>
                                    <option value="8:00 AM">8:00 AM</option>
                                    <option value="9:00 AM">9:00 AM</option>
                                    <option value="10:00 AM">10:00 AM</option>
                                    <option value="11:00 AM">11:00 AM</option>
                                    <option value="12:00 PM">12:00 PM</option>
                                    <option value="1:00 PM">1:00 PM</option>
                                    <option value="2:00 PM">2:00 PM</option>
                                    <option value="3:00 PM">3:00 PM</option>
                                    <option value="4:00 PM">4:00 PM</option>
                                    <option value="5:00 PM">5:00 PM</option>
                                    <option value="6:00 PM">6:00 PM</option>
                                    <option value="7:00 PM">7:00 PM</option>
                                    <option value="8:00 PM">8:00 PM</option>
                                    <option value="9:00 PM">9:00 PM</option>
                                    <option value="10:00 PM">10:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-primary">Add</button>
                </form>




            </div>
        </div>
    </div>


    @php
    $groupedAvailabilities = [];

    foreach($availabilities as $availability) {
        if ($availability->is_special == 0) {
            $dayOfWeek = \Carbon\Carbon::parse($availability->day_of_week)->format('l');

            if (!isset($groupedAvailabilities[$dayOfWeek])) {
                $groupedAvailabilities[$dayOfWeek] = [];
            }

            $groupedAvailabilities[$dayOfWeek][] = [
                'start_time' => date('H:i', strtotime($availability->start_time)),
                'end_time' => date('H:i', strtotime($availability->end_time)),
                'availability_id' => $availability->id,
            ];
        }
        }
    @endphp

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Weekly Availabilities</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($availabilities->isEmpty())
                    <tr>
                        <td colspan="3">No availabilities found</td>
                    </tr>
                    @endif
                    @foreach($availabilities as $availability)
                    @if($availability->is_special == 0)
                    <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($availability->day_of_week)->format('l') }}
                    <br>
                    <a href="#">[ Add hours ]</a>
                    </td>
                    <td class="text-center">{{ date('H:i', strtotime($availability->start_time)) }} - {{ date('H:i', strtotime($availability->end_time)) }}</td>
                    <td width="60px" class="text-center">
                            <form action="{{ route('student.availability.destroy', $availability) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-md"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>

</div>





<br>





<div class="row justify-content-center pt-5 mb-5" style="display: none;">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Dates Availabilities</div>
            <div class="card-body p-3">

            <div id="calendar"></div>

            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Dates Availabilities</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($availabilities->isEmpty())
                    <tr>
                        <td colspan="3">No availabilities found</td>
                    </tr>
                    @endif
                    @foreach($availabilities as $availability)
                    @if($availability->is_special == 1)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($availability->day_special)->format('d M, Y') }}</td>
                        <td class="text-center">{{ date('H:i', strtotime($availability->start_time)) }} - {{ date('H:i', strtotime($availability->end_time)) }}</td>
                        <td width="60px" class="text-center">
                            <form action="{{ route('student.availability.destroy', $availability) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-md"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endif
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
        @if($availability->is_special == 1)
        events.push({
            title: '{{ $availability->title }}',
            start: moment('{{ $availability->day_special }} {{ $availability->start_time }}').format(),
            end: moment('{{ $availability->day_special }} {{ $availability->end_time }}').format(),
        });
        @endif
    @endforeach

    $('#calendar').fullCalendar({
        eventLimit: 3, // If you set a number it will hide the itens
        eventLimitText: "More", // Default is `more` (or "more" in the lang you pick in the option)
        slotDuration: '00:15:00',
        defaultDate: moment(),
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
                    <label for="start-time2">Choose Start time:</label><br>
                    <select id="start-time2" class="swal-select">
                        ${generateSelectOptions(timeOptions)}
                    </select>
                </div>
                <br>
                <div class="swal-column">
                    <label for="end-time2">Choose End time:</label><br>
                    <select id="end-time2" class="swal-select">
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
            const startTime = $('#start-time2').val();
            const endTime = $('#end-time2').val();

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
                    day_special: date.format('YYYY-MM-DD'),
                    day_of_week: date.format('dddd'),
                    start_time: startTime,
                    end_time: endTime,
                    is_special:true
                },
                success: function(data) {
                    console.log('Success', data);
                    if(data.success){
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
