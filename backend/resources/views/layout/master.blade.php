<!DOCTYPE html>
<!--
Stockmanager Web Application by Spacehub Technologies
-->
<html lang="en" style="height: auto;">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Stock Manager</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <link rel="icon" type="image/png" href="{{ asset( 'img/medium2.png')}}">
  <style>
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  bottom: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -95px 0 0 -95px;
  width: 120px;
  height: 120px;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 }
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom {
  from{ bottom:-100px; opacity:0 }
  to{ bottom:0; opacity:1 }
}

#myDiv {
      display: none;
    }
  </style>
</head>

<body id="body" class="hold-transition sidebar-mini m-0 p-0" onload="myFunction()" style="height: auto;">

  <div id="loader">
    <img src="{{ asset('img/medium2.png')}}" alt="User Image">
    <div style="width: 300px;" class="p-4 m-4 text-secondary font-weight-bold">
      <i class="fas fa-cog fa-spin"></i> loading app...
    </div>
  </div>

  <div id="myDiv">
    <div id="dashboard" class="animate-bottom">
      <div class="wrapper" style="min-height: 90vh">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
      </div>
      <footer class="main-footer no-print text-left">
        <div class="float-right d-none d-sm-inline">Version 0.1.2</div>
        <strong>&copy; <span>SpaceHub Technologies</span>.</strong> All rights reserved.
      </footer>
    </div>
  </div>

  <!-- Load scripts AFTER Vue mounts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('script/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ asset('script/popper.js') }}"></script>
  <script src="{{ asset('script/bootstrap.min.js') }}"></script>
  <script src="{{ asset('script/select2.min.js') }}"></script>
  <script src="{{ asset('script/tilt.jquery.min.js') }}"></script>
  <script>
    $('.js-tilt').tilt({ scale: 1.1 });

    var myVar;

    function myFunction() {
      myVar = setTimeout(showPage, 500); // You can increase delay slightly if needed
    }

    function showPage() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("myDiv").style.display = "block";
      window.dispatchEvent(new Event('resize'));
      console.log('App loaded successfully');
    }

    window.addEventListener('sidebar_min', () => {
      document.getElementById('body').classList.add('sidebar-mini', 'sidebar-collapse');
    });

    window.addEventListener('close_sidebar_min', () => {
      document.getElementById('body').classList.remove('sidebar-mini', 'sidebar-collapse');
    });
  </script>
<script>
    // if('serviceWorker' in navigator) {
    //   navigator.serviceWorker
    //            .register('/sw.js')
    //            .then(function() { console.log("Service Worker Registered"); });
    // }
</script>
</body>
</html>
