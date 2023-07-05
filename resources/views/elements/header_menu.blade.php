<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
        <div class="container-fluid paddingLogo">

            <a href="{{  route('agenda') }}" class="navbar-brand d-none d-sm-block">
                <img src="{{ asset('img/logo.png') }}" width="50px" alt="SpotLogin">
            </a>

     

                <a class="user_profile d-block d-sm-none" href="<?= $BASE_URL;?>/admin/profile-update">

                    <div style="position: relative; width:48px; font-size:10px; display:inline;">
                            @if( $is_subscribed )
                            <span class="badge bg-success p-1" style="position: absolute; right:-1px; top:-18px; padding:2px!important;">premium</span>
                            @endif
                            @if( !$is_subscribed )
                                <?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                                    <span class="badge bg-info p-1" style="position: absolute; right:0px; top:-15px; padding:2px!important;">basic</span>
                                <?php } else { ?>
                                    <span class="badge bg-warning p-1" style="position: absolute; right:0px; top:-15px; padding:2px!important;">basic</span>
                                <?php } ?>
                            @endif
                        <?php if (!empty($AppUI->profileImage->path_name)): ?>
                            <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                        <?php else: ?>
                            <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                        <?php endif; ?>
                    </div>
                        
                        <span class="admin_name"><?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : 'Admin';?>
                            
                        </span>
                        
                    </a>

                <button type="button" class="navbar-toggler custom-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

         <div class="collapse navbar-collapse custom-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    @php 
                    $url = route('agenda');
                    $urlInvoice = route('invoiceList');
                    $urlStudentInvoice = route('invoiceList');
                    $urlTeacherInvoice = route('invoiceList');
                    $manualInvoice = route('manualInvoice');
                    if(!empty($schoolId)){ 
                        $url = route('agenda.id',[$schoolId]);
                        $urlInvoice = route('adminInvoiceList',[$schoolId]);
                        $urlStudentInvoice = route('studentInvoiceList.id',[$schoolId]);
                        $urlTeacherInvoice = route('teacherInvoiceList.id',[$schoolId]);
                        $manualInvoice = route('adminmanualInvoice',[$schoolId]);
                    }
                    @endphp

                    <div class="d-block d-sm-none pt-3">
                        
                    </div>
    
                    <a href="{{ $url }}" class="nav-item nav-link active text-center mr-2"><i class="fa-solid fa-calendar-days"></i> <span class="d-none d-sm-block"></span> {{ __('My Schedule')}}</a>
                    
                    @can('schools-list')
                        <?php if ($AppUI['person_type']=='SUPER_ADMIN'): ?>
                            <a href="{{ route('schools') }}" class="nav-item nav-link text-center mr-2">{{ __('Schools') }}</a>
                        <?php else: ?>
                            <a href="{{ route('school-update') }}" class="nav-item nav-link text-center mr-2">{{ __('School') }}</a>
                        <?php endif; ?>
                    @endcan

                    @if($AppUI->isTeacherSchoolAdmin() || $AppUI->isTeacherAdmin() || $AppUI->isTeacherMinimum() || $AppUI->isTeacherMedium() || $AppUI->isTeacherAll())
                        <a href="{{ route('updateTeacher') }}" class="nav-item nav-link text-center mr-2"><i class="fa-solid fa-user"></i> <span class="d-none d-sm-block"></span> {{ __('My Account') }}</a> 
                    @endif

                    @if($AppUI->isSuperAdmin() || $AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                        @can('teachers-list')
                            @if($AppUI['person_type'] != 'SUPER_ADMIN')
                               <a href="{{ route('teacherHome') }}" class="nav-item nav-link text-center mr-2"><i class="fa-solid fa-users-gear"></i> <span class="d-none d-sm-block"></span> {{ __('Teachers') }}</a> 
                            @endif
                        @endcan
                    @endif

                    @if($AppUI->isStudent())
                        <a href="{{ route('updateStudent') }}" class="nav-item nav-link text-center mr-2"><i class="fa-solid fa-user"></i> <span class="d-none d-sm-block"></span> {{ __('My Account') }}</a> 
                    @endif
                    

                    @can('students-list')
                        @if($AppUI['person_type'] != 'SUPER_ADMIN')
                           <a href="{{ route('studentHome') }}" class="nav-item nav-link text-center mr-2"><i class="fa-solid fa-users"></i> <span class="d-none d-sm-block"></span> {{ __('Students') }}</a> 
                        @endif
                    @endcan
                    <div class="nav-item dropdown">
                         <a href="#" class="nav-link dropdown-toggle text-center mr-2" data-bs-toggle="dropdown"><i class="fa-solid fa-file-invoice-dollar"></i> <span class="d-none d-sm-block"></span> {{ __('Invoicing') }}</a>
                        <div class="dropdown-menu header">

                        @if(!$AppUI->isStudent())
                            <a href="{{ $urlInvoice }}" class="dropdown-item"><i class="fa-solid fa-file-invoice"></i> {{ __("Invoice's List") }}</a>
   
                            <?php if( $is_subscribed || !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                                <a href="{{ $urlStudentInvoice }}" class="dropdown-item"><i class="fa-solid fa-file-invoice"></i> {{ __("Student's Invoice") }}</a>
                            <?php } ?>
                            @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherSchoolAdmin())
                                <a href="{{ $urlTeacherInvoice }}" class="dropdown-item"><i class="fa-solid fa-file-invoice"></i> {{ __("Teacher's Invoice") }}</a>
                            @endif
                            @if(!$AppUI->isTeacherReadOnly())
                            <?php if( $is_subscribed || !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                                    <a href="{{ $manualInvoice }}" class="dropdown-item"><i class="fa-solid fa-file-invoice"></i> {{ __('Manual Invoice') }}</a>
                             <?php } ?>
                            @endif
                        @else
                            <a href="{{ $urlInvoice }}" class="dropdown-item">{{ __('My Invoice') }}</a>
                        @endcan
                        </div>
                    </div>
                    @if($AppUI->isTeacherSchoolAdmin())
                    <div class="nav-item dropdown">
                         <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ __('School Invoicing') }}</a>
                        <div class="dropdown-menu header">
                            <a href="{{ $urlInvoice.'/school' }}" class="dropdown-item">{{ __("Invoice's List") }}</a>
                            <a href="{{ $urlStudentInvoice.'/school' }}" class="dropdown-item">{{ __("Student's Invoice") }}</a>
                            <a href="{{ $urlTeacherInvoice.'/school' }}" class="dropdown-item">{{ __("Teacher's Invoice") }}</a>
                        </div>
                    </div>
                    @endif

                    <a class="nav-item nav-link d-sm-none text-center text-danger" href="/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                    
                    @unlessrole('superadmin')
                        @unlessrole('student')
                            @if(count($AppUI->schools()) > 1)
                                <a href="/permission-check" class="nav-item nav-link permission-btn btn">{{ __('Change Permission') }}</a>
                            @endif
                        @endunlessrole
                    @endunlessrole
    
                </div>

                <!--Payment Reminded-->
                @if(!$AppUI->isStudent())
                    @include('elements.payment_remainder')
                @endif

                <div class="navbar-nav ms-auto user-area d-none d-sm-block">

                    <?php if (!empty($AppUI['id'])): ?>
                    <div class="d-flex align-items-center">
                        <a class="user_profile" href="<?= $BASE_URL;?>/admin/profile-update">

                  
                            <div style="position: relative; width:48px; font-size:10px; display:inline;">
                                @if(!$AppUI->isStudent())
                                    @if( $is_subscribed )
                                    <span class="badge bg-success p-1 d-none d-sm-block" style="position: absolute; right:0px;">premium</span>
                                    @endif
                                    @if( !$is_subscribed )
                                        <?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                                            <span class="badge bg-info d-none d-sm-block" style="position: absolute; right:0;">basic</span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning d-none d-sm-block" style="position: absolute; right:0;">basic</span>
                                        <?php } ?>
                                    @endif
                                @endif
                                <?php if (!empty($AppUI->profileImage->path_name)): ?>
                                    <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                                <?php else: ?>
                                    <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                                <?php endif; ?>
                            </div>
                       

                            <span class="admin_name"><?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : 'Admin';?>
                                @if( $is_subscribed )
                                    <span class="badge bg-success d-sm-none">premium</span>
                                @endif
                                @if( !$is_subscribed )
                                    <?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                                        <span class="badge bg-info d-sm-none">basic</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning d-sm-none">basic</span>
                                    <?php } ?>
                                @endif
                            </span>
                            
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><img class="dro_set" src="{{ asset('img/setting.svg') }}" width="36px" alt="globe"></a>
                            <div class="dropdown-menu header">
                                <!-- <a class="dropdown-item" href="/email/email_template">
                                    Modèle d'email
                                </a>
                                <a class="dropdown-item" href="/admin/update_core_dataset_options">
                                    data set master
                                </a> -->
                                <!--@if($AppUI['person_type'] != 'SUPER_ADMIN')
                                    @can('parameters-list')
                                        <a class="dropdown-item" href="{{ route('event_category.index') }}">{{ __('Parameters') }}</a>
                                    @endcan
                                @endif-->
                                @can('email-template-list')
                                    <a class="dropdown-item" href="/admin/email-template">{{ __('Email Template') }}</a>
                                @endcan
                                @can('terms-condition-list')
                                    <a class="dropdown-item" href="/admin/term_cond/term_cond_cms">{{ __('Terms & Conditions') }}</a>
                                @endcan
                                @can('language-list')
                                    <a class="dropdown-item" href="/admin/language">{{ __('Languages') }}</a>
                                @endcan
                                @can('translation-list')
                                    <a class="dropdown-item" href="/languages">{{ __('Translations') }}</a>
                                @endcan
                                @can('role-list')
                                    <a class="dropdown-item" href="/admin/roles">{{ __('Roles') }}</a>
                                @endcan


                                <a class="dropdown-item text-danger" href="/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Logout') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</div>