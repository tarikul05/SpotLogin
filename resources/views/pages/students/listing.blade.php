

  <div class="container-fluid body students_list mb-3">

    <header class="panel-heading" style="border: none;">
        <div class="row panel-row" style="margin:0;">
            <div class="col-sm-12 col-xs-12 header-area pb-3">
                <div class="page_header_class">
                    <label id="page_header" name="page_header">{{ __("Student\"s List") }}</label>
                </div>
            </div>
        </div>
    </header>

    @if(session('locked') == true)
        <div class="alert alert-warning">One or more students are locked with lessons to be invoiced.
            <ul>
                @foreach(session('lockedStudent') as $studentId)
                    @php
                        $student = $students->where('id', $studentId)->first();
                    @endphp
                    @if($student)
                        <li>{{ $student->full_name }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.delete') }}" method="POST">
        @method('delete')
        @csrf
        <input name="schoolId" type="hidden" value="{{$schoolId}}">
   <table id="example" class="table table-stripped table-hover" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th class="d-none d-sm-table-cell">&nbsp;</th>
                <th>{{ __('Name of the Student') }}</th>
                <th>{{ __('Email Address') }}</th>
                <th>{{ __('User Account') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)

            <tr id="row_{{ $student->id }}">
                <!--<td>{{ $student->id; }} </td>-->
                <td class="pt-3"><input type="checkbox" name="selected_students[]" value="{{ $student->id }}"></td>
                <td class="pt-2 d-none d-sm-table-cell">
                    <?php if (!empty($student->profileImageStudent->path_name)): ?>
                        <img src="{{ $student->profileImageStudent->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                    <?php elseif (!empty($student->user->profileImage->path_name)): ?>
                        <img src="{{ $student->user->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                    <?php else: ?>
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                    <?php endif; ?>
                </td>
                <td class="pt-3">
                    <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}"> {{ $student->full_name; }}</a>
                </td>
                <td class="pt-3">{{ $student->email; }} </td>
                <td class="pt-3">
                    @if(!$student->user)
                        <div class="d-block d-sm-none"><br></div>
                        <a disabled style="border:1px solid #EEE; font-size:12px;margin:0; width:150px;">{{ __('Not yet registered') }}</a><br>
                        @can('students-sent-mail')
                            <!--<button class="send-invite-btn" style="width: 150px; background-color:#17a2b8;  border:none; font-size:12px;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                <i class="fa-solid fa-envelope"></i> Send invite
                            </button>-->
                            <a href="javascript:void(0)" role="button" class="badge  send-invite-btn" style="width: 130px; size:11px; background-color:#17a2b8; border:none; font-size:12px; heigth:20px!important;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                <i class="fa-solid fa-envelope"></i> Send invite
                            </a>
                        @endcan
                    @else
                    <a disabled style="border:1px solid #EEE; font-size:12px; margin:0; width:150px;">{{ __('Registered') }}</a><br>    <!--<span class="">{{$student->user->username}}</span>-->
                        <!--<button class="send-password-btn" style="width: 150px; background-color:#17a2b8;  border:none; font-size:12px;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                            <i class="fa-solid fa-envelope"></i> Re-send password
                        </button>-->
                        <a href="javascript:void(0)" role="button" class="badge send-invite-btn send-password-btn" style="width: 130px; size:11px; background-color:#17a2b8;  border:none; font-size:12px; heigth:20px!important;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                            <i class="fa-solid fa-envelope"></i> Resend password
                        </a>
                    @endif
                </td>
                <td class="pt-3">{{ !empty($student->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
                @if($student->pivot->deleted_at)
                    <td>{{__('Deleted')}}</td>
                @else
                    <td class="pt-3">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-h txt-grey"></i>
                            </a>
                            <div class="dropdown-menu list action text-left">
                                @can('students-view')
                                <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                                @endcan
                                <a class="dropdown-item" href="{{ route('students.availabilities', $student) }}"><i class="fa fa-calendar txt-grey" aria-hidden="true"></i> {{ __('Availabilities')}}</a>
                                @can('teachers-delete')
                                <button class="dropdown-item delete-student-btn" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}">
                                    <i class="fa fa-trash txt-grey"></i> {{ __('Delete') }}
                                </button>
                                @endcan
                                @can('students-activate')
                                <form method="post" onsubmit="return confirm('{{ __("Are you sure want to change the status ?")}}')" action="{{route('studentStatus',['school'=>$student->pivot->school_id,'student'=>$student->id])}}">
                                    @method('post')
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $student->pivot->is_active }}">
                                    @if($student->pivot->is_active)
                                        <button  class="dropdown-item" type="submit" ><i class="fa fa-envelope txt-grey"></i> {{__('Switch to inactive')}}</button>
                                    @else
                                        <button  class="dropdown-item" type="submit" ><i class="fa fa-envelope txt-grey"></i> {{__('Switch to active')}}</button>
                                    @endif
                                </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="delete_studentList">
        <button type="submit" id="delete-selected" onclick="return confirm('Are you sure you want to delete the selected students?')">Delete the selected students</button>
    </div>
    </form>
  </div>



<!-- success modal-->
<div class="modal modal_parameter" id="sendMailOk">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:4px solid #97cc04;">
            <div class="modal-body text-center">
                <h1 class="text-success"><i class="fa-solid fa-check"></i></h1>
                <h3 class="text-success">{{__('Successfully sended') }}</h3>
                <p>{{__('Your student will receive an email with instructions.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
            </div>
        </div>
    </div>
</div>



@include('layouts.elements.modal_csv_import')
@section('footer_js')
<script>
    $(document).ready(function() {
        $(document).on('click', '.delete-student-btn', function(event) {
            event.preventDefault();

            var schoolId = $(this).attr('data-school');
            var studentId = $(this).attr('data-student');

            Swal.fire({
            title: 'Are you sure to delete this student ?',
            text: "Be carreful, this student still have lessons/events to be invoiced !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {


                var deleteUrl = '{{ route('studentDeleteDestroy', ['school' => ':school', 'student' => ':student']) }}';
                deleteUrl = deleteUrl.replace(':school', schoolId).replace(':student', studentId);

                // Sending an AJAX request to delete the student
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                    console.log(response);
                        $("#pageloader").fadeOut("fast");
                        if (response.status === "success") {
                            Swal.fire(
                                'Deleted!',
                                'The student has been deleted successfully.',
                                'success'
                            )
                            $("#row_" + studentId).fadeOut();
                        } else {
                            if(response.isFuturInvoice) {
                                Swal.fire(
                                'Student is locked',
                                'This student has lessons to invoice.',
                                'error'
                            )
                            } else {
                                Swal.fire(
                                'Error!',
                                'An error occurred while deleting the student.',
                                'error'
                            )
                            }

                        }
                    },
                    error: function(error) {
                        $("#pageloader").fadeOut("fast");
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the student.',
                            'error'
                        )
                    }
                });

                }
                })
        });
    });
    </script>
