@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 plan_center">
            <div class="columns">
                <ul class="price">
                    <li class="header">Basic</li>
                    <li class="grey">$ Free</li>
                    <li>Teacher</li>
                    <li>School</li>
                    <li>Agenda</li>
                    <li>----</li>
                    <li>-----</li> 
                    <li class="grey">After {{ $trial_ends_date }}! your trial plan will expired.</li>
                </ul>
            </div>

            <div class="columns">
                <ul class="price">
                    <li class="header" style="background-color:#04AA6D">premium</li>
                    <li class="grey"> &nbsp; </li>
                    <li>Teacher</li>
                    <li>School</li>
                    <li>Agenda</li>
                    <li>Coach</li>
                    <li>Invoice</li>
                    <li class="grey"><a href="{{ route('subscription.list') }}" class="button">Update</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection