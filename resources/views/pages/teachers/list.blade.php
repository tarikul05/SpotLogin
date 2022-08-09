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
  <div class="container-fluid">
   <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>&nbsp;</th>
                <th>{{ __('Name of the Teacher') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('User Account') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            @php
            if ($teacher->pivot->role_type == 'school_admin') continue;
            @endphp
            <tr>
                <td>{{ $teacher->id; }} </td>
                <td>
                    <?php if (!empty($teacher->user->profileImage->path_name)): ?>
                        <img src="{{ $teacher->user->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                    <?php else: ?>
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                    <?php endif; ?>
                </td>
                <td>
                    <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}">{{ $teacher->full_name }}</a>
                </td>
                <td>{{ $teacher->email; }} </td>
                <td>{{ !empty($teacher->user) ? 'Yes' : 'No' }} </td>
                <td>{{ !empty($teacher->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
                @if($teacher->pivot->deleted_at)
                    <td>{{__('Deleted')}}</td>
                @else
                <td>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h txt-grey"></i>
                        </a>
                        <div class="dropdown-menu list action text-left">

                            @can('teachers-update')
                            <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                            @endcan

                            @can('teachers-delete')
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to delete ?")}}')" action="{{route('teacherDelete',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('delete')
                                @csrf
                                <button  class="dropdown-item" type="submit" ><i class="fa fa-trash txt-grey"></i> {{__('Delete')}}</button>
                            </form>
                            @endcan
                            @can('teachers-delete')
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to change the status ?")}}')" action="{{route('teacherStatus',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('post')
                                @csrf
                                <input type="hidden" name="status" value="{{ $teacher->pivot->is_active }}">
                                @if($teacher->pivot->is_active)
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
  </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable({
            "responsive": true,
        });
        @can('teachers-create')
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">Add a professor</a>')
        @endcan
    } );
</script>
@endsection