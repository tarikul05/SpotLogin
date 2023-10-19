
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sportlogin</title>
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
    <script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
    <script src="{{ asset('js/common-scripts.js')}}"></script>
    <x-embed-styles />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.15/bootstrap-4.min.css" rel="stylesheet">
    @section('head_links')
    @show
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" style="background-color: #152245!important;">
      <a href="{{ route('admin.index') }}" class="navbar-brand" href="#">SUPERADMIN</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item @if(request()->is('admin/dashboard')) active @endif">
            <a class="nav-link" href="{{ route('admin.index') }}">Dashboard</a>
          </li>
          <li class="nav-item @if(request()->is('admin/schools')) active @endif">
            <a class="nav-link" href="{{ route('schools') }}">Schools</a>
          </li>
          <li class="nav-item @if(request()->is('admin/subscriptions')) active @endif">
            <a class="nav-link" href="{{ route('subscriptions.getSubscription') }}">Subscriptions</a>
          </li>
          <li class="nav-item dropdown @if(request()->is('admin/plans')) active @endif">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Plans</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a href="{{ route('plan.index') }}" class="dropdown-item" href="#">List all</a>
              <a href="{{ route('plans.store') }}" class="dropdown-item" href="#">Add new plan</a>
            </div>
          </li>
          <li class="nav-item dropdown @if(request()->is('admin/coupons') || request()->is('admin/create-coupon')) active @endif">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Coupons</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a href="{{ route('coupon.index') }}" class="dropdown-item" href="#">List all</a>
              <a href="{{ route('coupons.store') }}" class="dropdown-item" href="#">Add new coupon</a>
            </div>
          </li>
          <li class="nav-item dropdown @if(request()->is('admin/faqs')) active @endif">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">F.A.Q</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="{{ route('faqs.add') }}">Create F.A.Q</a>
              <a class="dropdown-item" href="{{ route('categories.add') }}">Create category</a>
              <a class="dropdown-item" href="{{ route('faqs.list') }}">Manage</a>
            </div>
          </li>
          <li class="nav-item dropdown @if(request()->is('admin/tasks')) active @endif">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tasks</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a href="{{ route('task.index') }}" class="dropdown-item" href="#">List all</a>
              <a href="{{ route('tasks.store') }}" class="dropdown-item" href="#">Add new task</a>
            </div>
          </li>
          <li class="nav-item @if(request()->is('admin/alerts')) active @endif">
            <a class="nav-link" href="{{ route('alert.index') }}">Alerts</a>
          </li>
          <li class="nav-item @if(request()->is('admin/contacts')) active @endif">
            <a class="nav-link" href="{{ route('contacts.index') }}">Messages</a>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <!--<input class="form-control mr-sm-2" type="text" placeholder="Search on app" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
          <a href="/logout" class="btn btn-outline-danger my-2 my-sm-0">Logout</a>
        </form>
      </div>
    </nav>

    <main role="main" class="container-fluid">
        @include('elements/flash-message')
        @yield('content')
    </main>

    @section('footer_js')
    @show

  </body>
</html>
