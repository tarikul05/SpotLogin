<?php
use App\Models\LessonPriceTeacher;
?>

<form method="POST" action="{{ route('selfUpdatePriceAction') }}">
    @csrf
    
    <div class="row justify-content-center pt-3 pb-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('Prices by category') }}</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                @if(!empty($eventCategory) && $eventCategory->count() > 0)

           

                    <div class="accordion" id="accordionExample">

                        @foreach($eventCategory as $key => $category)
                        <div class="accordion-item">
                          <h6 class="accordion-header" id="heading-{{ $key }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                                <b<small><i class="fa-solid fa-arrow-right"></i> {{$category->title}}</small></b>
                            </button>
                          </h6>
                          <div id="collapse-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordionExample" data-category-id="{{ $category->title }}" style="background-color: rgba(0, 0, 0, .03);">
                            <div class="accordion-body">
                                <table id="tariff_table_rate" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="titleFieldPage"><b>{{__('Type of course')}}</b></th>
                                            @if(!$AppUI->isSchoolAdmin() && !$AppUI->isTeacherSchoolAdmin())
                                            <th class="titleFieldPage">{{__('Type of billing')}}</th>
                                            @endif
                                            @if(($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()) && $category->s_std_pay_type == 2)
                                            <th class="sell titleFieldPage" style="text-align: right; font-size:12px;"><b>({{__('price for teacher /hour')}})</b></th>
                                            @else
                                            <th class="sell titleFieldPage" style="text-align: right; font-size:12px;"><b>({{__('per student /hour')}})</b></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lessonPrices as $key => $lessionPrice)
                                        <?php
                                        $class = "";
                                        if ($lessionPrice->divider == 1) {
                                            $textForTypeBilling = 'Private session';
                                            $textTooltip = "student";
                                        } elseif ($lessionPrice->divider == 9999) {
                                            $textForTypeBilling = 'Student more than 10';
                                            $textTooltip = "each of the students";
                                            $class = "hide-custom-price";
                                        } elseif ($lessionPrice->divider == -1) {
                                            $textForTypeBilling = 'Fixed price';
                                            $textTooltip = "each student";
                                            $classFiexPrice = "hide-show-more";
                                        } else {
                                            $textForTypeBilling = "Group lessons for $lessionPrice->divider students";
                                            $textTooltip = "each of the  $lessionPrice->divider  students";
                                        if (($lessionPrice->divider >=1 && $lessionPrice->divider < 6) || $lessionPrice->divider === 9999) {
                                                $class = "";
                                            } else {
                                                $class = "hide-custom-price";
                                            }
                                        }

                                        $studentPrice = $category->s_std_pay_type;

                                        if ($studentPrice == 1) {
                                            if ($lessionPrice->divider != -1) continue;
                                        } elseif ($studentPrice == 0) {
                                            if ($lessionPrice->divider == -1) continue;
                                        } else {
                                        }
                                        ?>
                                        <?php if(($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()) && $category->invoiced_type == "S") { ?>
                                            <?php if($lessionPrice->divider == -1) { ?>
                                            <tr class="{{$class}}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                                <td class="align-middle">{{__('Lessons/Events')}}</td>
                                                @if(!$AppUI->isSchoolAdmin() && !$AppUI->isTeacherSchoolAdmin())
                                                <td class="align-middle">{{ __($textForTypeBilling) }}</td>
                                                @endif
                                                <td class="align-middle">
                                                    <input data-toggle="tooltip" data-bs-trigger="hover" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/2) : '0.00' }}" type="text" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php } else { ?>
                                            <tr class="{{$class}}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
                                                <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                                <td class="align-middle">{{__('Lessons/Events')}}</td>
                                                @if(!$AppUI->isSchoolAdmin() && !$AppUI->isTeacherSchoolAdmin())
                                                <td class="align-middle">{{ __($textForTypeBilling) }}</td>
                                                @endif
                                                <td class="align-middle">
                                                    <input data-toggle="tooltip" data-bs-trigger="hover" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/2) : '0.00' }}" type="text" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                </td>
                                            </tr>
                                    <?php } ?>
                                        @endforeach
                                    </tbody>
                                </table>

                                <?php if($number_of_coaches > 0 && ($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())) { ?>


                                    <div class="accordion m-4" id="accordionExampleT{{$category->id}}">
                                        <div class="text-center p-3">
                                            <i class="fa fa-arrow-down" style="font-size:16px;color:#007bff;"></i><br>
                                            Setup this category to the {{ $number_of_coaches }} teachers settings
                                        </div>
                                        <div class="col-12">
                                        @foreach ($teachers as $key2 => $teacher)
                                            <div class="accordion-item" style="font-size:13px; max-width:550px; margin:0 auto;">
                                                <h6 class="accordion-header" id="heading-T{{ $key2 }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-T{{ $key2 }}" aria-expanded="false" aria-controls="collapse-T{{ $key2 }}">
                                                    <b><i class="fa fa-user mr-1"></i> {{ $teacher->firstname }} {{ $teacher->lastname }}</b>
                                                </button>
                                                </h6>
                                                <div id="collapse-T{{ $key2 }}" class="accordion-collapse collapse" aria-labelledby="heading-T{{ $key2 }}" data-bs-parent="#accordionExampleT{{$category->id}}">
                                                    <div class="accordion-body">

                                                        <table id="tariff_table_rate" class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{__('Type of course')}}</th>
                                                                    <!--<th>{{__('Type of billing')}}</th>-->
                                                                    @if(($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()) && $category->s_std_pay_type == 2)
                                                                    <th class="sell" style="text-align: right; font-size:12px;"><span>({{__('price for teacher /hour')}})</span></th>
                                                                    @else
                                                                        @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                                                                            <th class="sell" style="text-align: right; font-size:12px;"><span>({{__('per teacher /hour')}})</span></th>
                                                                        @endif
                                                                    <th class="sell" style="text-align: right; font-size:12px;"><span>({{__('per student /hour')}})</span></th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($lessonPrices as $key3 => $lessionPrice)
                                                                <?php                                                   
                                                                $thepriceInit = LessonPriceTeacher::where('event_category_id', $category->id)->where('teacher_id', $teacher->id)->first();
                                                                $theprice = !empty($thepriceInit) ? $thepriceInit : null;
                                                                ?>
                                                                <?php
                                                                $class = "";
                                                                if ($lessionPrice->divider == 1) {
                                                                    $textForTypeBilling = 'Private session';
                                                                    $textTooltip = "student";
                                                                } elseif ($lessionPrice->divider == 9999) {
                                                                    $textForTypeBilling = 'Student more than 10';
                                                                    $textTooltip = "each of the students";
                                                                    $class = "hide-custom-price";
                                                                } elseif ($lessionPrice->divider == -1) {
                                                                    $textForTypeBilling = 'Fixed price';
                                                                    $textTooltip = "each student";
                                                                    $classFiexPrice = "hide-show-more";
                                                                } else {
                                                                    $textForTypeBilling = "Group lessons for $lessionPrice->divider students";
                                                                    $textTooltip = "each of the  $lessionPrice->divider  students";
                                                                if (($lessionPrice->divider >=1 && $lessionPrice->divider < 6) || $lessionPrice->divider === 9999) {
                                                                        $class = "";
                                                                    } else {
                                                                        $class = "hide-custom-price";
                                                                    }
                                                                }
                        
                                                                $studentPrice = $category->s_std_pay_type;
                        
                                                                if ($studentPrice == 1) {
                                                                    if ($lessionPrice->divider != -1) continue;
                                                                } elseif ($studentPrice == 0) {
                                                                    if ($lessionPrice->divider == -1) continue;
                                                                } else {
                                                                }
                                                                ?>
                                                                <?php if(($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin()) && $category->invoiced_type == "S") { ?>
                                                                    <?php if($lessionPrice->divider == -1) { ?>
                                                                    <tr class="{{$class}}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][teacher_id]" value="{{ $teacher->id }}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ !empty($theprice) ? $theprice->id : '' }}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="price_fix">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                                                        <td class="align-middle">{{__('Lessons/Events')}}</td>
                                                                        <!--<td>{{ __($textForTypeBilling) }}</td>-->
     

                                                                        @if($category->s_std_pay_type === 1)
                                                                        <td class="align-middle">
                                                                            <input data-toggle="tooltip" data-bs-trigger="hover" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. the teacher will be paid ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_buy']/4) : '0.00' }}<hr>For 30 mn. the teacher will be paid ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_buy']/2) : '0.00' }}" type="text" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_buy]" value="{{ !empty($theprice) ? $theprice['price_buy'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                                        </td>
                                                                        @endif
                                                                        <td class="align-middle">
                                                                            <input data-toggle="tooltip" data-bs-trigger="hover" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($theprice) ? ($theprice['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ !empty($theprice) ? ($theprice['price_sell']/2) : '0.00' }}" type="text" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ !empty($theprice) ? $theprice['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                                        </td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                    <?php } else { ?>
                                                                    <tr class="{{$class}}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][teacher_id]" value="{{ $teacher->id }}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ $category->id }}">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="price_fix">
                                                                        <input type="hidden" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                                                        <td>{{__('Lessons/Events')}}</td>
                                                                        <!--<td>{{ __($textForTypeBilling) }}</td>-->
                                                                        <td>
                                                                            <input data-toggle="tooltip" data-bs-trigger="hover" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/2) : '0.00' }}" type="text" name="data2[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                                                        </td>
                                                                    </tr>
                                                            <?php } ?>
                                                                @endforeach
                                                            </tbody>
                                                        </table>



                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>

                                    <!--<table class="table table-bordered table-hover table-striped" id="table_prices_teacher" style="width: 100%;">
                                        <tr>
                                            <td class="text-right" style="text-align:right; width: 100%;">
                                                Setup this category to the {{ $number_of_coaches }} teachers settings
                                                <select name="number_of_coaches" id="number_of_coaches" style="text-align:right; width: 100%; max-width: 200px;">
                                                    <option value="0">Select a teacher</option>
                                                    @foreach ($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->firstname }} {{ $teacher->lastname }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    </table>-->

                                <?php } ?>

                         
                                @if($category->s_std_pay_type == 0)
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-theme-primary see_more_prices" id="add_new_price"><i class="fa fa-plus"></i> {{ __('See more') }}</a>
                                        <a href="#" style="display: none;" class="btn btn-theme-primary see_less_prices" id="hide_new_price"><i class="fa fa-minus"></i> {{ __('See less') }}</a>
                                    </div>
                                @endif
                            </div>
                          </div>
                        </div>
                        @endforeach

                      </div>



                    
                @else
                <i class="fa-solid fa-circle-info"></i> {{ __('Please create your first category for setup your prices') }}.
                @endif
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            <button type="submit" class="btn btn-success">{{ __('Save Prices by category') }}</button>
        </div>
    </div>


</div>
</form>
