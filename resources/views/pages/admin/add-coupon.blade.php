@extends('layouts.navbar')

@section('content')
<div class="container">
    <br><br><br>
    <h3>Créer un coupon</h3>
    <form method="POST" action="{{ route('coupons.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom du coupon</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="coupon_type">Type de coupon</label>
            <select name="coupon_type" class="form-control" id="coupon_type">
                <option value="percent_off">Pourcentage de réduction</option>
                <option value="amount_off">Montant de réduction</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount_or_percent">Montant ou Pourcentage</label>
            <input type="number" name="amount_or_percent" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="duration">Durée</label>
            <select name="duration" class="form-control" required>
                <option value="repeating">Récurrent</option>
                <option value="once">Une fois</option>
            </select>
        </div>
        <div class="form-group">
            <label for="duration_in_months">Mois de durée (si récurrent)</label>
            <input type="number" name="duration_in_months" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Créer le coupon</button>
    </form>
</div>
@endsection
