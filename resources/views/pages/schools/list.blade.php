@extends('layouts.navbar')
@section('head_links')
<link href="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script type="text/javascript">
    $(document).ready( function () {
        new DataTable('#list_tbl');
    });
</script>

@endsection

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
    <h3>Schools list</h3>
      <table id="list_tbl" class="table table-stripped table-hover" style="width:100%">
          <thead>
              <tr>
                <!--<th>#</th>-->
                <th></th>
                <th>School</th>
                <th>Type</th>
                <th>Created</th>
                <!--<th>Contact Person</th>-->
                <th>E-Mail</th>
                <th>Status</th>
                <th width="80">Action</th>
              </tr>
          </thead>
          <tbody>
            @php ($i = 1)
            @foreach ($schools as $key => $school)
              <tr>
                <!--<td>{{ $i++ }}</td>-->
                <td>
                  <?php if (!empty($school->logoImage->path_name)): ?>
                    <img src="{{ $school->logoImage->path_name }}" width='30' height='30' class='img-thumbnail'/>
                  <?php else: ?>
                    <img src="{{ asset('img/photo_blank.jpg') }}" width='30' height='30' class='img-thumbnail'/>
                  <?php endif; ?>
                </td>

                <td><b>{{ $school->school_name }}</b><br>[Login ID : {{ $school->school_code }}]</td>
                <td>{{ ($school->school_type == 'S')? 'School': 'Coach' }}</td>
                <td>{{ $school->incorporation_date }}</td>
                <!--<td>{{ $school->contact_firstname }}</td>-->
                <td>{{ $school->email }}<br>
                {{ $school->email2 }}
                </td>
                <td>
                  @if($school->is_active == 1)
                    <span class="badge bg-success text-white">{{__('Active')}}</span>
                  @else
                  <span class="badge bg-warning">{{__('Inactive')}}</span>
                  @endif
                </td>
                <td>

                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="{{ route('adminTeachers',[$school->id]) }}">{{ __('Teachers') }}</a>
                          <a class="dropdown-item" href="{{ route('adminStudents',[$school->id]) }}">{{ __('Students') }}</a>
                          <a class="dropdown-item" href="{{ route('adminInvoiceList',[$school->id]) }}">{{ __('Invoices') }}</a>
                          <a class="dropdown-item" href="{{ URL::to('/admin/school-update/'.$school->id)}}">{{ __('Edit') }}</a>
                          <a class="dropdown-item" onclick="deactivateUser({{ $school->id }})">{{ __('Disable') }}</a>
                        </div>
                      </div>

                  </td>
              </tr>
            @endforeach


          </tbody>
      </table>
      <br><br>
  </div>
</div>
@endsection


@section('footer_js')

<script>
    // Assume that you have an array of events with date_start and date_end properties.
    const events = @json($events); // Assurez-vous que vos événements sont correctement formatés en JSON.

    // Create an object to store the event count for each day.
    const eventCounts = {};

    // Calculate the 5 most recent days with events and count the events for each day.
    for (let i = 0; i < events.length; i++) {
        const startDate = new Date(events[i].date_start);
        const endDate = new Date(events[i].date_end);

        // Iterate through the days within the date range of each event.
        for (let date = startDate; date <= endDate; date.setDate(date.getDate() + 1)) {
            const dateString = date.toDateString();
            if (!eventCounts[dateString]) {
                eventCounts[dateString] = 1;
            } else {
                eventCounts[dateString]++;
            }
        }
    }

    // Get the labels and data for the chart from the last 5 days with events.
    const last5DaysWithEvents = Object.keys(eventCounts).slice(-10);
    const sortedLast5Days = last5DaysWithEvents.sort((a, b) => new Date(a) - new Date(b)); // Tri des dates
    const labels = sortedLast5Days.map(date => date.substr(4, 6)); // Extraction "Mon 01" à partir de "Mon Jan 01 2023".
    const data = sortedLast5Days.map(date => eventCounts[date]);

    // Créez et affichez le graphique.
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Events',
                data: data,
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    // Assumez que vous avez un tableau de stats avec les valeurs subTrial, subActive, subCanceled, subPastDue.
    const stats = @json($stats); // Assurez-vous que vos stats sont correctement formatées en JSON.

    const labels2 = ['Canceled', 'Active', 'Past Due', 'Trial'];
    const data2 = [stats.subCanceled, stats.subActive, stats.subPastDue, stats.subTrial];

    const backgroundColors = ['red', 'purple', 'yellow', 'green'];

    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels2,
            datasets: [{
                label: 'Subscription Counts',
                data: data2,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                datalabels: {
                    color: 'black',
                    formatter: function(value, context) {
                        return context.chart.data.labels[context.dataIndex];
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements[0]) {
                    const dataIndex = elements[0].index;
                    const isHidden = !ctx2.data.datasets[0]._meta[0].hidden[dataIndex];
                    ctx2.data.datasets[0]._meta[0].hidden[dataIndex] = isHidden;
                    ctx2.update();
                }
            }
        }
    });
</script>

<script type="text/javascript">
    $(document).ready( function () {

        new DataTable('#list_tbl');

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
