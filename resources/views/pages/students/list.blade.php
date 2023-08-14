@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
@endsection

@section('content')
  <div class="container-fluid body students_list mb-3">

    <header class="panel-heading" style="border: none;">
        <div class="row panel-row" style="margin:0;">
            <div class="col-sm-12 col-xs-12 header-area pb-3"> 
                <div class="page_header_class">
                    <label id="page_header" name="page_header"><i class="fa-solid fa-users"></i> {{ __('Student\'s List') }}</label>
                </div> 
            </div>
        </div>
    </header>

    <form action="{{ route('students.delete') }}" method="POST">
        @method('delete')
        @csrf
   <table id="example" class="table table-stripped table-hover" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>&nbsp;</th>
                <th>{{ __('Name of the Student') }}</th>
                <th>{{ __('Email Address') }}</th>
                <th>{{ __('User Account') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            
            <tr>
                <!--<td>{{ $student->id; }} </td>-->
                <td class="pt-3"><input type="checkbox" name="selected_students[]" value="{{ $student->id }}"></td>
                <td class="pt-2">
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
                        <button disabled style="border:1px solid #EEE; font-size:12px;margin:0; width:150px;">{{ __('Not yet registered') }}</button><br>
                        @can('students-sent-mail')
                            <button class="send-invite-btn" style="width: 150px; background-color:#17a2b8;  border:none; font-size:12px;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                <i class="fa-solid fa-envelope"></i> Send invite
                            </button>
                        @endcan
                    @else
                    <button disabled style="border:1px solid #EEE; font-size:12px; margin:0; width:150px;">{{ __('Registered') }}</button><br>    <!--<span class="">{{$student->user->username}}</span>-->
                        <button class="send-password-btn" style="width: 150px; background-color:#17a2b8;  border:none; font-size:12px;" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                            <i class="fa-solid fa-envelope"></i> Re-send password
                        </button>
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
                                @can('teachers-delete')
                                <form method="post" onsubmit="return confirm('{{ __("Are you sure want to delete ?")}}')" action="{{route('studentDelete',['school'=>$student->pivot->school_id,'student'=>$student->id])}}">
                                    @method('delete')
                                    @csrf
                                    <button  class="dropdown-item" type="submit" ><i class="fa fa-trash txt-grey"></i> {{__('Delete')}}</button>
                                </form>
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
@endsection
@include('layouts.elements.modal_csv_import')
@section('footer_js')
<script>
var sendInviteButtons = document.getElementsByClassName('send-invite-btn');
Array.prototype.forEach.call(sendInviteButtons, function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var schoolId = this.getAttribute('data-school');
        var studentId = this.getAttribute('data-student');
        if (confirm('Are you sure want to send an invitation to this student ?')) {
            var redirectUrl = '{{ route('studentInvitationGet', ['school' => ':school', 'student' => ':student']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);
            window.location.href = redirectUrl;
        }
    });
});
</script>
<script>
    var sendInviteButtons = document.getElementsByClassName('send-password-btn');
    Array.prototype.forEach.call(sendInviteButtons, function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var schoolId = this.getAttribute('data-school');
            var studentId = this.getAttribute('data-student');
            if (confirm('Are you sure want to send an invitation to reset the password of this student ?')) {
                var redirectUrl = '{{ route('studentPasswordGet', ['school' => ':school', 'student' => ':student']) }}';
                redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);
                window.location.href = redirectUrl;
            }
        });
    });
    </script>
<script>
    document.getElementById('select-all').addEventListener('change', function () {
        var checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
        
        if (this.checked) {
            var deleteButton = document.getElementById('delete-selected');
            deleteButton.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable({
            language: { search: "" },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],
            "responsive": true,
            "oLanguage": {
                "sLengthMenu": "Show _MENU_",
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