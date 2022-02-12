<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>
<script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css"/>
<!-- Links form inner page -->
@section('head_links')

@show

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
<section class="m-4 top">
    @include('elements.header_menu')
</section>
<section class="m-4">
	@include('elements/flash-message')
    @yield('content')
</section>

<!-- js comes form inner page start -->
@section('footer_js')
@show
<!-- js comes form inner page end -->
</body>
</html>