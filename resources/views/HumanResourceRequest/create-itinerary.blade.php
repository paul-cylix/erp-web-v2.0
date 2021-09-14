@extends('layouts.base')
@section('title', 'Itinerary Request') 
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
        window.location.href = "in-progress";
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

<form action="{{ route('save.itinerary.post') }}" method="POST">
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


    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                    <div class="card-body">   
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Reference Number</label>
                                    <input type="text" value="ITF-{{ date("Y") }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                </div>                             
                            </div>

                <input type="hidden" name="frmName" value="@yield('title')" id="frmName">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Requested Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{date('m/d/Y')}}" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>      
                            </div>
                            
                            <input type="hidden" name="rmName" id="rmName">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Reporting Manager</label>
                                    <select class="form-control select2 select2-default" name="rmID" id="rmID" data-dropdown-css-class="select2-default" onchange="getReportingManagerName(this)">
                                        <option value="0" selected class="d-none">Select Reporting Manager</option>
                                        @foreach ($managers as $mgr )
                                            <option value="{{ $mgr->RMID }}">{{ $mgr->RMName }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                                </div>                            
                            </div>
                        </div> 
                        <span class="text-danger">@error('jsonitineraryData'){{ $message }}@enderror</span>

                        {{-- Itinerary Details Table Start--}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Itinerary Details</div>                                          
                                            <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</a></div>
                                        </div>                                       
                                    </div> 
                                    <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-bordered " id="itineraryDetailsTable">
                                            <thead>
                                                <tr class="d-flex text-center" style="font-size: 13px;">
                                                    <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Client Name</th>
                                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                                    <th class="col-4 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                                    <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itineraryDetailsTbody">
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        {{-- Itinerary Details Table End --}}

                        <input type="hidden" name="jsonitineraryData" id="jsonitineraryData">

                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Itinerary Details</h5>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Added Successfully</div>                                             
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="clientName" id="clientName">
                                    <label for="exampleInputEmail1">Client Name</label>
                                    <select class="form-control select2 select2-default" name="clientID" id="clientID" data-dropdown-css-class="select2-default" style="width: 100%" onchange="getClientName(this)">
                                        <option value="0">Select Client Name</option>
                                        @foreach ($businesslist as $client )
                                        <option value="{{ $client->Business_Number }}">{{ $client->business_fullname }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="clientIDErr"></span>                                                  

                                </div>                            
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Authorized Time Start</label>
                                    <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeStart" data-target="#datetimepicker7"/>
                                        <div class="input-group-append" data-target="#datetimepicker7"  data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeStartErr"></span>                                                  

                                </div> 
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Authorized Time End</label>
                                    <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeEnd" data-target="#datetimepicker8"/>
                                        <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeEndErr"></span>                                                  

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Purpose</label>
                            <textarea class="form-control" rows="3"  id="purpose"></textarea>
                            <span class="text-danger" id="purposeErr"></span>                                                  

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addItineraryDetails">Add</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Itinerary Details</h5>

                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv1">Added Successfully</div>                                             
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="clientName1" id="clientName1">
                                    <label for="exampleInputEmail1">Client Name</label>
                                    <select class="form-control select2 select2-default" name="clientID1" id="clientID1" data-dropdown-css-class="select2-default" style="width: 100%" onchange="getClientName1(this)">
                                        @foreach ($businesslist as $client )
                                        <option value="{{ $client->Business_Number }}">{{ $client->business_fullname }}</option>
                                        @endforeach
                                    </select>
                <span class="text-danger" id="clientIDErr1"></span>                                                  
                                </div>                            
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Authorized Time Start</label>
                                    <div class="input-group date" id="datetimepicker71" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeStart1" data-target="#datetimepicker71"/>
                                        <div class="input-group-append" data-target="#datetimepicker71"  data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                <span class="text-danger" id="authTimeStartErr1"></span>    
                                </div> 
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Authorized Time End</label>
                                    <div class="input-group date" id="datetimepicker81" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeEnd1" data-target="#datetimepicker81"/>
                                        <div class="input-group-append" data-target="#datetimepicker81" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                <span class="text-danger" id="authTimeEndErr1"></span>                                                  
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Purpose</label>
                            <textarea class="form-control" rows="3"  id="purpose1"></textarea>
                <span class="text-danger" id="purposeErr1"></span>                                                  
                        </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save">Save Changes</button>
                    <input type="hidden" name="btnID" id="btnID">
                </div>
            </div>
        </div>
    </div>














<script>
    var btnID = 0;
        $('#addItineraryDetails').on('click',function(e){
       
            $('#successDiv').addClass('d-none');
            $('#successDiv1').addClass('d-none');

            e.preventDefault();
    btnID += 1;
    var clientID     = $('#clientID').val();
    var clientName   = $('#clientName').val();
    var authTimeStart    = $('#authTimeStart').val();
    var authTimeEnd  = $('#authTimeEnd').val();
    var purpose  = $('#purpose').val();




    const myvar = [];
                myvar.push(
                    clientID,
                    clientName,
                    authTimeStart,
                    authTimeEnd,
                    purpose,
                    btnID
                ); 

                console.log(myvar)


    var clientIDChecker = false;
    var authTimeStartChecker = false;
    var authTimeEndChecker = false;
    var purposeChecker = false;


        // Validation
        if(clientID === "0"){
            $('#clientIDErr').text('Client Name is required!');
            $('#successDiv').addClass('d-none')
        }else{
            clientIDChecker = true;
            $('#clientIDErr').text('');
        }

        if(authTimeStart){
            authTimeStartChecker = true;
            $('#authTimeStartErr').text('');

        }else{
            $('#authTimeStartErr').text('Auth Time Start Date is required!');
            $('#successDiv').addClass('d-none');
        }

        if(authTimeEnd){
            authTimeEndChecker = true;
            $('#authTimeEndErr').text('');

        }else{
            $('#authTimeEndErr').text('Auth Time End Date is required!');
            $('#successDiv').addClass('d-none');
        }

        if(purpose){
            purposeChecker = true;
            $('#purposeErr').text('');

        }else{
            $('#purposeErr').text('Purpose is required!');
            $('#successDiv').addClass('d-none');
        }

        




    if(clientIDChecker && authTimeStartChecker && authTimeEndChecker && purposeChecker){
        $('#itineraryDetailsTable #itineraryDetailsTbody').append(`
            <tr class="d-flex" style="font-size: 13px;">
                <td class="d-none">${clientID}</td>
                <td class="col-md-3">${clientName}</td>
                <td class="col-md-2">${authTimeStart}</td>
                <td class="col-md-2">${authTimeEnd}</td>
                <td class="col-md-4">${purpose}</td>
                <td class="d-none">${btnID}</td>
                <td class="col-1 text-center px-0">
                    <button class="btn btn-success editRowBtn" id="newID${btnID}" data-toggle="modal" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
    `);

        $('#successDiv').removeClass('d-none');
        $('#authTimeStart').val('');
        $('#authTimeEnd').val('');
        $('#purpose').val('');

    }


    // Delete Row Data from table
    $('.deleteRow').on('click',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });



    // Edit Row Data
    $('.editRowBtn').on('click',function(e){
    e.preventDefault();
        var clientID = $(this).parent().prev().prev().prev().prev().prev().prev().text();
        var clientName = $(this).parent().prev().prev().prev().prev().prev().text();
        var authTimeStart = $(this).parent().prev().prev().prev().prev().text();
        var authTimeEnd = $(this).parent().prev().prev().prev().text();
        var purpose = $(this).parent().prev().prev().text();
        var btnID = $(this).parent().prev().text();


        const myvar = [];
            myvar.push(
                clientID,
                clientName,
                authTimeStart,
                authTimeEnd,
                purpose,
                btnID
            ); 

        console.log(myvar)


        $('#clientID1').val(clientID).select2();
        $('#clientName1').val(clientName);
        $('#authTimeStart1').val(authTimeStart);
        $('#authTimeEnd1').val(authTimeEnd);
        $('#purpose1').val(purpose);
        $('#btnID').val(btnID);


    });


    // save
    $('#save').on('click',function(e){
    e.preventDefault();
    $('#successDiv').addClass('d-none');
    $('#successDiv1').addClass('d-none');

    var clientID     = $('#clientID1').val();
    var clientName   = $('#clientName1').val();
    var authTimeStart    = $('#authTimeStart1').val();
    var authTimeEnd  = $('#authTimeEnd1').val();
    var purpose  = $('#purpose1').val();
    var btnID  = $('#btnID').val();

    const myvar = [];
            myvar.push(
                clientID,
                clientName,
                authTimeStart,
                authTimeEnd,
                purpose,
                btnID
            ); 
        console.log(myvar)

    var clientIDChecker = false;
    var authTimeStartChecker = false;
    var authTimeEndChecker = false;
    var purposeChecker = false;

        // Validation
        if(clientID === "0"){
            $('#clientIDErr1').text('Client Name is required!');
            $('#successDiv1').addClass('d-none')
        }else{
            clientIDChecker = true;
            $('#clientIDErr1').text('');
        }

        if(authTimeStart){
            authTimeStartChecker = true;
            $('#authTimeStartErr1').text('');

        }else{
            $('#authTimeStartErr1').text('Auth Time Start Date is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(authTimeEnd){
            authTimeEndChecker = true;
            $('#authTimeEndErr1').text('');

        }else{
            $('#authTimeEndErr1').text('Auth Time End Date is required!');
            $('#successDiv1').addClass('d-none');
        }
        
        if(purpose){
            purposeChecker = true;
            $('#purposeErr1').text('');

        }else{
            $('#purposeErr1').text('Purpose is required!');
            $('#successDiv1').addClass('d-none');
        }
        
        if(clientIDChecker && authTimeStartChecker && authTimeEndChecker && purposeChecker){
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().text(clientID);
            $('#newID'+btnID).parent().prev().prev().prev().prev().prev().text(clientName);
            $('#newID'+btnID).parent().prev().prev().prev().prev().text(authTimeStart);
            $('#newID'+btnID).parent().prev().prev().prev().text(authTimeEnd);
            $('#newID'+btnID).parent().prev().prev().text(purpose);

            $('#successDiv1').removeClass('d-none');
            $('#authTimeStart1').val('');
            $('#authTimeEnd1').val('');
            $('#purpose1').val('');

        }
    
    
    })





})




</script>

<script>
    $('.closeBtn').on('click',function(){
        $('#successDiv').addClass('d-none');
        $('#successDiv1').addClass('d-none');

        $('#clientIDErr').text('');
        $('#authTimeStartErr').text('');
        $('#authTimeEndErr').text('');
        $('#purposeErr').text('');
        $('#clientIDErr1').text('');
        $('#authTimeStartErr1').text('');
        $('#authTimeEndErr1').text('');
        $('#purposeErr1').text('');

    })
</script>




{{-- Get Reporting Manager Name Start --}}
    <script>
        function getReportingManagerName(){
            let rmName = $( "#rmID option:selected" ).text();
            $('#rmName').val(rmName);
            console.log(rmName);
        }

        function getClientName(){
            let clientName = $( "#clientID option:selected" ).text();
            $('#clientName').val(clientName);
            console.log(clientName);
        }

        function getClientName1(){
            let clientName1 = $( "#clientID1 option:selected" ).text();
            $('#clientName1').val(clientName1);
            console.log(clientName1);
        }
    </script>
{{-- Get Reporting Manager Name End --}}

{{-- Submit all data Start --}}
    <script>
        $('#submit-all').on('click',function(e){
            // e.preventDefault();
            getdatainOTTable();
            $('#myError').removeClass('d-none');
            $('#myError').text('');


            console.log($('#rmID').val())

            if ($.trim($("#rmID").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete rmID required fields.');
            return false;
            }



            if ($.trim($("#jsonitineraryData").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete jsonitineraryData required fields.');
            return false;
            }
            $('#myError').addClass('d-none');
            // $('#myError').text('');
    

        })
    </script>
{{-- Submit all data End --}}


{{-- Get all data in table Start --}}
<script>
    function getdatainOTTable(){
        var itineraryData = [];

        $("#itineraryDetailsTable > #itineraryDetailsTbody > tr").each(function () {
        var clientID = $(this).find('td').eq(0).text();
        var clientName = $(this).find('td').eq(1).text();
        var authTimeStart = $(this).find('td').eq(2).text();
        var authTimeEnd = $(this).find('td').eq(3).text();
        var purpose = $(this).find('td').eq(4).text();
        
    
        var listTD = [];
        listTD.push(clientID,clientName,authTimeStart,authTimeEnd,purpose);
        itineraryData.push(listTD);
    
        });

        console.log(itineraryData);

            var jsonitineraryData = JSON.stringify(itineraryData);

            if (jsonitineraryData.length == 2) {
                $( "#jsonitineraryData" ).val('');
            } else {
                $( "#jsonitineraryData" ).val(jsonitineraryData);
            }
        
            console.log('this is data of jsonot',$('#jsonitineraryData').val());

    }
</script>
{{-- Get all data in table End --}}



    <script type="text/javascript">
        $(function () {
            $('#datetimepicker7').datetimepicker({
                // format: 'L'
                icons: {
                            time:"fas fa-clock"
                        }
            });
            $('#datetimepicker8').datetimepicker({
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
                // format: 'L'
            });
            // $("#datetimepicker7").on("change.datetimepicker", function (e) {
            //     $('#datetimepicker8').datetimepicker('minDate', e.date);
            // });
            // $("#datetimepicker8").on("change.datetimepicker", function (e) {
            //     $('#datetimepicker7').datetimepicker('maxDate', e.date);
            // });

            $('#datetimepicker71').datetimepicker({
                // format: 'L'
                icons: {
                            time:"fas fa-clock"
                        }
            });
            $('#datetimepicker81').datetimepicker({
                useCurrent: false,
                // format: 'L'
                icons: {
                            time:"fas fa-clock"
                }
            });
            // $("#datetimepicker71").on("change.datetimepicker", function (e) {
            //     $('#datetimepicker81').datetimepicker('minDate', e.date);
            // });
            // $("#datetimepicker81").on("change.datetimepicker", function (e) {
            //     $('#datetimepicker71').datetimepicker('maxDate', e.date);
            // });

        });
    </script>



@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>

{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
