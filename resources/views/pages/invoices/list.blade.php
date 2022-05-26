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
                    <td>{{ $invoice_type_all[$invoice->invoice_type]; }}</td>
                    @if ($invoice->invoice_type == 1)
                        <td>{{ $invoice->invoice_name.'-'.$invoice->client_name}}</td>
                    @else
                        <td>{{ $invoice->invoice_name.'-'.$invoice->seller_name }}</td>
                    @endif
                    <td>{{ $invoice->total_amount; }}</td>
                    @if ($invoice->payment_status_flag == 0)
                        <td class="text-center">
                            <div id="status_{{$invoice->id}}">
                                <span class="text-warn gilroy-semibold">{{$payment_status_all[$invoice->payment_status]}}</scan>
                            </div>
                        </td>
                    @else
                        <td class="text-center">
                            <div id="status_{{$invoice->id}}">
                                <span class="text-suces gilroy-semibold">{{$payment_status_all[$invoice->payment_status]}}</scan>
                            </div>
                        </td>
                    @endif
                    @if ($invoice->invoice_status > 1)

                        <td class="text-center">
                            <i class="fa fa-credit-card fa-lg mr-1 light-blue-txt pull-left" style="margin-right:5px; margin-top:3px;" onclick="UpdatePaymentStatus('{{$invoice->id}}')"></i>
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
                            @php
                                $edit_view_url = '';
                                //invoice_creation_type = y means manual invoice
                                if ($invoice->invoice_creation_type == 'Y') {
                                    $edit_view_url = '../invoice/invoice_manual.html?id='.$invoice->id;
                                } else {
                                    $edit_view_url = '../invoice/invoice_modification.html?id='.$invoice->id;
                                }
                            @endphp
                            

                            @if ($invoice->invoice_status > 1)
                                <a class="dropdown-item" href="{{ $edit_view_url }}">
                                    <i class="fa fa-eye txt-grey" aria-hidden="true"></i> 
                                    {{ __('View')}}
                                </a>
                                <a class="dropdown-item" href="{{ auth()->user()->isSuperAdmin() ? route('login.submit',['school'=> $schoolId,'invoice'=> $invoice->id]) : route('login.submit',['invoice' => $invoice->id]) }}">
                                    <i class="fa fa-file-pdf-o txt-grey" aria-hidden="true"></i> 
                                    {{ __('PDF')}}
                                </a>
                            @else
                                <a class="dropdown-item" href="{{ $edit_view_url }}">
                                    <i class="fa fa-pencil-alt txt-grey" aria-hidden="true"></i> 
                                    {{ __('Edit')}}
                                </a>
                            @endif

                            @if (($invoice->invoice_status > 1) && ($invoice->payment_status_flag == 0)) 
                                <a class="dropdown-item txt-grey send_email" href="" onclick="SendPayRemiEmail('{{$invoice->id}}','{{$invoice->invoice_type}}')"><i class="fa fa-envelope txt-grey"></i> {{__('Send Invoice')}}</a>
                            @endif
                                
                            
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