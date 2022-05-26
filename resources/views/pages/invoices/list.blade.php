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
          @if (!empty($invoices))
            @php
                $i = 0;
            @endphp
            @foreach($invoices as $invoice)
                @php
                    $i++;
                @endphp
            
                <tr>
                    <td class="txt-grey text-center">{{ $i }} </td>
                    <td>{{ $invoice->date_invoice; }}</td>
                    <td>{{ $invoice->invoice_type; }}</td>
                    <td>{{ $invoice->invoice_name; }}</td>
                    <td>{{ $invoice->total_amount; }}</td>
                    @if ($invoice->payment_status_flag == 0)
                        <td class="text-center">
                            <div id="status_{{$invoice->id}}">
                                <span class="text-warn gilroy-semibold">{{$invoice->payment_status}}</scan>
                            </div>
                        </td>
                    @else
                        <td class="text-center">
                            <div id="status_{{$invoice->id}}">
                                <span class="text-suces gilroy-semibold">{{$invoice->payment_status}}</scan>
                            </div>
                        </td>
                    @endif
                    @if ($invoice->invoice_status > 1)
                        <td class="text-center">
                            <i class="far fa-credit-card fa-lg mr-1 light-blue-txt pull-left" style="margin-right:5px; margin-top:3px;" onclick="UpdatePaymentStatus('{{$invoice->id}}')"></i>
                            <span class="small txt-grey pull-left">
                                <span class="change_button">Change</span>
                            </span>
                        </td>
                    @else
                        <td>
                        </td>
                    @endif
                    
                    <td>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-h txt-grey"></i>
                            </a>
                            <div class="dropdown-menu list action text-left">
                                
                                <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('login.submit',['school'=> $schoolId,'invoice'=> $invoice->id]) : route('login.submit',['invoice' => $invoice->id]) }}"><i class="fa fa-pencil txt-grey" aria-hidden="true"></i> {{ __('Edit Info')}}</a>
                            
                                <a class="dropdown-item" href=""><i class="fa fa-envelope txt-grey"></i> {{__('Switch to inactive')}}</a>
                            </div>
                        </div>  
                    </td>
                </tr>
            @endforeach
          @endif
        </tbody>
    </table>
  </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        $('#example').DataTable();
        $("#example_filter").append('<a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
       
    } );
</script>
@endsection