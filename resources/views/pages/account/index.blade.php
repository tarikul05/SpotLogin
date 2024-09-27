@extends('layouts.main')
@section('head_links')
<style>
    #paymentMethod_table {
        width: 100%;
    }
    #paymentMethod_table td {
        border:none!important;
        border-bottom:1px solid #EEE!important;
        font-size:15px;
        margin-bottom:15px!important;
        padding-top:7px!important;
        padding-bottom:7px!important;
    }
    #paymentMethod_table td img {
        height:30px!important;
        width:30px!important;
    }
    #paymentMethod_table tr:hover {
        border:1px solid #EEE!important;
        background-color:#fcfcfc!important;
    }
    #paymentMethod_table th {
        border:none!important;
        border-bottom:3px solid #EEE!important;
        font-size:13px;
        font-weight:bold;
    }
</style>
@endsection
@section('content')
    <div class="container">

        <div class="row justify-content-center pt-3">
            <div class="col-md-10">

        <div class="page_header_class pt-1" style="position: static;">
            <h5 class="titlePage">{{ __('Coach Account') }}</h5>
        </div>
        

        @include('pages.account.navbar')

        <div class="tab-content" id="ex1-content">

            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                @include('pages.account.informations')
            </div>

            <div class="tab-pane fade" id="tab_5" role="tabpanel" aria-labelledby="tab_5">
                @include('pages.account.payment-methods')
            </div>

            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
                @include('pages.account.plan')
            </div>

            <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
                @include('pages.account.invoices')
            </div>

            <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
                @include('pages.account.info-plus')
            </div>

        </div>
    </div>

        </div>
    </div>
@endsection
