@extends('layouts.navbar')
@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
        <h3>Alert server</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Priorité</th>
                    <th>Valide</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alerts as $alert)
                <tr>
                    <td>{{ $alert['id'] }}</td>
                    <td>{{ $alert['name'] }}</td>
                    <td>{{ $alert['description'] }}</td>
                    <td>{{ $alert['priority'] == 0 ? 'Faible' : ($alert['priority'] == 1 ? 'Moyenne' : 'Haute') }}</td>
                    <td>{{ $alert['status'] ? 'Oui' : 'Non' }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($alert['createdAt'])->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
