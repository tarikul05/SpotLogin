@extends('layouts.main')
@section('title','Home')
@section('content')

      @if(session()->has('error'))
          <div class="alert alert-danger invalid-feedback d-block">{{ session()->get('error') }}</div>
      @endif
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif
      @if (session('warning'))
        <div class="alert alert-warning">
          {{ session('warning') }}
        </div>
      @endif
      <!-- /hero section start -->
    <section class="hero-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="text-content">
                        <h1>Finding <span>a coach, a program or camp</span> has never been that simple.</h1>
                        <p>Coach me solutions is the easiest, safest and most affordable way to connect with an experienced coach who can help you improve your athletic performance and reach your individual goals.</p>

                        <a href="{{ url('/coach/list') }}" class="btn hero-button">Explore  <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-5">
                <div class="hero-image">
                    <img src="{{ asset('img/top-img.png') }}" alt="">
                </div>
                </div>
            </div>
        </div>
    </section>
    <section class="drop-arrow blue-arrow">
      <i class="fas fa-angle-down"></i>
    </section>
    
    <!-- /card section -->
    <section class="card-section">
      <div class="content">

        <h1>Steps For Your Great Sports Experience</h1>
        <p>Connecting you to professionally trained coaches, is what we strive to do.</p>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-sm-4 wid-50">
              <div class="card text-white card-has-bg click-col">        
                <div class="card-img-overlay d-flex flex-column">
                  <div class="card-body">
                    <h4 class="card-title mt-0 ">
                      <a herf="#">Step 1</a>
                    </h4>
                    <img class="card-img" src="{{ asset('img/purpose.png') }}" alt="">
                    <h6>
                      Go on the coach, camp 
                      or program tab and look for what you need
                    </h6>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-4 wid-50">
              <div class="card text-white card-has-bg click-col">        
                <div class="card-img-overlay d-flex flex-column">
                  <div class="card-body">
                    <h4 class="card-title mt-0 "><a herf="#">Step 2</a></h4>
                    <img class="card-img" src="{{ asset('img/tutorial.png') }}" alt="">
                    <h6> Click on the coach, the program or the camp to know more</h6>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 wid-50 mauto">
              <div class="card text-white card-has-bg click-col">        
                <div class="card-img-overlay d-flex flex-column">
                  <div class="card-body">
                    <h4 class="card-title mt-0 "><a herf="#">Step 3</a></h4>
                    <img class="card-img" src="{{ asset('img/trust.png') }}" alt="">
                    <h6>Choose the best way for you to contact them</h6>
                  </div>
                </div>
              </div>
            </div>
        
      </div>
        
      </div>
      </section>
      <section class="drop-arrow extra pink-arrow">
        <i class="fas fa-angle-down purple"></i>
      </section>
      <!-- /carosel section -->
      <section class="carousel-section">
        <h1>Here's what famous coaches say
          about Coach Me Solution...</h1>

          <div class="wrap">  
            <div class="slider">
              

              @foreach($testimonials as $testimonial)
                

                <div class="item">
                  <div class="card card_red text-center">
                    <div class="title">

                      <img class="pic" src="{{$BASE_URL}}/photo/testimonial_photo/{{$testimonial->image_path}}" alt="PAT">
                      <h2>{{ $testimonial->name }}</h2>
                    </div>
                    <p>"{!! nl2br(e($testimonial->comment)) !!}"</p>
                  </div>
                </div>
              @endforeach
              
              
              
              
            </div>
          </div>
      </section>
      <section class="drop-arrow">
        <i class="fas fa-angle-down light-purple"></i>
      </section>
      <section class="map-section">
        <div class="container">
          <div class="col-md-12 ">
          <h2 class="sp text-center">Find Rinks Around You</h2>
            <div class="card p-3">
              <div class="row">
              <div class="col-md-8">
                <div class="title">
                  <h2 class="pc">Find Rinks A Rinks Around You</h2>
                  
                  <div class="search-div mb-2">
                    <button type="button" onclick="centerMap(USbounds);" class="btn green-btn wid-50">Use my location</button>
                    <input type="text" placeholder="Search" id="address" value="" class="search-btn wid-50"><i class="bi bi-search"></i>
                    <input type="button" style="display:none;" id="id_of_button" value="Submit" onclick="codeAddress();" />
                    
                  </div>
                </div>
                <div class="polaroid">                 
                    <div id="map"></div>                 
                </div>
              </div>
              <div class="col-md-4" id="side_bar">
                <!-- <div class="address-group">
                  <div class="address">
                    <div class="number">
                      <img src="{{ asset('img/Ellipse 17.png') }}" alt="" srcset="">
                      <span>1</span>
                    </div>
                    <div class="description">
                      <h5>Kitsilano FSC</h5>
                      <a href="">info@kitsfsc.ca</a>
                      <p>
                        2690 Larch Street Vancouver, BC V6K4K9 604-737-6000
                      </p>
                      <a href="">www.kitsfsc.ca</a>
                      <h6>3,79 kilometers</h6>
                      <p class="gray">Directions</p>
                    </div>
                  </div>
                  <div class="address">
                    <div class="number">
                      <img src="{{ asset('img/Ellipse 17.png') }}" alt="" srcset="">
                      <span>2</span>
                    </div>
                    <div class="description">
                      <h5>Kitsilano FSC</h5>
                      <a href="">info@kitsfsc.ca</a>
                      <p>
                        2690 Larch Street Vancouver, BC V6K4K9 604-737-6000
                      </p>
                      <a href="">www.kitsfsc.ca</a>
                      <h6>3,79 kilometers</h6>
                      <p class="gray">Directions</p>
                    </div>
                  </div>
                  <div class="address">
                    <div class="number">
                      <img src="{{ asset('img/Ellipse 17.png') }}" alt="" srcset="">
                      <span>3</span>
                    </div>
                    <div class="description">
                      <h5>Kitsilano FSC</h5>
                      <a href="">info@kitsfsc.ca</a>
                      <p>
                        2690 Larch Street Vancouver, BC V6K4K9 604-737-6000
                      </p>
                      <a href="">www.kitsfsc.ca</a>
                      <h6>3,79 kilometers</h6>
                      <p class="gray">Directions</p>
                    </div>
                  </div>
                  
                </div> -->
              </div>
              </div>
                <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places&ext=.js"></script>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl_3j4BivMuCGpS5DS73Rkt7SNvy29eBQ&libraries=places&callback=initMap" async defer></script>
            </div>
          </div>
        </div>
      </section>

      <script type="text/javascript">
        


    $("#address").keyup(function(event) {
        if (event.keyCode === 13) {
          var val = $(this).val(); // get selected value
          if (val.length != 0) { 
            $("#id_of_button").click();
          }
        }
    });

 
    $('.bi-search').on('click',function(e){
      var val = $("#address").val(); // get selected value
      if (val.length != 0) { 
        $("#id_of_button").click();
      }
    });
    var worldMapData = <?php echo json_encode($rinks) ?>;
   
  var geocoder = null;
  var map = null;
  var customerMarker = null;
  var gmarkers = [];
  var closest = [];
  //var directionsDisplay = new google.maps.DirectionsRenderer();;
  //var directionsService = new google.maps.DirectionsService();


  var northEastLat = 37.468404;
  var northEastLng = -122.095122;
  var southWestLat = 37.415386;
  var southWestLng = -122.188678;



  var USbounds = {
        "south": -74.270134,
        "west": -74.019554,
        "north": 41.314926,
        "east": 40.997704
      };
  var EuropeBounds = {
        "south": 34.5428,
        "west": -31.464799900000003,
        "north": 82.1673907,
        "east": 74.35550009999997
      };

  var allowGeoRecall = true
  function getLocation() {   
      console.log('getLocation was called') 
      if(navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition, positionError);
      } else {
          hideLoadingDiv()
          console.log('Geolocation is not supported by this device')
      }
  }

  function positionError() {    
      console.log('Geolocation is not enabled. Please enable to use this feature')

      if(allowGeoRecall) getLocation()
  }

  function showPosition(position){
      console.log('posiiton accepted');
      console.log(position.coords.latitude);
      console.log(position.coords.longitude);
      allowGeoRecall = false
  }

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        console.log("User denied the request for Geolocation.");
        break;
      case error.POSITION_UNAVAILABLE:
        console.log("Location information is unavailable.");
        break;
      case error.TIMEOUT:
        console.log("The request to get user location timed out.");
        break;
      case error.UNKNOWN_ERROR:
        console.log("An unknown error occurred.");
        break;
    }
  }













  
  //getLocation();
  function centerMap(bounds) {




    console.log(navigator.geolocation);

    if (navigator.geolocation) {
      console.log('sss');
      //navigator.geolocation.getCurrentPosition(showPosition, showError);

      navigator.geolocation.getCurrentPosition(function(position) {
           var pos = {
             lat: position.coords.latitude,
             lng: position.coords.longitude
           };
            //var pos = {lat: 22.9050139, lng: 89.89402460000001};
             
            console.log(pos);

            var bounds = new google.maps.LatLngBounds(); 
            var pt = new google.maps.LatLng(pos.lat, pos.lng);

            var mapOptions = {
              center: pt,
              zoom: 12,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map.setOptions(mapOptions);
              
            bounds.extend(pt); 
            map.fitBounds(bounds);
            map.setZoom(12);

            geocoder.geocode({
              'location': pt
            }, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {

                var details = results[0].address_components;
                var city;
                var country;

                for (var i = details.length - 1; i >= 0; i--) {
                  for (var j = 0; j < details[i].types.length; j++) {
                    if (details[i].types[j] == 'locality') {
                      city = details[i].long_name;
                    } else if (details[i].types[j] == 'sublocality') {
                      city = details[i].long_name;
                    } else if (details[i].types[j] == 'neighborhood') {
                      city = details[i].long_name;
                    } else if (details[i].types[j] == 'postal_town') {
                      city = details[i].long_name;
                      console.log("postal_town=" + city);
                    } else if (details[i].types[j] == 'administrative_area_level_2') {
                      city = details[i].long_name;
                      console.log("admin_area_2=" + city);
                    }
                    // from "google maps API geocoding get address components"
                    // https://stackoverflow.com/questions/50225907/google-maps-api-geocoding-get-address-components
                    if (details[i].types[j] == "country") {
                      country = details[i].long_name;
                    }
                  }
                }

                console.log("city=" + city);

                var marker = new google.maps.Marker({
                  position: pt,
                  map: map,
                  title: "<div style = 'height:80px;width:200px'><b>Your location:</b><br />Country:" + country + "<br/>City:" + city
                });
                google.maps.event.addListener(marker, "click", function(e) {
                  var infoWindow = new google.maps.InfoWindow();
                  infoWindow.setContent(marker.title);
                  infoWindow.open(map, marker);
                });
                google.maps.event.trigger(marker, 'click');
                map.setCenter(results[0].geometry.location);
                // if (customerMarker) customerMarker.setMap(null);
                // customerMarker = new google.maps.Marker({
                //   map: map,
                //   position: results[0].geometry.location
                // });
                console.log('aaa'+results[0].geometry.location);
                closest = findClosestN(results[0].geometry.location, 12);
                // get driving distance
                closest = closest.splice(0, 12);
                
                calculateDistances(results[0].geometry.location, closest, 12);
              } else {
                alert('Geocode was not successful for the following reason: ' + status);
              }
            });


      }, function() {

              var pos = {lat: 23.810332, lng: 90.4125181};
             
              console.log(pos);

              var bounds = new google.maps.LatLngBounds(); 
              var pt = new google.maps.LatLng(pos.lat, pos.lng);

              var mapOptions = {
                center: pt,
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              };
              map.setOptions(mapOptions);
                
              bounds.extend(pt); 
              map.fitBounds(bounds);
              map.setZoom(12);

              geocoder.geocode({
                'location': pt
              }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                  var details = results[0].address_components;
                  var city;
                  var country;

                  for (var i = details.length - 1; i >= 0; i--) {
                    for (var j = 0; j < details[i].types.length; j++) {
                      if (details[i].types[j] == 'locality') {
                        city = details[i].long_name;
                      } else if (details[i].types[j] == 'sublocality') {
                        city = details[i].long_name;
                      } else if (details[i].types[j] == 'neighborhood') {
                        city = details[i].long_name;
                      } else if (details[i].types[j] == 'postal_town') {
                        city = details[i].long_name;
                        console.log("postal_town=" + city);
                      } else if (details[i].types[j] == 'administrative_area_level_2') {
                        city = details[i].long_name;
                        console.log("admin_area_2=" + city);
                      }
                      // from "google maps API geocoding get address components"
                      // https://stackoverflow.com/questions/50225907/google-maps-api-geocoding-get-address-components
                      if (details[i].types[j] == "country") {
                        country = details[i].long_name;
                      }
                    }
                  }

                  console.log("city=" + city);

                  var marker = new google.maps.Marker({
                    position: pt,
                    map: map,
                    title: "<div style = 'height:80px;width:200px'><b>Your location:</b><br/>Country:" + country + "<br/>City:" + city
                  });
                  google.maps.event.addListener(marker, "click", function(e) {
                    var infoWindow = new google.maps.InfoWindow();
                    infoWindow.setContent(marker.title);
                    infoWindow.open(map, marker);
                  });
                  google.maps.event.trigger(marker, 'click');


                  map.setCenter(results[0].geometry.location);
                  // if (customerMarker) customerMarker.setMap(null);
                  // customerMarker = new google.maps.Marker({
                  //   map: map,
                  //   position: results[0].geometry.location
                  // });
                  console.log('aaa'+results[0].geometry.location);
                  closest = findClosestN(results[0].geometry.location, 12);
                  // get driving distance
                  closest = closest.splice(0, 12);
                  console.log('ssssad');
                  console.log(closest);
                  calculateDistances(results[0].geometry.location, closest, 12);
                } else {
                  alert('Geocode was not successful for the following reason: ' + status);
                }
              });


             //handleLocationError(true, infoWindow, map.getCenter());
      });

      // x.innerHTML = "Latitude: " + position.coords.latitude + 
      //   "<br>Longitude: " + position.coords.longitude;
    } else {
        //x.innerHTML = "Geolocation is not supported by this browser.";
      
        var bounds = new google.maps.LatLngBounds(
          /* sw */
        {
            lat: southWestLat,
            lng: southWestLng
        },
          /* ne */
        {
            lat: northEastLat,
            lng: northEastLng
        }); 
        var bounds = new google.maps.LatLngBounds(); 
        var pt = new google.maps.LatLng("23.810332", "90.4125181");
          
        bounds.extend(pt); 
        map.fitBounds(bounds);
        map.setZoom(12);
        document.getElementById('address').value = 'Dhaka, Bangladesh';
        codeAddress();
    }
    //getLocation();
    //console.log(navigator.geolocation);
    
  }
  var map;

  function initMap() {
    geocoder = new google.maps.Geocoder();
    
    
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      //center: new google.maps.LatLng(60.16985569999999, 24.9383791),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var input = document.getElementById('address');

    var options = {
      //types: ['address'],
      //types: ['(cities)'],
      types: ['(regions)']
      // componentRestrictions: {
      //   country: 'us'
      // }
    };
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    autocomplete = new google.maps.places.Autocomplete(input, options);



    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      if (place.geometry) {
        // for (var i = 0; i < place.address_components.length; i++) {
        //   for (var j = 0; j < place.address_components[i].types.length; j++) {
        //     if (place.address_components[i].types[j] == "postal_code") {
        //       document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;

        //     }
        //   }
        // }
        //to see all the properties of the place object
        map.panTo(place.geometry.location);
        map.setZoom(12);
        // var marker = new google.maps.Marker({
        //     map: map,
        //     position: place.geometry.location
        //     //instead of
        //     //position: new google.maps.LatLng(location.geometry.location.lat, location.geometry.location.lng),
        // });
        codeAddress();
      } 
      else {
        alert("you entered an invalid location");
      }
      
    })
  
  
    var marker, i, markerContent, 
      infowindow = new google.maps.InfoWindow();
    var bounds = new google.maps.LatLngBounds();
    //document.getElementById('info').innerHTML = "found " + locations.length + " locations<br>";
    var sidebarHtml = '';
		var outputDiv = document.getElementById('side_bar');
    if (worldMapData.length>0) {
			sidebarHtml += '<div class="address-group">';
			for (i = 0; i < worldMapData.length; i++) { 
				var pt = new google.maps.LatLng(worldMapData[i].latitude, worldMapData[i].longitude);
				
				bounds.extend(pt); 
				var letterMarkers = ""+ (i + 1);
				marker = new google.maps.Marker({
					position: pt,
					map: map,
					animation: google.maps.Animation.DROP,
					address: worldMapData[i].address,
					label: letterMarkers,
					title: worldMapData[i].PropertyCode,
					html: worldMapData[i].PropertyCode + "<br>" + worldMapData[i].address + "<br>"
					//html: worldMapData[i].PropertyCode + "<br>" + worldMapData[i].address + "<br><br><a href='javascript:getDirections(customerMarker.getPosition(),&quot;" + worldMapData[i].address + "&quot;);'>Get Directions</a>"
				});
				gmarkers.push(marker);
		
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent(marker.html);
						infowindow.open(map, marker);
					}
				})(marker, i));

				console.log(worldMapData[i]);
				sidebarHtml += '<div class="address">';
					sidebarHtml += '<div class="number">';
						sidebarHtml += '<img src="img/Ellipse 17.png" alt="" srcset="">';
						sidebarHtml += '<span>'+letterMarkers+'</span>';
					sidebarHtml += '</div>';
					sidebarHtml += '<div class="description">';
							sidebarHtml += "<h5><a href='javascript:google.maps.event.trigger(worldMapData[" + i + "],\"click\");'>" + worldMapData[i].PropertyCode + "</a><br></h5>";
							sidebarHtml += '<p>'+ worldMapData[i].address +'</p>';
							sidebarHtml += '<h6>'+ (worldMapData[i].distance / 1000) + ' Kilometers approximately ' + worldMapData[i].duration +'</h6>';
							sidebarHtml += "<p class='gray'><a href='javascript:google.maps.event.trigger(worldMapData[" + worldMapData[i].idx_closestMark + "],\"click\");'>Directions</a></p>";
							
							//sidebarHtml += '<p class="gray">Directions</p>';
					sidebarHtml += '</div>';
				sidebarHtml += '</div>';
		
			}
			sidebarHtml += '</div>';
			//outputDiv.innerHTML = sidebarHtml;
		}
    console.log(bounds);
    map.fitBounds(bounds);

    google.maps.event.addListener(map, "click", function (event) {
      codeAddressByClick(event);
      var newBounds = new google.maps.LatLngBounds(event.latLng,event.latLng);
      
      map.fitBounds( newBounds.getCenter() );
      map.setZoom(12);
    }); //end addListener
  
  }












  function codeAddressByClick(locationV) {
    console.log('sssss');
    closest = findClosestN(locationV.latLng, 12);
    // get driving distance
    closest = closest.splice(0, 12);
    console.log('by click');
                  console.log(closest);
    calculateDistances(locationV.latLng, closest, 12);
    //MAKE AJAX CALL TO GOOGLE API BY CLICKED LAT & LNG CLICK POSITION
    jQuery.get( "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + locationV.latLng.lat() + ',' + locationV.latLng.lng(), function( data )   {
      //RUN THROUGH THE OBJECT AND SEE IF FIRST FORMATTED ADDRESS IS AVAILABLE
      if(data.results[0].formatted_address){
        //POPULATE THE ADDRESS FIELD
        jQuery('#address').val(data.results[0].formatted_address);
      }
    });
  }

  function codeAddress() {
    var address = document.getElementById('address').value;
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        if (customerMarker) customerMarker.setMap(null);
        customerMarker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location,
          title: "<div style = 'height:80px;width:200px;font-size: 15px;font-weight: bold;'><b>Your Searched City:"+results[0].formatted_address+"</b>"
        });
        google.maps.event.addListener(customerMarker, "click", function(e) {
          var infoWindow = new google.maps.InfoWindow();
          infoWindow.setContent(customerMarker.title);
          infoWindow.open(map, customerMarker);
        });
        //google.maps.event.trigger(customerMarker, 'click');
        console.log('aaa'+results[0].geometry.location);
        closest = findClosestN(results[0].geometry.location, 12);
        // get driving distance
        
        console.log('ooo');
                  console.log(closest);
        calculateDistances(results[0].geometry.location, closest, 12);
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }

  function findClosestN(pt, numberOfResults) {
    var closest = [];
    console.log('sss'+gmarkers.length);
    //document.getElementById('info').innerHTML += "processing " + gmarkers.length + "<br>";
    for (var i = 0; i < gmarkers.length; i++) {
      gmarkers[i].distance = google.maps.geometry.spherical.computeDistanceBetween(pt, gmarkers[i].getPosition());
      //document.getElementById('info').innerHTML += "process " + i + ":" + gmarkers[i].getPosition().toUrlValue(6) + ":" + gmarkers[i].distance.toFixed(2) + "<br>";
      gmarkers[i].setMap(null);
      closest.push(gmarkers[i]);
      closest.sort(sortByDist);
    }
    closest = closest.splice(0, numberOfResults);
    
    return closest;
  }

  function sortByDist(a, b) {
    //console.log(a.distance - b.distance);
    return (a.distance - b.distance)
  }

  function calculateDistances(pt, closest, numberOfResults) {
    var service = new google.maps.DistanceMatrixService();
    var request = {
      origins: [pt],
      destinations: [],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.IMPERIAL,
      avoidHighways: false,
      avoidTolls: false
    };
    for (var i = 0; i < closest.length; i++) {
      request.destinations.push(closest[i].getPosition());
    }
    service.getDistanceMatrix(request, function(response, status) {
      
      if (status != google.maps.DistanceMatrixStatus.OK) {
        alert('Error was: ' + status);
      } else {
        var origins = response.originAddresses;
        var destinations = response.destinationAddresses;
        var outputDiv = document.getElementById('side_bar');
				
        
        console.log(response);
        var results = response.rows[0].elements;
        var found = 0;
        // save title and address in record for sorting
        for (var i = 0; i < closest.length; i++) {
          //if (results[i].status=='OK') {
            results[i].title = closest[i].title;
            results[i].address = closest[i].address;
						results[i].distance = closest[i].distance;
            results[i].idx_closestMark = i;

						
            found++;
          //}
          
        }
        var sidebarHtml = '';
				outputDiv.innerHTML = sidebarHtml;
				var outputDiv = document.getElementById('side_bar');
        console.log('found'+found);
        if (found>0) {
          sidebarHtml += '<div class="address-group">';
          //results.sort(sortByDistDM);
          //console.log(results);
          for (var i = 0;
            ((i < numberOfResults) && (i < closest.length)); i++) {
            closest[i].setMap(map);
            //var letterMarkers = String.fromCharCode(97 + i);
            var letterMarkers = ""+ (i + 1);
            console.log(results);
            //if (results[i].status=='OK') {
              closest[results[i].idx_closestMark].setLabel(letterMarkers);
              sidebarHtml += '<div class="address">';
                  sidebarHtml += '<div class="number">';
                    sidebarHtml += '<img src="img/Ellipse 17.png" alt="" srcset="">';
                    sidebarHtml += '<span>'+letterMarkers+'</span>';
                  sidebarHtml += '</div>';
                  sidebarHtml += '<div class="description">';
                      sidebarHtml += "<h5><a href='javascript:google.maps.event.trigger(closest[" + results[i].idx_closestMark + "],\"click\");'>" + results[i].title + "</a><br></h5>";
                      sidebarHtml += '<p>'+ results[i].address +'</p>';
                      sidebarHtml += '<h6>'+ (results[i].distance / 1000).toFixed(2) + ' Kilometers approximately</h6>';
                      sidebarHtml += "<p class='gray'><a href='javascript:google.maps.event.trigger(closest[" + results[i].idx_closestMark + "],\"click\");'>Directions</a></p>";
                      
                      //sidebarHtml += '<p class="gray">Directions</p>';
                      sidebarHtml += '</div>';
              sidebarHtml += '</div>';
               //sidebarHtml += "<tr><td><div class='numberCircle'>" + letterMarkers + "</div><a href='javascript:google.maps.event.trigger(closest[" + results[i].idx_closestMark + "],\"click\");'>" + results[i].title + '</a><br>' + results[i].address + "<br>" + results[i].distance.text + ' approximately ' + results[i].duration.text + "<br><a href='javascript:getDirections(customerMarker.getPosition(),&quot;" + results[i].address + "&quot;);'>Get Directions</a></td></tr>"
            //}
          }
          sidebarHtml += '</div>';
          

        }
        console.log(sidebarHtml);

        outputDiv.innerHTML = sidebarHtml;
        
      }
    });
  }

  function getDirections(origin, destination) {
    var request = {
      origin: origin,
      destination: destination,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setMap(map);
        directionsDisplay.setDirections(response);
        $("#side_bar").css({
          "z-index": -100,
          "top": "135px"
        });
        $("#panel").css("z-index", 100);
        $("#mdiv").css("display", "block");

        directionsDisplay.setPanel(document.getElementById('panel'));


      }
    });
  }

  function sortByDistDM(a, b) {
    return (a.distance.value - b.distance.value)
  }

  //google.maps.event.addDomListener(window, 'load', initialize);


      </script>

    
      <!-- footer section -->
@endsection
  