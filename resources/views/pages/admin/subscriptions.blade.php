@extends('layouts.navbar')

@section('head_links')

@section('content')
<div class="content">
    <br><br><br>
    <div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Subscriptions</h3>
            <!-- Vous pouvez ajouter des boutons ou des liens ici si nécessaire -->
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <!--<th>ID</th>-->
                    <th>Email</th>
                    <th>ÉTAT</th>
                    <th>PLAN</th>
                    <th>DATE DE CRÉATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $subscription)
                <tr>
                    <!--<td>{{ $subscription['id'] }}</td>-->
                    <td>{{ $subscription['metadata']['note'] }}<br>
                    <!--<pre>{{ json_encode($subscription, JSON_PRETTY_PRINT) }}</pre></td>-->
                    <td>
                        @if($subscription['status'] == 'active' && $subscription['trial_end'] === null)
                          <span class="badge badge-success">{{ __('Active') }}</span>
                        @elseif($subscription['status'] == 'trialing' || $subscription['trial_end'] !== null)
                        <span class="badge badge-success">{{ __('Active') }}</span><br>
                        <span class="badge badge-warning">{{ __('Trial') }} until {{ date('M j, Y', $subscription['trial_end']) }}</span>
                        @elseif($subscription['status'] == 'canceled')
                          <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                          @else
                          <span class="badge badge-danger">{{ $subscription['status'] }}</span>
                        @endif
                      </td>
                    <td>
                        {{ $subscription['price_name'] }}<br>{{ $subscription['plan']['amount']/100 * $subscription['plan']['interval_count'] }} ({{  $subscription['plan']['currency'] }}) / {{ $subscription['plan']['interval'] }}
                    </td>
                    <td>{{ date('j M Y, H:i', $subscription['created']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
