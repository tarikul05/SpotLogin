<div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <form method="POST" action="{{ route('event_category.create') }}">
            @csrf
        <div class="card">
            <div class="card-header">School categories</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->


                    @php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp

                    @if(!empty($eventCat) && $eventCat->count() > 0)
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th style="width:150px;"> {{ __('Name')}}</th>
                                <th width="60" class="text-center"> {{ __('Color')}}</th>
                                <th>{{ __('Invoice type')}}</th>
                                <th>{{ __('Billing')}}</th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach($eventCat as $cat)
                                    <tr class="add_more_event_category_row invoice_part">
                                        <td class="align-middle" style="width:180px!important;">
                                            <div class="form-group">
                                                <input type="hidden" name="category[{{$count}}][id]" value="<?= $cat->id; ?>">
                                                <input style="width:180px;" type="text" class="form-control" name="category[{{$count}}][name]" value="{{ $cat->title }}">
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="form-group">
                                                <input type="text" name="category[{{$count}}][bg_color_agenda]" value="{{!empty($cat->bg_color_agenda) ? $cat->bg_color_agenda : old('bg_color_agenda')}}"  class="colorpicker dot category_bg_color_agenda" />
                                            </div>
                                        </td>
                                        <td class="text-left align-middle">
                                            <div>
                                                <input type="radio" class="form-check-input invcat_name" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>> <label> {{ __('School Invoiced') }}</label>
                                            </div>

                                            <div>
                                                <input type="radio" class="form-check-input invcat_name" name="category[{{$count}}][invoice]" value="T" <?php if($cat->invoiced_type == 'T'){ echo 'checked'; }  ?>> <label> {{ __('Teacher Invoiced') }}</label>
                                            </div>
                                        </td>
                                        <td>

                                            <div class="pack_invoice_area student form-group row" <?php if($cat->invoiced_type == 'T'){ echo 'style="display:none"'; }  ?>>
                                                @if(!$AppUI->isTeacherAdmin())
                                                <div class="col-md-6">
                                                    <label class="titl">Teachers</label>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="radio2{{$count}}">
                                                            <input type="radio" class="form-check-input" id="radio2{{$count}}" name="category[{{$count}}][s_thr_pay_type]" value="0" <?php if($cat->s_thr_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="radio{{$count}}">
                                                            <input type="radio" class="form-check-input" id="radio{{$count}}" name="category[{{$count}}][s_thr_pay_type]" value="1" <?php if($cat->s_thr_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <span class="d-none d-sm-inline-block" style="font-size:11px;">(per student /hour)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-md-6">
                                                    @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                                    <label class="titl">Students</label>
                                                    @endif
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="sradio2{{$count}}">
                                                            <input type="radio" class="form-check-input" id="sradio2{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="0" <?php if($cat->s_std_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="sradio{{$count}}">
                                                            <input type="radio" class="form-check-input" id="sradio{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="1" <?php if($cat->s_std_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <span class="d-none d-sm-inline-block" style="font-size:11px;">(per student /hour)</span>
                                                        </label>
                                                    </div>
                                                    @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="sradio3{{$count}}">
                                                            <input type="radio" class="form-check-input" id="sradio3{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="2" <?php if($cat->s_std_pay_type == 2){ echo 'checked'; }  ?>>Packaged
                                                        </label>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="pack_invoice_area teacher form-group row" <?php if($cat->invoiced_type == 'S'){ echo 'style="display:none"'; }  ?> >
                                                <div class="col-md-6">
                                                    @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                                    <label class="titl">Students</label>
                                                    @endif
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="tradio2{{$count}}">
                                                            <input type="radio" class="form-check-input" id="tradio2{{$count}}" name="category[{{$count}}][t_std_pay_type]" value="0" <?php if($cat->t_std_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="tradio{{$count}}">
                                                            <input type="radio" class="form-check-input" id="tradio{{$count}}" name="category[{{$count}}][t_std_pay_type]" value="1" <?php if($cat->t_std_pay_type == 1){ echo 'checked'; }  ?>>Fixed price <span class="d-none d-sm-inline-block" style="font-size:11px;">(per student /hour)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        </td>

                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-danger delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                            </tbody>
                        </table>

                        @else
                        <i class="fa-solid fa-circle-info"></i> Please create your first category<br><br>
                        @endif

                        <table class="table table-bordered bg-tertiary" id="add_more_event_category_div" style="display: none;">
                            <thead>
                                <th width="150">Name <span class="badge bg-info">new</span></th>
                                <th width="60" class="text-center">Color</th>
                                <th>Invoice type</th>
                                <th>Billing</th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_event_category_school_btn" data-last_event_cat_id="{{$count}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Category') }}
                        </button>
                      </div>


            </div>

        </div>

        <br>
        <button type="submit" class="btn btn-primary" id="btnSaveCategories">{{ __('Save Categories') }}</button>
    </form>


    </div>
</div>

