@extends('layouts.main')

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


