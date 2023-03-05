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
                    @if($type == 'school')
                    <label id="page_header_id" name="page_header_id">{{ __('Invoicing for the school') }}</label>
                    @else
                    <label id="page_header_id" name="page_header_id">{{ __('Invoicing for myself') }}</label>
                    @endif
                </div>
                @if($AppUI->isTeacherSchoolAdmin())
                    @if($type == 'school')
                        <Button class="btn btn-primary btn-sm" onclick="goLink('')">Move to Teacher Invoiced</Button>
                    @else
                        <Button class="btn btn-primary btn-sm" onclick="goLink('school')">Move to School Invoiced</Button>
                    @endif
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 btn-area">
                <div class="pull-right">
                    <input name="search_text" type="input" class="form-control search_text_box" id="search_text" value="" placeholder="">
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
                    <th>{{ __("Student’s name") }}</th>
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
                                    route('editStudent',['student' => $event->person_id]) }}?action=edit&tab=tab_3&inv_type={{$type}}" 
                            class="btn btn-sm btn-theme-success inv_butt_tobe_charged_cls">
                            {{ __('View items to be invoiced') }}</a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
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


    function goLink(text) {
        window.location.href = BASE_URL + '/admin/{{$schoolId}}/student-invoices/'+text
    }
</script>
@endsection