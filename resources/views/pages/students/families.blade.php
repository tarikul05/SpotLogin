<div class="row justify-content-center pt-1">
    <div class="col-md-12">
        <div class="card2" style="border-radius:10px;">
            <div class="card-header titleCardPage d-flex justify-content-between align-items-center">
                <b class="d-none d-sm-inline">{{ __("Families\"s List") }}</b>
                <input name="search_text_families" type="input" class="form-control search_text_box" id="search_text_families"  placeholder="Find a family">
            </div>
            <div class="card-body">

            <table id="example2" style="width:100%">
                <thead>
                <tr>
                    <th class="titleFieldPage p-0 text-left">{{ __('Name') }}</th>
                    <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('Email') }}</th>
                    <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('User Account') }}</th>
                    <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('Members') }}</th>
                    <th width="40" class="text-center titleFieldPage">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($families as $family)
                    <tr>
                        <td class="p-0 pl-1 text-left"><span data-toggle="modal" data-target="#family_{{ $family->id }}" style="cursor:pointer; border-bottom:1px dashed #a1a0a0;">{{ $family->firstname }}</span></td>
                        <td class="d-none d-lg-table-cell p-0 text-left">{{ $family->email }}</td>
                        <td class="d-none d-lg-table-cell p-0 text-left">
                            @if($family->has_user_account)
                            <a href="javascript:void(0)" disabled data-status="{{ $family->has_user_account}}" data-family="{{ $family->id }}">
                             <span class="badge bg-success">Registered</span>
                             @else
                           {{__("No")}} <span class="badge bg-info send-invite-btn-family" data-email="{{ $family->email }}" data-school="{{ $schoolId }}" data-family="{{ $family->id }}" style="cursor:pointer;"><i class="fa-solid fa-envelope"></i> {{ __('Send invite') }}</span>
                            </a>
                             @endif
                        </td>
                        <td class="d-none d-lg-table-cell p-0 text-left">
                            @if(count($family->students) > 1)
                                <div class="dropdown">
                                    <span class="student-name dropdown-toggle" data-toggle="dropdown" style="cursor:pointer">
                                        {{ $family->students[0]['firstname'] }} ({{ count($family->students) }})
                                        <i class="fa fa-caret-down"></i>
                                    </span>
                                    <ul class="dropdown-menu" style="padding:5px; font-size:13px; width:auto!important;">
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
                                    <a class="dropdown-item text-primary" data-toggle="modal" data-target="#family_{{ $family->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('Edit')}}
                                    </a>
                                    @if($family->has_user_account)
                                    <a href="javascript:void(0)" class="dropdown-item send-password-btn text-primary" data-email="{{ $family->email }}" data-school="{{ $schoolId }}" data-student="{{ $family->id }}" title="{{ __("Resend password") }}">
                                        <i class="fa-solid fa-envelope"></i>
                                        {{ __('Resend password') }}
                                    </a>
                                    @endif
                                    @if(!$family->has_user_account)
                                    <a href="javascript:void(0)" class="dropdown-item text-primary send-invite-btn-family" data-email="{{ $family->email }}" data-school="{{ $schoolId }}" data-family="{{ $family->id }}">
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

    <div class="row justify-content-center footer2" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.99!important; background-color:#fbfbfb!important; border:1px solid #fcfcfc;">
            @if($students)
            <button class="btn btn-outline-primary" id="nav-add-family-tab2" type="button">
                + {{ __('Create a family') }}
            </button>
            @endif
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
                <div class="modal-header text-white" style="background-color: #152245; heigth:85px!important; padding:8px!important;">
                    <h6 class="modal-title" style="font-size:17px; padding-top:5px;">
                        <i class="fa fa-users"></i>  {{ __('Update family') }}
                    </h6>
                    <button type="button" data-dismiss="modal" aria-label="Close" id="modalClose" class="close" data-bs-dismiss="modal" style="margin-top:-8px; font-size:23px;">
                        <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
                    </button>
                </div>
                <div class="modal-body p-3">
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
                <div class="modal-footer pt-2" style="background-color: #fdfdfd; heigth:85px!important; padding:8px!important;">
                    <a href="{{ route('delete.family', ['parentId' => $family->id]) }}" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this family?')">Delete Family</a>
                    <button type="submit" id="save_btn_family" class="btn btn-success">{{ __('Update') }} </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endforeach
