@extends('layouts.main')

@section('head_links')
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">

<script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

    <style>
        #teacher_table td {
            border:none!important;
            border-bottom:1px solid #EEE!important;
            font-size:15px;
            margin-bottom:15px!important;
            padding-top:7px!important;
            padding-bottom:7px!important;
        }
        #teacher_table td img {
            height:30px!important;
            width:30px!important;
        }
        #teacher_table tr:hover {
            border:1px solid #EEE!important;
            background-color:#fcfcfc!important;
        }
        #teacher_table th {
            border:none!important;
            border-bottom:3px solid #EEE!important;
            font-size:13px;
            font-weight:bold;
        }
        </style>
@endsection

@section('content')
  <div class="container">


<div class="row justify-content-center pt-3">
    <div class="col-md-10">

    <div class="page_header_class pt-1" style="position: static;">
        <h5 class="titlePage">{{ __("Teachers of the school") }}</h5>
    </div>

    

    @include('pages.teachers.navbar')

    <div class="tab-content" id="ex1-content" style="position: relative;">

  

<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">

    <div class="card2" style="border-radius:10px;">
        <div class="card-header titleCardPage d-flex justify-content-between align-items-center">
            <b class="d-none d-sm-inline">{{ __("Teacher\"s List") }}</b>
            <input name="search_text" type="input" class="form-control search_text_box" id="search_text"  placeholder="Find a teacher">
        </div>
        <div class="card-body">

    <table id="teacher_table" style="width:100%">
        <thead>
            <tr>
                <!--<th>{{ __('#') }}</th>-->
                <th>&nbsp;</th>
                <th class="titleFieldPage p-0 text-left">{{ __('Name of the Teacher') }}</th>
                <th class="titleFieldPage d-none d-lg-table-cell p-0 text-left">{{ __('Email') }}</th>
                <th class="titleFieldPage d-none d-lg-table-cell p-0 text-left">{{ __('User Account') }}</th>
                <th class="titleFieldPage d-none d-lg-table-cell p-0 text-left">{{ __('Status') }}</th>
                <th class="titleFieldPage p-0 text-center">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            @php
                if (($teacher->pivot->role_type == 'school_admin') && !$AppUI->isSuperAdmin()) continue;
            @endphp
            <tr>
                <td class="text-center" style="width:30px!important; margin: 0 auto; padding:4px;">
                    <?php if (!empty($teacher->user->profileImage->path_name)): ?>
                        <img src="{{ $teacher->user->profileImage->path_name }}" class="admin_logo" id="admin_logo">
                    <?php else: ?>
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo">
                    <?php endif; ?>
                </td>
                <td class="p-0 text-left">
                    <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}">
                        <span style="cursor:pointer; border-bottom:1px dashed #a1a0a0;">{{ $teacher->full_name }}</span>
                    </a>
                </td>
                <td class="p-0 text-left d-none d-lg-table-cell">{{ $teacher->email; }}</td>
                <td class="p-0 text-left d-none d-lg-table-cell">
                    @if(!$teacher->user)
                        <span>{{ __('No') }}</span>
                        <form method="post" style="display: inline;" class="form-inline" action="{{route('teacherInvitation',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                          @method('post')
                          @csrf
                          @if(!$teacher->pivot->is_sent_invite)
                              <button class="badge bg-info text-white" style="border:none;" type="submit" title="Send invitation" ><i class="fa-solid fa-envelope"></i> Send invite</button>
                          @else
                              <button class="badge bg-info text-white" style="border:none;" type="submit" title="Resend invitation"><i class="fa-solid fa-envelope"></i> Re-Send invite</button>
                          @endif
                        </form>
                    @else
                         <span class="">{{$teacher->user->username}}</span>
                    @endif
                </td>
                <td class="p-0 text-left d-none d-lg-table-cell">
                    @if (!empty($teacher->pivot->is_active))
                        Active
                    @else
                        <span class="badge bg-warning switch-teacher-btn" data-status="{{ $teacher->pivot->is_active }}" data-school="{{ $teacher->pivot->school_id }}" data-teacher="{{ $teacher->id }}" style="cursor:pointer;">Inactive</span>
                    @endif
                </td>
                @if($teacher->pivot->deleted_at)
                    <td>{{__('Deleted')}}</td>
                @else
                <td class="text-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h txt-grey"></i>
                        </a>
                        <div class="dropdown-menu list action text-left">
                            @can('teachers-update')

                            <a class="dropdown-item text-primary" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}">
                                <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit')}}
                            </a>

                            @endcan



                            @if(!$teacher->user)

                            <a href="javascript:void(0)" class="dropdown-item send-invite-btn text-primary" data-email="{{ $teacher->email }}" data-school="{{ $schoolId }}" data-teacher="{{ $teacher->id }}" title="{{ __("Send invitation") }}">
                                <i class="fa-solid fa-envelope"></i>
                                {{ __('Send invite') }}
                            </a>

                            @else

                            <a href="javascript:void(0)" class="dropdown-item send-password-btn text-primary" data-email="{{ $teacher->email }}" data-school="{{ $schoolId }}" data-teacher="{{ $teacher->id }}" title="{{ __("Send invitation") }}">
                                <i class="fa-solid fa-envelope"></i>
                                {{ __('Resend password') }}
                            </a>

                            @endif



                            <a href="javascript:void(0)" disabled data-status="{{ $teacher->pivot->is_active }}" data-school="{{ $teacher->pivot->school_id }}" data-teacher="{{ $teacher->id }}" class="switch-teacher-btn dropdown-item text-primary" href="#">
                                <i class="fa-solid fa-retweet"></i> {{ !empty($teacher->pivot->is_active) ? __('Switch to inactive')  : __('Switch to active') ; }}
                            </a>

                            @can('teachers-delete')
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to delete ?")}}')" action="{{route('teacherDelete',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('delete')
                                @csrf
                                <button class="dropdown-item text-danger" type="submit" ><i class="fa fa-trash text-danger"></i> {{__('Delete')}}</button>
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
</div></div></div>

