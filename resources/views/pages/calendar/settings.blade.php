@extends('layouts.main')
@section('head_links')
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
    <div class="container">

        <h5>Coach Settings</h5>

        @include('pages.settings.navbar')

        <div class="tab-content" id="ex1-content">

            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                @include('pages.settings.categories')
            </div>

            <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
                @include('pages.settings.locations')
            </div>

            <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
                @include('pages.settings.levels')
            </div>

            <div class="tab-pane fade" id="tab_taxes" role="tabpanel" aria-labelledby="tab_taxes">
                @include('pages.settings.taxes')
            </div>

            <div class="tab-pane fade" id="tab_5" role="tabpanel" aria-labelledby="tab_5">
                @include('pages.settings.agenda')
            </div>

            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
                @include('pages.settings.prices')
            </div>

        </div>
    </div>
@endsection

@section('footer_js')

    <script src="{{ asset('js/pages/settings/index.js') }}"></script>
    @if (session('success_new_cat'))
    <script>
    var newCategories = @json(session('newCategoryAdded'));
    </script>
    <script src="{{ asset('js/pages/settings/categories.js') }}"></script>
    @endif

@endsection
