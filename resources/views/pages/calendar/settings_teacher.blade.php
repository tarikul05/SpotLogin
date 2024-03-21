@extends('layouts.main')
@section('head_links')
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">

        @if($AppUI->isTeacherSchoolAdmin())
        <h5>{{ __('Teacher Settings') }}</h5>
        @endif
        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherMinimum())
        <h5>{{ __('Coach Settings') }}</h5>
        @endif
        @if($AppUI->isStudent())
        <h5>{{ __('Student Settings') }}</h5>
        @endif

        @include('pages.settings.navbar')

        <div class="tab-content" id="ex1-content">

            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                @include('pages.settings.categories_teacher')
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

        </div>
    </div>
@endsection

@section('footer_js')

<script type="text/javascript">
   $(document).on('click','#add_more_event_category_teacher_btn',function(){
        $('#btnSaveCategories').show();
        $('#add_more_event_category_div').fadeIn();
		var lst_id = $(this).attr('data-last_event_cat_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_event_cat_id',incre); //<span class="badge bg-info">new</span>
		var resultHtml = `<tr class="add_more_event_category_row">
        <td class="text-center align-middle">
        <div class="form-group">
            <input class="invcat_name" name="category[`+lst_id+`][invoice]" type="hidden" value="T" checked>
            <input type="text" class="form-control" name="category[`+lst_id+`][name]" value=""></div>
            </td>
        <td class="text-center align-middle">
        <input type="text" name="category[`+lst_id+`][bg_color_agenda]"  class="colorpicker dot category_bg_color_agenda" />
        </td>
        <td>
        <div class="form-check">
            <label class="form-check-label" for="sradio2`+lst_id+`">
                <input type="radio" class="form-check-input" id="sradio2`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="0">Hourly rate
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" for="sradio`+lst_id+`">
                <input type="radio" class="form-check-input" id="sradio`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="1">Fixed price (per student /hour)
            </label>
        </div>
        </td>
        <td class="align-middle text-center">
        <button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;
        $("#add_more_event_category_div").append(resultHtml);

        //Initalize colorPicker for new category
        initializeColorpicker($("#add_more_event_category_div .colorpicker").last());
        //Scroll to bottom
		window.scrollTo(0, document.body.scrollHeight);
    });



    $(document).ready(function(){

        $(document).on('click', "input[name$=\'[s_std_pay_type]\'][value='2']", function(event) {
            if ($(this).prop("checked")) {
                $(this).closest('.pack_invoice_area').find("input[name$=\'[s_thr_pay_type]\'][value='1']").prop('checked', true)
            }
        });

        //if student package selected, teacher can't be move on hourly rate
        $(document).on('click', "input[name$=\'[s_thr_pay_type]\'][value='0']", function(event) {
            var dd = $(this).closest('.pack_invoice_area').find("input[name$=\'[s_std_pay_type]\'][value='2']").prop('checked')
            if (dd) {
                alert("If the student is packaged the teacher can not be paid hourly")
                event.preventDefault();
            }
        });



        $(document).on('click', "input[name$=\'[invoice]\'][value='T']", function(event) {
            var type = $(this).val();

                $(this).closest(".invoice_part").find('.pack_invoice_area.student').hide();
                $(this).closest(".invoice_part").find('.pack_invoice_area.teacher').show();

        });
        $(document).on('click', "input[name$=\'[invoice]\'][value='S']", function(event) {
            var type = $(this).val();

                $(this).closest(".invoice_part").find('.pack_invoice_area.teacher').hide();
                $(this).closest(".invoice_part").find('.pack_invoice_area.student').show();

        });

    });





    </script>

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

    /*$(document).on('click','#add_new_price',function(){
        $('#add_new_price').fadeOut();
        $('#hide_new_price').fadeIn();
    });*/

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