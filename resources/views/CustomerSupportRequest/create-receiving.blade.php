@extends('layouts.base')
@section('title', 'RMA Receiving Request') 
@section('content')

@if(Session::get('form_submitted'))
<Script>
    swal({
        text: "{!! Session::get('form_submitted') !!}",
        icon: "success",
        closeOnClickOutside: false,
        closeOnEsc: false,        
        })
        .then(okay => {
        if (okay) {
        window.location.href = "/list-rma-receiving";
        }});
</Script>
@endif

@if(Session::get('form_error'))
<Script>
    swal({
        text: "{!! Session::get('form_error') !!}",
        icon: "error",
        closeOnClickOutside: false,
        closeOnEsc: false,               
        })
</Script>
@endif


<form action="{{ route('save.rma.rcvd') }}" method="POST">
@csrf
{{-- Submit Button --}}
    <div class="row" style="margin-top: -20px;"> 
        <div class="col-md-1">
            <div class="form-group">
                <input style="width:100%;"  type="submit" class="btn btn-primary" id="submit-all" value="Submit"/>                                
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group">
                <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
            </div>
        </div> 
    </div>
{{-- End Submit Button --}}
    <input type="hidden" name="jsonrmaData" id="jsonrmaData">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
            
                    <div class="card-body">
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>
                                                       
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Created On</label>
                                    <input type="text" value="{{ date("Y/m/d") }}" name="rdateTime" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                    <span class="text-danger">@error('rdateTime'){{ $message }}@enderror</span>
              
                                </div>                            
                            </div>

                            <input type="hidden" name="rmName" id="rmName">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Manager</label>
                                    {{-- <input type="text" class="form-control" name="projectManager" id="projectManager"  placeholder="" onkeypress="return /[a-z ]/i.test(event.key)" > --}}
                                    {{-- <label for="exampleInputEmail1">Reporting Manager</label> --}}
                                    <select class="form-control select2 select2-default" name="rmID" id="rmID" data-dropdown-css-class="select2-default" onchange="getReportingManagerName(this)">
                                        <option value="0" selected class="d-none">Select Project Manager</option>
                                        @foreach ($managers as $mgr )
                                            <option value="{{ $mgr->RMID }}">{{ $mgr->RMName }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                             
                                </div>         
                            </div>  

                  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Created By</label>
                                    <input type="text"  class="form-control" name="createdBy" value="{{ session('LoggedUser_FullName') }}" readonly>
                                    <span class="text-danger">@error('createdBy'){{ $message }}@enderror</span>
                            
                                </div>                            
                            </div>
                        </div>


{{-- Table Start--}}
          <div class="row">
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                        <div class="row">
                            <div class="col" style="font-size:18px; padding-top:5px;">RMA Receiving Details</div>                                          
                            <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</a></div>
                        </div>                                       
                    </div> 
                    <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                        <table class="table table-bordered " id="rmaReceivingTable">
                            <thead>
                                <tr class="d-flex text-center" style="font-size: 13px;">
                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Project Name</th>
                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Client Name</th>
                                    <th class="col-2" style="position: sticky; top: 0; background: white; ">Issue</th>
                                    <th class="col-2" style="position: sticky; top: 0; background: white; ">Brand</th>
                                    <th class="col-2" style="position: sticky; top: 0; background: white; ">Model</th>
                                    <th class="col-2" style="position: sticky; top: 0; background: white; ">Serial Number</th>
                                    <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Qty</th>
                                    <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">UOM</th>
                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Date Received</th>
                              

                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Action</th>
                                </tr>
                            </thead>
                            <tbody id="rmaReceivingBody">
                              
                            </tbody>
                        </table>
                    </div>
                    {{-- footer /Pagination part --}}
                    <div class="card-footer clearfix">
                    </div>
                </div>
            </div>                                    
        </div>
{{-- Table End --}}

                    </div>
                    {{-- Card Body END --}}
                </form>    
            </div>
        </div>
    </div>
{{-- Row END --}}


{{-- Sweet ALert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
{{-- Sweet Alert End --}}











{{-- Modal Start --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add RMA Item to Receive</h5>
                    <button type="button" class="close closeModalOne" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Added Successfully</div>                                             
                   
                        <div class="row">
                          
                            <div class="col-md-6">
                              <div class="form-group">
                                <label for="projectID">Project Name</label>
                                <select id="projectID" name="projectID" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                    <option selected disabled hidden style='display: none' value=''></option>
                                    @foreach ($projects as $prj)
                                         <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="projectIDErr"></span>                                                  

                            </div>                       
                            </div>
                            <input id='projectName' name="projectName" type="hidden" class="form-control readonly" >
                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>
                                    <input id="clientName" name="clientName" type="text" class="form-control" placeholder="" readonly >
                                </div>
                            </div>
                        </div>
                        
                        <div class="row"> 



                            <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputEmail1">Brand</label>
                                  <input type="text" class="form-control" id="brand"  placeholder="">
                                  <span class="text-danger" id="brandErr"></span>                                                  
                              </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Date Received</label>
                                    <div class="input-group date" id="datetimepicker50" data-target-input="nearest">
                                        <input type="text" value="{{ $dateTime }}" class="form-control datetimepicker-input" id="dateReceived" data-target="#datetimepicker50"/>
                                        <div class="input-group-append" data-target="#datetimepicker50" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                <span class="text-danger" id="dateReceivedErr"></span>   
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker50').datetimepicker(
                                      {
                                        ignoreReadonly: true,
                                        icons: {
                                        time:"fas fa-clock"
                                        }
                                      }
                                    );
                                });
                            </script>


                        </div>

                        <div class="row">

                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Model</label>
                                <input type="text" class="form-control" id="model"  placeholder="">
                                <span class="text-danger" id="modelErr"></span>                                                  
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Serial Number</label>
                                <input type="text" class="form-control" id="serialNumber"  placeholder="">
                                <span class="text-danger" id="serialNumberErr"></span>                                                  
                            </div>
                          </div>
                        </div>
      
                        <div class="row">   
                            
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputEmail1">Quantity</label>
                                  <input type="number" class="form-control" id="qty" step=".01" value="1" placeholder="">
                                  <span class="text-danger" id="qtyErr"></span>                                                  
                              </div>
                            </div>


                            <input type="hidden" id="uomName">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Unit of Measure</label>
                                    <select class="form-control select2 select2-default"  id="uom" data-dropdown-css-class="select2-default" onchange="getUoM(this)">
                                        <option selected value='0'>Select UoM</option>
                                        @foreach ($uom as $data )
                                            <option value="{{ $data->id }}">{{ $data->UoM }}</option>
                                        @endforeach
                                    </select>                                 
                                  <span class="text-danger" id="uomErr"></span>                                                  
                                </div>         
                            </div>  
             
                        </div>

                        <div class="row">
                            <div class="col-md-12">                     
                                <label for="addIssue">Add Issue</label>
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="add a comment here" name="addIssue" id="addIssue" style="height: 100px"></textarea>
                                        <span class="text-danger" id="addIssueErr"></span>                                                  
                                    </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModalOne" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveDetails">Add</button>
                </div>
            </div>
        </div>
    </div>
{{-- Modal End --}}



<!-- Modal Update Start -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Update Overtime Details</h5>
              <button type="button" class="close closeModalTwo" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv1">Added Successfully</div>                                             
           
                <div class="row">
                  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="projectID1">Project Name</label>
                        <select id="projectID1" name="projectID1" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails1(this.value)">
                            <option selected disabled hidden style='display: none' value=''></option>
                            @foreach ($projects as $prj)
                                 <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="projectID1Err"></span>                                                  

                    </div>                       
                    </div>
                    <input id='projectName1' name="projectName1" type="hidden" class="form-control readonly" >
                    <input id="clientID1" name="clientID1" type="hidden" class="form-control" placeholder="" readonly>
                    <input id="mainID1" name="mainID1" type="hidden" class="form-control" placeholder="" readonly>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clientName1">Client Name</label>
                            <input id="clientName1" name="clientName1" type="text" class="form-control" placeholder="" readonly >
                        </div>
                    </div>
                </div>
                
                <div class="row"> 



                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Brand</label>
                          <input type="text" class="form-control" id="brand1"  placeholder="">
                          <span class="text-danger" id="brand1Err"></span>                                                  
                      </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Date Received</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" value="{{ $dateTime }}" class="form-control datetimepicker-input" id="dateReceived1" data-target="#datetimepicker2"/>
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        <span class="text-danger" id="dateReceived1Err"></span>   
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('#datetimepicker2').datetimepicker(
                              {
                                useCurrent: true,
                                icons: {
                                time:"fas fa-clock"
                                }
                              }
                            );
                        });
                    </script>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Model</label>
                        <input type="text" class="form-control" id="model1"  placeholder="">
                        <span class="text-danger" id="model1Err"></span>                                                  
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Serial Number</label>
                        <input type="text" class="form-control" id="serialNumber1"  placeholder="">
                        <span class="text-danger" id="serialNumber1Err"></span>                                                  
                    </div>
                  </div>
                </div>

                <div class="row">   

                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="exampleInputEmail1">Quantity</label>
                          <input type="number" value="1" class="form-control" step=".01" id="qty1" placeholder="">
                          <span class="text-danger" id="qty1Err"></span>                                                  
                      </div>
                    </div>

                    <input type="hidden" id="uomName1">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Unit of Measure</label>
                            <select class="form-control select2 select2-default"  id="uom1" data-dropdown-css-class="select2-default" onchange="getUoM1(this)">
                                <option value="0" selected>Select UoM</option>
                                @foreach ($uom as $data )
                                    <option value="{{ $data->id }}">{{ $data->UoM }}</option>
                                @endforeach
                            </select>                                 
                          <span class="text-danger" id="uom1Err"></span>                                                  
                        </div>         
                    </div>  
      
                </div>

                <div class="row">
                    <div class="col-md-12">                     
                        <label for="addIssue">Add Issue</label>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="add a comment here" name="addIssue1" id="addIssue1" style="height: 100px"></textarea>
                                <span class="text-danger" id="addIssue1Err"></span>                                                  
                            </div>
                    </div>
                </div>

        </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary closeModalTwo"  data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="update">Save changes</button>
              <input type="hidden" name="btnID" id="btnID">
          </div>
      </div>
  </div>
