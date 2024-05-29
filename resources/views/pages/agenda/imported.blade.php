@extends('layouts.main')

@section('head_links')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h5>{{ __('Import Agenda') }}</h5>

            @if($counter > 0)
            <div class="container mt-2">
                <h6><small style="color:#0075bf">{{ $counter }} lessons ready to add to your agenda</small></h6>
                Select lessons and choose a category and a list of students for validate the import.<br>

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
            </div>
            @else
            Nothing to import.
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-block text-center border-0">
                <h3 class="modal-title light-blue-txt gilroy-bold" id="signupModalLabel">{{ __('Finish your import') }}</h3>
                <p class="mb-0"><small>{{ __('Please confirm the required informations') }}</small></p>
                <a href="#" class="close" id="modalClose" data-bs-dismiss="modal" style="position: absolute; right: 10px; top: 10px; border-radius:50%!important; padding:3px; font-size:23px;">
                    <i class="fa-solid fa-circle-xmark fa-lg" style="color:#0075bf;"></i>
                </a>
            </div>
            <div class="modal-body" id="modal-body">
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
        $('#myModal').addClass('d-none');
        $('#myModal').modal('show');
        $('#myModal').on('shown.bs.modal', function () {
            $('#myModal').removeClass('d-none');
        });
    }

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
            imported: row.data('imported')
        };

        var studentsContent = '<p><span style="color:#0075bf;">New lesson for ' + selectedRowData.date + '</span> between ' + selectedRowData.start_time + ' and ' + selectedRowData.end_time + '</p><hr>';
        studentsContent += '<p><i class="fa fa-arrow-right"></i> <b>Find this student(s) in your list</b><br><span style="font-size:11px;">' + selectedRowData.students_names + '</span></p>';
        studentsContent += '<select id="studentSelect" multiple="multiple">';
        @foreach ($students as $student)
        studentsContent += '<option value="{{ $student->student_id }}">{{ $student->nickname }}</option>';
        @endforeach
        studentsContent += '</select>';

        var categoriesContent = '<br><br><br><p><i class="fa fa-arrow-right"></i> <b>Find the category in your list</b><br><span style="font-size:11px;">' + selectedRowData.title + '</span></p>';
        categoriesContent += '<select class="form-control" id="categorySelect" name="categorySelect">';
        @foreach ($categories as $category)
        categoriesContent += '<option value="{{ $category->id }}">{{ $category->title }}</option>';
        @endforeach
        categoriesContent += '</select>';

        var modalContent = studentsContent + categoriesContent;
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
    }

    function initCategorySelect() {
        $('#categorySelect').addClass('form-control');
    }

    $('#myModal').on('shown.bs.modal', function () {
        initStudentMultiselect();
        initCategorySelect();
    });

    $('#addLesson').on('click', function() {
        var selectedStudents = $('#studentSelect').val();
        var selectedCategory = $('#categorySelect').val();

        if (!selectedStudents || !selectedCategory) {
            alert('Please select students and a category.');
            return;
        }

        var postData = {
            _token: '{{ csrf_token() }}',
            lesson_data: selectedRowData,
            students: selectedStudents,
            category: selectedCategory
        };

        $.post("{{ route('import.addLesson') }}", postData, function(response) {
            if(response) {
                $('#row_' + selectedRowData['id']).addClass('bg-success');
                $('#row_' + selectedRowData['id']).find('td:eq(8)').html('Yes');
                $('#row_' + selectedRowData['id']).addClass('text-white');
                $('#myModal').modal('hide');
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