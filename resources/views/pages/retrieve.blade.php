@extends('layouts.retrieve')

@section('title', 'Sportlogin')

@section('content')

<!-- Full Page Image Header with Vertically Centered Content -->
<section class="why-sportlogin">
  <div class="container-fluid">
    <div class="row text-center">
      <div class="col-12 pt-3">
        <h3 class="gilroy-regular txtdarkblue">{{ __('Find my Login ID') }}</h3>
      </div>
      <div class="col-12">
        <img src={{ asset('img/logo-blue.png') }} width="120">
      </div>
      <div class="col-12">
        <!-- bootstrap form centered -->
        <div class="d-flex justify-content-center">
          <form class="form-inline">
            <select class="form-select" aria-label="Default select example">
                <option value="null" selected>Choose a Login ID</option>
                @foreach($usernames as $username)
                <option value="{{ $username }}">{{ $username->username }}</option>
              @endforeach
              </select>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
