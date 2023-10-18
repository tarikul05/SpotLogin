@extends('layouts.navbar')

@section('content')
<div class="container">
    <br><br><br>
    <h3>Créer un plan</h3>
    <form method="POST" action="{{ route('plans.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom du plan</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="product">ID du produit associé</label>
            <input type="text" name="product" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">ID du prix associé</label>
            <input type="text" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="currency">Devise du prix</label>
            <input type="text" name="currency" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="unit_amount_decimal">Montant (en décimal)</label>
            <input type="number" step="0.01" name="unit_amount_decimal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="interval">Intervalle de facturation</label>
            <select name="interval" class="form-control" required>
                <option value="day">Jour</option>
                <option value="week">Semaine</option>
                <option value="month">Mois</option>
                <option value="year">An</option>
            </select>
        </div>
        <div class="form-group">
            <label for="interval_count">Nombre d'intervalle</label>
            <input type="number" name="interval_count" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tax_behavior">Comportement fiscal</label>
            <select name="tax_behavior" class="form-control" required>
                <option value="inclusive">Inclusif</option>
                <option value="exclusive">Exclusif</option>
                <option value="unspecified">Non spécifié</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer le plan</button>
    </form>
</div>
@endsection
