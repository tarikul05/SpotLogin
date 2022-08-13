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
  <div class="container-fluid students_list">
   <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>&nbsp;</th>
                <th>{{ __('Name of the Student') }}</th>
                <th>{{ __('Email Address') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            @php
            if ($student->pivot->role_type == 'school_admin') continue;
            @endphp
            <tr>
                <td>{{ $student->id; }} </td>
                <td>
                    <?php if (!empty($student->profileImageStudent->path_name)): ?>
                        <img src="{{ $student->profileImageStudent->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                    <?php elseif (!empty($student->user->profileImage->path_name)): ?>
                        <img src="{{ $student->user->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe"> 
                    <?php else: ?>
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                    <?php endif; ?>
                </td>
                <td>
                    <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}"> {{ $student->full_name; }}</a>
                </td>
                
                <td>{{ $student->email; }}</td>
                <td>{{ !empty($student->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
                @if($student->pivot->deleted_at)
                    <td>{{__('Deleted')}}</td>
                @else
                    <td>
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
                            </div>
                        </div>  
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
@endsection
@include('layouts.elements.modal_csv_import')
@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable({
            language: { search: "" },
            "responsive": true,
            "oLanguage": {
                "sLengthMenu": "Show _MENU_",
            }
        });
        @can('students-create')
        $("#example_filter").append('<a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.export',['school'=> $schoolId]) : route('student.export') }}" target="_blank" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Download Excel")}}</a><a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Upload Excel")}}</a><a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
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
        $("#csv_import").submit(function (e) {
            // if(validateForm()) {
            // } else {
                e.preventDefault(e);  
            //}
        }).validate({
            // Specify validation rules
            rules: {
                csvFile: {
                    required: true,
                    //accept: "text/csv"
                }
            },
            // Specify validation error messages
            messages: {
                csvFile:"{{ __('Please select a csv file') }}"
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function (form) {
                let loader = $('#pageloader');
                loader.show("fast");
                // return false;
                // var formdata = $("#csv_import").serializeArray();
                // var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';


                // formdata.push({
                //     "name": "type",
                //     "value": "student_submit"
                // });
                // formdata.push({
                //     "name": "_token",
                //     "value": csrfToken
                // });
                // $.ajax({
                //     url: BASE_URL + '/{{ auth()->user()->isSuperAdmin() ? route("admin.student.import",["school"=> $schoolId]) : route("student.import") }}',
                //     data: formdata,
                //     type: 'POST',
                //     dataType: 'json',
                //     //async: false,
                //     //encode: true,
                //     beforeSend: function (xhr) {
                //         loader.show("fast");
                //     },
                //     success: function (data) {
                //         if (data.status == 0) {
                //             var username = $("#csvFile").val();
                //             $("#importModal").modal('hide');
                //             successModalCall("{{ __('Imported Successfully') }}");
                //             // setTimeout(function () {
                //             //     window.location.href = data.login_url;
                //             // }, 1000);
                //         } else {
                //             errorModalCall("{{ __('Invalid username or password') }}");
                //         }
                //     }, // sucess
                //     error: function (ts) {
                //         errorModalCall("{{ __('Invalid username or password') }}");
                //     },
                //     complete: function () {
                //         loader.hide("fast");
                //     }
                // });
                // return false; // required to block normal submit since you used ajax
            }
        });
    });
</script>
@endsection