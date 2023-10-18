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
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ÉTAT</th>
                    <th>FACTURATION</th>
                    <th>PRODUIT</th>
                    <th>DATE DE CRÉATION</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription['id'] }}</td>
                    <td>{{ $subscription['status'] }}</td>
                    <td>{{ $subscription['currency'] }}</td>
                    <td>{{ $subscription['product'] }}</td>
                    <td>{{ date('j M Y, H:i', $subscription['created']) }}</td>
                    <td>{{ $subscription['customer'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
