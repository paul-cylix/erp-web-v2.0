<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
</head>
<body>

    <div style="height: 100%;"  class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
              <a href="/dashboard" class="nav-link">Dashboard</a>
            </li> 
          </ul> 
      
          <!-- Right navbar links -->
          <ul class="navbar-nav ml-auto"> 
              
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
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
                    <a href="#" class="dropdown-item">
                      <i class="fas fa-settings mr-2"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item">
                      <i class="fas fa-logout mr-2"></i> Logout 
                    </a> 
                  </div>
                </li>   
            </li>   
          </ul>
        </nav>
        <!-- /.navbar -->
      
        <!-- Main Sidebar Container -->
        <aside style="height: 100%;" class="main-sidebar sidebar-dark-primary elevation-6"> 
      
          <!-- Sidebar -->
          <div style="height: 100%;" class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="{{asset('assets/dist/img/jr.jpg')}}" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                <a href="#" class="d-block">Rosevir Ceballos Jr.</a>
              </div>
            </div>
      
            <!-- SidebarSearch Form -->
            <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                  </button>
                </div>
              </div>
            </div>
      
            
            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">New Workflow</li> 
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                      Accounting & Finance
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="/create-rfp" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Payment</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="/create-re" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Reimbursement</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="/create-pc" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Petty Cash</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="/create-ca" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Cash Advance </p>
                      </a> 
                    </li>  
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Human Resource
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="/create-ot" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Overtime 
                      </p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="/create-leave" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Leave 
                      </p>
                      </a> 
                    </li>  
                    <li class="nav-item">
                      <a href="/create-itinerary" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Itinerary 
                      </p>
                      </a> 
                    </li>  
                    <li class="nav-item">
                      <a href="/create-incidentreport" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Incident Report 
                      </p>
                      </a> 
                    </li>  
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Master List
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Customer Entry</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Item Entry</p>
                      </a> 
                      </li>  
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Supplier Entry</p>
                      </a> 
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Operations
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Labor Resources</p>
                      </a> 
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Purchasing
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Purchase Request</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Purchase Order</p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Direct Purchase Order</p>
                      </a> 
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Sales
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="/create-sof-delivery" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Sales Order - Delivery 
                      </p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="/create-sof-project" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Sales Order - Project 
                      </p>
                      </a> 
                    </li>  
                    <li class="nav-item">
                      <a href="/create-sof-demo" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Sales Order - Demo 
                      </p>
                      </a> 
                    </li>  
                    <li class="nav-item">
                      <a href="/create-sof-poc" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Sales Order - POC 
                      </p>
                      </a> 
                    </li>
                    <li class="nav-item">
                      <a href="sof-pending" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                      Sales Order - Pending 
                      </p>
                      </a> 
                    </li>  
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                    Supply Chain
                    <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                        Materials
                        <i class="right fas fa-angle-left"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>MRF - Project</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>MRF - Delivery</p>
                          </a>
                        </li> 
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>MRF - Demo</p>
                          </a>
                        </li> 
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>MRF - POC</p>
                          </a>
                        </li> 
                      </ul>
                    </li> 
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                        Assets
                        <i class="right fas fa-angle-left"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>ARF - Project</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>ARF - Delivery</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>ARF - Demo</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>ARF - POC</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>ARF - Internal</p>
                          </a>
                        </li> 
                      </ul>
                    </li> 
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                        Supplies
                        <i class="right fas fa-angle-left"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>SuRF - Project</p>
                          </a>
                        </li> 
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                          <i class="far fa-dot-circle nav-icon"></i>
                          <p>SuRF - Internal</p>
                          </a>
                        </li> 
                      </ul>
                    </li> 
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                        Release Stocks 
                        </p>
                      </a> 
                    </li>  
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                        RMA 
                        </p>
                      </a> 
                    </li>  
                  </ul>
                </li>
                <li class="nav-header">My Workflow</li>
                <li class="nav-item">
                  <a href="/participants" class="nav-link">
                    <i class="nav-icon far fa-circle text-white"></i>
                    <p class="text">Participants</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/inputs" class="nav-link">
                    <i class="nav-icon far fa-circle text-primary"></i>
                    <p class="text">Inputs</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/approvals" class="nav-link">
                    <i class="nav-icon far fa-circle text-info"></i>
                    <p class="text">Approvals</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/in-progress" class="nav-link">
                    <i class="nav-icon far fa-circle text-warning"></i>
                    <p>In Progress</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/clarifications" class="nav-link">
                    <i class="nav-icon far fa-circle text-info"></i>
                    <p class="text">Clarifications</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/approved" class="nav-link">
                    <i class="nav-icon far fa-circle text-success"></i>
                    <p>Approved</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/withdrawn" class="nav-link">
                    <i class="nav-icon far fa-circle text-secondary"></i>
                    <p class="text">Withdrawn</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/rejected" class="nav-link">
                    <i class="nav-icon far fa-circle text-danger"></i>
                    <p class="text">Rejected</p>
                  </a>
                </li>
              </ul>
            </nav>
        </div>
    </aside>
            
    @yield('content')

    <footer class="main-footer">
        <strong>Copyright &copy; 2020 <a style="color: #11ba27" href="https://www.cylix.ph/">Cylix Technologies Inc.</a></strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.5-UT
        </div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>


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
<!-- dropzonejs -->
<script src="{{asset('assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
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
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  });

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false;

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template");
  previewNode.id = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  });

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
  });

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
  });

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
  });

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0";
  });

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
  };
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true);
  };
  // DropzoneJS Demo Code End
</script>


</body>
</html>