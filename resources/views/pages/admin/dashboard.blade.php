@extends('layouts.navbar')
@section('head_links')
<link href="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs4/jq-3.7.0/jszip-3.10.1/dt-1.13.6/af-2.6.0/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<link href="{{ asset('css/admin.css')}}" rel="stylesheet">
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
    <div class="row">
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading dark-blue">
                        <i class="fa fa-users fa-fw fa-2x"></i>
                    </div>
                </a>
                <div class="circle-tile-content dark-blue">
                    <div class="circle-tile-description text-faded">
                        Schools
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{ $stats['countSchools'] }}
                        <span id="sparklineA"></span>
                    </div>
                    <a href="{{ route('schools') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading green">
                        <i class="fa-solid fa-sack-dollar fa-fw fa-2x pt-4"></i>
                    </div>
                </a>
                <div class="circle-tile-content green">
                    <div class="circle-tile-description text-faded">
                        Revenue
                    </div>
                    <div class="circle-tile-number text-faded">
                        ${{ $stats['totalAmountActivePlans'] }}
                    </div>
                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading blue">
                        <i class="fa fa-shopping-cart fa-fw fa-2x"></i>
                    </div>
                </a>
                <div class="circle-tile-content blue">
                    <div class="circle-tile-description text-faded">
                        Subscriptions
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{ $stats['subActive'] }}
                        <span id="sparklineC"></span>
                    </div>
                    <a href="{{ route('subscriptions.getSubscription') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading orange">
                        <i class="fa fa-bell fa-fw fa-2x"></i>
                    </div>
                </a>
                <div class="circle-tile-content orange">
                    <div class="circle-tile-description text-faded">
                        Alerts
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{ $stats['alertCount'] }} New
                    </div>
                    <a href="{{ route('alert.index') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading red">
                        <i class="fa fa-tasks fa-fw fa-2x"></i>
                    </div>
                </a>
                <div class="circle-tile-content red">
                    <div class="circle-tile-description text-faded">
                        Tasks
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{ $stats['taskCount'] }}
                        <span id="sparklineB"></span>
                    </div>
                    <a href="{{ route('task.index') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6">
            <div class="circle-tile">
                <a href="#">
                    <div class="circle-tile-heading purple">
                        <i class="fa fa-comments fa-fw fa-2x"></i>
                    </div>
                </a>
                <div class="circle-tile-content purple">
                    <div class="circle-tile-description text-faded">
                        Messages
                    </div>
                    <div class="circle-tile-number text-faded">
                        {{ $stats['ContactFormCount'] }}
                        <span id="sparklineD"></span>
                    </div>
                    <a href="{{ route('contacts.index') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div cass="card">
        <div class="row card-body">
            <div class="col-lg-12">
                <canvas id="myChart3" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>

    <div cass="card">
        <div class="row card-body">
            <div class="col-lg-6">
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-lg-6">
                <canvas id="myChart2" style="max-height: 350px"></canvas>
            </div>
        </div>
    </div>s

  </div>
</div>
@endsection

@section('footer_js')
<script>
    const events = @json($events);
    const eventCounts = {};

    for (let i = 0; i < events.length; i++) {
        const startDate = new Date(events[i].date_start);
        const endDate = new Date(events[i].date_end);

        for (let date = startDate; date <= endDate; date.setDate(date.getDate() + 1)) {
            const dateString = date.toDateString();
            if (!eventCounts[dateString]) {
                eventCounts[dateString] = 1;
            } else {
                eventCounts[dateString]++;
            }
        }
    }

    const last5DaysWithEvents = Object.keys(eventCounts).slice(-10);
    const sortedLast5Days = last5DaysWithEvents.sort((a, b) => new Date(a) - new Date(b));
    const labels = sortedLast5Days.map(date => date.substr(4, 6));
    const data = sortedLast5Days.map(date => eventCounts[date]);

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
    const stats = @json($stats);

    const labels2 = ['Canceled', 'Active', 'Past Due', 'Trial'];
    const data2 = [stats.subCanceled, stats.subActive, stats.subPastDue, stats.subTrial];

    const backgroundColors = ['red', 'green', 'orange', 'purple'];

    const ctx2 = document.getElementById('myChart2');

    new Chart(ctx2, {
        type: 'pie',
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
                legend: {
                    position: 'top',
                },
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

<script>
    const subscriptions = @json($subsriptions);
    console.log(subscriptions);

    // Créez un objet pour stocker les données par date
    const dataByDate = {};

    // Remplissez l'objet avec les données
    subscriptions['data'].forEach(sub => {
        const date = new Date(sub.created * 1000).toLocaleDateString();
        if (!dataByDate[date]) {
            dataByDate[date] = {
                active: 0,
                due: 0,
                cancel: 0,
                trial: 0,
            };
        }
        if (sub.status === 'active') {
            dataByDate[date].active++;
        } else if (sub.status === 'past_due') {
            dataByDate[date].due++;
        } else if (sub.status === 'canceled') {
            dataByDate[date].cancel++;
        } else if (sub.status === 'trial') {
            dataByDate[date].trial++;
        }
    });

    const uniqueDates = Object.keys(dataByDate);
    const dateLabels = uniqueDates.reverse();
    const activeCounts = dateLabels.map(date => dataByDate[date].active).reverse();
    const dueCounts = dateLabels.map(date => dataByDate[date].due).reverse();
    const cancelCounts = dateLabels.map(date => dataByDate[date].cancel).reverse();
    const trialCounts = dateLabels.map(date => dataByDate[date].trial).reverse();

    const ctx4 = document.getElementById('myChart3');

    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: dateLabels,
            datasets: [{
                label: 'Active',
                data: activeCounts,
                backgroundColor: 'green',
            },
            {
                label: 'Due',
                data: dueCounts,
                backgroundColor: 'orange',
            },
            {
                label: 'Canceled',
                data: cancelCounts,
                backgroundColor: 'red',
            },
            {
                label: 'Trial',
                data: trialCounts,
                backgroundColor: 'purple',
            }],
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Nombre de Subscriptions par Catégorie par Date'
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
</script>

@endsection
