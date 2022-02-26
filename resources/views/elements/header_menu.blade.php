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
                        <a href="#" class="nav-item nav-link active">{{ __('My Schedule')}}</a>
                        <a href="#" class="nav-item nav-link">{{ __('School') }}</a>
                        <a href="#" class="nav-item nav-link">{{ __('Teachers') }}</a>
                        <a href="#" class="nav-item nav-link">{{ __('Students') }}</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ __('Invoicing') }}</a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item">{{ __('Invoice\'s List') }}</a>
                                <a href="#" class="dropdown-item">{{ __('Student\'s Invoice') }}</a>
                                <a href="#" class="dropdown-item">{{ __('Professor\'s Invoice') }}</a>
                                <a href="#" class="dropdown-item">{{ __('Manual Invoice') }}</a>
                            </div>
                        </div>
                        <a href="#" class="nav-item nav-link">{{ __('Dashboard') }}</a>
                        @unlessrole('superadmin')
                            <a href="/permission-check" class="nav-item nav-link permission-btn btn">{{ __('Change Permission') }}</a>
                        @endunlessrole
                        
                    </div>
                    <div class="navbar-nav ms-auto user-area">
                        
                        <a class="user_profile" href="<?= $BASE_URL;?>/admin/profile-update">
                            <span class="admin_name">{{ __(auth()->user()->username) }}</span>
                            <img src="{{ asset('img/admin.jpeg') }}" class="admin_logo" alt="globe">
                        </a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><img class="dro_set" src="{{ asset('img/setting.svg') }}" width="36px" alt="globe"></a>
                            <div class="dropdown-menu">
                                <!-- <a class="dropdown-item" href="/email/email_template">
                                    Modèle d'email
                                </a>
                                <a class="dropdown-item" href="/admin/update_core_dataset_options">
                                    data set master
                                </a> -->
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
                                <a class="dropdown-item" href="/logout">{{ __('Logout') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>