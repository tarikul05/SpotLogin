@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> Plans </div>
                <div class="card-body">
                    @if(session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="plan-lists plan_center">
                        @foreach($plans as $plan)
                            <div class="columns">
                                <ul class="price">
                                    <li class="header" style="background-color:#04AA6D">{{ $plan['plan_name']->name }}</li>
                                    <li class="grey">
                                        ${{ number_format($plan['amount'], 2) }} / {{ $plan['interval'] }}<br>
                                        <span style="color: green;">save ${{ number_format((15*$plan['interval_count'] - $plan['amount']),2)}}</span>
                                    </li>
                                    <li class="grey"><a href="" class="button">Choose</a></li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection