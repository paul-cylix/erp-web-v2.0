<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Attendance User Register</title>
  {{-- admin lte --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

  {{-- Select 2 --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  {{-- Select 2 bootstrap --}}
  {{-- <link rel="stylesheet" href="/path/to/select2.css"> --}}
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

  {{-- font awesome --}}
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    
    <style>
      .overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}

.addOpacity{
  opacity: 0.1;
}
    </style>
</head>

<body style="background-color:	#2d3335">

  <div class="d-flex justify-content-center align-items-center mt-5 pt-5">
    <div class="container col-lg-4">

      <div class="card card-secondary ">
        <div class="card-header">
          <h3 class="card-title">Registration Form</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
      {{-- spinner --}}

      



        <form>
          <div class="overlay" id="loading" hidden>
            <div class="d-flex justify-content-center align-items-center">
              <div class="spinner-border text-dark" role="status">
              </div>
              <span class="ml-2">Loading...</span>
            </div>
          </div>

          <aside>
          <div class="card-body">
            <div class="alert alert-warning alert-dismissible" id="alert" hidden>
              <h5><i class="icon fas fa-info" id="icon"></i><span id="banner"></span></h5>
                <ul id="listOptions">
                </ul>
            </div>

 

            <div class="form-group">
              {{-- <small><label for="exampleInputPassword1">Select Employee</label></small> --}}
              <input type="hidden" name="employeeName" id="employeeName">
              <select class="select2" style="width: 100%;" id="employeeId" >
                <option selected value="-1">Select Employee</option>
                @foreach ($employees as $employee )
                <option value="{{ $employee->SysPK_Empl}}">{{ $employee->Name_Empl }}</option>
                @endforeach
              </select>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" class="form-control" id="username" placeholder="Username/Email">
            </div>


            <div class="row">
              <div class="col-lg-6">
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="passForm" placeholder="Password" id="pass">
                  <div class="input-group-append">
                    <span class="input-group-text" id="passToggle" style="cursor: pointer"><i class="fas fa-eye"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="input-group mb-3">
                  <input type="password" class="form-control" id="cpassForm" placeholder="Confirm Password" id="cpass">
                  <div class="input-group-append">
                    <span class="input-group-text " id="cPassToggle" style="cursor: pointer"><i class="fas fa-eye"></i></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg">
                <div class="form-group">
                  {{-- <small><label for="exampleInputPassword1">Rank</label></small> --}}
                  <input type="hidden" id="rank">
                  <select class="form-control select2bs4" id="isManager" style="width: 100%;">
                    <option selected="selected" value="-1">Select Rank</option>
                    <option value="0">Employee</option>
                    <option value="1">Manager</option>
                  </select>
                </div>

              </div>
              <div class="col-lg">
                <div class="form-group">
                  <select class="form-control select2bs4" id="manager" style="width: 100%;">
                    <option selected="selected" value="-1">Select Manager</option>
                    @foreach ( $managers as $manager )
                      <option value='{"id":"{{ $manager->id }}","username":"{{ $manager->username }}","employee_id":"{{ $manager->employee_id }}","manager_name":"{{ $manager->manager_name }}"}'>{{ $manager->manager_name }}</option>
                   
                      
                   
                      @endforeach
                  </select>
                </div>
              </div>
            </div>


          </div>
          <!-- /.card-body -->

          <div class="card-footer text-right">
            <button type="submit" id="submit" class="btn btn-secondary btn-sm btn-block">Register</button>
          </div>
        </form>
      </aside>

        {{-- <button onclick="disableForm()">test</button>
        <button onclick="enableForm()">test 2</button>
        <button onclick="clearForm()">test 3</button> --}}
      </div>
    </div>

    <!-- /.card -->
  </div>
  {{-- jquery --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  {{-- select2 --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  {{-- admin lte --}}
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

  <script>
    $(function () {
      $('select').select2({
        theme: 'bootstrap4',
      });
    })




  $("#passToggle").click(function(){
    const classId = "#passToggle"
    const formId = "#passForm"
    showPassword(classId,formId);


  });

  $("#cPassToggle").click(function(){
   
    const classId = "#cPassToggle"
    const formId = "#cpassForm"
    showPassword(classId,formId);


  });
  

  function showPassword(classId,formId){
    let className = $(classId).children().attr('class');
 
    if (className === 'fas fa-eye') {
      $(classId).children().removeClass();
      $(classId).children().addClass('fas fa-eye-slash');

      $(formId).prop({type:"text"});
  
    } else {
      $(classId).children().removeClass();
      $(classId).children().addClass('fas fa-eye');
      $(formId).prop({type:"password"});

    }
  }

  $('#employeeId').change(function() {
    let employeeId = $(this).val();
    let employeeName = $(this).find('option:selected').text();
    $('#employeeName').val(employeeName)
  });

  $('#isManager').change(function() {
    let rank = $(this).find('option:selected').text();
    $('#rank').val(rank)

    console.log(rank)
  });


  $('#manager').change(function() {
    let manager = $(this).val();
    let managerName = $(this).find('option:selected').text();

    manager = JSON.parse(manager)

    console.log(manager,managerName)

  });



  $("#submit").click(function(e){
    e.preventDefault();
    removeAlertList();
    disableForm();
    let employeeName = $('#employeeName').val();
    let employeeId = $('#employeeId').val();
    let username = $('#username').val();
    let passValue = $('#passForm').val();
    let cpassValue = $('#cpassForm').val();
    let isManager = $('#isManager').val();
    let rank = $('#rank').val();


    let manager = $('#manager').val();
    manager = JSON.parse(manager)
    
    let managerId = manager.id;
    let managerUsername = manager.username;
    let managerName = manager.manager_name;
    let managerEmployeeId = manager.employee_id;

    $.ajaxSetup({
      headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: 'http://127.0.0.1:8000/api/register-user',
        // headers: {
        //     'Content-Type':'application/json',
        //     'Accept' : 'application/json',
        // },
        dataType: 'json', 
        data:{
          username : username,
          fullname : employeeName,
          employeeId : employeeId,
          isManager : isManager,
          password : passValue,
          password_confirmation: cpassValue,
          rank : rank,
          managerId : managerId,
        },
        }).done(function(response) {

        // done pero failed yung request mag return ng error response from api
          if(response.failed){
            // alert('failed ito', response);
            // console.log(response.failed)

            const object = response.failed;
            for (const property in object) {
              // console.log(`${object[property]}`);
              $('#listOptions').append(`<li>${object[property]}</li>`)
            }
            
            // alertType = "alert-danger";
            alertType = "alert alert-danger alert-dismissible";
            icon = "icon fas fa-ban";
            text = "Error!";

            addAlertDialog(alertType,icon,text);
            
            
          }

        // done yung request mag return ng success response from api
          if (response.success) {
            // alert('sucess ito', response);
            alertType = "alert alert-success alert-dismissible";
            icon = "icon fas fa-check";
            text = "Success!";
            $('#listOptions').append(`<li>User has been successfully registered!</li>`)
            addAlertDialog(alertType,icon,text);
            clearForm();
          }
        
        // fail from server / api
        }) .fail(function(response) {
          // alert( "error" , response);
          $('#listOptions').append(`<li>Please Contact the Administrator</li>`)

        // always prompt
        })  .always(function(response) {
          // alert( "finished" , response);
          enableForm();

        });


 });



 $('input').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
 
  function removeAlertList(){
    $("#listOptions").children("li").remove();
    // $("#alert").attr("hidden",true);
    $("#icon").removeClass();

    $("#alert").removeClass();
    // $("#alert").addClass("alert alert-warning alert-dismissible");
    $("#banner").text('')
  }

  function addAlertDialog(alertType,icon,text){
    $("#alert").removeClass();
    $("#alert").addClass(alertType); // alertType 'alert-danger'
    $("#icon").removeClass();
    $("#icon").addClass(icon); // icon icon fas fa-ban
    $("#banner").text(text); // text 'Error!
    $('#alert').removeAttr('hidden');
  }

  function disableForm(){
    $("#employeeId").attr("disabled",true);
    $("#username").attr("disabled",true);
    $("#passForm").attr("disabled",true);
    $("#cpassForm").attr("disabled",true);
    $("#isManager").attr("disabled",true);
    $("#manager").attr("disabled",true);

    $("aside").addClass('addOpacity')

    $("#loading").removeAttr("hidden");

  }

  function enableForm(){
    $("#employeeId").removeAttr("disabled");
    $("#username").removeAttr("disabled");
    $("#passForm").removeAttr("disabled");
    $("#cpassForm").removeAttr("disabled");
    $("#isManager").removeAttr("disabled");
    $("#manager").removeAttr("disabled");

    $("aside").removeClass();
    $("#loading").attr("hidden",true);
  }

  function clearForm(){
    // $('#employeeId').val(-1).select2();
    // $('#employeeId').select2().val(-1);
    $('#username').val('');
    $('#passForm').val('');
    $('#cpassForm').val('');
    // $('#isManager').val(-1).select2();
    // $('#manager').val(-1).select2();

  }




  </script>


</body>

</html>