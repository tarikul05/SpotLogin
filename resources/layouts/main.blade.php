<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<meta name="_token" content="{{ csrf_token() }}">
<script type="text/javascript">
    var baseUrl = '{{ $BASE_URL }}';
    var CURRENT_URL = '{{ $CURRENT_URL }}';
    var controller = '{{ $controller }}';
    var action = '{{ $action }}';
    var HTTP_HOST = '{{ $_SERVER['HTTP_HOST'] }}';
    var MESSAGE_CONFIRM_DELETE = '{{ __('MESSAGE_CONFIRM_DELETE') }}';
</script>
</head>
<body>
<div class="m-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('images/logo.svg') }}" height="28" alt="CoolBrand">
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
                @if (Route::has('logout'))
                    @auth
                        <a href="{{ url('/logout') }}" class="bnav-item nav-link">Logout</a>
                    @else
                        <a href="" class="nav-item nav-link"  data-toggle="modal" data-target="#exampleModalCenter">Sign-up as a coach</a>
                    @endauth
                @endif
                </div>
            </div>
        </div>
    </nav>
</div>
<section class="m-4">
    @yield('content')
</section>
</body>
</html>