<div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <img src="{{ asset('img/logo.png') }}" width="45px" alt="SpotLogin">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
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
                        <a href="{{ $url }}" class="nav-item nav-link active">{{ __('My Schedule')}}</a>
                        
                        @can('schools-list')
                            <?php if ($AppUI['person_type']=='SUPER_ADMIN'): ?>
                                <a href="{{ route('schools') }}" class="nav-item nav-link">{{ __('Schools') }}</a>
                            <?php else: ?>
                                <a href="{{ route('school-update') }}" class="nav-item nav-link">{{ __('School') }}</a>
                            <?php endif; ?>
                        @endcan

                        @hasanyrole('teachers_admin|teachers_all|teachers_medium|teachers_minimum')
                            <a href="{{ route('updateTeacher') }}" class="nav-item nav-link">{{ __('My Account') }}</a> 
                        @else
                            @can('teachers-list')
                                @if($AppUI['person_type'] != 'SUPER_ADMIN')
                                   <a href="{{ route('teacherHome') }}" class="nav-item nav-link">{{ __('Teachers') }}</a> 
                                @endif
                            @endcan
                        @endhasanyrole

                        @hasanyrole('student')
                            <a href="{{ route('updateStudent') }}" class="nav-item nav-link">{{ __('My Account') }}</a> 
                        @endhasanyrole
                        

                        @can('students-list')
                            @if($AppUI['person_type'] != 'SUPER_ADMIN')
                               <a href="{{ route('studentHome') }}" class="nav-item nav-link">{{ __('Students') }}</a> 
                            @endif
                        @endcan
                        <div class="nav-item dropdown">
                             <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ __('Invoicing') }}</a>
                            <div class="dropdown-menu header">

                            @if(!$AppUI->isStudent())
                                <a href="{{ $urlInvoice }}" class="dropdown-item">{{ __('Invoice\'s List') }}</a>
                                <a href="{{ $urlStudentInvoice }}" class="dropdown-item">{{ __('Student\'s Invoice') }}</a>
                                <a href="{{ $urlTeacherInvoice }}" class="dropdown-item">{{ __("Professor's Invoice") }}</a>
                                <a href="{{ $manualInvoice }}" class="dropdown-item">{{ __('Manual Invoice') }}</a>
                            @else
                                <a href="{{ $urlInvoice }}" class="dropdown-item">{{ __('My Invoice') }}</a>
                            @endcan
                            </div>
                        </div>
                        
                        <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{__('coming soon')}}" class="nav-item nav-link">{{ __('Dashboard') }}</a>
                        @unlessrole('superadmin')
                            @unlessrole('student')
                                @if(count($AppUI->schools()) > 1)
                                    <a href="/permission-check" class="nav-item nav-link permission-btn btn">{{ __('Change Permission') }}</a>
                                @endif
                            @endunlessrole
                        @endunlessrole
                        
                    </div>
                    <div class="navbar-nav ms-auto user-area">
                        <?php if (!empty($AppUI['id'])): ?>
                            <a class="user_profile" href="<?= $BASE_URL;?>/admin/profile-update">
                                <span class="admin_name"><?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : 'Admin';?></span>
                                <?php if (!empty($AppUI->profileImage->path_name)): ?>
                                    <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo"  alt="globe">
                                <?php else: ?>
                                    <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo" alt="globe">
                                <?php endif; ?>

                                
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
                                    @if($AppUI['person_type'] != 'SUPER_ADMIN')
                                        @can('parameters-list')
                                            <a class="dropdown-item" href="{{ route('event_category.index') }}">{{ __('Parameters') }}</a>
                                        @endcan
                                    @endif
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


                                    @if($AppUI->isSchoolAdmin() || $AppUI->isTeacherAdmin())
                                        <a href="{{ route('updateTeacher') }}" class="dropdown-item">{{ __('My Account') }}</a> 
                                    @endif

                                    <a class="dropdown-item" href="/logout">{{ __('Logout') }}</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>