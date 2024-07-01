@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
  <div class="container">

    <div class="row justify-content-center pt-3">
        <div class="col-md-10">

            <div class="page_header_class pt-1" style="position: static;">
                @if($type == 'school')
                <h5 class="titlePage">{{ __('Invoicing for the school') }}</h5>
                @else
                <h5 class="titlePage">{{ __('Invoicing System') }}</h5>
                @endif
            </div>


    <div class="table-responsive1">
        <input id="seleted_auto_id" name="seleted_auto_id" style="display: none;">
        <input id="p_school_id" name="p_school_id" style="display: none;">
        <input id="seleted_invoice_type" name="seleted_invoice_type" style="display: none;">
        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status"></select>
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>{{ __('Name of the Teacher') }}</th>
                    <th>{{ __('Items') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
            @if (!empty($allTeacherEvents))
                @php
                    $i = 0;
                @endphp
                @foreach($allTeacherEvents as $event)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td>
                            <?php if (!empty($event->profile_image)): ?>
                                <img src="{{ $event->profile_image }}" class="admin_logo" id="admin_logo"  alt="globe">
                            <?php else: ?>
                                <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                            <?php endif; ?>
                     {{ $event->teacher_full_name; }}</td>
                        <td align="center">{{ $event->invoice_items; }}</td>
                        <td align="center">
                            <a id="inv_butt_tobe_charged" name="inv_butt_tobe_charged" 
                            href="{{ auth()->user()->isSuperAdmin() ? 
                                    route('adminEditTeacher',['school'=> $schoolId,'teacher'=> $event->person_id]) : 
                                    route('editTeacher',['teacher' => $event->person_id]) }}?action=edit&tab=tab_3&inv_type={{$type}}"
                            class="btn btn-sm btn-primary inv_butt_tobe_charged_cls">
                            {{ __('View items to be invoiced') }}</a>
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
        window.location.href = BASE_URL + '/admin/{{$schoolId}}/teacher-invoices/'+text
    }
</script>
@endsection