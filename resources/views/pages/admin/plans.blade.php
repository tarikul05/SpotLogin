@extends('layouts.navbar')

@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
    <div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Plans Stripe</h3>
            <div class="col-lg-6 col-md-6 col-xs-12 text-right">
                <a href="{{ route('plans.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajouter un plan
                </a>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Créé le</th>
                    <th>Plan par défaut</th>
                    <th>Taxe Code</th>
                    <th>Devise</th> <!-- Nouvelle colonne pour la devise -->
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                <tr>
                    <td>{{ $plan->id }}</td>
                    <td>{{ $plan->name }}
                     @if ($plan->price)
                        - Durée: {{ $plan->price->recurring->interval_count }} mois
                        - Prix: {{ $plan->price->unit_amount / 100 }} {{ $plan->price->currency }}
                     @endif
                    </td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($plan->created)->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $plan->default_price }}</td>
                    <td>{{ $plan->tax_code }}</td>
                    <td>{{ $plan->price->currency }}</td> <!-- Afficher la devise du prix associé au plan -->
                </tr>
                @endforeach
            </tbody>



        </table>
    </div>
</div>
@endsection
