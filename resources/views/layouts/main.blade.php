<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
<title>@yield('title')</title>
<script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<!-- Links form inner page -->
@section('head_links')

@show

<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/payment_style.css') }}">
<meta name="_token" content="{{ csrf_token() }}">
<script type="text/javascript">
    var BASE_URL = "{{ URL::to('/')}}";
    var CURRENT_URL = '{{ $CURRENT_URL ?? '' }}';
    var controller = '{{ $controller ?? '' }}';
    var action = '{{ $action ?? '' }}';
    var HTTP_HOST = '{{ $_SERVER['HTTP_HOST'] }}';
    var MESSAGE_CONFIRM_DELETE = '{{ __('MESSAGE_CONFIRM_DELETE') }}';
    function getTimeZone() {
      var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
      return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
    }
    
</script>
<script src="{{ asset('js/common-scripts.js')}}"></script>

	
</head>
<body>
<?php 
    use Illuminate\Support\Facades\Route;
    $get_action = Route::getCurrentRoute()->getName();
    $get_action = strtolower(str_replace('.','-', $get_action));
?>
<section class="">
    <div class="pc_only">
        @include('elements.payment_remainder')
    </div>
    <div class="m-4 top header-navbar">
        @include('elements.header_menu')
    </div>
    <div class="sp_only">
        @include('elements.payment_remainder')
    </div>
</section>

<section class="{{ $get_action }}" id="main-content">
	@include('elements/flash-message')
    @yield('content')
    <div id="pageloader">
      <img src="{{ asset('img/loading.gif') }}" alt="processing..." />
    </div>
</section>

<!-- js comes form inner page start -->
@section('footer_js')
@show
<!-- js comes form inner page end -->
</body>
</html>