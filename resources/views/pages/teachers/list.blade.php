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
                <th>{{ __('Name of the Teacher') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            @php
            if ($teacher->pivot->role_type == 'school_admin' || $teacher->pivot->role_type == 'teachers_admin') continue;
            @endphp
            <tr>
                <td>{{ $teacher->id; }} </td>
                <td> {{ $teacher->full_name }}</td>
                <td>{{ $teacher->email; }} {{ $teacher->pivot->school_id}}</td>
                <td>{{ !empty($teacher->is_active) && !empty($teacher->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
                @if($teacher->pivot->deleted_at)
                    <td>{{__('Deleted')}}</td>
                @else
                <td>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h txt-grey"></i>
                        </a>
                        <div class="dropdown-menu list action text-left">
                            @can('teachers-view')
                            <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                            @endcan

                            @can('teachers-delete')
                            <form method="post" onsubmit="return confirm('{{ __("Are you sure want to delete ?")}}')" action="{{route('teacherDelete',['school'=>$teacher->pivot->school_id,'teacher'=>$teacher->id])}}">
                                @method('delete')
                                @csrf
                                <button  class="dropdown-item btn" type="submit" ><i class="fa fa-trash txt-grey"></i> {{__('Delete')}}</button>
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
        $('#example').DataTable();
        @can('teachers-create')
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">Add a professor</a>')
        @endcan
    } );
</script>
@endsection