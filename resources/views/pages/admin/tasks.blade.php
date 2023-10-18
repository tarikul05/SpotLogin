@extends('layouts.navbar')
@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
        <div class="row">
    <h3 class="col-lg-6 col-md-6 col-xs-12">Tasks Development</h3>
        <div class="col-lg-6 col-md-6 col-xs-12 text-right">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add a task
        </a>
        </div>
        </div>
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
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task['id'] }}</td>
                    <td>{{ $task['name'] }}</td>
                    <td>{{ $task['description'] }}</td>
                    <td>{{ $task['priority'] == 0 ? 'Faible' : ($task['priority'] == 1 ? 'Moyenne' : 'Haute') }}</td>
                    <td>{{ $task['status'] ? 'Oui' : 'Non' }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($task['createdAt'])->format('Y-m-d H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
