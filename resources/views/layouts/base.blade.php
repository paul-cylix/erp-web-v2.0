<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.min.css')}}">  
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bs-stepper/css/bs-stepper.min.css')}}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('assets/plugins/dropzone/min/dropzone.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  {{-- Paul Css --}}
  <link rel="stylesheet" href="{{ asset('assets/css/mystyle.css') }}">
  {{-- Paul Dropzone --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  {{-- Data Tables --}}
 
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css"> --}}
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">



  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  
 





  
  {{-- Toastr --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
 
  <style>

  #example{
    font-size: 13px;
    table-layout: fixed; // ***********add this
  word-wrap:break-word;
  }

 #example_filter{
   margin-left: 50px;
   /* padding-left: 50px; */
 }   

/* circle button */
.btn-circle.btn-xl {
  width: 50px;
  height: 50px;
  padding: auto;
  border-radius: 35px;
  font-size: 24px;
  line-height: 1.33;
  text-align: center;
}

.btn-circle {
  width: 30px;
  height: 30px;
  padding: 6px 0px;
  border-radius: 15px;
  text-align: center;
  font-size: 12px;
  line-height: 1.42857;
}
 
/* button position right */
button.fixed-button{
  position: fixed;
  bottom: 20px;
  right: 20px; 
}
/* accordion */
.modal-content{
  box-shadow: 0 0 0px rgba(0,0,0,0.);
  color:#212529;
  padding: 0;
  font-size: 15px;
  
  }
.panel-group{
  border-radius:300px;
}

.panel-title a{
/* background-color:green;
max-width: 60%; */
}


.panel-default>.panel-heading {
  color: #333;
  /* background-color: #fff;
  border-color: #e4e5e7; */
  padding: 0;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  margin-bottom: -8px;
  
}

.panel-default>.panel-heading a {
  display: block;
  padding: 10px 15px;
  background-color: #333333;
  color: #d0d4db;
  font-family: sans-serif;
  font-size: 15px;
  transition: all 0.3s ease;
}

.panel-default>.panel-heading a:hover {
  background-color: #555555;
  color: #fff;
}
.panel-default>.panel-heading a:after {
  content: "";
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 300;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  float: right;
  transition: transform .25s linear;
  -webkit-transition: -webkit-transform .25s linear;
}

.panel-default>.panel-heading a[aria-expanded="true"] {
  background-color:#555555;
  color: #fff;
}

.panel-default>.panel-heading a[aria-expanded="true"]:after {
  content: "\2212";
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}

.panel-default>.panel-heading a[aria-expanded="false"]:after {
  content: "\002b";
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
}

/* child accordion */
.panel-subheading a{

}

.panel-subheading a:hover{
  background-color:#999999;
  color: #fff;
}

.panel-subheading a:after {
  content: "";
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 300;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  float: right;
  transition: transform .25s linear;
  -webkit-transition: -webkit-transform .25s linear;
}

.panel-subheading a[aria-expanded="true"] {
  background-color:#999999;
  color: #fff;
}

.panel-subheading a[aria-expanded="true"]:after {
  content: "\2212";
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}

.panel-subheading a[aria-expanded="false"]:after {
  content: "\002b";
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
}

/* Indention of child links in accordion */
.indention{
  padding-left: 50px;
}

/* #idUl li a{
  color:red;
} */

#idUl li a:hover{
  background-color:rgba(106, 106, 106, 0.4);
}



#idUl li a.active{
  /* background-color:#6c757d; */
  background-color:rgba(106, 106, 106, 0.4);
  /* opacity: 0.4; */
  color: white;
}



    
      </style>
</head>

