<div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><img class="dro_set" src="{{ asset('img/setting.svg') }}" width="36px" alt="globe"></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/users/edit_user">
                                    Votre compte utilisateur
                                </a>
                                <a class="dropdown-item" href="/param/parameters">
                                    paramètres
                                </a>
                                <a class="dropdown-item" href="/term_cond/accept_term_cond">
                                    Conditionsd'utilisations et<br/> politique de confidentialité
                                </a>
                                <a class="dropdown-item" href="/email/email_template">
                                    Modèle d'email
                                </a>
                                <a class="dropdown-item" href="/admin/language_master_list">
                                    Language Master
                                </a>
                                <a class="dropdown-item" href="/home_page_cms">
                                   Gérer la page d'accueil
                                </a>
                                <a class="dropdown-item" href="/admin/page_translate_master_list">
                                    translate master
                                </a>
                                <a class="dropdown-item" href="/admin/update_core_dataset_options">
                                    data set master
                                </a>
                                <a class="dropdown-item" href="/admin/page_master_list">
                                    page master
                                </a>
                                <a class="dropdown-item" href="/term_cond/term_cond_cms">
                                    Conditions d'utilisations et<br/> politique de confidentialité
                                </a>
                                <a class="dropdown-item" href="/">
                                    Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>