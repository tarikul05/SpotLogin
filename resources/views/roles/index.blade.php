@extends('layouts.navbar')
@section('content')
<div class="content">
    <br><br><br>

<div class="container-fluid">
    <h3>Roles</h3>
    <div class="justify-content-center mb-4">
        <div class="card">
            <div class="card-header">Roles
                @can('role-create')
                    <!-- <span class="float-right">
                        <a class="btn btn-sm btn-primary" href="{{ route('roles.create') }}">New Role</a>
                    </span> -->
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-success" href="{{ route('roles.show',$role->id) }}">Show</a>
                                    @can('role-edit')
                                        <a class="btn btn-sm btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                    @endcan
                                {{--
                                    @can('role-delete')
                                        {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->render() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
