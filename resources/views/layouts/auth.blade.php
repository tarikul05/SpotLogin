<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
  <title>@yield('title')</title>

  <!-- Bootstrap -->
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Bootstrap select box-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">

  <!-- fontawesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">


  <!-- Theme style -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/main_style.css') }}">

  <!-- flag icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">


  <script src="{{ asset('js/jquery-3.5.1.js')}}"></script>


  <meta name="_token" content="{{ csrf_token() }}">
  <script type="text/javascript">
    var BASE_URL = "{{ URL::to('/')}}";
    var CURRENT_URL = '{{ $CURRENT_URL }}';
    var controller = '{{ $controller }}';
    var action = '{{ $action }}';
  </script>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bgdarkblue shadow fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img src="{{ asset('img/logo.png') }}" width="36">
      </a>
      <ul class="navbar-nav ml-auto align-items-center" style="flex-direction: row!important;">
        <li class="nav-item active">
          <a class="px-2 nav-link login_btn" href="#ourSolutions" >{{ __('Our solutions') }}</a>
        </li>
        <li class="nav-item">
          <select id="setLan" class="selectpicker" data-width="fit" >
            @foreach ($language as $key => $lan)
                <option 
                value="{{ $lan->language_code }}"
                @if ($lan->language_code == app()->getLocale())
                    selected="selected"
                @endif
                data-icon="{{ $lan->flag_class}}"
                >  {{ $lan->title }}</option>
            @endforeach
          </select>
        </li>

        <li class="nav-item active">
          <a class="px-2 nav-link login_btn" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">{{ __('Login') }}</a>
        </li>
        <li class="nav-item active">
          <a class="px-2 nav-link login_btn" href="#" data-bs-toggle="modal" data-bs-target="#schoolsignupModal">{{ __('Sign up') }}</a>
        </li>
        <li class="nav-item">
          <a class="px-2 nav-link" href="#"><img src="{{ asset('img/globe.svg') }}" width="32" height="32"></a>
        </li>
      </ul>
      <!-- </div> -->
    </div>
  </nav>


  @yield('content')
  <div id="pageloader">
      <img src="{{ asset('img/loading.gif') }}" alt="processing..." />
  </div>

  <footer>
    <h2 class="gilroy-regular txtdarkblue">{{ __('Contact us') }}</h2>
    <p class="mb-0"><a href="#" class="txtdarkblue"><img src="{{ asset('img/call.svg') }}" alt=""> +41 22 50 17 956 </a></p>

    <p class="mb-0"><a href="#" class="txtdarkblue"><img src="{{ asset('img/email.svg') }}" alt=""> contact@sportlogin.ch</a></p>
  </footer>

  
  @include('layouts.elements.modal_login')
  @include('layouts.elements.modal_reset_login')
  @include('layouts.elements.modal_school_sign_up')
  @include('layouts.elements.modal_forgot_password')
  @include('layouts.elements.modal_loader')

  
  <!--common script for all pages-->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>


  <!--common script for all pages-->
    
  <script src="{{ asset('js/common-scripts.js')}}"></script>

</body>
<script type="text/javascript">
  $(document).ready(function() {


    $("#setLan").change(function(event) {
      var lanCode = $(this).val();
      window.location.href = BASE_URL+"/setlang/"+lanCode ;
    });


    



  });
  
</script>

</html>