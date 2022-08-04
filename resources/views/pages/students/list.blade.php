@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
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
                                <a class="dropdown-item" href=""><i class="fa fa-envelope txt-grey"></i> {{__('Switch to inactive')}}</a>
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
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
        @endcan
    } );
</script>
@endsection