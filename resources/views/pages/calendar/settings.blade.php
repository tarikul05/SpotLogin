@extends('layouts.main')
@section('head_links')
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
    <div class="container">

        <div class="row justify-content-center pt-3">
            <div class="col-md-10">

        <div class="page_header_class pt-1" style="position: static;">
            @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
            <h5 class="titlePage">{{ __('School Settings') }}</h5>
            @endif
            @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherMinimum())
            <h5 class="titlePage">{{ __('Coach Settings') }}</h5>
            @endif
            @if($AppUI->isStudent())
            <h5 class="titlePage">{{ __('Student Settings') }}</h5>
            @endif
        </div>

        @include('pages.settings.navbar')

        <div class="tab-content" id="ex1-content">

            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                @include('pages.settings.categories_school')
                @else
                @include('pages.settings.categories')
                @endif
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
    $(document).on('click','#add_more_event_category_school_btn',function(){
        $('#btnSaveCategories').show();
        $('#add_more_event_category_div').fadeIn();
        var lst_id = $(this).attr('data-last_event_cat_id');
        var incre = (parseInt(lst_id)+1);
        $(this).attr('data-last_event_cat_id',incre); //<span class="badge bg-info">new</span>
        var resultHtml = `<tr class="add_more_event_category_row invoice_part">
        <td width="150" class="text-center align-middle">
        <div class="form-group">
            <input type="text" class="form-control table-name-width" name="category[`+lst_id+`][name]" value="">
        </div>
        </td>
        <input type="hidden" name="category[`+lst_id+`][bg_color_agenda]" value="#EEE" class="colorpicker dot category_bg_color_agenda" />
        <!--<td class="text-center align-middle">
            <input type="text" name="category[`+lst_id+`][bg_color_agenda]"  class="colorpicker dot category_bg_color_agenda" />
        </td>-->
        <td class="align-middle">
            @if($AppUI->isTeacherAdmin())
                <input class="form-check-input invcat_name" name="category[`+lst_id+`][invoice]" type="hidden" value="S" checked>
            @endif
            @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                <div>
                    <input class="form-check-input invcat_name" name="category[`+lst_id+`][invoice]" type="radio" value="S" checked> <label> School Invoiced</label>
                </div>
                <!--<div>
                    <input class="form-check-input invcat_name" name="category[`+lst_id+`][invoice]" type="radio" value="T"> <label> Teacher Invoiced </label>
                </div>-->
            @endif
        </td>
        <td>
            <div class="pack_invoice_area student form-group row">
            @if(!$AppUI->isTeacherAdmin())
            <div class="col-md-6">
                <label class="titl">Teachers</label>
                <!--<div class="form-check">
                    <label class="form-check-label" for="radio2`+lst_id+`">
                        <input type="radio" class="form-check-input" id="radio2`+lst_id+`" name="category[`+lst_id+`][s_thr_pay_type]" value="0">Hourly rate
                    </label>
                </div>-->
                <div class="form-check">
                    <label class="form-check-label" for="radio`+lst_id+`">
                        <input type="radio" class="form-check-input" id="radio`+lst_id+`" name="category[`+lst_id+`][s_thr_pay_type]" value="1" checked>Fixed price <span style="font-size:11px;" class="d-none d-sm-inline-block">(per student /hour)</span>
                    </label>
                </div>
            </div>
            @endif
            <div class="col-md-6">
                @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                <label class="titl">Students</label>
                @endif
                <!--<div class="form-check">
                    <label class="form-check-label" for="sradio2`+lst_id+`">
                        <input type="radio" class="form-check-input" id="sradio2`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="0">Hourly rate
                    </label>
                </div>
                -->
                @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                <div class="form-check">
                    <label class="form-check-label" for="sradio3`+lst_id+`">
                        <input type="radio" class="form-check-input" id="sradio3`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="2" checked>Packaged
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="sradio`+lst_id+`">
                        <input type="radio" class="form-check-input" id="sradio`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="1">Fixed price <span style="font-size:11px;" class="d-none d-sm-inline-block">(per student /hour)</span>
                    </label>
                </div>
                @endif
            </div>
        </div>
        <div class="pack_invoice_area teacher form-group row" style="display:none">
            <div class="col-md-6">
                @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                <label class="titl">Students</label>
                @endif
                <div class="form-check">
                    <label class="form-check-label" for="tradio2`+lst_id+`">
                        <input type="radio" class="form-check-input" id="tradio2`+lst_id+`" name="category[`+lst_id+`][t_std_pay_type]" value="0">Hourly rate
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="tradio`+lst_id+`">
                        <input type="radio" class="form-check-input" id="tradio`+lst_id+`" name="category[`+lst_id+`][t_std_pay_type]" value="1">Fixed price <br><span class="d-none d-sm-block">(per student /hour)</span>
                    </label>
                </div>
            </div>
        </div>
        </td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;
        $("#add_more_event_category_div tbody").append(resultHtml);


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

        $(document).on('change', '#number_of_coaches', function(event) {
            var id = $(this).val();
            window.location.href = "edit-teacher/" + id +'?tab=tab_2';;
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
