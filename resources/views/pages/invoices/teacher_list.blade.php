@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <style>
        #example1 td {
            border:none!important;
            border-bottom:1px solid #EEE!important;

            margin-bottom:15px!important;
            padding-top:7px!important;
            padding-bottom:7px!important;
        }
        #example1 td img {
            height:30px!important;
            width:30px!important;
        }
        #example1 tr:hover {
            border:1px solid #EEE!important;
            background-color:#fcfcfc!important;
        }
        #example1 th {
            border:none!important;
            border-bottom:3px solid #EEE!important;
            font-size:13px;
            font-weight:bold;
        }
        </style>
@endsection

@section('content')
  <div class="container">

    <div class="row justify-content-center pt-3">
        <div class="col-md-10">

            <div class="page_header_class pt-1 pb-2" style="position: static;">
                @if($type == 'school')
                <h5 class="titlePage">{{ __('Invoicing for the school') }}</h5>
                @else
                <h5 class="titlePage">{{ __('Invoicing System') }}</h5>
                @endif
            </div>

            <div class="card2" style="border-radius:10px;">
                <div class="card-header titleCardPage d-flex justify-content-between align-items-center">
                    <b class="d-none d-sm-inline">{{ __('Invoice System') }}</b>
                    <input name="search_text" type="input" class="form-control search_text_box" id="search_text"  placeholder="Find a teacher">
                </div>
                <div class="card-body">

    <div class="table-responsive1">
        <input id="seleted_auto_id" name="seleted_auto_id" style="display: none;">
        <input id="p_school_id" name="p_school_id" style="display: none;">
        <input id="seleted_invoice_type" name="seleted_invoice_type" style="display: none;">
        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status"></select>
        <table id="example1" style="width:100%">
            <thead>
                <tr>
                    <th class="titleFieldPage">{{ __('Name of the Teacher') }}</th>
                    <th class="titleFieldPage" style="text-align: center;">{{ __('Items') }}</th>
                    <th class="titleFieldPage" style="text-align: right;">{{ __('Action') }}</th>
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
                        <td style="text-align: center;">
                            <span class="badge bg-primary" style="font-size:15px;">
                                {{ $event->invoice_items; }}
                            </span>
                        </td>
                        <td style="text-align: right;">
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
</div>
</div>
@endsection


@section('footer_js')
<script type="text/javascript">
  $(document).ready(function () {

    var table = $('#example1').DataTable({
    stateSave: true,
    dom: '<"top"f>rt<"bottom"lp><"clear">',
    ordering: true, // Disable column sorting
    searching: true, // Enable searching with the search input
    paging: true, // Disable pagination
    info: false, // Disable information display
    columnDefs: [
    { targets: [0,1], orderable: true }, // Autoriser le tri pour les colonnes 0 et 4
    { targets: '_all', orderable: false } // Désactiver le tri pour toutes les autres colonnes
    ]
    });

    $('#search_text').on('keyup change', function () {
        table.search($(this).val()).draw();
    });

    $("#example1_filter").hide();
    });

    function goLink(text) {
        window.location.href = BASE_URL + '/admin/{{$schoolId}}/teacher-invoices/'+text
    }
</script>
@endsection