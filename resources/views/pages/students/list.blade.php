@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
  <div class="container-fluid">
   <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
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
                <td>{{ $student->id; }}
                <td>{{ $student->firstname.' '.$student->middlename.' '.$student->lastname; }}</td>
                <td>{{ $student->email; }}</td>
                <td>{{ !empty($student->is_active) ? 'Active' : 'Inactive'; }}</td>
                <td>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h txt-grey"></i>
                        </a>
                        <div class="dropdown-menu action text-left">
                            @can('students-view')
                            <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                            @endcan
                            <a class="dropdown-item" href=""><i class="fa fa-envelope txt-grey"></i> {{__('Switch to inactive')}}</a>
                        </div>
                    </div>  
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable();
        @can('students-create')
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
        @endcan
    } );
</script>
@endsection