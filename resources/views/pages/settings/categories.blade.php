<form method="POST" action="{{ route('event_category.create') }}">
    @csrf
    
    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">Categories</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                    @php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp

                    @if(!empty($eventCat) && $eventCat->count() > 0)
                        <table class="table table-stripped table-hover" id="main_category_table">
                            <thead>
                                <th width="30%" class="titleFieldPage"> {{ __('Name')}}</th>
                                <th width="60" class="text-center titleFieldPage"> {{ __('Color')}}</th>
                                <th class="titleFieldPage">{{ __('Billing')}}</th>
                                <th width="40" class="text-center titleFieldPage">Action</th>
                            </thead>
                            <tbody>
                                @foreach($eventCat as $cat)
                                    <tr class="add_more_event_category_row">
                                        <td class="align-middle">
                                            <div class="form-group">
                                                <input type="hidden" class="invcat_name" name="category[{{$count}}][invoice]" value="S" <?php if($cat->invoiced_type == 'S'){ echo 'checked'; }  ?>>
                                                <input type="hidden" name="category[{{$count}}][id]" value="<?= $cat->id; ?>">
                                                <input type="text" class="form-control" name="category[{{$count}}][name]" value="{{ $cat->title }}">
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="form-group">
                                                <input type="text" name="category[{{$count}}][bg_color_agenda]" value="{{!empty($cat->bg_color_agenda) ? $cat->bg_color_agenda : old('bg_color_agenda')}}"  class="colorpicker dot category_bg_color_agenda" />
                                            </div>
                                        </td>
                                        <td>
                                            @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                                <label class="titl">{{ __('Students')}}</label>
                                                @endif
                                                <div class="form-check">
                                                    <label class="form-check-label" for="sradio2{{$count}}">
                                                        <input type="radio" class="form-check-input" id="sradio2{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="0" <?php if($cat->s_std_pay_type == 0){ echo 'checked'; }  ?>>{{ __('Hourly rate')}}
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label" for="sradio{{$count}}">
                                                        <input type="radio" class="form-check-input" id="sradio{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="1" <?php if($cat->s_std_pay_type == 1){ echo 'checked'; }  ?>>{{ __('Fixed price')}}<span class="d-none d-sm-inline"> ({{ __('per studen /hour')}})</span>
                                                    </label>
                                                </div>
                                                @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
                                                <div class="form-check">
                                                    <label class="form-check-label" for="sradio3{{$count}}">
                                                        <input type="radio" class="form-check-input" id="sradio3{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="2" <?php if($cat->s_std_pay_type == 2){ echo 'checked'; }  ?>>Packaged
                                                    </label>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a class="text-danger delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                            </tbody>
                        </table>

                        @else
                        <i class="fa-solid fa-circle-info"></i> Please create your first category<br><br>
                        @endif

                        <table class="table table-stripped" id="add_more_event_category_div" style="display: none;">
                            <thead>
                                <th class="titleFieldPage" width="30%">Name</th>
                                <th width="60" class="text-center titleFieldPage">Color</th>
                                <th class="titleFieldPage">Billing</th>
                                <th width="40" class="text-center titleFieldPage">Action</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_event_category_btn" data-last_event_cat_id="{{$count}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Category') }}
                        </button>
                      </div>

               
            </div>
        </div>

    
    </div>

    
    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            <button type="submit" class="btn btn-success" id="btnSaveCategories">{{ __('Save Categories') }}</button>
        </div>
    </div>

    </div>
    </form>
    


