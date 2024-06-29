@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<link href="{{ asset('css/datetimepicker-lang/bootstrap-datetimepicker.css')}}" rel="stylesheet">
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data-10-year-range.js" integrity="sha512-QSV7x6aYfVs/XXIrUoerB2a7Ea9M8CaX4rY5pK/jVV0CGhYiGSHaDCKx/EPRQ70hYHiaq/NaQp8GtK+05uoSOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/fullcalendar.js')}}"></script>
<link href="{{ asset('css/admin_main_style.css')}}" rel='stylesheet' />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h5>{{ __('Import Agenda') }}</h5>

            @php
                $deleteImportUrl = route('import.deleteLessons');
                $addAllLessonUrl = route('import.addAllLessons');
            @endphp 

            @if($counter > 0)

                <h6><small style="color:#0075bf">{{ $counter }} {{ $counter > 1 ? 'lessons':'lesson' }} ready to add to your agenda</small></h6>
                Select {{ $counter > 1 ? 'each lesson':'lesson' }} to configure or add all at once.<br>

                <form class="form-horizontal" id="add_lesson" method="post" name="add_lesson" action="{{ route('import.addLesson') }}" role="form">
                    @csrf

                    <table class="table table-bordered table-hover mt-3" style="table-layout: auto; width: auto;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Count students</th>
                                <th>Students names</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Coach</th>
                                <th style="text-align: center;">Imported</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                            <tr data-date="{{ $row->date }}" id="row_{{$row->id}}" style="cursor:pointer;"
                                data-id="{{$row->id}}"
                                data-start-time="{{ $row->start_time }}"
                                data-end-time="{{ $row->end_time }}"
                                data-count-students="{{ $row->count_students }}"
                                data-students-names="{{ $row->students_names }}"
                                data-title="{{ $row->title }}"
                                data-duration="{{ $row->duration }}"
                                data-coach="{{ $row->coach }}"
                                data-category="{{ $row->category }}"
                                data-students="{{ $row->students }}"
                                data-imported="{{ $row->imported }}">
                                <td>{{$row->date}}</td>
                                <td>{{$row->start_time}}</td>
                                <td>{{$row->end_time}}</td>
                                <td>{{$row->count_students}}</td>
                                <td>{{$row->students_names}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{$row->duration}}</td>
                                <td>{{$row->coach}}</td>
                                <td style="text-align: center;">{{$row->imported ? 'Yes':'No'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                <button class="btn btn-lg btn-outline-danger" id="deleteAll">Delete all</button>
                <button class="btn btn-lg btn-primary" id="addAll">Add {{ $counter > 1 ? 'all lessons':'lesson' }}</button>
            @else
            Nothing to import.
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade login" id="addAgendaModal" tabindex="-1" role="dialog" aria-labelledby="addAgendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header text-white" style="background-color: #152245;">
                <h6 class="modal-title page_header_class">
                  <i class="fa-regular fa-calendar-plus"></i> {{ __('Finish your import') }}
                </h6>
                <button type="button" class="close" id="modalClose" class="btn btn-light" data-bs-dismiss="modal" style="padding-top:24px; background-color: transparent; text-decoration: none; border:none; font-size:20px;">
                    <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
                </button>
            </div>

            <!--<div class="modal-header d-block text-center border-0">
                <h3 class="modal-title light-blue-txt gilroy-bold" id="signupModalLabel">{{ __('Finish your import') }}</h3>
                <p class="mb-0"><small>{{ __('Please confirm the required informations') }}</small></p>
                <a href="#" class="close" id="modalClose" data-bs-dismiss="modal" style="position: absolute; right: 10px; top: 10px; border-radius:50%!important; padding:3px; font-size:23px;">
                    <i class="fa-solid fa-circle-xmark fa-lg" style="color:#0075bf;"></i>
                </a>
            </div>-->
            <div class="modal-body" id="modal-body" style="padding:50px;">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="addLesson" style="background-color: #0075bf; border-color: #0075bf;">{{ __('Add Lesson') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_js')
<script>
    let selectedRowData = {};

    function showModal(content) {
        $('#modal-body').html(content);
        $('#addAgendaModal').addClass('d-none');
        $('#addAgendaModal').modal('show');
        $('#addAgendaModal').on('shown.bs.modal', function () {
            $('#addAgendaModal').removeClass('d-none');
        });
    }

    $('#addAll').on('click', function() {
        setTimeout(() => {
            window.location.href = "{{ $addAllLessonUrl }}";
        }, 100);
    });

    $('#deleteAll').on('click', function() {

        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
        title: "Are you sure you want to delete all import?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            $('#pageloader').fadeIn();
            setTimeout(() => {
                window.location.href = "{{ $deleteImportUrl }}";
            }, 1000);
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {}
        });

    });

    $('tbody').on('click', 'tr', function() {
        let row = $(this);
        let importedStatus = row.find('td:eq(8)').text().trim();

        if (importedStatus === 'Yes') {
            return;
        }

        selectedRowData = {
            id: row.data('id'),
            date: row.data('date'),
            start_time: row.data('start-time'),
            end_time: row.data('end-time'),
            count_students: row.data('count-students'),
            students_names: row.data('students-names'),
            title: row.data('title'),
            duration: row.data('duration'),
            coach: row.data('coach'),
            category: row.data('category'),
            students: row.data('students'),
            imported: row.data('imported')
        };

        var studentsContent = '<div class="text-center pt-2"><h6><span style="color:#0075bf;">New lesson for ' + selectedRowData.date + '</span><br>between ' + selectedRowData.start_time + ' and ' + selectedRowData.end_time + '</h6></div><br>';
        studentsContent += '<div class="card bg-tertiary hide_coach_off p-3" style="border-color:#b3d6ec;">';
        studentsContent += '<p><label for="new_date">Select student(s):</label> <span style="font-size:11px;">(select: ' + selectedRowData.students_names + ')</span></p>';
        studentsContent += '<div class="student_list"><div class="input-group"><span class="input-group-addon"><i class="fa-solid fa-users"></i></span>';
        studentsContent += '<select id="studentSelect" class="multiselect" multiple="multiple">';
        @foreach ($students as $student)
        studentsContent += '<option value="{{ $student->student_id }}">{{ $student->nickname }}</option>';
        @endforeach
        studentsContent += '</select>';
        studentsContent += '</div></div>';
        studentsContent += '</div>';

        var categoriesContent = '<br><label for="new_date">Category:</label> <span style="font-size:11px;">(select ' + selectedRowData.title + ')</span><br>';
        categoriesContent += '<select class="form-control" id="categorySelect" name="categorySelect">';
        @foreach ($categories as $category)
        categoriesContent += '<option value="{{ $category->id }}"' + (selectedRowData.category == '{{ $category->id }}' ? ' selected' : '') + '>{{ $category->title }}</option>';
        @endforeach
        categoriesContent += '</select>';

        var locationsContent = '<br><label for="new_date">Location:</label><br>';
        locationsContent += '<select class="form-control" id="locationSelect" name="locationSelect">';
        locationsContent += '<option value="">Select a location</option>';
        @foreach ($locations as $location)
        locationsContent += '<option value="{{ $location->id }}">{{ $location->title }}</option>';
        @endforeach
        locationsContent += '</select>';

        var dateAndTimeContent = '<br>';
        dateAndTimeContent += '<div class="col-md-12 row">';
        dateAndTimeContent += '<div class="col-md-3 p-1"><label for="new_date">Date:</label><div class="input-group"><input id="new_date" name="new_date" type="text" class="form-control" value="' + selectedRowData.date + '" autocomplete="off"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div>';
        dateAndTimeContent += '<div class="col-md-3 p-1"><label for="new_start_time">Start Time:</label><div class="input-group"><input id="new_start_time" name="new_start_time" type="text" class="form-control timepicker_start" value="' + selectedRowData.start_time + '"><span class="input-group-addon"><i class="fa-solid fa-clock"></i></span></div></div>';
        dateAndTimeContent += '<div class="col-md-3 p-1"><label for="new_end_time">End Time:</label><div class="input-group"><input id="new_end_time" name="new_end_time" type="text" class="form-control" value="' + selectedRowData.end_time + '" autocomplete="off"><span class="input-group-addon"><i class="fa-solid fa-clock"></i></span></div></div>';
        dateAndTimeContent += '</div>';

        var modalContent = studentsContent + categoriesContent + locationsContent + dateAndTimeContent;
        showModal(modalContent);
    });

    function initStudentMultiselect() {
        $('#studentSelect').multiselect({
            buttonWidth: '100%',
            dropRight: false,
            enableFiltering: true,
            includeSelectAllOption: true,
            includeFilterClearBtn: true,
            search: true,
            noneSelected: "{{ __('None selected') }}",
            selectAllText: "{{ __('All Students') }}",
            enableCaseInsensitiveFiltering: true,
            enableFullValueFiltering: false,
        });

        var studentIds = selectedRowData.students.split(',').map(function(id) { return id.trim(); });
        studentIds.forEach(function(id) {
            $('#studentSelect option[value=' + id + ']').prop('selected', true);
        });
        $('#studentSelect').multiselect('refresh');
    }

    function initDateAndTimePickers() {

        var start_time = moment(selectedRowData.start_time).format("HH:mm:00");
	    var end_time = moment(selectedRowData.end_time).format("HH:mm:00");

        $('#new_date').datetimepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayBtn: true,
            minuteStep: 10,
            minView: 3,
            weekStart: 1,
            maxView: 3,
            viewSelect: 3,
            todayBtn: false,
        });

        $('#new_start_time').timepicker({
            timeFormat: 'HH:mm',
            interval: 5,
            minTime: '0',
            maxTime: '23:59',
            defaultTime: selectedRowData.start_time,
            dynamic: false,
            dropdown: true,
            scrollbar: true,
        });

        $('#new_end_time').timepicker({
            timeFormat: 'HH:mm',
            interval: 5,
            minTime: '0',
            defaultTime: selectedRowData.end_time,
            maxTime: '23:59',
            startTime: '00:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
        });
    }

    function initCategorySelect() {
        $('#categorySelect').addClass('form-control');
    }

    $('#addAgendaModal').on('shown.bs.modal', function () {
        initStudentMultiselect();
        initCategorySelect();
        initDateAndTimePickers();
    });

    $('#addLesson').on('click', function() {
        var selectedStudents = $('#studentSelect').val();
        var selectedCategory = $('#categorySelect').val();
        var selectedLocation = $('#locationSelect').val();

        var newDate = $('#new_date').val();
        var newStartTime = $('#new_start_time').val();
        var newEndTime = $('#new_end_time').val();
        //return console.log('alors ?', newDate);

        if (!selectedStudents || selectedStudents.length === 0 || 
            !selectedCategory || selectedCategory.length === 0 || 
            !newDate || !newStartTime || !newEndTime) {
            Swal.fire({
                title: 'Please select students, a category, and provide date and time.',
                icon: "error"
            });
            return;
        }

        var postData = {
            _token: '{{ csrf_token() }}',
            lesson_data: selectedRowData,
            students: selectedStudents,
            category: selectedCategory,
            location: selectedLocation,
            date: newDate,
            start_time: newStartTime,
            end_time: newEndTime
        };

        $.post("{{ route('import.addLesson') }}", postData, function(response) {
            if(response) {
                $('#row_' + selectedRowData['id']).addClass('bg-success');
                $('#row_' + selectedRowData['id']).find('td:eq(8)').html('Yes');
                $('#row_' + selectedRowData['id']).addClass('text-white');
                $('#addAgendaModal').modal('hide');
                Swal.fire({
                    title: "{{ __('Lesson Successfully created') }}",
                    icon: "success"
                });
            } else {
                Swal.fire({
                    title: "{{ __('An error occured. Please retry later...') }}",
                    icon: "error"
                });
            }
        });
    });
</script>
@endsection