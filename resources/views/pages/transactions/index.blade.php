@extends('layouts.main')

@section('head_links')
<style>
    #transactions_table td {
        border:none!important;
        border-bottom:1px solid #EEE!important;
        font-size:15px;
        margin-bottom:15px!important;
        padding-top:7px!important;
        padding-bottom:7px!important;
    }
    #transactions_table td img {
        height:30px!important;
        width:30px!important;
    }
    #transactions_table tr:hover {
        border:1px solid #EEE!important;
        background-color:#fcfcfc!important;
    }
    #transactions_table th {
        border:none!important;
        border-bottom:3px solid #EEE!important;
        font-size:13px;
        font-weight:bold;
    }
</style>
@endsection

@section('content')
<div class="content">
	<div class="container">

        <div class="row justify-content-center pt-3 pb-5">
			<div class="col-md-10">

		<div class="page_header_class pt-1" style="position: static;">
            <h5 class="titlePage">{{ __('Transactions récentes') }}</h5>
        </div>

    @if(count($transactions) > 0)
        <table id="transactions_table" style="width:100%">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Montant</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ !empty($transaction->description) ? $transaction->description : 'Payment invoice ID: '.$transaction->metadata->invoice_id }}</td>
                        <td>{{ number_format($transaction->amount / 100, 2) }} {{ strtoupper($transaction->currency) }}</td>
                        <td>
                            @if ($transaction->status === 'succeeded')
                                <span class="badge bg-success">Succeeded</span>
                            @elseif ($transaction->status === 'processing')
                                <span class="badge bg-warning">Processing</span>
                            @elseif ($transaction->status === 'requires_action')
                                <span class="badge bg-info">Requires Action</span>
                            @elseif ($transaction->status === 'requires_payment_method')
                                <span class="badge bg-secondary">Requires Payment Method</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($transaction->status) }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($transaction->created)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($transaction->status !== 'succeeded')
                                <button class="btn btn-sm btn-primary" onclick="reloadPage()">
                                    <i class="fas fa-sync-alt"></i> Vérifier statut
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune transaction trouvée pour cet utilisateur.</p>
    @endif
    </div>
</div>
</div>
</div>
@endsection

@section('footer_js')
<script>
    function reloadPage() {
        location.reload();
    }
</script>
@endsection