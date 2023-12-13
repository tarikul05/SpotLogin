@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

    <style type="text/css">
    #example1 {
        font-size:13px;
    }
    </style>
@endsection

@section('content')
  <div class="container">

    <h5>{{ __("Invoices List") }}</h5>

    @if(!$AppUI->isStudent())
    <nav class="subNav">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            <button onclick="addFilter('')" class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('List all') }}
            </button>

            <button onclick="addFilter('Student')" class="nav-link" id="nav-import_export-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('Invoices') }}
            </button>

            @if($AppUI->isSchoolAdmin())
            <button onclick="addFilter('Teacher')" class="nav-link" id="nav-import_export-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('Teacher invoices') }}
            </button>
            @endif

            <button onclick="addFilter('Manuel')" class="nav-link" id="nav-add-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('Manual invoices') }}
            </button>

        </div>
    </nav>
    @endif



    <div class="row justify-content-center pt-5 mb-5">
        <div class="col-md-9">
        <div class="card">
            <div class="card-header">{{ __('Invoices List') }}</div>
            <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-9 col-xs-12 col-lg-9"></div>
                <div class="col-md-3 col-xs-12 col-lg-3">
                    <input name="search_text" type="input" class="form-control search_text_box" id="search_text"  placeholder="Find an invoice">
                </div>
            </div>

    <div class="table-responsive11">
        <input id="seleted_auto_id" name="seleted_auto_id" style="display: none;">
        <input id="p_school_id" name="p_school_id" style="display: none;" value="<?php echo $school->id;?>">
        <input id="seleted_invoice_type" name="seleted_invoice_type" style="display: none;">
        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status">
            <option value="1">{{ __('Paid') }}</option>
            <option value="0">{{ __('Unpaid') }}</option>
        </select>
        <table id="example1" class="table table-stripped table-hover" style="width:100%">
            <thead>
                <tr>
                <th class="mobile-hide">{{ __('Date') }}</th>
                <th class="sp_only">{{ __('Client') }}</th>
                <th class="mobile-hide">{{ __('Type') }}</th>
                <th class="mobile-hide">{{ __('Invoice Name') }}</th>
                <th>{{ __('Amount') }}</th>
                <th class="mobile-hide">{{ __('Status') }}</th>
                <th class="mobile-hide"></th>
                <th></th>
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
                        $urlInvoice = route('invoiceList');
                        $edit_view_url = '';
                        //invoice_type = 0 means manual invoice
                        if ($invoice->invoice_type == 0) {
                            if ($invoice->invoice_status == 10) {
                                if(!empty($schoolId)){
                                    $edit_view_url = route('adminmodificationInvoice',[$schoolId,$invoice->id]);
                                } else {
                                    $edit_view_url = route('modificationInvoice',[$invoice->id]);
                                }
                            }else{
                                $edit_view_url = '/'.$schoolId.'/modification-invoice/'.$invoice->id;
                            }
                        } else {
                            if(!empty($schoolId)){
                                $edit_view_url = route('adminmodificationInvoice',[$schoolId,$invoice->id]);
                            } else {
                                $edit_view_url = route('modificationInvoice',[$invoice->id]);
                            }
                        }
                        //$zone = $timeZone;
                        //$invoice->date_invoice = Helper::formatDateTimeZone($invoice->date_invoice, 'long','UTC',$zone);

                    @endphp

                    <tr>
                        <!-- <td style="display: none">{{ $invoice->id; }}</td>
                        <td style="display: none"><div id="status_id_{{ $invoice->id; }}">{{$invoice->payment_status}}</div></td> -->
                        <!--<th>&nbsp;</th>-->
                        <!--<td class="txt-grey text-left">{{ $i }} </td>-->

                        <td class="responsive-td mobile-hide">
                            @php
                            $date = new DateTime($invoice->date_invoice);
                            @endphp
                            {{ $date->format('d M Y') }}
                        </td>
                        <td class="sp_only responsive-td">
                            <span class="d-block d-sm-none">
                            @php
                            $date = new DateTime($invoice->date_invoice);
                            @endphp
                            <b>{{ $date->format('d M Y') }}</b>
                            </span>
                         <?= $invoice->client_name ?>
                        </td>
                        @php
                        if($invoice->invoice_type ==0){
                            @endphp
                            <td class="responsive-td mobile-hide">{{ $invoice_type_all[$invoice->invoice_type]; }} (M)</td>
                            @php
                        } else {
                            @endphp
                            <td class="responsive-td mobile-hide">{{ $invoice_type_all[$invoice->invoice_type]; }}</td>
                            @php
                        }
                        $invoice_name = $invoice->invoice_name;
                        if($invoice->invoice_type ==1){
                            $invoice_name .= '-'.$invoice->client_name. '-ID#' . $invoice->id;
                        } else {
                            $invoice_name .= '-'.$invoice->client_name. '-ID#' . $invoice->id;
                        }
                        @endphp
                        <td class="responsive-td mobile-hide">{{ $invoice_name}}</td>

                        @if ($invoice->invoice_type == 0)
                        <td class="responsive-td">{{ $invoice->invoice_currency }} <b>{{ number_format($invoice->total_amount + $invoice->tax_amount + $invoice->extra_expenses, 2) }}</b></td>
                        @else
                        <td class="responsive-td">{{ $invoice->invoice_currency }} <b>{{ number_format($invoice->total_amount, 2) }}</b></td>
                        @endif

                        <i style="display: none; margin-right:5px; margin-top:3px;" id="loaderStatusPayment" class="loaderStatusPayment fa fa-spinner" aria-hidden="true"></i>
                        @if ($invoice->payment_status == 0)
                            <td class="responsive-td mobile-hide text-left">
                                <div id="status_{{$invoice->id}}">
                                    @if(!$AppUI->isStudent())
                                    <span class="small txt-grey pull-left">
                                        <i class="fa fa-credit-card" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i>
                                        <span style="cursor: pointer;" id="payment_btn" data-invoice-id="{{$invoice->id}}"  data-invoice-status="{{ $invoice->payment_status }}" class="payment_btn change_button"><span class="text-warn gilroy-semibold">{{__($payment_status_all[$invoice->payment_status])}}</span></span>
                                    </span>
                                    @endif
                                    @if($AppUI->isStudent())
                                        <span class="small txt-grey pull-left">
                                            <i class="fa fa-credit-card" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i>
                                            {{__($payment_status_all[$invoice->payment_status])}}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="responsive-td mobile-hide text-left" width="150">
                                <div id="status_{{$invoice->id}}">
                                @if(!$AppUI->isStudent())
                                    <span class="small txt-grey pull-left">
                                        <i class="fa fa-credit-card" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i>
                                        <span style="cursor: pointer;" id="payment_btn" data-invoice-id="{{$invoice->id}}"  data-invoice-status="{{ $invoice->payment_status }}" class="payment_btn change_button"><span class="text-suces gilroy-semibold">{{__($payment_status_all[$invoice->payment_status])}}</span></span>
                                    </span>
                                @endif
                                @if($AppUI->isStudent())
                                    <span class="small txt-grey pull-left">
                                        <i class="fa fa-credit-card text-success" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i>
                                        <span class="text-suces gilroy-semibold">{{$payment_status_all[$invoice->payment_status]}}</span>
                                    </span>
                                @endif
                                </div>
                            </td>
                        @endif
                        @if ($invoice->invoice_status > 1)
                            @if(!$AppUI->isStudent())
                            <td class="responsive-td text-center mobile-hide">
                                <!--<i style="display: none; margin-right:5px; margin-top:3px;" id="loaderStatusPayment" class="fa fa-spinner fa-lg mr-1 light-blue-txt pull-left" aria-hidden="true"></i>
                                <i class="fa fa-credit-card fa-lg mr-1 light-blue-txt pull-left" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i>
                                <span class="small txt-grey pull-left">
                                      <span style="cursor: pointer;" id="payment_btn" data-invoice-id="{{$invoice->id}}"  data-invoice-status="{{ $invoice->payment_status }}" class="change_button">{{ __('Change')}}</span>
                                </span>-->
                                @php
                                    $existingEntry = DB::table('invoice_sended')
                                    ->where('invoice_id', $invoice->id)
                                    ->latest()
                                    ->first();
                                @endphp

                                @if($existingEntry)
                                <span style="font-size:11px;">{{ __('sent') }} <span class="text-success"> {{ \Carbon\Carbon::parse($existingEntry->created_at)->timezone($school->timezone)->format('d M, Y  H:i') }}</span></span>
                                <button id="approved_btn" target="" href="" class="btn btn-link" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})"><i class="fa-solid fa-envelope-open-text"></i> <span class="d-none d-sm-inline" style="font-size:12px;">{{__('Re-Send by email')}}</span></button>
                                @else
                                <span style="font-size:11px;">{{ __('Invoice not sent') }}</span>
                                <button id="approved_btn" target="" href="" class="btn btn-link" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})"><i class="fa-solid fa-envelope-open-text"></i> <span class="d-none d-sm-inline" style="font-size:12px;">{{__('Send by email')}}</span></button>
                                @endif
                            </td>
                            @else
                            <td class="mobile-hide">
                            </td>
                            @endif
                        @else
                            <td class="mobile-hide"></td>
                        @endif

                        <td class="text-right">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h txt-grey"></i>
                                </a>
                                <div class="dropdown-menu list action text-left">
                                    @if ($invoice->invoice_status > 1)
                                        <a class="dropdown-item" href="{{ $edit_view_url }}">
                                            <i class="fa fa-eye txt-grey" aria-hidden="true"></i>
                                            {{ __('View')}}
                                        </a>
                                        <a target="_blank" class="dropdown-item" href="{{ route('generateInvoicePDF',['invoice_id'=> $invoice->id, 'type' => 'print_view']) }}">
                                            <i class="fa fa-file-pdf txt-grey" aria-hidden="true"></i>
                                            {{ __('PDF')}}
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="{{ $edit_view_url }}">
                                            <i class="fa fa-pencil-alt txt-grey" aria-hidden="true"></i>
                                            {{ __('Edit')}}
                                        </a>
                                    @endif

                                    @if (($invoice->payment_status == 0) && (!$AppUI->isStudent()))
                                        <a class="dropdown-item txt-grey send_email" href="javascript:void(0)" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})"><i class="fa fa-envelope txt-grey"></i> {{__('Send reminder email')}}</a>
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

                </div>
            </div>
        </div>
    </div>

  </div>
