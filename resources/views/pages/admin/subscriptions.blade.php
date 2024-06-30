@extends('layouts.navbar')

@section('head_links')

@section('content')
<div class="content">






    <br><br><br>
    <div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Subscriptions</h3>
            <!-- Vous pouvez ajouter des boutons ou des liens ici si nécessaire -->
        </div>
    
        @foreach ($subscriptionsByStatus as $status => $subscriptions)
            <h2>Abonnements {{ ucfirst(str_replace('_', ' ', $status)) }}</h2>
            
            @if(count($subscriptions) > 0)
                <table class="table table-stripped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            @if ($status == 'canceled')
                                <th>Date d'annulation</th>
                                <th>Raison de l'annulation</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->price_name }}<br>
                                    {{ $subscription->items->data[0]->plan->amount/100 }} {{ $subscription->items->data[0]->plan->currency }} /{{ $subscription->items->data[0]->plan->interval }}</td>
                                <td><a href="details/user={{ $subscription->metadata->userID }}&school={{ $subscription->metadata->schoolID }}">{{ $subscription->metadata->name }} ({{ $subscription->metadata->email }})</a></td>
                                <td>{{ getStatusExplanation($subscription->status) }}</td>
                                <td>{{ date('d-m-Y', $subscription->current_period_start) }}</td>
                                <td>{{ date('d-m-Y', $subscription->current_period_end) }}</td>
                                @if ($status == 'canceled')
                                    <td>{{ date('d-m-Y', $subscription->canceled_at) }}</td>
                                    <td>{{ $subscription->cancel_at_period_end ? 'Fin de la période' : 'Annulé immédiatement' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun abonnement {{ $status }}.</p>
            @endif
        @endforeach
    </div>


    
</div>
@endsection


<?php

// Ajoutez cette fonction d'aide à la fin du fichier Blade
function getStatusExplanation($status) {
    switch ($status) {
        case 'active':
            return 'L\'abonnement est actif et en cours de facturation.';
        case 'canceled':
            return 'L\'abonnement a été annulé et n\'est plus facturé.';
        case 'incomplete':
            return 'L\'abonnement a été créé mais son premier paiement a échoué.';
        case 'incomplete_expired':
            return 'L\'abonnement n\'a pas été complété et a expiré.';
        case 'past_due':
            return 'L\'abonnement est en retard de paiement.';
        case 'trialing':
            return 'L\'abonnement est en période d\'essai gratuite.';
        case 'unpaid':
            return 'L\'abonnement est en statut impayé car tous les paiements ont échoué.';
        default:
            return 'Statut inconnu.';
    }
}
?>