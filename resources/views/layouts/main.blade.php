<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<meta name="_token" content="{{ csrf_token() }}">
<script type="text/javascript">
    var CURRENT_URL = '{{ $CURRENT_URL }}';
    var controller = '{{ $controller }}';
    var action = '{{ $action }}';
    var HTTP_HOST = '{{ $_SERVER['HTTP_HOST'] }}';
    var MESSAGE_CONFIRM_DELETE = '{{ __('MESSAGE_CONFIRM_DELETE') }}';
</script>
</head>
<body>
<div class="m-4 top">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <img src="{{ asset('img/logo.png') }}" width="45px" alt="SpotLogin">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav">
                        <a href="#" class="nav-item nav-link active">Mon agenda</a>
                        <a href="#" class="nav-item nav-link">École</a>
                        <a href="#" class="nav-item nav-link">Professeurs</a>
                        <a href="#" class="nav-item nav-link">Élèves</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Facturation</a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item">Liste des factures</a>
                                <a href="#" class="dropdown-item">Facturation élèves</a>
                                <a href="#" class="dropdown-item">Facturation professeurs</a>
                                <a href="#" class="dropdown-item">Facture manuelle</a>
                            </div>
                        </div>
                        <a href="#" class="nav-item nav-link">Dashboard</a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <span class="admin_name">S'identifier</span>
                        <img src="{{ asset('img/admin.jpeg') }}" class="admin_logo" alt="globe">
                        <img src="{{ asset('img/setting.svg') }}" width="36px" alt="globe">
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<section class="m-4">
    @yield('content')
</section>
</body>
</html>