<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
    @include('pages.teachers.import_export')
</div>

    </div>
</div></div>
  </div>

  <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
    <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.99!important; background-color:#fbfbfb!important; border:1px solid #fcfcfc;">
        <a class="btn btn-outline-primary" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">
            <i class="fa fa-plus"></i> {{ __('Add new teacher') }} ({{ $teachers->where('pivot.role_type', '!=', 'school_admin')->count() }}/{{ $number_of_coaches > 0 ? $number_of_coaches : 1 }})
        </a>
    </div>
</div>

@include('layouts.elements.modal_csv_teacher_import')

@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
    var table = $('#teacher_table').DataTable({
    dom: '<"top"f>rt<"bottom"lp><"clear">',
    ordering: false,
    searching: true,
    paging: true,
    info: false,
    pagingType: 'simple', 
    drawCallback: function (settings) {
        var api = this.api();
        var pageInfo = api.page.info();

            if (pageInfo.recordsTotal <= pageInfo.length) {
                $('.dataTables_paginate').hide();
                $('.dataTables_length').hide();  
            } else {
                $('.dataTables_paginate').show();
                $('.dataTables_length').show();  
            }
        }
    });

        $('#search_text').on('keyup change', function () {
            table.search($(this).val()).draw();
        });

        $("#teacher_table_filter").hide();

        @can('teachers-create')
        $("#teacher_table_filter").append('<a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teacher.export',['school'=> $schoolId]) : route('teacher.export') }}" target="_blank" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Export")}}</a><a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Import")}}</a><a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">Add a professor</a>')
        @endcan

        if (window.location.href.indexOf('#login') != -1) {
            $('#importModal').modal('show');
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
                return true;
            }
        });
    } );
</script>


<script>
    $(document).ready(function() {
        $(document).on('click', '.send-password-btn', function(event) {
            event.preventDefault();
            $("#pageloader").fadeIn("fast");
    
            var schoolId = $(this).attr('data-school');
            var teacherId = $(this).attr('data-teacher');
    
            //if (confirm('Are you sure want to send an invitation to reset the password of this student ?')) {
                var redirectUrl = '{{ route('teacherPasswordGet', ['school' => ':school', 'teacher' => ':teacher']) }}';
                redirectUrl = redirectUrl.replace(':school', schoolId).replace(':teacher', teacherId);
    
                // Sending an AJAX request
                $.ajax({
                    url: redirectUrl,
                    method: 'GET',
                    success: function(response) {
                        $("#pageloader").fadeOut("fast");
                        //$('#sendMailOk').modal('show');
                        Swal.fire(
                            'Successfully sended',
                            'Your teacher will receive an email with instructions to reset his password',
                           'success'
                        )
                    },
                    error: function(error) {
                        $("#pageloader").fadeOut("fast");
                        console.log(error);
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
<script>
    $(document).ready(function() {
        $(document).on('click', '.send-invite-btn', function(event) {
            event.preventDefault();
            $("#pageloader").fadeIn("fast");
            var schoolId = $(this).attr('data-school');
            var teacherId = $(this).attr('data-teacher');
            //if (confirm('Are you sure want to send an invitation to this student ?')) {
                var redirectUrl = '{{ route('teacherInvitation', ['school' => ':school', 'teacher' => ':teacher']) }}';
                redirectUrl = redirectUrl.replace(':school', schoolId).replace(':teacher', teacherId);
    
                // Sending an AJAX request
                $.ajax({
                    url: redirectUrl,
                    method: 'GET',
                    success: function(response) {
                        $("#pageloader").fadeOut("fast");
                        //$('#sendMailOk').modal('show');
    
                        Swal.fire(
                            'Successfully sended',
                            'Your teacher will receive an email with instructions',
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
        $(document).on('click', '.switch-teacher-btn', function(event) {
            event.preventDefault();

            var schoolId = $(this).attr('data-school');
            var teacherId = $(this).attr('data-teacher');
            var currentStatus = $(this).attr('data-status');

            var redirectUrl = '{{ route('teacherStatus', ['school' => ':school', 'teacher' => ':teacher']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':teacher', teacherId);

            Swal.fire({
            title: currentStatus === '1' ? 'Are you sure to switch this teacher to inactive status ?' : 'Are you sure to switch this teacher to active status ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
            }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData();
                formData.append('status', currentStatus);

                $.ajax({
                url: redirectUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response);
                    $("#pageloader").fadeOut("fast");
                    if (response.status === "success") {
                        
                        let timerInterval;
                        Swal.fire({
                        icon: "success",
                        title: currentStatus === "1" ? "Teacher desactivated" : "Teacher activated",
                        html: "One moment...",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                        }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                        });

                    } else {
                        Swal.fire(
                            'Error!',
                            'An error occurred while updating the status.',
                            'error'
                        )
                    }
                },
                error: function (error) {
                    console.log(error);
                    $("#pageloader").fadeOut("fast");
                    Swal.fire(
                        'Error!',
                        'An error occurred while updating the status.',
                        'error'
                    )
                }
            });

                }
                })
        });
    });
    </script>

@endsection
