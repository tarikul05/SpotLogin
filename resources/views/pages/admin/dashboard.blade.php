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
        <div class="col-lg-2 col-sm-6 col-xs-12">
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
        <div class="col-lg-2 col-sm-6 col-xs-12">
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
                    <a href="{{ route('subscriptions.getActiveSubscriptions') }}" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-xs-12">
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
        <div class="col-lg-2 col-sm-6 col-xs-12">
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
        <div class="col-lg-2 col-sm-6 col-xs-12">
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
        <div class="col-lg-2 col-sm-6 col-xs-12">
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

    <!--<div cass="card">
        <div class="row card-body">
            <div class="col-lg-12">
                <canvas id="myChart3" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>-->
    <br><br>
    <div cass="card">
        <div class="row card-body">
            <div class="col-lg-6">
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-lg-6">
                <canvas id="myChart2" style="max-height: 350px"></canvas>
            </div>
        </div>
    </div>

    <br><br>
    <h5>    <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" width="120"> Liste des Événements</h5>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Description</th>
                <th>Reçu le</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events_stripe as $event)
            @if($event['type'])
            <tr>

                <td>
                @if ($event['type'] === 'setup_intent.created')
                    Une intention de paiement a
                @if($event['data']['object']['status'] === "requires_payment_method") {{ "échouée" }} @else {{ "réussie" }} @endif
               @elseif ($event['type'] === 'invoice.upcoming')
                    {{ $event['data']['object']['customer_email'] }} a une facture à venir d'un montant de {{ number_format($event['data']['object']['total'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} (avec paiement automatique prévu) le {{ \Carbon\Carbon::createFromTimestamp($event['data']['object']['next_payment_attempt'])->format('j M.') }}
                @elseif ($event['type'] === 'payout.paid')
                    Un virement de {{ number_format($event['data']['object']['amount'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} devrait apparaître sur votre relevé bancaire
                @elseif ($event['type'] === 'payout.reconciliation_completed')
                    Le rapport de rapprochement d'un virement de {{ number_format($event['data']['object']['amount'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} est prêt
                @elseif ($event['type'] === 'coupon.created')
                    Un nouveau bon de réduction doté de l'ID {{ $event['data']['object']['id'] }} a été créé
                @elseif ($event['type'] === 'customer.created')
                    {{ $event['data']['object']['name'] }} est un nouveau client
                    @elseif ($event['type'] === 'payout.created')
                    Un nouveau virement de {{ number_format($event['data']['object']['amount'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été créé et sera versé le {{ \Carbon\Carbon::createFromTimestamp($event['data']['object']['arrival_date'])->format('j M.') }}
                    @elseif ($event['type'] === 'customer.subscription.trial_will_end')
                    La période d'essai dont bénéficié le client {{ $event['data']['object']['metadata']['note'] }} se termine le {{ \Carbon\Carbon::createFromTimestamp($event['data']['object']['trial_end'])->format('j M.') }}
                    @elseif ($event['type'] === 'customer.subscription.deleted')
                    L'abonnement de {{ $event['data']['object']['metadata']['note'] }} à {{ $event['data']['object']['items']['data'][0]['plan']['id'] }} a été annulé
                    @elseif ($event['type'] === 'invoice.finalized')
                    Un brouillon de facture de {{ number_format($event['data']['object']['total'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été finalisé pour {{ $event['data']['object']['customer_email'] }}
                    @elseif ($event['type'] === 'invoice.payment_succeeded')
                    Le paiement de la facture de {{ $event['data']['object']['customer_email'] }} d'un montant de {{ number_format($event['data']['object']['total'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été effectué
                    @elseif ($event['type'] === 'invoice.paid')
                    La facture de {{ $event['data']['object']['customer_email'] }} d'un montant de  {{ number_format($event['data']['object']['total'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été payée
                    @elseif ($event['type'] === 'invoice.updated')
                    La facture de {{ $event['data']['object']['customer_email'] }} a été modifiée
                    @elseif ($event['type'] === 'payment_intent.created')
                    Une nouvelle tentative de paiement {{ $event['data']['object']['id'] }} de {{ number_format($event['data']['object']['amount'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été créee
                    @elseif ($event['type'] === 'payment_intent.succeeded')
                    Un montant de {{ number_format($event['data']['object']['amount'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été débité à {{ $event['data']['object']['receipt_email'] }}
                    @elseif ($event['type'] === 'payment_method.attached')
                    Un nouveau moyen de paiement {{ $event['data']['object']['type'] }} a été ajouté pour le client {{ $event['data']['object']['customer'] }}
                    @elseif ($event['type'] === 'payment_method.updated')
                    Un moyen de paiement {{ $event['data']['object']['id'] }} a été modifié
                    @elseif ($event['type'] === 'payment_method.deleted')
                    Un moyen de paiement {{ $event['data']['object']['id'] }} a été supprimé
                    @elseif ($event['type'] === 'customer.subscription.created')
                    L'abonnement de {{ $event['data']['object']['metadata']['note'] }} à {{ $event['data']['object']['items']['data'][0]['plan']['id'] }} a été créé
                    @elseif ($event['type'] === 'customer.subscription.updated')
                    L'abonnement de {{ $event['data']['object']['metadata']['note'] }} à {{ $event['data']['object']['items']['data'][0]['plan']['id'] }} a été mis à jour
                    @elseif ($event['type'] === 'invoice.payment_failed')
                    Le paiement de la facture de {{ $event['data']['object']['customer_email'] }} a échoué
                    @elseif ($event['type'] === 'customer.subscription.deleted')
                    L'abonnement de {{ $event['data']['object']['metadata']['note'] }} à {{ $event['data']['object']['items']['data'][0]['plan']['id'] }} a été annulé
                    @elseif ($event['type'] === 'invoice.payment_action_required')
                    Le paiement de la facture de {{ $event['data']['object']['customer_email'] }} est requis
                    @elseif ($event['type'] === 'customer.subscription.trial_started')
                    La période d'essai de {{ $event['data']['object']['metadata']['note'] }} a démarré
                    @elseif ($event['type'] === 'customer.subscription.trial_ended')
                    La période d'essai de {{ $event['data']['object']['metadata']['note'] }} a terminé
                    @elseif ($event['type'] === 'customer.subscription.updated')
                    L'abonnement de {{ $event['data']['object']['metadata']['note'] }} à {{ $event['data']['object']['items']['data'][0]['plan']['id'] }} a été mis à jour

                    @elseif ($event['type'] === 'invoice.voided')
                    La facture de {{ $event['data']['object']['customer_email'] }} a été annulée
                    @elseif ($event['type'] === 'charge.succeeded')
                    Le paiement de la facture de {{ $event['data']['object']['customer_email'] }} a été effectué
                    @elseif ($event['type'] ===  'invoice.created')
                    La facture de {{ $event['data']['object']['customer_email'] }} d'un montant de {{ number_format($event['data']['object']['total'] / 100, 2) }} {{ strtoupper($event['data']['object']['currency']) }} a été créée
                    @elseif ($event['type'] === 'invoice.updated')
                    La facture de {{ $event['data']['object']['customer_email'] }} a été modifiée
                    @elseif ($event['type'] === 'invoice.payment_action_required')
                    La facture de {{ $event['data']['object']['customer_email'] }} est requise
                    @elseif ($event['type'] === 'invoice.payment_succeeded')
                    Le paiement de la facture de {{ $event['data']['object']['customer_email'] }} a été effectué
                    @elseif ($event['type'] === 'balance.available')
                    Votre solde a des nouvelles opérations disponibles
                    @elseif ($event['type'] === 'customer.discount.created')
                    Le bon de réduction {{ $event['data']['object']['coupon']['name'] }} a été utilisé
                    @elseif ($event['type'] === 'customer.updated')
                    Les détails du client {{ $event['data']['object']['email'] }} ont été mise à jour
                @else
                    {{ $event['type'] }}
                @endif
                </td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($event['created'])->format('Y-m-d H:i:s') }}</td>
                <td><a href="https://dashboard.stripe.com/test/events/{{ $event['id'] }}" target="_blank">voir sur Stripe</a></td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

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
