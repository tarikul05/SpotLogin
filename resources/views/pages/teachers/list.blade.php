@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
  <div class="m-4">
    <div class="pull-right">
        <a style="display: block; margin: 10px;" class="btn btn-theme-success" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">Add a professor</a>
    </div>
   <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('Name of the Teacher') }}</th>
                <th>{{ __('Email') }}</th>
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
                <td><?= $teacher->firstname.' '.$teacher->middlename.' '.$teacher->lastname; ?></td>
                <td><?= $teacher->email; ?></td>
                <td><?= $teacher->status; ?></td>
                <td>
                  <a class="btn btn-sm btn-theme-success" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $teacher->id]) : route('editTeacher',['teacher' => $teacher->id]) }}"> {{ __('Edit')}} </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>{{ __('Name of the Teacher') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </tfoot>
    </table>
  </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable();
    } );
</script>
@endsection