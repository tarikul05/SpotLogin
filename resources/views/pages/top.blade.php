@extends('layouts.auth')
@section('title', 'Sportlogin')
@section('content')
<!-- Full Page Image Header with Vertically Centered Content -->
<header class="masthead">
  <div class="circle-bg"></div>
  <div class="container-fluid h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12">
        <h1 class="mb-0 gilroy-bold text-white"><img src="{{ asset('img/SPORT-LOGIN-logo.png') }}">Sportlogin</h1>
        <h4 class="gilroy-bold text-white">{{ __('Let Sportlogin do your off-ice') }} </h4>
        <!-- <h4 class="gilroy-bold text-white">Finally an app that makes the coaches life easier</h4>
                <p class="gilroy-normal text-white">Simplify your daily organization</p> -->
        <div class="masthead-btn-area">
          <a href="#" class="head-btn">
            <img src="{{ asset('img/app-store.svg') }}" width="148">
          </a>
          <a href="#" class="head-btn">
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

        <p class="gilroy-light txtdarkblue">{{ __('Communicate with the parents within the app') }} <br>
        <h4 class="gilroy-bold">Cooming Soon</h4>
        </p>

      </div>
      <div class="col-lg-3 col-md-6 col-6 sportlogin-feature">
        <div class="sportlogin-feature-icon">
          <img src="{{ asset('img/payment.svg') }}" alt="" class="mx-auto">
        </div>
        <h4 class="gilroy-bold">{{ __('Payment') }} </h4>
        <p class="gilroy-light txtdarkblue">{{ __('Allow your students to pay online in a click') }}
        <h4 class="gilroy-bold">{{ __('Cooming Soon') }} </h4>
        </p>
      </div>
    </div>
  </div>
</section>

<section class="light-grey-bg about-us">
  <div class="container-fluid p-0" style="max-width: 100%;">
    <div class="row no-gutters">
      <div class="col-lg-6 col-md-12 order-1 order-lg-2">
        <div class="about-us-cont-bg" style="background: url({{ asset('img/nguyen-thu-hoai-v0H-vn0BixI-un@2x.png') }}); background-size: cover;background-position: center;">
          <h1 class="mb-0 gilroy-bold text-white"><img src="{{ asset('img/SPORT-LOGIN-logo.png') }}" width="78">Sportlogin</h1>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 py-5 about-us-content order-2 order-lg-1">
        <h2 class="gilroy-regular txtdarkblue mb-4">What is Sportlogin?</h2>
        <p class="gilroy-light txtdarkblue">Over the years, I've noticed spending more and more time
          managing planning, invoices and communication rather than actually coaching. </p>
        <p class="gilroy-light txtdarkblue">I had to find a sustainable solution to remediate that. I
          decided to create a new solution as I couldn't find an affordable, simple enough solution on the
          market. </p>
        <p class="gilroy-light txtdarkblue">That allowed me to save significant administrative time to keep
          the focus on the main activity.</p>
        <p class="gilroy-light txtdarkblue">It's been 10 years I'm using this solution called
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
        <h2 class="gilroy-regular txtdarkblue mb-4">Software capabilities</h2>
        <ul class="list-unstyled">
          <li><i class="fas fa-chevron-right"></i> <b>PLAN,</b> lessons and events easily </li>
          <li><i class="fas fa-chevron-right"></i> <b>PERSONNALIZE,</b> your different fees</li>
          <li><i class="fas fa-chevron-right"></i> <b>MANAGE,</b> your students profile
          </li>
          <li><i class="fas fa-chevron-right"></i> <b>STAY INFORMED,</b> with real time visibility of the planning, for you and your students</li>
          <li><i class="fas fa-chevron-right"></i> <b>INVOICE,</b> easily and automatically </li>
          <li><i class="fas fa-chevron-right"></i> <b>KEEP TRACK,</b> of payments and invoices </li>
        </ul>
      </div>
    </div>
  </div>
</section>
@endsection