<body>
  
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    
       
      
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " data-widget="pushmenu" id="pushmenuBTN" href="#" role="button"><i class="mt-1 fas fa-angle-double-left"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          {{-- <a href="/dashboard" class="nav-link">{{ dd(session('session_detail')); }}</a> --}}
        </li> 

        @php
        $company = session('companies');
        @endphp
 

        <div class="dropdown show nav-item d-none d-sm-inline-block">
          <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           {{ session('LoggedUser_CompanyName') }}
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          @foreach ($company as $c )
            <a class="dropdown-item" href="/change-company/{{ $c->companyID }}/{{ $c->companyName }}">{{ $c->companyName }}</a>
          @endforeach
            {{-- <button onClick="window.location.reload();">Refresh Page</button> --}}
          </div>
        </div>
      </ul> 
     
      <ul class="navbar-nav ml-auto">   
       
        <li class="nav-item dropdown">
          {{-- <a class="nav-link" data-toggle="dropdown" href="#"> --}}
            {{-- <i class="far fa-bell"></i> --}}
            {{-- <span class="badge badge-warning navbar-badge">15</span> --}}





          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button"><i class="fas fa-bars"></i></a> 
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">My Account</span>
              <div class="dropdown-divider"></div>
              <a href="http://intranet.cylix.ph/" class="dropdown-item">
                <i class="fas fa-settings mr-2"></i> Cylix Intranet
              </a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('auth.logout') }}" class="dropdown-item">
                <i class="fas fa-logout mr-2"></i> Logout 
              </a> 
            </div>
          </li>   
        </li>   
      </ul>
    </nav>   
  
    <!-- Main Sidebar Container -->
    <aside style="height: 100%;" class="main-sidebar sidebar-dark-primary elevation-6">
      <div style="height: 100%;" class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            {{-- <img src="{{asset('assets/dist/img/jr.jpg')}}" class="img-circle elevation-2" alt="User Image"> --}}
          </div>
          <div class="info">
            {{-- <a href="#" class="d-block">{{ $LoggedUserInfo['name'] }}</a> --}}
            {{-- <a href="#" class="d-block">{{ $data[''] }}</a> --}}
            <a href="/dashboard" class="d-block">{{ session('LoggedUser_FullName') }}</a>
          </div>
        </div>
        
        {{-- <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div> --}}
  
        <nav class="mt-2" id="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" id="idUl" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">My Workflow</li>
            <li class="nav-item">
              <a href="/participants" class="nav-link {{ Request::is('participants*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-white"></i>
                <p class="text">Participants</p>
                <span class="badge badge-pill badge-light float-right" id="notifpart"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="/inputs" class="nav-link {{ Request::is('inputs*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-primary" style="color:yellow !important;"></i>
                <p class="text">Inputs</p>
                <span class="badge badge-pill badge-light float-right" id="notifinpu"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="/approvals" class="nav-link {{ Request::is('approvals*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-info"></i>
                <p class="text">Approvals</p>
                <span class="badge badge-pill badge-light float-right" id="notifappr"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="/in-progress" class="nav-link {{ Request::is('in-progress*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-primary" ></i>
                <p>In Progress</p>
                <span class="badge badge-pill badge-light float-right" id="notifinpr"></span>
                
                {{-- <span class="badge badge-pill badge-light float-right">25</span> --}}
              </a>
            </li>
            <li class="nav-item">
              <a href="/clarifications" class="nav-link {{ Request::is('clarifications*') ? 'active' : '' }}" >
                <i class="nav-icon far fa-circle " style="color:orange !important;"></i>
                <p class="text">Clarifications</p>
                <span class="badge badge-pill badge-light float-right" id="notifclar"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="/approved" class="nav-link {{ Request::is('approved*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-success"></i>
                <p>Approved</p>
                <span class="badge badge-pill badge-light float-right" id="notifappd"></span>

                {{-- <span class="badge badge-pill badge-light float-right">25</span> --}}
              </a>
            </li>
            <li class="nav-item">
              <a href="/withdrawn" class="nav-link {{ Request::is('withdrawn*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-secondary"></i>
                <p class="text">Withdrawn</p>
                <span class="badge badge-pill badge-light float-right" id="notifwith"></span>

                {{-- <span class="badge badge-pill badge-light float-right">25</span> --}}
              </a>
            </li>
            <li class="nav-item">
              <a href="/rejected" class="nav-link {{ Request::is('rejected*') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Rejected</p>
                <span class="badge badge-pill badge-light float-right" id="notifreje"></span>
              </a>
            </li>
          </ul>
        </nav>

        
      </div>
    </aside>
  
  
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              {{-- <h1 class="m-0">@yield('title')</h1> --}}
            </div><!-- /.col --> 
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
  
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
           @yield('content')
        </div>
      </section> 
      

      
    </div>
    
    {{-- <footer class="main-footer">
      <strong>Copyright &copy; 2020 <a style="color: #11ba27" href="https://www.cylix.ph/">Cylix Technologies Inc.</a></strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 1.0.5-UT
      </div>
    </footer>
   --}}
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    
  </div>

  <!-- ./wrapper -->
{{-- Paul Dropzone --}}
{{-- <script>
  var segments = location.href.split('/');
  var action = segments[4];
  console.log(action);
  if (action == 'dropzone') {
      var acceptedFileTypes = "image/*, .psd"; //dropzone requires this param be a comma separated list
      var fileList = new Array;
      var i = 0;
      var callForDzReset = false;
      $("#dropzonewidget").dropzone({
    
          addRemoveLinks: true,
          maxFiles: 4,
          acceptedFiles: 'image/*',
          maxFilesize: 5,
          init: function () {
              this.on("success", function (file, serverFileName) {
                  file.serverFn = serverFileName;
                  fileList[i] = {
                      "serverFileName": serverFileName,
                      "fileName": file.name,
                      "fileId": i
                  };
                  i++;
              });
          }
      });
  }
  </script>
  
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.8.1/min/dropzone.min.js" integrity="sha512-OTNPkaN+JCQg2dj6Ht+yuHRHDwsq1WYsU6H0jDYHou/2ZayS2KXCfL28s/p11L0+GSppfPOqwbda47Q97pDP9Q==" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
   --}}
{{-- End of Paul Dropzone --}}
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script> 
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
<!-- InputMask --> 
<script src="{{asset('assets/plugins/inputmask/jquery.inputmask.min.js')}}"></script> 
<!-- bootstrap color picker -->
<script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script> 
<!-- Bootstrap Switch -->
<script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- BS-Stepper -->
<script src="{{asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
{{-- Sweet alert paul --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

<!-- dropzonejs paul comment-->
{{-- <script src="{{asset('assets/plugins/dropzone/min/dropzone.min.js')}}"></script> --}} 


<script type="text/javascript">
  submitForms = function(){
    document.forms["file"].submit();

}
</script>

<script type="text/javascript">

 var dzqCount = 1;
 var dzqPartner = 1;
  Dropzone.options.dropzoneForm = {
    autoProcessQueue : false,
    parallelUploads : 50,
    addRemoveLinks : true,
   //  acceptedFiles : ".png,.jpg,.gif,.bmp,.jpeg,.psd,.pdf,.zip",
    

    init:function(){
      console.log(dzqCount);
      
      var submitButton = document.querySelector("#submit-all");
      myDropzone = this;
      // console.log(this.getQueuedFiles().length);

      submitButton.addEventListener('click', function(){
        myDropzone.processQueue();
        // var dzqCount = this.getAcceptedFiles().length;
        // var dzqCount = JSON.stringify(dzqCount);
        // document.getElementById("dzqCount").value = dzqCount;
      });

      this.on("addedfile", function(file) { 
      let count = myDropzone.getAcceptedFiles().length;
      
      dzqPartner = dzqPartner + count; 
      dzqCount = dzqCount + dzqPartner;
      output = document.getElementById('output');
      // output.innerText = dzqCount;
      console.log(dzqCount);
     });

      this.on("complete", function(){
        if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
        {
          var _this = this;
          _this.removeAllFiles();
        }
        // load_images();
      });
    }

  };
  </script>
{{-- Dropzone end --}}

<!-- AdminLTE App -->
<script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script> 
<!-- Page specific script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
    $('#projectStart').datetimepicker({
        format: 'L'
    });
    $('#projectEnd').datetimepicker({
        format: 'L'
    });
    $('#downPaymentDateReceived').datetimepicker({
        format: 'L'
    });
    $('#dateOfInvoice').datetimepicker({
        format: 'L'
    });
    $('#invoiceDateNeeded').datetimepicker({
        format: 'L'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })
  // BS-Stepper Init
  // document.addEventListener('DOMContentLoaded', function () {
  //   window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  // });

  // // DropzoneJS Demo Code Start
  // Dropzone.autoDiscover = false;

  // // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  // var previewNode = document.querySelector("#template");
  // previewNode.id = "";
  // var previewTemplate = previewNode.parentNode.innerHTML;
  // previewNode.parentNode.removeChild(previewNode);

  // var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
  //   url: "/target-url", // Set the url
  //   thumbnailWidth: 80,
  //   thumbnailHeight: 80,
  //   parallelUploads: 20,
  //   previewTemplate: previewTemplate,
  //   autoQueue: false, // Make sure the files aren't queued until manually added
  //   previewsContainer: "#previews", // Define the container to display the previews
  //   clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  // });

  // myDropzone.on("addedfile", function(file) {
  //   // Hookup the start button
  //   file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
  // });

  // // Update the total progress bar
  // myDropzone.on("totaluploadprogress", function(progress) {
  //   document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
  // });

  // myDropzone.on("sending", function(file) {
  //   // Show the total progress bar when upload starts
  //   document.querySelector("#total-progress").style.opacity = "1";
  //   // And disable the start button
  //   file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
  // });

  // // Hide the total progress bar when nothing's uploading anymore
  // myDropzone.on("queuecomplete", function(progress) {
  //   document.querySelector("#total-progress").style.opacity = "0";
  // });

  // // Setup the buttons for all transfers
  // // The "add files" button doesn't need to be setup because the config
  // // `clickable` has already been specified.
  // document.querySelector("#actions .start").onclick = function() {
  //   myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
  // };
  // document.querySelector("#actions .cancel").onclick = function() {
  //   myDropzone.removeAllFiles(true);
  // };
  // DropzoneJS Demo Code End
</script>

<script>
  $('#pushmenuBTN').on('click',function(){
    // alert('hi');
    let myBool = $('#pushmenuBTN').children().hasClass("fa-angle-double-left");
    if(myBool){
      console.log('true');
      $('#pushmenuBTN').children().removeClass("fa-angle-double-left");
      $('#pushmenuBTN').children().addClass("fa-angle-double-right");

    }else{
      console.log('false');
    $('#pushmenuBTN').children().removeClass("fa-angle-double-right");
    $('#pushmenuBTN').children().addClass("fa-angle-double-left");
    }

    $( ".badge" ).toggle( "slow", function() {
    // Animation complete.
  });
  })
</script>






{{-- Datatables --}}
{{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script> --}}
{{-- Dont Un comment --}}
{{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> --}} 


{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script> --}}

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>




<script>
    $('#example').DataTable( {
      "scrollX": true,
      "order": [],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    "columnDefs": [
      { "width": "10px", "targets": 0 },
      { "width": "40px", "targets": 1 },
      { "width": "10px", "targets": 2 },
      { "width": "70px", "targets": 3 },
      { "width": "70px", "targets": 4 },
      { "width": "70px", "targets": 5 },
      { "width": "70px", "targets": 6 },
      { "width": "70px", "targets": 7 },
      { "width": "20px", "targets": 8 }
    ]
    } );

    $('input[type="search"]').css("width", "500px");
    $('#example_filter').addClass("d-inline").addClass("text-right").addClass("p-0");

</script>

<script>
  // $('#example_wrapper').append( "<div class="d-flex">" );
  // $('#example_filter').after( "</div>" );

    
</script>

{{-- <script src="">
  $('#example').DataTable( {
    dom: 'Bfrtip',

    buttons: [
        {
            extend: 'collection',
            text: 'Export',
            buttons: [ 'csv-flash', 'xls-flash', 'pdf-flash' ]
        }
    ]
} );
</script> --}}

{{--  --}}

{{-- Get Notification --}}
<script>
$(function(){
    $.ajax({
        type:'GET',
        url: '/notification-status?function=getLoggedUserNotif',
        success: function (data){

        var participant = data['participantsCount'];
        var input = data['inputsCount'];
        var approval = data['approvalsCount'];
        var inprogress = data['inProgressCount'];
        var clarification = data['clarificationCount'];
        var approved = data['approvedCount'];
        var withdrawn = data['withdrawnCount'];
        var reject = data['rejectedCount'];

          $('#notifpart').text(participant);
          $('#notifinpu').text(input);
          $('#notifappr').text(approval);
          $('#notifinpr').text(inprogress);
          $('#notifclar').text(clarification);
          $('#notifappd').text(approved);
          $('#notifwith').text(withdrawn);
          $('#notifreje').text(reject);
          
          $('#indexForInput').text(approval);
          $('#indexForApprovals').text(clarification);
          $('#indexForClarification').text(input);
          $('#indexForRejected').text(reject);


          
        }
    });
});
</script>




<script>
  function viewClaComments(FRM_CLASS,id){

  $.get('/clarifications-comments/'+FRM_CLASS+'/'+id,function(comments){
      
    var asd = document.getElementById('messagecontainer');


    if (comments.length > 0){


      for (var i = 0; i<comments.length; i++){
    var claMessage= comments[i]['MESSAGE'];
    var claSender= comments[i]['UserFullName'];
    var claRecipient= comments[i]['SENDERNAME'];
    var claTs= new Date(comments[i]['TS']);
    claTs = claTs.toString().slice(0, 24);
    var claParentID= comments[i]['ParentID'];
    var claUserLevel= comments[i]['USERLEVEL'];
    var frmName= comments[i]['FRM_NAME'];

    
    $('#messagesLabelForm').text(frmName);

    if(claParentID == 0){

        $('#messagecontainer').append('<div class="container" style="margin-bottom: 20px;">'+
                                        '<div class="row">'+
                                        
                                            '<div class="col text-center">'+  
                                            '<img src="http://dummyimage.com/60" alt="" style="box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">'+
                                            '</div>'+
                                            
                                            '<div class="col-11" >'+
                                            '<div class="container">'+

                                                '<div class="row">'+

                                                    '<div class="col main-content" style="background-color: #dee1e3;  border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); padding:10px 15px 10px 15px; ">'+
                                                        
                                                        '<div class="row">'+

                                                            '<div class="sender-name col" style="font-size: 14px; font-weight:bold;">'+claSender+
                                                            
                                                            '</div>'+
                                                            '<div class="col text-right"style="font-size: 14px;">'+claTs+
                                                            
                                                            '</div>'+
                                                            
                                                        '</div>'+

                                                        '<div class="row">'+
                                                            '<div class="recipeint-name col"style="font-size: 14px;" >To: '+claRecipient+
                                                                
                                                            '</div>'+
                                                            '<div class="col text-right"style="font-size: 14px;">'+claUserLevel+
                                                                
                                                            '</div>'+

                                                        '</div>'+
                                                        '<div class="row" >'+
                                                            '<div class="comment-content col" style="margin-top: 10px;">'+claMessage+
                                                                
                                                            '</div>'+
                                                        '</div>'+

                                                        
                                                    '</div>'+
                                                '</div>'+

                                        
                                            '</div>'+
                                            '</div>'+

                                        '</div>'+
                                    '</div>'
         );
    }else{
        $('#messagecontainer').append('<div class="container"  style="margin-bottom: 20px;">'+
                                        '<div class="row">'+
                                        
                                            '<div class="col text-right" style="padding-right:16px;">'+
                                            '<img src="http://dummyimage.com/60" alt="" style="box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">'+
                                            '</div>'+
                                            
                                            '<div class="col-10" >'+
                                            '<div class="container">'+

                                                '<div class="row">'+
                                                    '<div class="col main-content" style="background-color: #dee1e3;  border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); padding:10px 15px 10px 15px; ">'+
                                                        '<div class="row">'+

                                                            '<div class="sender-name col" style="font-size: 14px; font-weight:bold;">'+claSender+
                                                            
                                                            '</div>'+
                                                            '<div class="col text-right"style="font-size: 14px;">'+claTs+
                                                
                                                            '</div>'+
                                                            
                                                        '</div>'+
                                                        '<div class="row">'+
                                                            '<div class="recipeint-name col"style="font-size: 14px;" >To: '+claRecipient+
                                                                
                                                            '</div>'+
                                                            '<div class="col text-right"style="font-size: 14px;">'+claUserLevel+
                                                                
                                                            '</div>'+

                                                        '</div>'+
                                                        '<div class="row" >'+
                                                            '<div class="comment-content col" style="margin-top: 10px;">'+claMessage+
                                                                
                                                            '</div>'+
                                                        '</div>'+
                                                        
                                                    '</div>'+
                                                '</div>'+

                            
                                            '</div>'+
                                            '</div>'+

                                        '</div>'+
                                    '</div>'
         );

   
    }   
}













    } else {
      
      $('#messagecontainer').append('<div class="container"> <h6> no comments </h6> </div>'  );
      
    }
    

})
}


function deleteComments(){
// console.log('test');
$('#messagecontainer').empty();
}

</script>











<script>

  function viewStatus(FRM_CLASS,id){
      // alert(status);
      $.get('/approval-status/'+FRM_CLASS+'/'+id,function(status){

      for (var i = 0; i<status.length; i++){
          var uidChecker= status[i]['Approved_By'];
          var signDateChecker= status[i]['SIGNDATETIME'];
          var approveChecker= status[i]['ApprovedRemarks'];

          // Convert UID Null to empty string
          if(uidChecker == 'NULL'){
              uidChecker = "";
          }else if(uidChecker == ''){
              uidChecker = "";
          }else if(uidChecker == null){
              uidChecker = "";
          }else{
              uidChecker
          }

          // Convert UID Null to empty string
          if(signDateChecker == 'NULL'){
              signDateChecker = "";
          }else if(signDateChecker == ''){
              signDateChecker = "";
          }else if(signDateChecker == null){
              signDateChecker = "";
          }else{
              signDateChecker
          }

          // Convert UID Null to empty string
          if(approveChecker == 'NULL'){
              approveChecker = "";
          }else if(approveChecker == ''){
              approveChecker = "";
          }else if(approveChecker == null){
              approveChecker = "";
          }else{
              approveChecker
          }

      $('#tdata').append("<tr id ='toBeDeleted'>"+
          "<td>"+status[i]['USER_GRP_IND']+"</td>"+ 
          "<td>"+status[i]['STATUS']+"</td>"+   

          // "<td>"+status[i]['UID_SIGN']+"</td>"+   
          "<td>"+ uidChecker +"</td>"+   

          
          // "<td>"+status[i]['SIGNDATETIME']+"</td>"+
          "<td>"+signDateChecker+"</td>"+      
          // "<td>"+status[i]['ApprovedRemarks']+"</td>"+
          "<td>"+approveChecker+"</td>"+       
          "</tr>");
      }
  
      console.log(status);

      // mindex.splice(0, mindex.length, status);
      // console.log(mindex);
      // console.log(status)
      // console.log(status.length)

      // console.log(status[0]);
      // console.log(status[0]['FNAME']);
      // $('#row1').html(status[0]['USER_GRP_IND']);


      // OK
      // $('#tdata').append("<tr>"+
      //     "<td>"+mindex[i]['FNAME']+"</td>"+   
      //     +"</tr>");
      //     console.log(i);

})






// console.log(status[0]);
// console.log(status[0]['FNAME']);
// $('#firstname2').html(status[0]['PROCESSID']);







      // var xhr = new XMLHttpRequest();

      // xhr.open('get','/approvals', true);

      // xhr.onprogress = function(){
      //     console.log('readystate:' ,xhr.readyState);
      // }

      // xhr.onload = function(){
      //     if(this.status == 200){
      //         console.log(this.responseText);
      //     }
      // }

      // xhr.send();
  }

  function toBeDelete(){
      // $('#toBeDeleted > tr').remove();
      $('#myTableId tbody').empty();
      console.log('test');
  }


  function nullChecker(){
      if ($('#person_data[document_type]').val() != ''){}
  }
</script>




</body>
</html>