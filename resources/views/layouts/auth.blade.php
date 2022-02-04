<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
                    <a class="px-2 nav-link login_btn" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                </li>
                <li class="nav-item">
                    <a class="px-2 nav-link" href="#"><img src="{{ asset('img/globe.svg') }}" width="32" height="32"></a>
                </li>
            </ul>
            <!-- </div> -->
        </div>
    </nav>
    
          
    @yield('content')

    <footer>
      <h2 class="gilroy-regular txtdarkblue">Contact us</h2>
      <p class="mb-0"><a href="#" class="txtdarkblue"><img src="{{ asset('img/call.svg') }}" alt=""> +41 22 50 17 956 </a></p> 
      
      <p class="mb-0"><a href="#" class="txtdarkblue"><img src="{{ asset('img/email.svg') }}" alt=""> contact@sportlogin.ch</a></p>
    </footer>


    @include('layouts.elements.modal_login')


    <!--common script for all pages-->
    <script src="{{ asset('js/jquery-3.5.1.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

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
      //PopulateLanguage();
      //PopulateCountry();
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