<script>
$(document).ready(function() {
    $(document).on('click', '.send-invite-btn', function(event) {
        event.preventDefault();
        $("#pageloader").fadeIn("fast");
        var schoolId = $(this).attr('data-school');
        var studentId = $(this).attr('data-student');
        //if (confirm('Are you sure want to send an invitation to this student ?')) {
            var redirectUrl = '{{ route('studentInvitationGet', ['school' => ':school', 'student' => ':student']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);

            // Sending an AJAX request
            $.ajax({
                url: redirectUrl,
                method: 'GET',
                success: function(response) {
                    $("#pageloader").fadeOut("fast");
                    //$('#sendMailOk').modal('show');

                    Swal.fire(
                        'Successfully sended',
                        'Your student will receive an email with instructions',
                        'success'
                    )

                },
                error: function(error) {
                    $("#pageloader").fadeOut("fast");
                    alert('Error occurred while sending the invitation. Please try again.');
                }
            });
        //}
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.send-password-btn', function(event) {
        event.preventDefault();
        $("#pageloader").fadeIn("fast");

        var schoolId = $(this).attr('data-school');
        var studentId = $(this).attr('data-student');

        //if (confirm('Are you sure want to send an invitation to reset the password of this student ?')) {
            var redirectUrl = '{{ route('studentPasswordGet', ['school' => ':school', 'student' => ':student']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);

            // Sending an AJAX request
            $.ajax({
                url: redirectUrl,
                method: 'GET',
                success: function(response) {
                    $("#pageloader").fadeOut("fast");
                    //$('#sendMailOk').modal('show');
                    Swal.fire(
                        'Successfully sended',
                        'Your student will receive an email with instructions to reset their password',
                       'success'
                    )
                },
                error: function(error) {
                    $("#pageloader").fadeOut("fast");
                    alert('Error occurred while sending the password reset invitation. Please try again.');
                    Swal.fire(
                        'Sorry,',
                        'Error occurred while sending the password reset invitation. Please try again.',
                       'error'
                    )

                }
            });
        //} else {
        //    $("#pageloader").fadeOut("fast");
        //}
    });
});
    </script>

