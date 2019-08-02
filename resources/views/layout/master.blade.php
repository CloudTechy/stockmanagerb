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
<<<<<<< HEAD
  <title>Stock Manager</title>
=======
  <title>StockManager</title>
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
<div id = "loader"  id="" >
   <img src="{{ asset( 'img/medium2.png')}}" class=" " alt="User Image">
  <div style="width: 300px; margin-left: -60px" class="p-4 m-4 text-secondary font-weight-bold">
   <i class="fas fa-cog fa-spin"></i> loading app...
</div>
</div>

<body id="body" class="hold-transition sidebar-mini m-0 p-0"  style="height: auto;" onload="myFunction()">

  <div  id = "myDiv" style="display:none;">
    <div class="wrapper" id="dashboard" class="animate-bottom">

              <router-view :token = 'token'></router-view>
              <vue-progress-bar></vue-progress-bar>


      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
          <h5>Title</h5>
          <p>Sidebar content</p>
        </div>
      </aside>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <footer class="main-footer no-print text-left">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
         Version 0.1.0
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ date('y') }} <span>SpaceHub Technologies</span>.</strong> All rights reserved.
      </footer>

    </div>
    <!-- ./wrapper -->
    </div>

<!-- REQUIRED SCRIPTS -->
<script type="text/javascript" src= "{{ asset('js/app.js') }}"></script>
<!-- jQuery -->
</script><script src="{{ asset( 'script/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
<!--===============================================================================================-->
  <script src="{{ asset( 'script/popper.js')}}" type="text/javascript"></script>
  <script src="{{ asset( 'script/bootstrap.min.js')}}" type="text/javascript"></script>
<!--===============================================================================================-->
  <script src="{{ asset( 'script/select2.min.js')}}" type="text/javascript"></script>
<!--===============================================================================================-->
  <script src="{{ asset( 'script/tilt.jquery.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $('.js-tilt').tilt({
      scale: 1.1
    })
  </script>
<script type="text/javascript">
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<!--===============================================================================================-->
  <script src="{{ asset( 'script/main.js')}}" type="text/javascript"></script>

<script>
var myVar;

function myFunction() {
  myVar = setTimeout(showPage, 0);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
  window.dispatchEvent(new Event('resize'));
}
window.addEventListener('sidebar_min', () => {
  document.getElementById('body').classList.add('sidebar-mini', 'sidebar-collapse')
})
window.addEventListener('close_sidebar_min', () => {
  document.getElementById('body').classList.remove('sidebar-mini', 'sidebar-collapse')
})

</script>
<script>
    if('serviceWorker' in navigator) {
      navigator.serviceWorker
               .register('/sw.js')
               .then(function() { console.log("Service Worker Registered"); });
    }
</script>
</body>
</html>
