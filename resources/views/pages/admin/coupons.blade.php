@extends('layouts.navbar')
@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
        <div class="row">
        <h3 class="col-lg-6 col-md-6 col-xs-12">Coupons Stripe</h3>
        <div class="col-lg-6 col-md-6 col-xs-12 text-right">
        <a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Ajouter un coupon
        </a>
        </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Montant</th>
                    <th>Devise</th>
                    <th>Durée</th>
                    <th>Mois de durée</th>
                    <th>Pourcentage de réduction</th>
                    <th>Valide</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $coupon)
                <tr>
                    <td>{{ $coupon['id'] }}</td>
                    <td>{{ $coupon['name'] }}</td>
                    <td>{{ $coupon['amount_off'] }}</td>
                    <td>{{ $coupon['currency'] }}</td>
                    <td>{{ $coupon['duration'] }}</td>
                    <td>{{ $coupon['duration_in_months'] }}</td>
                    <td>{{ $coupon['percent_off'] }}%</td>
                    <td>{{ $coupon['valid'] ? 'Oui' : 'Non' }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($coupon['created'])->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
