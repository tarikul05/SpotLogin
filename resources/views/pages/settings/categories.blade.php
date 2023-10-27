<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Categories</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                <form method="POST" action="{{ route('event_category.create') }}">
                    @csrf
                    @php $count= isset($eventLastCatId->id) ? ($eventLastCatId->id) : 1; @endphp

                        <table class="table table-bordered table-hover">
                            <thead>
                                <th width="30%">Name</th>
                                <th width="60" class="text-center">Color</th>
                                <th>Billing</th>
                                <th width="40" class="text-center">Action</th>
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
                                                <label class="titl">Students</label>
                                                @endif
                                                <div class="form-check">
                                                    <label class="form-check-label" for="sradio2{{$count}}">
                                                        <input type="radio" class="form-check-input" id="sradio2{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="0" <?php if($cat->s_std_pay_type == 0){ echo 'checked'; }  ?>>Hourly rate
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label" for="sradio{{$count}}">
                                                        <input type="radio" class="form-check-input" id="sradio{{$count}}" name="category[{{$count}}][s_std_pay_type]" value="1" <?php if($cat->s_std_pay_type == 1){ echo 'checked'; }  ?>>Fixed price<span class="d-none d-sm-inline"> (per student /hour)</span>
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
                                            <button type="button" class="btn btn-danger delete_event" data-category_id="<?= $cat->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                            </tbody>
                        </table>

                        <table class="table table-bordered" id="add_more_event_category_div" style="display: none;">
                            <thead>
                                <th width="30%">Name</th>
                                <th width="60" class="text-center">Color</th>
                                <th>Billing</th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_event_category_btn" data-last_event_cat_id="{{$count}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Category') }}
                        </button>
                      </div>

                    <br>
                    <button type="submit" class="btn btn-success">{{ __('Save Categories') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