</div>
{{-- Modal Update End --}}



<script>
    function showDetails(id) {
        if (window.XMLHttpRequest) {
            let projectName = $("#projectID option:selected").text();
            $('#projectName').val(projectName);
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var txt = xmlhttp.responseText.replace("[", "");
                txt = txt.replace("]", ""); 
                var res = JSON.parse(txt);
                document.getElementById("clientName").value = res.clientName;
                document.getElementById("clientID").value = res.clientID;
                document.getElementById("mainID").value = res.mainID;
            }
        }
        xmlhttp.open("GET","/get-client/"+id,true);
        xmlhttp.send();
    }

    function showDetails1(id) {
        if (window.XMLHttpRequest) {
            let projectName = $("#projectID1 option:selected").text();
            $('#projectName1').val(projectName);
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var txt = xmlhttp.responseText.replace("[", "");
                txt = txt.replace("]", ""); 
                var res = JSON.parse(txt);
                document.getElementById("clientName1").value = res.clientName;
                document.getElementById("clientID1").value = res.clientID;
                document.getElementById("mainID1").value = res.mainID;
            }
        }
        xmlhttp.open("GET","/get-client/"+id,true);
        xmlhttp.send();
    }

</script>

<script>
      var btnID = 0;
   
    $('#saveDetails').on('click',function(e){
        e.preventDefault();
       
        btnID += 1;
        console.log(btnID)
        

        let projectID = $("#projectID").val();
        let projectName = $('#projectName').val();
        let clientID = $('#clientID').val();
        let mainID = $('#mainID').val();
        let clientName = $('#clientName').val();
        let addIssue = $('#addIssue').val();
        let brand = $('#brand').val();
        let model = $('#model').val();
        let serialNumber = $('#serialNumber').val();
        let dateReceived = $('#dateReceived').val();
        let qty = $('#qty').val();
        let uom = $('#uom').val();
        let uomName = $('#uomName').val();

        
        
        let projectIDChecker = false
        let addIssueChecker = false
        let brandChecker = false
        let modelChecker = false
        let serialNumberChecker = false
        let dateReceivedChecker = false
        let qtyChecker = false
        let uomChecker = false

        

        if(projectID){
            projectIDChecker = true;
            $('#projectIDErr').text('');

        }else{
            $('#projectIDErr').text('Project Name is required!');
            $('#successDiv').addClass('d-none');
        }
        
        if(addIssue){
            addIssueChecker = true;
            $('#addIssueErr').text('');

        }else{
            $('#addIssueErr').text('Issue is required!');
            $('#successDiv').addClass('d-none');
        }

        if(brand){
            brandChecker = true;
            $('#brandErr').text('');

        }else{
            $('#brandErr').text('Band is required!');
            $('#successDiv').addClass('d-none');
        }
        
        if(model){
            modelChecker = true;
            $('#modelErr').text('');

        }else{
            $('#modelErr').text('Model is required!');
            $('#successDiv').addClass('d-none');
        }

        if(serialNumber){
            serialNumberChecker = true;
            $('#serialNumberErr').text('');

        }else{
            $('#serialNumberErr').text('Serial Number is required!');
            $('#successDiv').addClass('d-none');
        }

        if(dateReceived){
            dateReceivedChecker = true;
            $('#dateReceivedErr').text('');

        }else{
            $('#dateReceivedErr').text('Date Received is required!');
            $('#successDiv').addClass('d-none');
        }

        if(qty){
            qtyChecker = true;
            $('#qtyErr').text('');

        }else{
            $('#qtyErr').text('Quantity is required!');
            $('#successDiv').addClass('d-none');
        }

        if(uom  === '0'){
            $('#uomErr').text('UoM is required!');
            $('#successDiv').addClass('d-none');
          

        }else{
            uomChecker = true;
            $('#uomErr').text('');
        }

        if (projectIDChecker && addIssueChecker && brandChecker && modelChecker && serialNumberChecker && dateReceivedChecker && qtyChecker && uomChecker) {
            $("#rmaReceivingTable #rmaReceivingBody").append(`
            <tr class="d-flex" style="font-size: 13px;">
              <td class="d-none">${uom}</td>
              <td class="col-2">${projectName}</td>
              <td class="col-2">${clientName}</td>
              <td class="col-2">${addIssue}</td>
              <td class="col-2">${brand}</td>
              <td class="col-2">${model}</td>
              <td class="col-2">${serialNumber}</td>
              <td class="col-1">${qty}</td>
              <td class="col-1">${uomName}</td>
              <td class="col-2">${dateReceived}</td>
              <td class="d-none">${projectID}</td>
              <td class="d-none">${clientID}</td>
              <td class="d-none">${btnID}</td>
      
              <td class="col-1 text-center px-0">
                <button class="btn btn-success editRowBtn" id="newID${btnID}" data-toggle="modal" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>            
          `);

          $('#successDiv').removeClass('d-none');
          
            $('#addIssue').val('');
            $('#brand').val('');
            $('#model').val('');
            $('#serialNumber').val('');
            $('#uom').val('0').select2();
            $('#uomName').val('');

            
            // $('#uom').val('');
            // $('#dateReceived').val('');

  
        }
        // End if

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Delete Row Data from table
        $('.deleteRow').on('click',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        })

        // Edit Row Data
        $('.editRowBtn').on('click',function(e){
            e.preventDefault();
        var uomid = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var projectName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var clientName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var addIssue = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var brand = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var model = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var serialNumber = $(this).parent().prev().prev().prev().prev().prev().prev().prev().text();
        var qty = $(this).parent().prev().prev().prev().prev().prev().prev().text();
        var uom = $(this).parent().prev().prev().prev().prev().prev().text();
        var dateReceived = $(this).parent().prev().prev().prev().prev().text();
        var projectID = $(this).parent().prev().prev().prev().text();
        var clientID = $(this).parent().prev().prev().text();
        var btnID = $(this).parent().prev().text();


            console.log(uomid);

        const myvar = [];
            myvar.push(
                uomid,
                projectName,
                clientName,
                addIssue,
                brand,
                model,
                serialNumber,
                qty,
                uom,
                dateReceived,
                projectID,
                clientID,
                btnID
            ); 


        // Setting up data to update form
        
        $('#projectID1').val(projectID).select2();
        $('#clientName1').val(clientName);
        $('#addIssue1').val(addIssue);
        $('#brand1').val(brand);
        $('#model1').val(model);
        $('#serialNumber1').val(serialNumber);
        $('#qty1').val(qty);
        $('#uom1').val(uomid).select2();
        $('#uomName1').val(uom);
        $('#dateReceived1').val(dateReceived);
        $('#projectID1').val(projectID);
        $('#clientID1').val(clientID);
        $('#btnID').val(btnID);

        });
        // End edit row


        // update details
        $('#update').on('click',function(e){
        e.preventDefault();
      
        var projectName = $('#projectName1').val();
        var clientName = $('#clientName1').val();
        var addIssue = $('#addIssue1').val();
        var brand = $('#brand1').val();
        var model = $('#model1').val();
        var serialNumber = $('#serialNumber1').val();
        var qty = $('#qty1').val();
        var uom = $('#uomName1').val();
        var uomid = $('#uom1').val();
        var dateReceived = $('#dateReceived1').val();
        var projectID = $('#projectID1').val();
        var clientID = $('#clientID1').val();
        var btnID = $('#btnID').val();
        
        let projectIDChecker = false
        let addIssueChecker = false
        let brandChecker = false
        let modelChecker = false
        let serialNumberChecker = false
        let dateReceivedChecker = false
        let qtyChecker = false
        let uomChecker = false

        

        if(projectID){
            projectIDChecker = true;
            $('#projectID1Err').text('');

        }else{
            $('#projectID1Err').text('Project Name is required!');
            $('#successDiv1').addClass('d-none');
        }
        
        if(addIssue){
            addIssueChecker = true;
            $('#addIssue1Err').text('');

        }else{
            $('#addIssue1Err').text('Issue is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(brand){
            brandChecker = true;
            $('#brand1Err').text('');

        }else{
            $('#brand1Err').text('Brand is required!');
            $('#successDiv1').addClass('d-none');
        }
        
        if(model){
            modelChecker = true;
            $('#model1Err').text('');

        }else{
            $('#model1Err').text('Model is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(serialNumber){
            serialNumberChecker = true;
            $('#serialNumber1Err').text('');

        }else{
            $('#serialNumber1Err').text('Serial Number is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(dateReceived){
            dateReceivedChecker = true;
            $('#dateReceived1Err').text('');

        }else{
            $('#dateReceived1Err').text('Date Received is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(qty){
            qtyChecker = true;
            $('#qty1Err').text('');

        }else{
            $('#qty1Err').text('Quantity is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(uomid === '0'){
            $('#uom1Err').text('UoM is required!');
            $('#successDiv1').addClass('d-none');

        }else{
            uomChecker = true;
            $('#uom1Err').text('');
        }

        if (projectIDChecker && addIssueChecker && brandChecker && modelChecker && serialNumberChecker && dateReceivedChecker && qtyChecker && uomChecker) {
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(uomid);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(projectName);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(clientName);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(addIssue);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(brand);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().text(model);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().text(serialNumber);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().text(qty);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().text(uom);
            $('#newID'+btnID).parent().prev().prev().prev().prev().text(dateReceived);
            $('#newID'+btnID).parent().prev().prev().prev().text(projectID);
            $('#newID'+btnID).parent().prev().prev().text(clientID);

          $('#successDiv1').removeClass('d-none');
          
            $('#addIssue1').val('');
            $('#brand1').val('');
            $('#model1').val('');
            $('#serialNumber1').val('');
            $('#uom1').val('0').select2();
            $('#uomName1').val('');
            // $('#qty1').val('');
            // $('#uom1').val('');
            // $('#dateReceived1').val('');

            










  
        }
        // End if

        
        });







    });
    // End Save Details



    


    $('.closeModalOne').on('click',function(e){
        e.preventDefault();
        $('#successDiv').addClass('d-none');
        $('#projectIDErr').text('');
        $('#addIssueErr').text('');
        $('#brandErr').text('');
        $('#modelErr').text('');
        $('#serialNumberErr').text('');
        $('#dateReceivedErr').text('');
        $('#uomErr').text('');
        $('#itemCode').val('');
        $('#brand').val('');
        $('#model').val('');
        $('#addIssue').val('');
        $('#serialNumber').val('');
        $('#uom').val('0').select2();
        $('#uomName').val('');
        
    })
    // End Close modal one

    

    $('.closeModalTwo').on('click',function(e){
        e.preventDefault();
        $('#successDiv1').addClass('d-none');
        $('#projectID1Err').text('');
        $('#addIssue1Err').text('');
        $('#brand1Err').text('');
        $('#model1Err').text('');
        $('#serialNumber1Err').text('');
        $('#dateReceived1Err').text('');
        $('#uom1Err').text('');
        $('#itemCode1').val('');
        $('#brand1').val('');
        $('#model1').val('');
        $('#addIssue1').val('');
        $('#serialNumber1').val('');
        $('#uom1').val('0').select2();
        $('#uomName1').val('');
    })
    // End Close modal one




</script>

{{-- Get all data in table Start --}}
<script>
  function getdatainTable(){
      var rmaArrayData = [];

      $("#rmaReceivingTable > #rmaReceivingBody > tr").each(function () {
      var uomid = $(this).find('td').eq(0).text();
      var projectName = $(this).find('td').eq(1).text();
      var clientName = $(this).find('td').eq(2).text();
      var itemCode = $(this).find('td').eq(3).text();
      var brand = $(this).find('td').eq(4).text();
      var model = $(this).find('td').eq(5).text();
      var serialNumber = $(this).find('td').eq(6).text();
      var qty = $(this).find('td').eq(7).text();
      var uom = $(this).find('td').eq(8).text();
      var dateReceived = $(this).find('td').eq(9).text();
      var projectID = $(this).find('td').eq(10).text();
      var clientID = $(this).find('td').eq(11).text();
  
      var listTD = [];
      listTD.push(uomid,projectName,clientName,itemCode,brand,model,serialNumber,qty,uom,dateReceived,projectID,clientID);
      rmaArrayData.push(listTD);
  
      });

      console.log(rmaArrayData);

          var jsonrmaData = JSON.stringify(rmaArrayData);

          if (jsonrmaData.length == 2) {
              $( "#jsonrmaData" ).val('');
          } else {
              $( "#jsonrmaData" ).val(jsonrmaData);
          }
      
          console.log('this is data rma',$('#jsonrmaData').val());

  }
</script>
{{-- Get all data in table End --}}

{{-- Submit all data Start --}}
<script>
  $('#submit-all').on('click',function(e){
      // e.preventDefault();
      getdatainTable();

        if ($.trim($("#rmID").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please select Project Manager.');
            return false;
            }

        // if ($.trim($("#projectManager").val()) === "") {
        // $('#myError').removeClass('d-none');
        // $('#myError').text('Please complete required fields.');
        // return false;
        // }

        if ($.trim($("#jsonrmaData").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete RMA Receiving Details table.');
        return false;
        }
  })
</script>
{{-- Submit all data End --}}

{{-- Get rm name --}}
<script>
    function getReportingManagerName(){
        let rmName = $( "#rmID option:selected" ).text();
        $('#rmName').val(rmName);
        console.log(rmName);
    }
</script>


<script>
    function getUoM(){
        let uomName = $( "#uom option:selected" ).text();
        $('#uomName').val(uomName);
        console.log(uom);
    }
</script>

<script>
    function getUoM1(){
        let uomName1 = $( "#uom1 option:selected" ).text();
        $('#uomName1').val(uomName1);
        console.log(uom);
    }
</script>





@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
