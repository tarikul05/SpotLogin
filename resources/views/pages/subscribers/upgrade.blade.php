@extends('layouts.main')
@section('content')
<section class="pricing">
    <div class="container">
        <div class="pricing-title">
            <h3 class="h3">Simple, transparent pricing </h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 plans">
                <div class="columns">
                    <ul class="price">
                        <li class="plan_name">Basic plan</li>
                        <li class="plan_interval">Free <span class="plan_type">/month</span></li>
                        <li>Teacher</li>
                        <li>School</li>
                        <li>Agenda</li>
                        <li>----</li>
                        <li>-----</li> 
                        <li class="grey">After {{ $trial_ends_date }}! your trial plan will expired.</li>
                    </ul>
                </div>

                <div class="columns premium-plan">
                    <ul class="price">
                        <li class="plan_name">Premium plan</li>
                        <li class="plan_interval">20$ <span class="plan_type">/month</span></li>
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
</section>
<style>
    .main-content{
        background: #f2fbff;
    }
</style>
@endsection