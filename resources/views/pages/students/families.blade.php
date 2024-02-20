<div class="row justify-content-center pt-1">
    <div class="col-md-12">
        <div class="card" style="border-radius:10px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <b class="d-none d-sm-inline">{{ __("Families\"s List") }}</b>
                <input name="search_text_families" type="input" class="form-control search_text_box" id="search_text_families"  placeholder="Find a family">
            </div>
            <div class="card-body">

            <table class="table table-bordered table-hover" id="example2" style="width:100%">
                <thead>
                <tr>
                    <th class="d-none d-lg-table-cell">{{ __('Name') }}</th>
                    <th class="d-none d-lg-table-cell">{{ __('Email') }}</th>
                    <th class="d-none d-lg-table-cell">{{ __('Status') }}</th>
                    <th class="d-none d-lg-table-cell">{{ __('Members') }}</th>
                    <th width="40" class="text-center d-none d-lg-table-cell">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($families as $family)
                    <tr>
                        <td>{{ $family->firstname }}</td>
                        <td>{{ $family->email }}</td>
                        <td>
                            @if($family->has_user_account)
                            <a href="javascript:void(0)" disabled data-status="{{ $family->has_user_account}}" data-family="{{ $family->id }}">
                             <span class="badge bg-success">Registered</span>
                             @else
                            <span class="badge bg-info">Not yet registered</span>
                            </a>
                             @endif
                        </td>
                        <td>
                            @if(count($family->students) > 1)
                                <div class="dropdown">
                                    <span class="student-name dropdown-toggle" data-toggle="dropdown" style="cursor:pointer">
                                        {{ $family->students[0]['firstname'] }} ({{ count($family->students) }})
                                        <i class="fa fa-caret-down"></i>
                                    </span>
                                    <ul class="dropdown-menu" style="padding:5px; font-size:14px; width:150px;">
                                        @foreach($family->students as $family_student)
                                            <li><i class='fa fa-regular fa-user'></i> {{ $family_student['firstname'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                @foreach($family->students as $family_student)
                                    {{ $family_student['firstname'] }}
                                @endforeach
                            @endif
                        </td>
                    <td class="text-center" width="40">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h txt-grey"></i>
                                </a>
                                <div class="dropdown-menu list action text-left">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#family_{{ $family->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('Edit')}}
                                    </a>
                                    @if(!$family->has_user_account)
                                    <a href="javascript:void(0)" class="dropdown-item send-invite-btn-family" data-email="{{ $family->email }}" data-school="{{ $schoolId }}" data-family="{{ $family->id }}">
                                        <i class="fa-solid fa-envelope"></i>
                                        {{ __('Re-send invite')}}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

@foreach($families as $family)
<form enctype="multipart/form-data" class="form-horizontal" id="add_family" method="post" action="{{ route('student.updateFamilyAction') }}"  name="add_family" role="form">
    <!-- Modal for editing family details -->
    <div class="modal fade confirm" id="family_{{ $family->id }}" tabindex="-1" aria-hidden="true"
        aria-labelledby="family_{{ $family->id }}" name="family_{{ $family->id }}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #152245;">
                    <h6 class="modal-title page_header_class">
                        <i class="fa fa-users"></i>  {{ __('Update family') }}
                    </h6>
                    <button type="button" data-dismiss="modal" aria-label="Close" id="modalClose" class="btn btn-light close" data-bs-dismiss="modal" style="margin-top:0px; font-size:23px;">
                        <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
                    </button>
                </div>
                <div class="modal-body">
                        @csrf
                    <input name="parent_id" type="hidden" value="{{ $family->id }}">
                    <div class="from-group">
                        <label for="name"><b>{{ __('Family name') }}</b></label>
                        <input class="form-control" id="family_name" placeholder="{{ __('Family name') }}" name="family_name" type="text" required value="{{ $family->firstname }}">
                    </div>
                    <!-- Select all members of the family -->
                    <div class="from-group mt-3">
                        <label for="principal_name"><b>{{ __('Select all members of the family') }}</b></label>
                        <select class="form-select" multiple id="students_family_{{ $family->id }}" name="students_family[]" required data-family-id="{{ $family->id }}">
                            @if(is_array($family->students))
                                @foreach($family->students as $family_student)
                                    <option value="{{ $family_student['id'] }}" id="{{ $family_student['id'] }}" selected>{{ $family_student['firstname'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="from-group mt-3">
                        <label for="principal_email_family"><b>{{ __('Contact Email') }}</b> <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Parents email addresses are added to this list when you check students (if parents email adresses are registered)')}}"></i></label>
                        <select class="form-control" id="principal_email_family" name="principal_email_family">
                           <option>{{ $family->email }}</option>
                        </select>
                        <div class="custom-email-input mt-3" style="display: none;">
                            <label for="custom_email"><b>{{__('Custom address')}}</b></label>
                            <input class="form-control" id="custom_email" placeholder="{{__('Custom address')}}" name="custom_email" type="email">
                        </div>
                    </div>

                </div>
                <div class="modal-footer pt-2" style="background-color: #152245;">
                    <a href="{{ route('delete.family', ['parentId' => $family->id]) }}" class="btn btn-theme-warn" onclick="return confirm('Are you sure you want to delete this family?')">Delete Family</a>
                    <button type="submit" id="save_btn_family" class="btn btn-theme-success">{{ __('Update') }} </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endforeach
