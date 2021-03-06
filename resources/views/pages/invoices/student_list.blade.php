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
                    <label id="page_header_id" name="page_header_id">Student's Bill</label></div>
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
        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status"></select>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('#') }}</th>
                    <th>{{ __('Student’s name') }}</th>
                    <th>{{ __('Items') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
            @if (!empty($allStudentEvents))
                @php
                    $i = 0;
                @endphp
                @foreach($allStudentEvents as $event)
                    @php
                        $i++;
                    @endphp
                
                    <tr>
                        <td class="txt-grey text-center">{{ $i }} </td>
                        <td>
                            <?php if (!empty($event->profile_image)): ?>
                                <img src="{{ $event->profile_image }}" class="admin_logo" id="admin_logo"  alt="globe">
                            <?php else: ?>
                                <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                            <?php endif; ?>
                        {{ $event->student_full_name; }}</td>
                        <td>{{ $event->invoice_items; }}</td>
                       
                        <td align="center">
                            <a id="inv_butt_tobe_charged" name="inv_butt_tobe_charged" 
                            href="{{ auth()->user()->isSuperAdmin() ? 
                                    route('adminEditStudent',['school'=> $schoolId,'student'=> $event->person_id]) : 
                                    route('editStudent',['student' => $event->person_id]) }}?action=edit&tab=tab_3" 
                            class="btn btn-sm btn-theme-success inv_butt_tobe_charged_cls">
                            View items to be invoiced</a>
                        </td>
                    
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


</script>
@endsection