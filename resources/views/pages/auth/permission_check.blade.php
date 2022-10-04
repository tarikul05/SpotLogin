@extends('layouts.main')

@section('content')

<div class="container-md">
    <header class="panel-heading" style="border: none;">
        <div class="row panel-row" style="margin:0;">
            <div class="col-sm-6 col-xs-12 header-area">
                <div class="page_header_class">
                    <label id="page_header" name="page_header">{{ __('Set your Default School') }}</label>
                </div>
            </div> 
        </div>          
    </header>
    <div class="row">
        @foreach ($schools as $school)
          <div class="col-sm-6">
            <div class="card mr-20">
              <div class="card-body">
@php
    #$role = str_replace('_', ' ', $school->pivot->role_type); 
    $role = $school->pivot->role_type;
    $roleName = 'Undefined';
    if(in_array($role, ['teachers_all', 'teachers_medium','teachers_minimum'])){
        $roleName = 'Teacher';
    }elseif(in_array($role, ['school_admin','teachers_admin'])){
        $roleName = 'Admin';
    }
@endphp
                <h5 class="card-title">{{ $school->school_name }}</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    {!! Form::open(['method' => 'POST','route' => ['check.permission'],'style'=>'display:inline']) !!}
                    {!! Form::hidden('sch', $school->id, ['class' => 'form-control']) !!}
                    {!! Form::submit('Logged in As '.$roleName, ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                    @if ($user->roleType() === $role && $role == $selected_role)
                        <span class="bg-success text-white p-1 rounded">checked</span>
                    @endif
                    
              </div>
            </div>
          </div>
        @endforeach
    </div>

</div>
<style type="text/css">
    .mr-20{
        margin-right: 20px;
    }
</style>

