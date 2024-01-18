@extends('layouts.auth')
@section('title', 'Sportlogin')
@section('content')
<!-- Full Page Image Header with Vertically Centered Content -->
<header class="masthead">
  <div class="circle-bg"></div>
  <div class="container-fluid h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12 bloc-title">
        <h1 class="mb-0 gilroy-bold text-white"><!--<img src="{{ asset('img/christmas.png') }}">-->Sportlogin</h1>
        <h4 class="gilroy-bold text-white">{{ __('Your off-ice champion') }} </h4>
        <!-- <h4 class="gilroy-bold text-white">Finally an app that makes the coaches life easier</h4>
                <p class="gilroy-normal text-white">Simplify your daily organization</p> -->
        <div class="masthead-btn-area" style="display: none;">
          <a href="https://apps.apple.com/us/app/sportlogin/id6443801938" class="head-btn">
            <img src="{{ asset('img/app-store.svg') }}" width="148">
          </a>
          <a href="https://play.google.com/store/apps/details?id=com.sportlogin.app" class="head-btn">
            <img src="{{ asset('img/play-store.svg') }}" width="148">
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="phone-bg mx-auto text-right"></div>
</header>

<section class="why-sportlogin">
  <div class="container-fluid">
    <div class="row text-center">
      <div class="col-12">
        <h2 class="gilroy-regular txtdarkblue">{{ __('Why Sportlogin?') }}</h2>
      </div>
      <div class="col-lg-3 col-md-6 col-6 sportlogin-feature">
        <div class="sportlogin-feature-icon">
          <img src="{{ asset('img/schedule.svg') }}" alt="" class="mx-auto">
        </div>
        <h4 class="gilroy-bold">{{ __('Schedule') }} </h4>
        <p class="gilroy-light txtdarkblue"> {{ __('Manage your schedule with your own personalized fees') }} </p>
      </div>
      <div class="col-lg-3 col-md-6 col-6 sportlogin-feature">
        <div class="sportlogin-feature-icon">
          <img src="{{ asset('img/invoice.svg') }}" alt="" class="mx-auto">
        </div>
        <h4 class="gilroy-bold">{{ __('Invoices') }}</h4>
        <p class="gilroy-light txtdarkblue">{{ __('Automatically edit your invoices and send them in') }} </p>
      </div>
      <div class="col-lg-3 col-md-6 col-6 sportlogin-feature">
        <div class="sportlogin-feature-icon">
          <img src="{{ asset('img/communication.svg') }}" alt="" class="mx-auto">
        </div>
        <h4 class="gilroy-bold">{{ __('Communication') }}</h4>

        <p class="gilroy-light txtdarkblue">{{ __('Manage your students') }} {{ __('Import/Export') }}<br>
        <!--<h4 class="gilroy-bold">{{ __('Coming Soon')}}</h4>-->
        </p>

      </div>
      <div class="col-lg-3 col-md-6 col-6 sportlogin-feature">
        <div class="sportlogin-feature-icon">
          <img src="{{ asset('img/payment.svg') }}" alt="" class="mx-auto">
        </div>
        <h4 class="gilroy-bold">{{ __('Payment') }} </h4>
        <p class="gilroy-light txtdarkblue">{{ __('Allow your students to pay online in a click') }} <span class="badge bg-light text-dark">{{ __('Coming Soon') }}</span>
        <!--<h4 class="gilroy-bold">{{ __('Coming Soon') }} </h4>-->
        </p>
      </div>
    </div>
  </div>
</section>