<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable({
            language: { search: "" },
            theme: 'bootstrap4',
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            "responsive": true,
            "oLanguage": {
                "sLengthMenu": "Show _MENU_",
            }
        });

        var searchInput = document.querySelector('.dataTables_wrapper .dataTables_filter input');
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.backgroundImage = 'none';
            }
            else {
                this.style.backgroundImage = 'url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2ZXJzaW9uPSIxLjEiICAgaWQ9InN2ZzQ0ODUiICAgdmlld0JveD0iMCAwIDIxLjk5OTk5OSAyMS45OTk5OTkiICAgaGVpZ2h0PSIyMiIgICB3aWR0aD0iMjIiPiAgPGRlZnMgICAgIGlkPSJkZWZzNDQ4NyIgLz4gIDxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhNDQ5MCI+ICAgIDxyZGY6UkRGPiAgICAgIDxjYzpXb3JrICAgICAgICAgcmRmOmFib3V0PSIiPiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC9kYzpmb3JtYXQ+ICAgICAgICA8ZGM6dHlwZSAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4gICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPiAgICAgIDwvY2M6V29yaz4gICAgPC9yZGY6UkRGPiAgPC9tZXRhZGF0YT4gIDxnICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0xMDMwLjM2MjIpIiAgICAgaWQ9ImxheWVyMSI+ICAgIDxnICAgICAgIHN0eWxlPSJvcGFjaXR5OjAuNSIgICAgICAgaWQ9ImcxNyIgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjAuNCw4NjYuMjQxMzQpIj4gICAgICA8cGF0aCAgICAgICAgIGlkPSJwYXRoMTkiICAgICAgICAgZD0ibSAtNTAuNSwxNzkuMSBjIC0yLjcsMCAtNC45LC0yLjIgLTQuOSwtNC45IDAsLTIuNyAyLjIsLTQuOSA0LjksLTQuOSAyLjcsMCA0LjksMi4yIDQuOSw0LjkgMCwyLjcgLTIuMiw0LjkgLTQuOSw0LjkgeiBtIDAsLTguOCBjIC0yLjIsMCAtMy45LDEuNyAtMy45LDMuOSAwLDIuMiAxLjcsMy45IDMuOSwzLjkgMi4yLDAgMy45LC0xLjcgMy45LC0zLjkgMCwtMi4yIC0xLjcsLTMuOSAtMy45LC0zLjkgeiIgICAgICAgICBjbGFzcz0ic3Q0IiAvPiAgICAgIDxyZWN0ICAgICAgICAgaWQ9InJlY3QyMSIgICAgICAgICBoZWlnaHQ9IjUiICAgICAgICAgd2lkdGg9IjAuODk5OTk5OTgiICAgICAgICAgY2xhc3M9InN0NCIgICAgICAgICB0cmFuc2Zvcm09Im1hdHJpeCgwLjY5NjQsLTAuNzE3NiwwLjcxNzYsMC42OTY0LC0xNDIuMzkzOCwyMS41MDE1KSIgICAgICAgICB5PSIxNzYuNjAwMDEiICAgICAgICAgeD0iLTQ2LjIwMDAwMSIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==)';
            }
        });

        @can('students-create')
        $("#example_filter").append('<a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.export',['school'=> $schoolId]) : route('student.export') }}" target="_blank" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Export")}}</a><a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Import")}}</a><a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
        @endcan

        if (window.location.href.indexOf('#login') != -1) {
            $('#importModal').modal('show');
        }
        function validateForm() {
            var currency_code = document.getElementById("currency_code").value;
            var currency_title = document.getElementById("currency_title").value;
            var sort_order = document.getElementById("sort_order").value;
            let error = false;
            if (currency_code == null || currency_code == "") {
                    $('#currency_code').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
                    document.getElementById("currency_code").focus();
                    error = true;
            }
            if (currency_title == null || currency_title == "") {
                document.getElementById("currency_title").focus();
                $('#currency_title').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
                error = true;
            }


            if (error) {
                return false;
            }else{
                return true;
            }
        }
        $("#csv_import").validate({
            // Specify validation rules
            rules: {
                csvFile: {
                    required: true
                }
            },
            // Specify validation error messages
            messages: {
                csvFile:"{{ __('Please select a Excel file') }}"
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function (form) {
                $("#pageloader").fadeIn("fast");
                return true;
            }
        });
    });
    
    
</script>
@endsection