</div>

<div class="modal fade confirm-modal" id="email_list_modal" tabindex="-1" aria-hidden="true"
        aria-labelledby="email_list_modal" name="email_list_modal">
        <div class="modal-dialog modal-dialog-centered mt-5" role="document">
            <div class="modal-content">
                <div class="modal-header text-center border-0">
                    <h4 class="light-blue-txt gilroy-bold">{{ __('Send the invoice') }}</h4>
                </div>
                <div class="modal-body row" style="margin: 0 auto;padding-top: 0;">
                    <!-- <form id="email_list_form" name="email_list_form" method="POST"> -->
                        <div class="alert alert-info">
                            <div class="form-group col-md-12">
                            <i class="fa-solid fa-file-pdf"></i> {{ __('Format type file') }}: PDF<br>
                            <i class="fa-regular fa-envelope"></i> {{ __('Send Type') }}: {{ __('By email') }}
                            </div>
                        </div>
                        <div class="form-group row col-md-12" id="father_email_div">
                            <div class="btn-group border-bottom col-md-9 text-left">
                                <input  type="checkbox" id="father_email_chk" name="father_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="father_email_chk" id="father_email_cap" name="father_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Father's email")}})</div>
                            </div>
                            <div class="col-md-3 border-bottom pt-2 text-right d-none d-sm-block">
                                ({{ __("Father's email")}})
                            </div>
                        </div>

                        <div class="form-group row col-md-12" id="mother_email_div">
                            <div class="btn-group col-md-9 border-bottom text-left">
                                <input type="checkbox" id="mother_email_chk" name="mother_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="mother_email_chk" id="mother_email_cap" name="mother_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Mother's email")}})</div>
                            </div>
                            <div class="col-md-3 border-bottom pt-2 text-right d-none d-sm-block">
                                ({{ __("Mother's email")}})
                            </div>
                        </div>

                        <div class="form-group row col-md-12" id="student_email_div">
                            <div class="btn-group col-md-9 text-left">
                                <input type="checkbox" id="student_email_chk" name="student_email_chk" value="" style="float: left;margin: 15px 5px;width: 15px;height: 15px;" checked>
                                <label for="student_email_chk" id="student_email_cap" name="student_email_cap"></label>
                                <div class="d-block d-sm-none text-small" style="font-size:10px;">({{ __("Student's email")}})</div>
                            </div>
                            <div class="col-md-3 pt-2 text-right d-none d-sm-block">
                                <small>({{ __("Student's email")}})</small>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="text-left">
                                <div class="checked">
                                    <input class="form-control" style="display: block;" type="email" id="other_email" name="other_email" placeholder="Add another email adress here" value="" maxlength="100">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 text-left">
                                <div>
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                                <button type="submit" id="email_send" class="btn btn-sm btn-theme-success">{{ __('Send') }}</button>
                        </div>

                    <!-- </form> -->

                </div>
            </div>
        </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
    var table = $('#example1').DataTable({
        dom: '<"top"f>rt<"bottom"lp><"clear">',
    ordering: false, // Disable column sorting
    searching: true, // Enable searching with the search input
    paging: true, // Disable pagination
    info: false, // Disable information display
});
    $('#search_text').on('keyup change', function () {
        table.search($(this).val()).draw();
    });
    $("#example1_filter").hide();

    });

    function SendPayRemiEmail(p_value,p_invoice_type,p_school_id) {

        $('#seleted_auto_id').val(p_value);
        $('#p_school_id').val(p_school_id);
        $('#seleted_invoice_type').val(p_invoice_type);
        var p_attached_file = '';

        var find_flag = 0;
        //return false;
        //populate lis of emails
        $.ajax({
            url: BASE_URL + '/pay_reminder_email_fetch',
            //url: 'invoice_data.php',
            data: 'type=email_list&p_auto_id=' + p_value,
            type: 'POST',
            dataType: 'json',
            //async: false,
            success: function (result) {
                //console.log(result);
                if (result.status) {
                    confirmPayReminderModalCall(p_value,'{{ __('Do you want to send email')}}',result.data,p_school_id);
                    return false;

                }
                else {
                    errorModalCall('{{ __("Event validation error")}}');
                }

            },   // sucess
            error: function (ts) {
                errorModalCall(GetAppMessage('error_message_text'));
                //alert(ts.responseText + 'populate Invoice Payment Status')
            }
        }); // Ajax


        $("#email_list_modal").modal('show');

    };


    $('#email_send').click(function (e) {
        var p_emails = '', p_attached_file = '';
        var p_inv_auto_id = $('#seleted_auto_id').val();
        var p_seleted_invoice_type = $('#seleted_invoice_type').val();
        var p_school_id = document.getElementById("p_school_id").value;
        if ((document.getElementById("father_email_chk").checked == true) && (document.getElementById("father_email_chk").value != '')) {
            p_emails = document.getElementById("father_email_chk").value + "|";
        }
        if ((document.getElementById("mother_email_chk").checked == true) && (document.getElementById("mother_email_chk").value != '')) {
            p_emails += document.getElementById("mother_email_chk").value + "|";
        }

        if ((document.getElementById("student_email_chk").checked == true) && (document.getElementById("student_email_chk").value != '')) {
            p_emails += document.getElementById("student_email_chk").value + "|";
        }
        if ($('#other_email').val() != '') {
            p_emails += $('#other_email').val();
        }


        //console.log(p_seleted_invoice_type);
        if (p_seleted_invoice_type == 2) {
            SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        } else {
            SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        }


    });

    function addFilter(text) {

        $('#search_text').val(text).change()
    }


    $('#email_send').click(function (e) {
        var p_emails = '', p_attached_file = '';
        var p_inv_auto_id = document.getElementById("invoice_id").value;
        var p_seleted_invoice_type = document.getElementById("invoice_type").value;
        var p_school_id = document.getElementById("p_school_id").value;

        if (document.getElementById("father_email_chk").checked) {
            if (document.getElementById("father_email_cap").textContent != '') {
                p_emails = document.getElementById("father_email_cap").textContent + "|";
            }
        }
        if (document.getElementById("mother_email_chk").checked) {
            if (document.getElementById("mother_email_cap").textContent != '') {
                p_emails += document.getElementById("mother_email_cap").textContent + "|";
            }
        }

        if (document.getElementById("student_email_chk").checked) {
            if (document.getElementById("student_email_cap").textContent != '') {
                p_emails += document.getElementById("student_email_cap").textContent + "|";
            }
        }

        if ($('#other_email').val() != '') {
            p_emails += $('#other_email').val();
        }


        console.log('list emails send', p_emails);

        SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id)

    });

    $(document).ready( function () {
    $('.payment_btn').click(function (e) {

        e.preventDefault();

        if($(this).data('invoice-status') == 1 || $(this).data('invoice-status') == 2) {
            loading();
            return setTimeout(() => {
                 actionpaid('paid', $(this).data('invoice-id'), 1);
            }, 800);
        }
            Swal.fire({
            title: "{{ __('Choose payment status') }}",
                text: "{{ __('How student paid this invoice ?') }}",
                icon: "question",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "{{ __('actionPaid') }}",
                denyButtonText: "{{ __('actionCashPaid') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                loading();
                setTimeout(() => {
                    actionpaid('paid', $(this).data('invoice-id'), $(this).data('invoice-status'));
                }, 1100);
            } else if (result.isDenied) {
                loading();
                setTimeout(() => {
                    actionpaid('cash', $(this).data('invoice-id'), $(this).data('invoice-status'));
                }, 800);
            }
            })
    });
});


    function loading() {
        let timerInterval;
        Swal.fire({
        html: "{{ __('ongoing treatment') }}",
        timer: 2000,
        timerProgressBar: false,
        didOpen: () => {
            Swal.showLoading();
            timerInterval = setInterval(() => {
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
        }
        });
    }


    function actionpaid(type,invoiceId,invoiceState) {

        document.getElementById("loadercreditCardPayment").style.display = "none";
        document.getElementById("loaderStatusPayment").style.display = "block";

            var paymentStatusAll = @json($payment_status_all);
            var p_invoice_id = invoiceId;
            var payment_status = '';
            var payment_success_modal = "";

            if (p_invoice_id == '') {
                errorModalCall('Invalid_invoice');
                return false;
            }

            if (invoiceState == '1') {
                payment_status = '0';
                payment_success_modal = "{{ __('invoice payment updated to unpaid') }}";
            } else {
                payment_status = '1';
                payment_success_modal = "{{ __('invoice payment updated to paid') }}";
                if(type === "cash") {
                    payment_status = '2';
                    payment_success_modal = "{{ __('invoice payment updated to cash paid') }}";
                }

            }
            //alert('payment_status='+payment_status);
            var status = '';
            var data = 'type=update_payment_status&p_payment_status=' + payment_status + '&p_auto_id=' + p_invoice_id;
            // console.log(data);
            // return false;
            $.ajax({
                url: BASE_URL+'/update_payment_status',
                data: data,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (result) {
                $('#pageloader').fadeOut('fast');
                    status = result.status;
                    if (status == 'success') {
				        successModalCall(payment_success_modal);
                        if (payment_status == '1') {
                            //document.getElementById("status_" + p_invoice_id).innerHTML = '<span class="small txt-grey pull-left"><i class="fa fa-credit-card fa-lg" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i> <span class="text-suces gilroy-semibold">' + paymentStatusAll[payment_status] + '</span></span>';
                        } else {
                            //document.getElementById("status_" + p_invoice_id).innerHTML = '<span class="small txt-grey pull-left"><i class="fa fa-credit-card fa-lg" id="loadercreditCardPayment" style="margin-right:5px; margin-top:3px;"></i> <span class="text-warn gilroy-semibold">' + paymentStatusAll[payment_status] + '</span></span>';
                        }
                        setTimeout(function() {
                            var redirectUrl = './invoices';
                            window.location.href = redirectUrl;
                        }, 800);

                    }
                    else {
                        $('#pageloader').fadeOut('fast');
                        errorModalCall('error_message_text');
                    }
                },   //success
                error: function (ts) {
                    $('#pageloader').fadeOut('fast');
                    errorModalCall('error_message_text');

                }
            }); //ajax-type

    };

    function UpdatePaymentStatus(p_auto_id) {
        $("#pageloader").fadeIn();
        var payment_status;
        var v_status = "status_" + p_auto_id;
        var v_status_id = "status_id_" + p_auto_id;
        var p_payment_status = document.getElementById(v_status_id).innerHTML;

        if (p_auto_id == '') {
            //alert('Invalid invoice.. ');
            errorModalCall(GetAppMessage('error_message_text'));
            return false;
        }

        if (p_payment_status == 0) {
            payment_status = 1;
        } else {
            payment_status = 0;
        }

        $('#inv_payment_status').val(payment_status);
        //let payment_text_paid = '';
        //let payment_text_unpaid = '';
        let payment_text_paid = "<span class='gilroy-bold' id='" + v_status + "' style='color:" + ((payment_status == 0) ? '#FF8000' : '#97CC04') + ";text-align:center;'>" + __('Paid') + "</span>";
        let payment_text_unpaid = "<span class='gilroy-bold' id='" + v_status + "' style='color:" + ((payment_status == 0) ? '#FF8000' : '#97CC04') + ";text-align:center;'>" + __('Unpaid') + "</span>";


        //console.log('status='+((p_payment_status = 0) ? 1 : 0));

        var data = 'type=update_payment_status&p_payment_status=' + payment_status + '&p_auto_id=' + p_auto_id;
        console.log(data);
        var status = '';
        var data = data;
        // document.getElementById(v_status_id).innerHTML = payment_status;
        // document.getElementById(v_status).innerHTML = payment_text;
        $.ajax({

                url: BASE_URL + '/update_payment_status',
                data: data,
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function (result) {
                    status = result.status;
                    if (status == 'success') {
                        console.log(result.payment_status);
                        payment_status = result.payment_status;
                        document.getElementById(v_status_id).innerHTML = payment_status;
                        if (payment_status == '1') {
                            document.getElementById(v_status).innerHTML = payment_text_paid;
                        } else {
                            document.getElementById(v_status).innerHTML = payment_text_unpaid;
                        }
                    }
                    else {
                        //alert('update failed.. Please contact system administrator..');
                        errorModalCall(GetAppMessage('error_message_text'));
                    }
                },   //success
                error: function (ts) { errorModalCall(GetAppMessage('error_message_text'));
                //alert(ts.responseText + ' Update Invoice Payment Status=' + status)
                }
        }); //ajax-type
        return false;
    }

</script>
@endsection