<section class="light-grey-bg about-us">
  <div class="container-fluid p-0" style="max-width: 100%;">
    <div class="row no-gutters">
      <div class="col-lg-6 col-md-12 order-1 order-lg-2 position-relative">
        <div class="bg-light text-center" style="margin-bottom:0px; padding-bottom:0px;">
          <h1 class="mb-0 gilroy-bold text-white text-center position-absolute" style="font-size:20px; background-color:#000; border-radius:13px; margin:10px; opacity:.5; padding:3px; padding-right:15px;" id="title_video"><img src="{{ asset('img/SPORT-LOGIN-logo.png') }}" width="40">Sportlogin demo</h1>
          <video controls width="100%" style="border:none; width:100%; padding:0 margin:0; margin-bottom:0px; padding-bottom:0px;" poster="img/nguyen-thu-hoai-v0H-vn0BixI-un@2x.png" preload="auto">
        <source style="width:100%;" src="{{ asset('videos/sportlogin.mp4') }}" type="video/mp4" />
          </video>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 py-5 about-us-content order-2 order-lg-1">
        <h2 class="gilroy-regular txtdarkblue mb-4">{{ __('What is Sportlogin?') }}</h2>
        <p class="gilroy-light txtdarkblue">{{ __("Over the years, I've noticed spending more and more time managing planning, invoices and communication rather than actually coaching") }}.</p>
        <p class="gilroy-light txtdarkblue">{{ __("I had to find a sustainable solution to remediate that. I decided to create a new solution as I couldn't find an affordable, simple enough solution on the market") }}.</p>
        <p class="gilroy-light txtdarkblue">{{ __('That allowed me to save significant administrative time to keep the focus on the main activity') }}.</p>
        <p class="gilroy-light txtdarkblue">{{ __("It's been 10 years I'm using this solution called") }}
          <b>SPORTLOGIN</b>
        </p>
      </div>
    </div>
  </div>
</section>
<section class="light-blue-bg software-capabilities">
  <div class="container-fluid p-0" style="max-width: 100%;">
    <div class="row no-gutters">
      <div class="col-lg-6 col-md-12">
        <div class="software-capabilities-cont-bg" style="background: url({{ asset('img/amelia-bartlett-OgT83CPGbQI-un_fi@2x.png') }}); background-size: cover;background-position: center;">
        </div>
      </div>
      <div class="col-lg-6 col-md-12 py-5 software-capabilities-content">
        <h2 class="gilroy-regular txtdarkblue mb-4">{{ __("Software capabilities") }}</h2>
        <ul class="list-unstyled">
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("PLAN") }},</b> {{ __("lessons and events easily") }} </li>
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("PERSONNALIZE") }},</b> {{ __("your different fees") }}</li>
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("MANAGE") }},</b> {{ __("your students profile") }}</li>
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("STAY INFORMED") }},</b> {{ __("with real time visibility of the planning, for you and your students") }}</li>
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("INVOICE") }},</b> {{ __("easily and automatically") }}</li>
          <li><i class="fas fa-chevron-right"></i> <b>{{ __("KEEP TRACK") }},</b> {{ __("of payments and invoices") }}</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="light-grey-bg about-us" id="ourSolutions">
  <div class="container-fluid p-0" style="max-width: 100%;">
    <div class="row no-gutters">
      <div class="col-lg-6 col-md-12 order-1 order-lg-2">
        <div class="about-us-cont-bg" style="background: url({{ asset('img/nguyen-thu-hoai-v0H-vn0BixI-un@2x.png') }}); background-size: cover;background-position: center;">
          <h1 class="mb-0 gilroy-bold text-white"><img src="{{ asset('img/SPORT-LOGIN-logo.png') }}" width="78">Sportlogin</h1>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 py-5 about-us-content order-2 order-lg-1">
        <p class="gilroy-light txtdarkblue"><b>{{ __("Single coach") }} :</b> {{ __("Now, if a user chooses a single coach account, they don’t have access to the “teacher” tab in the header. So a single teacher account cannot become a school account for now") }}.</p>
        <p class="gilroy-light txtdarkblue"><b>{{ __("School") }} :</b> {{ __("But if the user selects school accounts, they don’t have a limit on the amount of teachers they can add. So they can move from 2 teachers to 10 teachers if they want, the only different will be when they decide to go premium, they will have to pay for the right account type") }}.</p>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalStudent" tabindex="-1" role="dialog" aria-labelledby="modalStudent" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #152245;">
          <h5 class="modal-title" id="exampleModalLabel">Students Login</h5>
            <i class="fa-solid fa-circle-xmark fa-lg text-light close" data-bs-dismiss="modal" style="margin-top:-7px; border:none; cursor:pointer; font-size:25px;"></i>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-3">
                    <img src="{{ asset('img/StudentStep1.png') }}" alt="" class="img-fluid img-thumbnail" width="100%">
                </div>
                <div class="col-md-8 p-2 pt-4 text-center">
                    <h6 class="gilroy-light txtdarkblue"><i class="fa-solid fa-circle-info"></i> {{ __("You receive an email from your coach with a link to create your account") }}.</h6>
                    <p></p>
                    <h6 class="gilroy-light txtdarkblue"><i class="fa-solid fa-circle-info"></i> {{ __("Click on the link and follow the instructions") }}.</h6>
                </div>
            </div>
