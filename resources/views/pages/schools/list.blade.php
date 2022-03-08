@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
<div class="content">
	<div class="container-fluid body">
    <header class="panel-heading" style="border: none;">
      <div class="row panel-row" style="margin:0;">
        <div class="col-sm-6 col-xs-12 header-area">
          <div class="page_header_class">
            <label id="page_header" name="page_header">School Key Information</label>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12 btn-area">
          <div class="pull-right">
              <input name="search_text" type="input" class="form-control search_text_box" id="search_text" value="" placeholder="Search">
          </div>
        </div>    
      </div>          
    </header>
    <div class="m-4">
      <table id="list_tbl" class="display" style="width:100%">
          <thead>
              <tr>
                  <th>Name of the School</th>
                  <th>Type</th>
                  <th>Incorporation Date </th>
                  <th>Contact Person</th>
                  <th>Contact Person e-Mail</th>
                  <th>Status</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
            @foreach ($schools as $key => $school)
              <tr>
                <td>{{ $school->school_name }}</td>
                <td>School</td>
                <td>{{ $school->incorporation_date }}</td>
                <td>{{ $school->contact_firstname }}</td>
                <td>{{ $school->email }}</td>
                <td>{{ $school->is_active }}</td>
                <td>
                  <a href="{{ URL::to('/admin/school-update/'.$school->id)}}">edit </a>
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
        
        //var lang_json_file=getLangJsonFileName();
            $('#list_tbl').DataTable( {
                //"responsive": true,
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
</script>
@endsection