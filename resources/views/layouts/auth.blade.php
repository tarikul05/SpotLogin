<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap select box-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta/css/bootstrap-select.min.css">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    


    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/mainstyle.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <!-- flag icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
    
    <meta name="_token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        var BASE_URL = "{{ URL::to('/')}}";
        var CURRENT_URL = '{{ $CURRENT_URL }}';
        var controller = '{{ $controller }}';
        var action = '{{ $action }}';
    </script>
  </head>
  <body>
    <div class="m-4 top">
      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-dark bgdarkblue shadow fixed-top">
          <div class="container-fluid">
              <a class="navbar-brand" href="/">
                  <img src="{{ asset('img/logo.png') }}" width="36">
              </a>
              <ul class="navbar-nav ml-auto align-items-center" style="flex-direction: row!important;">
                  <li class="nav-item">
                      
                      <select id="language_selectpicker" class="selectpicker" data-width="fit">
                          
                      </select> 
                      
                  </li>
                  
                  <li class="nav-item active">
                      <a class="px-2 nav-link login_btn" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
                  </li>
                  <li class="nav-item">
                      <a class="px-2 nav-link" href="#"><img src="{{ asset('img/globe.svg') }}" width="32" height="32"></a>
                  </li>
              </ul>
              <!-- </div> -->
          </div>
      </nav>
      <!-- Full Page Image Header with Vertically Centered Content -->
      <header class="masthead">
          <div class="circle-bg"></div>
          <div class="container-fluid h-100">
              <div class="row h-100 align-items-center">
                  <div class="col-12">
                      <h1 class="mb-0 gilroy-bold text-white"><img src="{{ asset('img/SPORT-LOGIN-logo.png') }}">Sportlogin</h1>
                      <h4 class="gilroy-bold text-white">Let Sportlogin do your off-ice </h4>
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
            
    </div>
    <section class="m-4">
        @yield('content')
    </section>


    <!--common script for all pages-->
    <script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta/js/bootstrap-select.min.js"></script>
   

    <!-- <script src='lib/moment.js'></script>
    <script src='lib/moment-timezone-with-data.js'></script>


    <script src="../teamvg/common/custom_sm.js?dt=new Date()" type="text/javascript"></script>
    <script src="../teamvg/js/custom_translate_website.js" type="text/javascript"></script> -->

    
  </body>
  <script>
    var TIMEZONEOFFSET='';
    var langid=$('#language_selectpicker').val();
    $(document).ready( function () {
      $("#language_selectpicker").change(function(){
        langid=$('#language_selectpicker').val();
        document.cookie = "glang_id="+langid+";path=/";
        setCookie('glang_id', langid, 365);
        getCookie('glang_id');
        setSessionStorage('glang_id', langid);
        setSessionStorage('Language', langid);            
        //LoadAppMessages();   //load app messages
                
      });
      PopulateLanguage();
      PopulateCountry();
      //LoadAppMessages();   //load app messages 
      
      

      function PopulateCountry() {
        $.ajax({
            url: 'admin/get_master_data.php',
            data: 'type=country',
            type: 'POST',
            dataType: 'json',
            async: false,
            encode: true,
            success: function (data) {
                var resultHtml = '';
                $.each(data, function (key, value) {
                    resultHtml += '<option value="' + value.code + '">' + value.name + '</option>';
                });
                $('#country_id').html(resultHtml);
                $('#country_id').selectpicker('refresh');

            },   // sucess
            error: function (ts) { 
                errorModalCall(GetAppMessage('error_message_text'));

            }
        });
      }



      function PopulateLanguage(){
        $.ajax({
          url: 'admin/get_master_data.php',
          data: 'type=language_list&TIMEZONEOFFSET='+TIMEZONEOFFSET,
          type: 'POST',                     
          dataType: 'json',
          async: false,
          success: function(data) {
            var resultHtml ='';
                    $.each(data, function(key,value){
                        //onclick="lang_Click('+value.id+','+"'"+value.name+"'"+')"
                        if (getCookie('glang_id') == value.id){
                            //resultHtml+='<option value="'+value.id+'" data-id="'+value.id+'" data-icon="'+value.language_icon+'" onclick="lang_Click('+"'"+value.id+"'"+','+"'"+value.name+"'"+');" selected>'+value.name+'</option>';
                            resultHtml+='<option value="'+value.id+'" data-id="'+value.id+'" data-icon="'+value.language_icon+'" selected>'+value.name+'</option>';
                        } else {
                            //resultHtml+='<option value="'+value.id+'" data-id="'+value.id+'" data-icon="'+value.language_icon+'" onclick="lang_Click('+"'"+value.id+"'"+','+"'"+value.name+"'"+');">' +value.name+'</option>';
                            resultHtml+='<option value="'+value.id+'" data-id="'+value.id+'" data-icon="'+value.language_icon+'" >' +value.name+'</option>';
                        }
                        
            });
            
            //alert(resultHtml);
            $('#language_selectpicker').html(resultHtml);
            $('#language_selectpicker').selectpicker('refresh');
            langid=getCookie('glang_id');
            console.log('langid='+langid);
            if (langid.trim() ==''){
              document.getElementById("language_selectpicker").selectedIndex = "0"; 
              langid=$('#language_selectpicker').val();
              setCookie('glang_id', langid, 365);
              setSessionStorage('glang_id', langid);
              setSessionStorage('Language', langid);            
            } 
            else{
              setSessionStorage('glang_id', langid);
              setSessionStorage('Language', langid);            

            }
      

          },   // sucess
          error: function(ts) { 
              errorModalCall(GetAppMessage('error_message_text'));

          }
        }); 
	    }

	    function AcceptCookies(){
        setCookie('cookies_accepted','Y',365);
      }
      

    } );
  </script>
</html>