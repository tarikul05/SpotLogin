@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
  <div class="m-4">
   <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('Name of the Teacher') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Status') }}</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td><?= $teacher->firstname.' '.$teacher->middlename.' '.$teacher->lastname; ?></td>
                <td><?= $teacher->email; ?></td>
                <td><?= $teacher->status; ?></td>
                <td>
                  <a class="btn btn-sm btn-theme-success" href="{{ route('editTeacher',[$teacher->id]) }}"> {{ __('Edit')}} </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
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