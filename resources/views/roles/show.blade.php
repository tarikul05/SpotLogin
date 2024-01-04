@extends('layouts.navbar')
@section('content')
<div class="content">
    <br><br><br>
<div class="container-fluid">
    <h3>Edit role</h3>
    <div class="justify-content-center mb-4">
        <div class="card">
            <div class="card-header">Role
                @can('role-create')
                    <span class="float-right">
                        <a class="btn btn-sm btn-primary" href="{{ route('roles.index') }}">Back</a>
                    </span>
                @endcan
            </div>
            <div class="card-body">
                <div class="lead">
                    <strong>Name:</strong>
                    {{ $role->name }}
                </div>
                <div class="lead">
                    <strong>Permissions:</strong>
                    @if(!empty($rolePermissions))
                        @foreach($rolePermissions as $permission)
                            <label class="badge bg-success">{{ $permission->name }}</label>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
