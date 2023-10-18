@extends('layouts.navbar')

@section('content')
<div class="container">
    <br><br><br>
    <h3>Add a task</h3>
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom de la task</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="coupon_type">Description</label>
            <textarea cols="30" name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="priority">Priorité</label>
            <select name="priority" class="form-control" required>
                <option value="0">Faible</option>
                <option value="1">Moyenne</option>
                <option value="2">Haute</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Créer la task</button>
    </form>
</div>
@endsection
