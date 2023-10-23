@extends('layouts.navbar')

@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
    <div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Rapport Comptable</h3>
            <!-- Vous pouvez ajouter des boutons ou des liens ici si nécessaire -->
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>PRIX</th>
                    <th>ACHETEUR</th>
                    <th>ÉTAT</th>
                    <th>PLAN</th>
                    <th>DATE DE CRÉATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription['plan']['amount']/100 * $subscription['plan']['interval_count'] }}€</td>
                    <td>{{ $subscription['metadata']['note'] }}</td>
                    <td>
                        @if($subscription['status'] == 'active')
                          <span class="badge badge-success">{{ $subscription['status'] }}</span>
                        @elseif($subscription['status'] == 'trialing')
                          <span class="badge badge-warning">{{ $subscription['status'] }}</span>
                        @elseif($subscription['status'] == 'canceled')
                          <span class="badge badge-danger">{{ $subscription['status'] }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $subscription['price_name'] }}<br>
                    </td>
                    <td>{{ date('j M Y, H:i', $subscription['created']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Affichage du total en argent -->
        <div class="total-amount">
            <p>Total : {{ $totalAmount / 100 }}€</p>
            @if ($discountAmount > 0)
                <p>Remise : {{ $discountAmount / 100 }}€</p>
                <p>Total après remise : {{ ($totalAmount - $discountAmount) / 100 }}€</p>
            @endif

        </div>
    </div>
</div>
@endsection
