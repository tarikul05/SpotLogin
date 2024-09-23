@extends('layouts.main')

@section('head_links')
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
<link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

    <style>
        #teacher_table td {
            border:none!important;
            border-bottom:1px solid #EEE!important;
            font-size:15px;
            margin-bottom:10px;
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
            font-size:15px;
            font-weight:bold;
        }
        </style>
@endsection

@section('content')
  <div class="container">


<div class="row justify-content-center pt-3">
    <div class="col-md-10">

    <div class="page_header_class pt-1" style="position: static;">
        <h5 class="titlePage">{{ __("Teacher\"s List") }}</h5>
    </div>

    @include('pages.teachers.navbar')

    <div class="tab-content" id="ex1-content">

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
                <th class="titleFieldPage p-0 text-left">{{ __('Email') }}</th>
                <th class="titleFieldPage p-0 text-left">{{ __('User Account') }}</th>
                <th class="titleFieldPage p-0 text-left">{{ __('Status') }}</th>
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
                    <?php if (!empty($teacher->profileImage->path_name)): ?>
                        <img src="{{ $teacher->profileImage->path_name }}" class="admin_logo" id="admin_logo">
                    <?php else: ?>
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo">
                    <?php endif; ?>
                </td>
                <td class="p-0 text-left">
                    <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}">{{ $teacher->full_name }}</a>
                </td>
                <td class="p-0 text-left">{{ $teacher->email; }}</td>
                <td class="p-0 text-left">
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
                <td class="p-0 text-left">{{ !empty($teacher->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
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
                            <form method="get" action="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}">
                                @csrf
                                <button class="dropdown-item" type="submit" ><i class="fa fa-pencil txt-grey"></i> {{__('Edit Info')}}</button>
                            </form>
                            @endcan

                            @if(!$teacher->user)
                              <form method="post" action="{{route('teacherInvitation',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('post')
                                @csrf
                                @if(!$teacher->pivot->is_sent_invite)
                                    <button class="dropdown-item" type="submit" title="Send invitation"><i class="fa fa-envelope txt-grey"></i> Send invite</button>
                                @else
                                    <button class="dropdown-item" type="submit" title="Resend invitation" ><i class="fa fa-envelope txt-grey"></i> Re-Send invite</button>
                                @endif
                              </form>
                            @endif

                            @if($teacher->user)
                              <form method="get" action="{{route('teacherPasswordGet',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('get')
                                @csrf
                                    <button class="dropdown-item" type="submit" title="Send password"><i class="fa fa-envelope txt-grey"></i> {{ __('Resend password') }}</button>          
                              </form>
                            @endif

                            @can('teachers-delete')
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to delete ?")}}')" action="{{route('teacherDelete',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('delete')
                                @csrf
                                <button class="dropdown-item" type="submit" ><i class="fa fa-trash txt-grey"></i> {{__('Delete')}}</button>
                            </form>
                            @endcan
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to change the status ?")}}')" action="{{route('teacherStatus',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('post')
                                @csrf
                                <input type="hidden" name="status" value="{{ $teacher->pivot->is_active }}">
                                @if($teacher->pivot->is_active)
                                    <button class="dropdown-item" type="submit" ><i class="fa fa-envelope txt-grey"></i> {{__('Switch to inactive')}}</button>
                                @else
                                    <button class="dropdown-item" type="submit" ><i class="fa fa-envelope txt-grey"></i> {{__('Switch to active')}}</button>
                                @endif
                            </form>
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
@endsection

@include('layouts.elements.modal_csv_teacher_import')
@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        var table =  $('#teacher_table').DataTable({
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            ordering: false,
            searching: true,
            paging: true,
            info: false,
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
@endsection
