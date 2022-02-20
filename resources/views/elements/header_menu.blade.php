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
                    </div>
                    <div class="navbar-nav ms-auto">
                        <span class="admin_name">{{ __('Username') }}</span>
                        <img src="{{ asset('img/admin.jpeg') }}" class="admin_logo" alt="globe">
                        <img src="{{ asset('img/setting.svg') }}" width="36px" alt="globe">
                    </div>
                </div>
            </div>
        </nav>
    </div>