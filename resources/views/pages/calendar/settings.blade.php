@extends('layouts.main')
@section('head_links')
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
    <div class="container">

        <h5>{{ __('Coach Settings') }}</h5>

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

    <script>
        $(document).ready(function() {
        $('#timezone').select2({
            width: '100%',
            placeholder: '{{ __('Select Timezone')}}',
            allowClear: false,
        });
    });
    </script>

<script type="text/javascript">

    let currencyCode = "{{ $school->default_currency_code }}";

    $(function () {
      $('[data-toggle="tooltip"]').tooltip({html:true,placement:"right"})
    })

    $(document).ready(function(){
        $('.input-price').keyup(function(e){
            if ($(this).hasClass('input-price')) {
            var divider = $(this).data('bs-divider');
            console.log(divider);

            var textForTypeBilling, textTooltip;

        if (divider == 1) {
            textForTypeBilling = 'Private session';
            textTooltip = "student";
        } else if (divider == 9999) {
            textForTypeBilling = 'Student more than 10';
            textTooltip = "each of the students";
        } else if (divider == -1) {
            textForTypeBilling = 'Fixed price';
            textTooltip = "each student";
        } else {
            textForTypeBilling = "Group lessons for " + divider + " students";
            textTooltip = "each of the " + divider + " students";
        }

        var newValue = this.value;
        var tooltipText = "For 15 mn. " + textTooltip + " will pay ("+currencyCode+") " + (newValue / 4) + "<hr>For 30 mn. " + textTooltip + " will pay ("+currencyCode+") " + (newValue / 2);

            var tooltipElement = $(this).closest('[data-toggle="tooltip"]');
                tooltipElement.attr('data-bs-original-title', tooltipText);
                tooltipElement.tooltip('show');
            }
        });
    });

    $(document).on('click','#add_new_price',function(){
        $('.hide-custom-price').slideDown('slow');
        $('#add_new_price').hide();
    });

    </script>
    <script src="{{ asset('js/pages/settings/index.js') }}"></script>
    @if (session('success_new_cat'))
    <script>
    var newCategories = @json(session('newCategoryAdded'));
    </script>
    <script src="{{ asset('js/pages/settings/categories.js') }}"></script>
    @endif

    <script>
    var categories = @json($eventCat);
    if(categories.length === 0) {
        $('#btnSaveCategories').hide();
    }
    </script>


@endsection
