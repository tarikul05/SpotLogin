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
                <th>{{ __('Date') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Event') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            @php
            if ($invoice->pivot->role_type == 'school_admin') continue;
            @endphp
            <tr>
                <td>{{ $invoice->id; }}
                <td>{{ $invoice->firstname.' '.$invoice->middlename.' '.$invoice->lastname; }}</td>
                <td>{{ $invoice->email; }}</td>
                <td>{{ !empty($invoice->is_active) && !empty($invoice->pivot->is_active) ? 'Active' : 'Inactive'; }}</td>
                <td>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h txt-grey"></i>
                        </a>
                        <div class="dropdown-menu list action text-left">
                            
                            <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'invoice'=> $invoice->id]) : route('editStudent',['invoice' => $invoice->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                           
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
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.invoice.create',['school'=> $schoolId]) : route('invoice.create') }}">{{__("Add New")}}</a>')
       
    } );
</script>
@endsection