<hr>
            <div class="row">

                <div class="col-md-8 p-2 pt-4 text-center">
                    <h6 class="gilroy-light txtdarkblue"><i class="fa-solid fa-circle-info"></i> {{ __("Complete your personnal informations") }}.</h6>
                    <p></p>
                    <h6 class="gilroy-light txtdarkblue"><i class="fa-solid fa-circle-info"></i> {{ __("Valid for create your account") }}.</h6>
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('img/StudentStep2.png') }}" alt="" class="img-fluid img-thumbnail" width="100%">
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="switchModalBtn" class="btn btn-primary close" data-bs-dismiss="modal" aria-label="Close">OK</button>
        </div>
      </div>
    </div>
  </div>



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #152245;">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Privacy policy') }}</h5>
            <i class="fa-solid fa-circle-xmark fa-lg text-light close" data-bs-dismiss="modal" style="margin-top:-7px; border:none; cursor:pointer; font-size:25px;"></i>
        </div>
        <div class="modal-body">
            {!! $template->tc_text !!}
        </div>
        <div class="modal-footer">
          <button type="button" id="switchModalBtn" class="btn btn-primary close" data-bs-dismiss="modal" aria-label="Close">{{ __('I understand') }}</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #152245;">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Terms & conditions') }}</h5>
            <i class="fa-solid fa-circle-xmark fa-lg text-light close" data-bs-dismiss="modal" style="margin-top:-7px; border:none; cursor:pointer; font-size:25px;"></i>
        </div>
        <div class="modal-body">
            {!! $template->spp_text !!}
        </div>
        <div class="modal-footer">
          <button type="button" id="switchModalBtn" class="btn btn-primary close" data-bs-dismiss="modal" aria-label="Close">{{ __('I understand') }}</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#switchModalBtn').on('click', function() {
        $('#exampleModal2').modal('hide');
      });

      $('#exampleModal2').on('hidden.bs.modal', function (e) {
        $('#schoolsignupModal').modal('show');
        //document.getElementById('terms_condition').checked = true;
        //$('#terms_condition').prop('checked', true).trigger('change');
      });
    });

    $('#terms_condition').on('click', function() {
    if($(this).prop('checked')) {
      // Si la case est cochée, activez le bouton
      $('#signup_form_button').prop('disabled', false);
    } else {
      // Sinon, désactivez le bouton
      $('#signup_form_button').prop('disabled', true);
    }
  });
    </script>

<script>
    // Javascript complete function for detect if user is on web ; android or ios
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    if(isMobile.any()) {
        $(".masthead-btn-area").fadeIn("fast");
        $(".bloc-title").addClass("pt-2");
    } else {
        $(".masthead-btn-area").fadeIn("fast");
        // add class pt-2 to .masthead-btn-area
        $(".bloc-title").addClass("pt-0");
    }

    const media = document.querySelector("video");
    media.addEventListener("click", playPauseMedia);
    function playPauseMedia() {
        if (media.paused) {
            media.play();
        } else {
            media.pause();
        }
    }
    media.onpause = function() {
        console.log("User Paused");
        const titlevideo = document.getElementById("title_video");
        titlevideo.style.display = "block";
    };
    media.onplay = function() {
        console.log("User Started");
        const titlevideo = document.getElementById("title_video");
        titlevideo.style.display = "none";
    };

</script>
@endsection
