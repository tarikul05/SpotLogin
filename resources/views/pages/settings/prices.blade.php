<div class="row justify-content-center pt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Prices by category</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                @if(!empty($eventCategory) && $eventCategory->count() > 0)

                <form method="POST" action="{{ route('selfUpdatePriceAction') }}">
                    @csrf

                    <div class="accordion" id="accordionExample">

                        @foreach($eventCategory as $key => $category)
                        <div class="accordion-item">
                          <h6 class="accordion-header" id="heading-{{ $key }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                                <h6><small><i class="fa-solid fa-arrow-right"></i> {{$category->title}}</small></h6>
                            </button>
                          </h6>
                          <div id="collapse-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordionExample" data-category-id="{{ $category->title }}">
                            <div class="accordion-body">
                                <table id="tariff_table_rate" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{__('Type of course')}}</th>
                                            <th>{{__('Type of billing')}}</th>
                                            <th class="sell" style="text-align: right;"><span>{{__('Per student /hour')}}</span></th>
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
                                    <tr class="{{$class}}">
                                        <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][id]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['id'] : '' }}">
                                        <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_student]" value="{{$lessionPrice->lesson_price_student}}">
                                        <input type="hidden" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][lesson_price_id]" value="{{$lessionPrice->id}}">
                                        <td>{{__('Lessons/Events')}}</td>
                                        <td>{{ __($textForTypeBilling) }}</td>
                                        <td>
                                            <input data-toggle="tooltip" data-bs-divider="{{ $lessionPrice->divider }}" data-bs-original-title="For 15 mn. {{  $textTooltip }}  will pay  ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/4) : '0.00' }}<hr>For 30 mn. {{  $textTooltip }} will pay ({{ $school->default_currency_code }}) {{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? ($ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell']/2) : '0.00' }}" type="text" name="data[{{$category->id}}][{{$lessionPrice->lesson_price_student}}][price_sell]" value="{{ isset($ltprice[$category->id][$lessionPrice->lesson_price_student]) ? $ltprice[$category->id][$lessionPrice->lesson_price_student]['price_sell'] : '0.00' }}" style="text-align:right" class="form-control input-price numeric float <?= ($studentPrice == 1) && ($lessionPrice->divider != -1) ? 'd-none' : '' ?>">
                                        </td>
                                    </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($category->s_std_pay_type == 0)
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-theme-primary" id="add_new_price"><i class="fa fa-plus"></i> {{ __('See more') }}</a>
                                        <a href="#" style="display: none;" class="btn btn-theme-primary" id="hide_new_price"><i class="fa fa-plus"></i> {{ __('See less') }}</a>
                                    </div>
                                @endif
                            </div>
                          </div>
                        </div>
                        @endforeach

                      </div>



                    <br>
                    <button type="submit" class="btn btn-primary">{{ __('Save Prices by category') }}</button>
                </form>
                @else
                <i class="fa-solid fa-circle-info"></i> Please create your first category for setup your prices.
                @endif
            </div>
        </div>
    </div>
</div>
