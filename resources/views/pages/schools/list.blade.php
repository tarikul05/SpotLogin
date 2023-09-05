@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
@endsection

@section('content')
<div class="content">
	<div class="container-fluid body">
    <header class="panel-heading" style="border: none;">
      <div class="row panel-row">
        <div class="col-sm-6 col-xs-12 header-area">
          <div class="page_header_class">
            <label id="page_header" name="page_header">Registered Schools</label>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12 btn-area">
          <div class="pull-right">
              <input name="search_text" type="input" class="form-control search_text_box" id="search_text" value="" placeholder="Search">
          </div>
        </div>
      </div>
    </header>
    <div>
      <table id="list_tbl" class="display" style="width:100%">
          <thead>
              <tr>
                <!--<th>#</th>-->
                <th></th>
                <th>Name of the School</th>
                <th>Type</th>
                <th>Incorporation Date </th>
                <!--<th>Contact Person</th>-->
                <th>Principal E-Mail</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
          </thead>
          <tbody>
            @php ($i = 1)
            @foreach ($schools as $key => $school)
              <tr>
                <!--<td>{{ $i++ }}</td>-->
                <td>
                  <?php if (!empty($school->logoImage->path_name)): ?>
                    <img src="{{ $school->logoImage->path_name }}" width='30' height='30' class='img-circle account-img-small'/>
                  <?php else: ?>
                    <img src="{{ asset('img/photo_blank.jpg') }}" width='30' height='30' class='img-circle account-img-small'/>
                  <?php endif; ?>
                </td>

                <td>{{ $school->school_name }}</td>
                <td>{{ ($school->school_type == 'S')? 'School': 'Coach' }}</td>
                <td>{{ $school->incorporation_date }}</td>
                <!--<td>{{ $school->contact_firstname }}</td>-->
                <td>{{ $school->email }}<br>
                {{ $school->email2 }}
                </td>
                <td>
                  @if($school->is_active == 1)
                    {{__('Active')}}
                  @else
                    {{__('Inactive')}}
                  @endif
                </td>
                <td>
                  <a class="btn btn-sm btn-primary" href="{{ route('adminTeachers',[$school->id]) }}"> {{ __('Teachers')}} </a>
                  <a class="btn btn-sm btn-warning" href="{{ route('adminStudents',[$school->id]) }}"> {{ __('Students')}} </a>
                   <a class="btn btn-sm btn-info" href="{{ route('adminInvoiceList',[$school->id]) }}"> {{ __('Invoices')}} </a>
                   <a class="btn btn-sm btn-theme-success" href="{{ URL::to('/admin/school-update/'.$school->id)}}"> {{ __('Edit')}} </a>
                   <a class="btn btn-sm btn-danger" onclick="deactivateUser({{ $school->id }})"> {{ __('Disable')}}</a>
                </td>
              </tr>
            @endforeach


          </tbody>
      </table>
    </div>
  </div>
</div>
@endsection


@section('footer_js')
<script type="text/javascript">
    $(document).ready( function () {

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

        //var lang_json_file=getLangJsonFileName();
            $('#list_tbl').DataTable( {
                "responsive": true,
                "searching": true,
                "bProcessing": true,
                "bDestroy": true,
                "order": [[2, "asc"]],
                "bFilter": false,
                "bInfo": true,
                "lengthChange": false,
                "info": true,
                // "language": {
                //     "url": lang_json_file,
                //     paginate: {
                //       next: '>', // or '?'
                //       previous: '<' // or '?'
                //     }
                // },
                "pageLength": 10,
                "sPrevious": "<",
                "sNext": ">" // This is the link to the next page
                ,"bJQueryUI": false
            });

            var table = $('#list_tbl').DataTable();
            $('#search_text').on('keyup change', function () {
                //table.search(this.value).draw();
                $('#list_tbl').DataTable().search($(this).val()).draw();

            });
    });


    function deactivateUser(userId) {
        console.log('stop ID ', userId)
        data = 'user_id=' + userId
    $.ajax({
        url: BASE_URL + '/deactivate_user',
        type: 'POST',
        data,
        dataType: 'json',
        async:false,
        success: function(response) {
           // reload the current page
           window.location.reload();
        },
        error: function(e) {
            alert('Erreur lors de la désactivation de l\'utilisateur');
        }
    });
}


</script>
@endsection
