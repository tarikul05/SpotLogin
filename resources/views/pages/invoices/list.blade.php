@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
  <div class="container-fluid">
    <header class="panel-heading" style="border: none;">
        <div class="row panel-row" style="margin:0;">
            <div class="col-sm-6 col-xs-12 header-area">
                <div class="page_header_class">
                    <label id="page_header_id" name="page_header_id">List of invoice(s)</label></div>
            </div>
            <!--<div class="pull-right col-xs-6" style="text-align:right;">-->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 btn-area">
                <div class="pull-right">
                    <input name="search_text" type="input" class="form-control search_text_box" id="search_text" value="" placeholder="">
                
                <!--<a class="btn btn-info" href="#"><i class="fa fa-flag" onclick="buttn_click()"></i> Enregistrer</a>-->
                <!-- <a id="save_btn" name="save_btn" href="#" class="btn btn-primary" style="float: right;">Save</a> -->
                </div>
            </div>
        </div>
    </header>
    <div class="table-responsive1">
        <input id="seleted_auto_id" name="seleted_auto_id" style="display: none;">
        <input id="p_school_id" name="p_school_id" style="display: none;">
        
        
        <input id="seleted_invoice_type" name="seleted_invoice_type" style="display: none;">
        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status">
            <option value="1">Paid</option>
            <option value="0">Unpaid</option>
        </select>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('#') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Event') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th></th>
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
                        <td style="display: none">{{ $invoice->id; }}</td>

                        <td style="display: none"><div id="status_id_{{ $invoice->id; }}">{{$invoice->payment_status}}</div></td>
                        <td class="txt-grey text-center">{{ $i }} </td>
                        <td>{{ date('d M Y', strtotime($invoice->date_invoice)); }}</td>
                        @php
                        if($invoice->invoice_type ==0){
                            @endphp
                            <td>{{ $invoice_type_all[$invoice->invoice_type]; }}(M)</td>

                            @php
                        } else {
                            @endphp
                            <td>{{ $invoice_type_all[$invoice->invoice_type]; }}</td>
                            @php
                        }
                        @endphp
                        
                        @php
                        $invoice_name = $invoice->invoice_name;
                        if($invoice->invoice_type ==1){
                            $invoice_name .= '-'.$invoice->client_name;
                        } else {
                            $invoice_name .= '-'.$invoice->seller_name;
                        }
                        @endphp
                        <td>{{ $invoice_name}}</td>
                        
                        <td>{{ $invoice->total_amount; }}</td>
                        @if ($invoice->payment_status == 0)
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
                            <td></td>
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
                                    if ($invoice->invoice_type == 0) {
                                        $edit_view_url = '/admin/'.$schoolId.'/manual-invoice/'.$invoice->id;
                                    } else {
                                        $edit_view_url = '/admin/invoice/'.$invoice->id;
                                    }
                                @endphp
                                

                                @if ($invoice->invoice_status > 1)
                                
                                    <a class="dropdown-item" href="{{ $edit_view_url }}">
                                        <i class="fa fa-eye txt-grey" aria-hidden="true"></i> 
                                        {{ __('View')}}
                                    </a>
                                    <a class="dropdown-item" href="{{route('generatePDF',['invoice'=> $invoice->id]) }}">
                                        <i class="fa fa-file-pdf-o txt-grey" aria-hidden="true"></i> 
                                        {{ __('PDF')}}
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ $edit_view_url }}">
                                        <i class="fa fa-pencil-alt txt-grey" aria-hidden="true"></i> 
                                        {{ __('Edit')}}
                                    </a>
                                @endif

                                @if (($invoice->invoice_status > 1) && ($invoice->payment_status == 0)) 
                                    <a class="dropdown-item txt-grey send_email" href="javascript:void(0)" onclick="SendPayRemiEmail({{$invoice->id}},{{$invoice->invoice_type}},{{$invoice->school_id}})"><i class="fa fa-envelope txt-grey"></i> {{__('Send Invoice')}}</a>
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

  <div class="modal fade confirm-modal" id="email_list_modal" tabindex="-1" aria-hidden="true"
    aria-labelledby="email_list_modal" name="email_list_modal">
        <div class="modal-dialog mt-5" role="document">
            <div class="modal-content">
                <div class="modal-header text-center border-0">
                    <h4 class="light-blue-txt gilroy-bold">Send a reminder</h4>
                </div>
                <div class="modal-body row" style="margin: 0 auto;padding-top: 0;">
                    <!-- <form id="email_list_form" name="email_list_form" method="POST"> -->

                        <div class="form-group col-md-12" id="father_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="father_email_chk" name="father_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="father_email_chk" id="father_email_cap" name="father_email_cap">Father's email:</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12" id="mother_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="mother_email_chk" name="mother_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="mother_email_chk" id="mother_email_cap" name="mother_email_cap">Mother's email:</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12" id="student_email_div">
                            <div class="btn-group text-left">
                                <input type="checkbox" id="student_email_chk" name="student_email_chk" value="" style="float: left;margin: 8px 5px;width: 20px;height: 20px;" checked>
                                <label for="student_email_chk" id="student_email_cap" name="student_email_cap">Student's email:</label>
                            </div>

                        </div>

                        <div class="form-group col-md-12">
                            <div class="text-left">
                                <div class="checked">
                                    <input class="form-control" style="display: block;" type="email" id="other_email" name="other_email" placeholder="other email if any." value="" maxlength="100">
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
                                <button type="submit" id="email_send" class="btn btn-sm btn-theme-success">Send</button>
                        </div>

                    <!-- </form> -->

                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {
        

        var table = $('#example').DataTable({
            //"responsive": true,
            //"searching": true,
            //"bProcessing": true,
            "bDestroy": true, 
            "order": [[0, "asc"]],
            "bFilter": true,
            "bInfo": false,
            "lengthChange": false,
            "info": true,
            "pageLength": 10  
            ,'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [-1] /* 1st one, start by the right */
            }],
            //,"paging": false
            //"serverSide":true,
            "pagingType": "simple_numbers"
            
        });
        $('#search_text').on('keyup change', function () {
            //table.search(this.value).draw();
            //table.clear().draw();
            console.log($(this).val());
            table.search($(this).val()).draw();
            
        });
        $("#example_filter").hide();
        
    } );
    function pay_reminder_email(p_event_school_id,p_from_date,p_to_date,p_event_type_id,p_student_id,p_teacher_id,p_event_id){
        var data='p_event_school_id='+p_event_school_id+'&p_from_date='+p_from_date+'&p_to_date='+p_to_date+'&p_event_type_id='+p_event_type_id+'&p_student_id='+p_student_id+'&p_teacher_id='+p_teacher_id+'&p_event_id='+p_event_id;
        
            //e.preventDefault();
            $.ajax({type: "POST",
                url: BASE_URL + '/validate_multiple_events',
                data: data,
                dataType: "JSON",
                success:function(result){
                    document.getElementById("btn_validate_events").style.display = "none";
                    var status =  result.status;
                    //$('#calendar').fullCalendar('removeEvents');
                    //$('#calendar').fullCalendar( 'removeEventSource', JSON.parse(json_events) )
                    //alert(status);
                    getFreshEvents();      //refresh calendar 
                    //window.location.reload(false);
                    
                },   //success
                error: function(ts) { 
                    errorModalCall('validate_multiple_events:'+ts.responseText+'-'+GetAppMessage('error_message_text'));
                    // alert(ts.responseText)
                }
            }); //ajax-type
    }

    function SendPayRemiEmail(p_value,p_invoice_type,p_school_id) {
        
        $('#seleted_auto_id').val(p_value);
        $('#p_school_id').val(p_school_id);
        
        $('#seleted_invoice_type').val(p_invoice_type);
        //console.log('p_value='+p_value);
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
                    confirmPayReminderModalCall(p_value,'Do you want to validate events',result.data,p_school_id);
                    return false;
                    
                }
                else {
                    errorModalCall('{{ __("Event validation error ")}}');
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


        console.log(p_seleted_invoice_type);
        if (p_seleted_invoice_type == 2) {
            SendInvoiceEmail('send_approve_pdf_invoice', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        } else {
            SendInvoiceEmail('reminder_email_unpaid', p_inv_auto_id, p_attached_file, p_emails,p_school_id);
        }


    });


    function UpdatePaymentStatus(p_auto_id) {
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
        let payment_text_paid = "<span class='gilroy-bold' id='" + v_status + "' style='color:" + ((payment_status == 0) ? '#FF8000' : '#97CC04') + ";text-align:center;>'>Paid</span>";
        let payment_text_unpaid = "<span class='gilroy-bold' id='" + v_status + "' style='color:" + ((payment_status == 0) ? '#FF8000' : '#97CC04') + ";text-align:center;>'>Unpaid</span>";

        
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