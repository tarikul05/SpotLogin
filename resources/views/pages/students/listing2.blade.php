<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">List all</div>
            <div class="card-body">

                <form method="POST" action="{{ route('students.delete') }}">
                    @csrf
                    <input name="schoolId" type="hidden" value="{{$schoolId}}">
                    <table class="table table-stripped table-hover" id="studentList">
                        <thead>
                        <tr>
                            <th colspan="2" width="10px">
                                <div style="display:flex;">
                                  <input type="checkbox" id="select-all">
                                  <small style="font-size:11px; padding-left:4px;">check all</small>
                                </div>
                              </th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="110px" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="add_more_level_row" id="row_{{ $student->id }}">
                                    <td style="width: 10px;" class="vertical-align"><input type="checkbox" name="selected_students[]" value="{{ $student->id }}"></td>
                                    <td class="text-center" style="width:45px; text-align: center;">
                                        <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}">
                                        <?php if (!empty($student->profileImageStudent->path_name)): ?>
                                        <img src="{{ $student->profileImageStudent->path_name }}" class="img-thumbnail" id="admin_logo"  alt="Sportlogin">
                                    <?php elseif (!empty($student->user->profileImage->path_name)): ?>
                                        <img src="{{ $student->user->profileImage->path_name }}" class="img-thumbnail" id="admin_logo"  alt="Sportlogin">
                                    <?php else: ?>
                                        <img src="{{ asset('img/photo_blank.jpg') }}" class="img-thumbnail" id="admin_logo" alt="Sportlogin">
                                    <?php endif; ?>
                                        </a>
                                    </td>
                                    <td style="position: relative;">
                                        <!--<a disabled style="border:1px solid #EEE; font-size:12px; margin:0; width:auto; position:absolute; right:0; top:0; background-color:#EEE;">{{$student->user ?  __('Registered') : __('Not yet registered') }}</a>-->
                                        <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}">
                                           <b>{{ $student->full_name; }}</b> @if($student->user)| ID: {{$student->user->username}}@endif<br>
                                           {{ $student->email; }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" disabled data-status="{{ $student->pivot->is_active }}" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}" class="switch-student-btn" style="border:1px solid #EEE; font-size:12px; margin:0; width:auto; background-color:#EEE;">{{$student->user ?  __('Registered') : __('Not yet registered') }}</a><br>
                                        <a href="javascript:void(0)" disabled data-status="{{ $student->pivot->is_active }}" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}" class="switch-student-btn" style="border:1px solid #EEE; font-size:12px; margin:0; width:150px;" href="#"><i class="fa-solid fa-retweet"></i> {{ !empty($student->pivot->is_active) ? 'Switch to inative' : 'Switch to active'; }}</a>

                                    </td>
                                    <td class="text-center align-middle">

                                        <div class="btn-group">
                                            <div class="dropdown" id="dropdownActions" style="margin-top:0; padding-top:0;">
                                            <span class="btn btn-theme-outline">Actions <i class="fa fa-caret-down"></i></span>
                                            <div class="dropdown-content">

                                                <a style="display: none; display:inline-block; min-width: 150px;" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}"
                                                    class="btn btn-sm btn-info m-1 mb-2">
                                                    <i class="fa-solid fa-pen-to-square"></i> <span id ="btn_validate_events_cap">{{__('Edit')}}</span>
                                                </a>

                                                <a style="display: none; display:inline-block; min-width: 150px;"
                                                href="{{ route('students.availabilities', $student) }}"
                                                    class="btn btn-sm btn-default m-1 mb-2">
                                                    <i class="fa-solid fa-calendar"></i> <span id ="btn_validate_events_cap">{{__('Availabilities')}}</span>
                                                </a>

                                                @if(!$student->user)
                                            <div class="d-block d-sm-none"><br></div>
                                            @can('students-sent-mail')
                                                <a href="javascript:void(0)"  style="display: none; display:inline-block; min-width: 150px;"
                                                class="btn btn-sm btn-default m-1 mb-2 send-invite-btn"  data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                                    <i class="fa-solid fa-envelope"></i> Send invite
                                                </a>
                                            @endcan
                                            @else
                                                <a href="javascript:void(0)"  style="display: none; display:inline-block; min-width: 150px;"
                                                 class="btn btn-sm btn-default m-1 mb-2 send-password-btn" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                                    <i class="fa-solid fa-envelope"></i> Resend password
                                                </a>
                                            @endif


                                                <a style="display: none; display:inline-block; min-width: 150px;"
                                                href="javascript:void(0)"
                                                class="btn btn-sm btn-danger delete-student-btn m-1 mb-2" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}">
                                                    <i class="fa fa-trash"></i> {{ __('Delete') }}
                                                </a>


                                            </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br>
                    <button class="btn btn-danger btn-md" type="submit" id="delete-selected" onclick="return confirm('Are you sure you want to delete the selected students?')">Delete selected students</button>
                </form>
            </div>
        </div>
    </div>
</div>





<!-- success modal-->
<div class="modal modal_parameter" id="sendMailOk">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:4px solid #97cc04;">
            <div class="modal-body text-center">
                <h1 class="text-success"><i class="fa-solid fa-check"></i></h1>
                <h3 class="text-success">{{__('Successfully sended') }}</h3>
                <p>{{__('Your student will receive an email with instructions.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
            </div>
        </div>
    </div>
</div>
