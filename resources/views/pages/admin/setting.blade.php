@extends('layouts.navbar')

@section('content')
<div class="container">
    <br><br><br>
    <h3>Settings</h3>
    <form method="POST" action="{{ route('admin.maintenance.update') }}">
        @csrf
        @method('POST')
        <div class="form-group">
            <label for="message">Message de maintenance</label>
            <input type="text" class="form-control" id="message" name="message" value="{{ $maintenance->message }}">
        </div>
        <div class="form-group">
            <label for="start_date">Date/Heure de début de la maintenance</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ $maintenance->start_date }}">
        </div>
        <div class="form-group">
            <label for="active">Activer la maintenance</label>
            <select class="form-control" id="active" name="active">
                <option value="0" {{ $maintenance->active == 0 ? 'selected' : '' }}>Désactiver</option>
                <option value="1" {{ $maintenance->active == 1 ? 'selected' : '' }}>Activer</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Enregistrer les paramètres de maintenance</button>
    </form>
</div>
@endsection