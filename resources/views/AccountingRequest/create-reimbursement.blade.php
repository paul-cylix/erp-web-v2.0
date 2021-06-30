@extends('layouts.base')
@section('title', 'Reimbursement Request') 
@section('content')

<form action="{{ route('save.re') }}" method="POST" enctype="multipart/form-data" >
@csrf
<div class="row" style="margin-top: -20px;"> 
    <div class="col-md-1">
        <div class="form-group">
            <input style="width:100%;"  type="submit" class="btn btn-primary" id="submit-all" value="Submit"/>                                
        </div>
    </div>

    <input type="hidden" name="xdData" id="xdData">
    <input type="hidden" name="tdData" id="tdData">

    <div class="col-md-1">
        <div class="form-group">
            <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
        </div>
    </div> 
</div>


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

@error('amount')              
<Script>
swal({
text: "Please complete required fields!",
icon: "error",
closeOnClickOutside: false,
closeOnEsc: false,        
})
</Script>
@enderror


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


    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">Reimbursement Request</h3>
                </div>

                    <div class="card-body">
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    {{-- <input type="text" class="form-control" value="{{ $ref1 }}" readonly > --}}
                                    <input type="text" class="form-control" placeholder="RE-{{ date("Y") }}-" readonly>                                  
                                </div>                            
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateRequested">Requested Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{date('m/d/Y')}}" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reportingManager">Reporting Manager</label>
                                    <select id="reportingManager" name="reportingManager" class="form-control select2 select2-default"  data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getRMName(this)">
                                        <option selected disabled hidden style='display: none' value=''></option>
                                        @foreach ($mgrs as $rm)
                                            <option value="{{$rm->RMID}}">{{$rm->RMName}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('reportingManager'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectName">Project Name</label>
                                    <select id="projectName" name="projectName" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                        <option selected disabled hidden style='display: none' value=''></option>
                                        @foreach ($projects as $prj)
                                             <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
                                </div>
                            </div>
                            
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
                                    <label for="exampleInputEmail1">Payee Name</label>
                                    <input type="text" class="form-control" id="payeeName" name="payeeName" placeholder="">
                                    <span class="text-danger">@error('payeeName'){{ $message }}@enderror</span>

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateNeeded">Date Needed</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" />
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger">@error('dateNeeded'){{ $message }}@enderror</span>

                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Total Amount</label>
                                    <input data-type="currency" min="0" style="text-align: right" type="number" readonly class="form-control" name="amount" id="amount" placeholder="0.00" >
                                    <span class="text-danger">@error('amount'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>

                        

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">                                            
                                    <label for="purpose">Purpose</label> 
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                    <span class="text-danger">@error('purpose'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>

                        {{-- Expense Details --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                                            <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</a></div>

                                        </div>                                       
                                    </div> 

                                    <div class="card-body table-responsive p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-hover text-nowrap" id="xdTable">
                                            <thead>
                                                <tr>
                                                    <th style="position: sticky; top: 0; background: white; ">Date</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Expense Type</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Remarks</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Amount</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="xdTbody">
                            
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                    <div class="container">
                                    <div class="row float-right" style="margin-right: 50px;">
                                    {{-- <span >Total Amount:</span> --}}
                                    </div>
                                    </div>
                                    <div class="container">
                                        <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount"></span></h6>

                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        <span class="text-danger">@error('xdData'){{ $message }}@enderror</span>

                        {{-- Expense Details --}}
                    
                        {{-- Transportation Details --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">

                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                                            <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</a></div>

                                        </div>
                                    </div>

                                    <div class="card-body table-responsive p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-hover text-nowrap" id="tdTable" >
                                            <thead>
                                                <tr>
                                                    <th style="position: sticky; top: 0; background: white;" >Date</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Destination From</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Destination To</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Mode of Transportation</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Remarks</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Amount</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tdTbody">
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                        <div class="container">
                                        <div class="row float-right" style="margin-right: 50px;">
                                        {{-- <span >Total Amount:</span> --}}
                                        </div>
                                        </div>
                                        
                                        <div class="container">
                                        <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        <span class="text-danger">@error('tdData'){{ $message }}@enderror</span>

                        <br>

                        {{-- Transportation details --}}

                        {{-- Attachments --}}
                        <label class="btn btn-primary" style="font-weight:normal;">
                            Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                        </label>

                        
                        {{-- Attachments of no edit --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
            
                                    <div class="card-header" style="height:50px;">
                                        <div class="row ">
                                            <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
                                        </div>
                                    </div>
                                    {{-- Card body --}}
                                    <div class="card-body" >


                                        {{-- Table attachments --}}
                                        <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                            <table id= "attachmentsTable"class="table table-hover" >
                                                <thead >
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    {{-- <th>Size</th> --}}
                                                    <th>Temporary Path</th>
                                                    <th>Actions</th>

                                                </tr>
                                                </thead>
                                                <tbody >
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Table attachments End--}}

            
                                        
                                    </div>
                                    {{-- Card body END --}}


            
                                </div>
                            </div>
                        </div>
                        {{-- End Attachments --}}
                        {{-- Attachments --}}        
                        
                    </form>



                    {{-- <div id="toTestButton" class="btn btn-info">Test</div> --}}

                        {{-- Modal --}}
                        <!-- Modal Expense Detail -->
                        <div class="modal fade" id="expenseDetail" tabindex="-1" aria-labelledby="expenseDetail" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="expenseDetailLabel">Expense Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                {{-- START ADD MODAL--}}
                                <div class="container-fluid">
                                <div class="p-3 mb-2 bg-success text-white d-none" id="xpsuccessdiv">Added Successfully</div>                                             

                                    <div class="row">
                                        <div class="col-md-12">
                                    
                                                <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" class="form-control" aria-describedby="helpId" id="dateXD">
                                                        <script>
                                                            var today = new Date();
                                                            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                                                            var data = date;
                                                            document.getElementById("dateXD").valueAsDate = new Date(data);
                                                        </script>
                                                        <span class="text-danger" id="dateErrXD"></span>                                                  
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Expense Type</label>
                                                        <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" id="typeXD">
                                                            @foreach ($expenseType as $xpType)
                                                            <option value="{{$xpType->type}}">{{$xpType->type}}</option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <span class="text-danger" id="typeErrXD"></span>--}}
                                                    </div>
                                                </div>

                        
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Amount</label>
                                                        <input type="number" class="form-control" placeholder="0.00" aria-describedby="helpId"  id="amountXD">
                                                        <span class="text-danger" id="amountErrXD"></span>
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Remarks</label>
                                                            <textarea class="form-control" rows="5"  placeholder="input text here"  id="remarksXD"></textarea>
                                                            <span class="text-danger" id="remarksErrXD"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- END ADD--}}
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="getExpenseData()">Insert</button>

                                </div>
                            </div>
                            </div>
                        </div>
                        {{-- End Modal Expense Detail --}}

                        {{-- <div class="btn btn-info" id="testbutton">test</div> --}}

                        <!-- Modal Transportation Details -->
                        <div class="modal fade" id="transpoDetails" tabindex="-1" aria-labelledby="transpoDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="transpoDetailsLabel">Transportation Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                {{-- START ADD MODAL--}}
                                <div class="container-fluid">
                                <div class="p-3 mb-2 bg-success text-white d-none" id="tdsuccessdiv">Added Successfully</div>                                             

                                    <div class="row">
                                        <div class="col-md-12">                                   
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" class="form-control" aria-describedby="helpId" id="dateTD">
                                                        <script>
                                                            var today = new Date();
                                                            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                                                            var data = date;
                                                            document.getElementById("dateTD").valueAsDate = new Date(data);
                                                        </script>
                                                        <span class="text-danger" id="dateErrTD"></span>                                                  
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Mode of transportation</label>
                                                        <select class="form-control select2 select2-default" id="typeTD" data-dropdown-css-class="select2-default" style="width: 100%;" >
                                                            @foreach ($transpoSetup as $tdType)
                                                            <option value="{{$tdType->MODE}}">{{$tdType->MODE}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger" id="typeErrTD"></span>                                                  
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Amount</label>
                                                        <input type="number" class="form-control" id="amountTD" placeholder="0.00" dis aria-describedby="helpId" >
                                                        <span class="text-danger" id="amountErrTD"></span>                                                  
                                                    </div>
                                                </div>                                               
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Destination from</label>
                                                        <input type="text" class="form-control" id="fromTD" placeholder="" aria-describedby="helpId" >
                                                        <span class="text-danger" id="fromErrTD"></span>                                                  
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Destination to</label>
                                                        <input type="text" class="form-control" id="toTD" placeholder="" aria-describedby="helpId" >
                                                        <span class="text-danger" id="toErrTD"></span>                                                  
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Remarks</label>
                                                            <textarea class="form-control" rows="5" id="remarksTD"  placeholder="input text here"></textarea>
                                                            <span class="text-danger" id="remarksErrTD"></span>                                                  
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- END ADD--}}
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="getTransportationData()">Insert</button>

                                </div>
                            </div>
                            </div>
                        </div>
                        {{-- End Modal Transportation Details --}}
                        {{-- End Modal --}}


                    
                    </div>                            
            </div>
        </div>
    </div>
<script>
    $(function () {
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
    })
</script>

{{-- <script>
    $('#testbutton').on('click',function(){
        var myrows = $('#attachmentsTable tbody tr').length;
        alert(myrows);
    })
</script> --}}




{{-- Expense Details --}}
{{-- <script>
function getExpenseData(){
    var dateXD = $('#dateXD').val();
    var typeXD = $('#typeXD').val(); 
    var amountXD = $('#amountXD').val(); 
    var remarksXD = $('#remarksXD').val(); 


    var amountXDChecker = false;
    var remarksXDChecker = false;


if(amountXD){
    amountXDChecker = true;
    $('#amountErrXD').text('');

}else{
    $('#amountErrXD').text('Amount is required!');
    $('#xpsuccessdiv').addClass('d-none')

}

if(remarksXD){
    remarksXDChecker = true;
    $('#remarksErrXD').text('');

}else{
    $('#remarksErrXD').text('Remarks is required!');
    $('#xpsuccessdiv').addClass('d-none')

}


if(amountXDChecker && remarksXDChecker ){

    $('#xdTable tbody').append('<tr>'+
                                        '<td>'+dateXD+'</td>'+
                                        '<td>'+typeXD+'</td>'+
                                        '<td>'+remarksXD+'</td>'+
                                        '<td>'+amountXD+'</td>'+
                                        '<td>'+
                                            '<a class="btn btn-danger removeXDRow" onClick ="deleteXDRow()" >Delete</a>'+
                                        '</td>'+
                                    '</tr>'
        );
        xdUpdateData()
    

        $('#xpsuccessdiv').removeClass('d-none')
        $('#amountXD').val(''); 
        $('#remarksXD').val(''); 
    }


}



function deleteXDRow(){
    $('#xdTable').on('click','tr a.removeXDRow',function(e){
    e.preventDefault();
    $(this).closest('tr').remove();
    xdUpdateData()

    });
    
}


function xdUpdateData(){

    var objectXD = [];
    var myAmtXD = 0 ;

    $("#xdTable > #xdTbody > tr").each(function () {
            var dateXD = $(this).find('td').eq(0).text();
            var typeXD = $(this).find('td').eq(1).text();
            var remarksXD = $(this).find('td').eq(2).text();
            var amountXD = $(this).find('td').eq(3).text();
         
            var listXD = [];
            listXD.push(dateXD,typeXD,remarksXD,amountXD);
            objectXD.push(listXD);

            var xdJsonData = JSON.stringify(objectXD);
            $( "#xdData" ).val(xdJsonData);
        });

        for(var i = 0; i<objectXD.length; i++){
                var numAmt = objectXD[i]['3'];
                myAmtXD += parseFloat(numAmt);

               
    $('#xdTotalAmount').text(myAmtXD);



    console.log(objectXD);

    }
    

}
</script> --}}




{{-- Expense Details --}}
<script>
    function getExpenseData(){
        var dateXD = $('#dateXD').val();
        var typeXD = $('#typeXD').val(); 
        var amountXD = $('#amountXD').val(); 
        var remarksXD = $('#remarksXD').val(); 

    
    
        var amountXDChecker = false;
        var remarksXDChecker = false;
    
    
        if(amountXD){
            amountXDChecker = true;
            $('#amountErrXD').text('');
    
        }else{
            $('#amountErrXD').text('Amount is required!');
            $('#xpsuccessdiv').addClass('d-none');
    
        }
    
        if(remarksXD){
            remarksXDChecker = true;
            $('#remarksErrXD').text('');
        }else{
            $('#remarksErrXD').text('Remarks is required!');
            $('#xpsuccessdiv').addClass('d-none');
        }
    
        if(amountXDChecker && remarksXDChecker){
    
            $('#xdTable tbody').append('<tr>'+
                                                '<td>'+dateXD+'</td>'+
                                                '<td>'+typeXD+'</td>'+
                                                '<td>'+remarksXD+'</td>'+
                                                '<td>'+amountXD+'</td>'+
                                                '<td>'+
                                                    '<a class="btn btn-danger removeXDRow" onClick ="deleteXDRow()" >Delete</a>'+
                                                '</td>'+
                                            '</tr>'
            );
            xdUpdateData()
        
            $('#xpsuccessdiv').removeClass('d-none')
            $('#amountXD').val(''); 
            $('#remarksXD').val(''); 
           
      
        }
    
    }
    
    
    function deleteXDRow(){
        $('#xdTable').on('click','tr a.removeXDRow',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        xdUpdateData()
        });
    
    }
    
    
    function xdUpdateData(){
    
    var objectXD = [];
    var myAmtXD = 0 ;
    
    $("#xdTable > #xdTbody > tr").each(function () {
            var dateXD = $(this).find('td').eq(0).text();
            var typeXD = $(this).find('td').eq(1).text();
            var remarksXD = $(this).find('td').eq(2).text();
            var amountXD = $(this).find('td').eq(3).text();
      
         
            var listXD = [];
            listXD.push(dateXD,typeXD,remarksXD,amountXD);
            objectXD.push(listXD);
    
         
        });

        var xdJsonData = JSON.stringify(objectXD);
        $( "#xdData" ).val(xdJsonData);
    
        for(var i = 0; i<objectXD.length; i++){
                    var numAmt = objectXD[i]['3'];
                    myAmtXD += parseFloat(numAmt);
            
        }
    
        $('#xdTotalAmount').text(myAmtXD);
        console.log(objectXD);
        getTotalAmount();
        console.log($( "#xdData" ).val());
        
    }
    
    </script>

{{-- ExpenseDetails --}}



{{-- Transportation Details --}}
<script>
function getTransportationData(){
    var dateTD = $('#dateTD').val();
    var typeTD = $('#typeTD').val(); 
    var amountTD = $('#amountTD').val(); 
    var fromTD = $('#fromTD').val(); 
    var toTD = $('#toTD').val(); 
    var remarksTD = $('#remarksTD').val();


    var dateTDChecker = false;
    // var typeTDChecker = false;
    var amountTDChecker = false;
    var fromTDChecker = false;
    var toTDChecker = false;
    var remarksTDChecker = false;


    if(amountTD){
        amountTDChecker = true;
        $('#amountErrTD').text('');

    }else{
        $('#amountErrTD').text('Amount is required!');
        $('#tdsuccessdiv').addClass('d-none');

    }


    if(fromTD){
        fromTDChecker = true;
        $('#fromErrTD').text('');
    }else{
        $('#fromErrTD').text('Destination from is required!');
        $('#tdsuccessdiv').addClass('d-none');

    }

    if(toTD){
        toTDChecker = true;
        $('#toErrTD').text('');
    }else{
        $('#toErrTD').text('Destination to is required!');
        $('#tdsuccessdiv').addClass('d-none');

    }

    if(remarksTD){
        remarksTDChecker = true;
        $('#remarksErrTD').text('');
    }else{
        $('#remarksErrTD').text('Remarks is required!');
        $('#tdsuccessdiv').addClass('d-none');

    }

    if(amountTDChecker && fromTDChecker && toTDChecker && remarksTDChecker){


        $('#tdTable tbody').append('<tr>'+
                                            '<td>'+dateTD+'</td>'+
                                            '<td>'+fromTD+'</td>'+
                                            '<td>'+toTD+'</td>'+
                                            '<td>'+typeTD+'</td>'+
                                            '<td>'+remarksTD+'</td>'+
                                            '<td>'+amountTD+'</td>'+
                                            '<td>'+
                                                '<a class="btn btn-danger removeTDRow" onClick ="deleteTDRow()" >Delete</a>'+
                                            '</td>'+
                                        '</tr>'
        );
        tdUpdateData()
    
        $('#tdsuccessdiv').removeClass('d-none')
        $('#amountTD').val(''); 
        $('#fromTD').val(''); 
        $('#toTD').val(''); 
        $('#remarksTD').val('');
  
    }

}


function deleteTDRow(){
    $('#tdTable').on('click','tr a.removeTDRow',function(e){
    e.preventDefault();
    $(this).closest('tr').remove();
    tdUpdateData()
    });

}


function tdUpdateData(){

var objectTD = [];
var myAmtTD = 0 ;

$("#tdTable > #tdTbody > tr").each(function () {
        var dateTD = $(this).find('td').eq(0).text();
        var fromTD = $(this).find('td').eq(1).text();
        var toTD = $(this).find('td').eq(2).text();
        var typeTD = $(this).find('td').eq(3).text();
        var remarksTD = $(this).find('td').eq(4).text();
        var amountTD = $(this).find('td').eq(5).text();
     
        var listTD = [];
        listTD.push(dateTD,fromTD,toTD,typeTD,remarksTD,amountTD);
        objectTD.push(listTD);

     
    });
    var tdJsonData = JSON.stringify(objectTD);
    $( "#tdData" ).val(tdJsonData);

    for(var i = 0; i<objectTD.length; i++){
                var numAmt = objectTD[i]['5'];
                myAmtTD += parseFloat(numAmt);
        
    }

    $('#tdTotalAmount').text(myAmtTD);
    console.log(objectTD);
    getTotalAmount();

}

</script>




<script>
function getTotalAmount(){
    var getXDSubTotalChecker = false;
    var getTDSubTotalChecker = false;

    var getXDSubTotal = parseFloat($('#xdTotalAmount').text());
    var getTDSubTotal = parseFloat($('#tdTotalAmount').text());

    if (getXDSubTotal){
        getXDSubTotalChecker = true;
    }

     if (getTDSubTotal){
        getTDSubTotalChecker = true;
    }  
    
    
  
if (getXDSubTotalChecker && getTDSubTotalChecker) {
    $('#amount').val(getXDSubTotal+getTDSubTotal);
} else if (getXDSubTotalChecker === true) {

// var getXDSubTotal = (getXDSubTotal).toLocaleString(
// undefined, { minimumFractionDigits: 2 }
// );
$('#amount').val(getXDSubTotal);

} else if (getTDSubTotalChecker === true) {
    $('#amount').val(getTDSubTotal);
} else {
    $('#amount').val(0.00);
}

}
</script>


<script>
    $('#submit-all').on('click',function(){
        if ($.trim($("#reportingManager").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#projectName").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#dateNeeded").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#payeeName").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#amount").val()) <= 0) {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#purpose").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if( ($('#attachmentsTable tbody tr').length) <= 0){
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false; 
        }

        // if ($.trim($("#xdData").val()) === "" || $.trim($("#tdData").val()) === "") {
        // $('#myError').removeClass('d-none');
        // $('#myError').text('Please complete required fields.');
        // return false;
        // }

        

    })
</script>






















{{-- Attachments --}}
<script>
    var main = [];
        $(document).ready(function() {
          $('input[type="file"]').on("change", function() {
            let files = this.files;
            console.log(files);
            console.dir(this.files[0]);
            $('#attachmentsTable tbody tr').remove();  
                for(var i = 0; i<files.length; i++){
                var tmppath = URL.createObjectURL(files[i]);   
                    var semi = [];
                    semi.push(files[i]['name'],files[i]['type'],files[i]['size'],tmppath);
                    main.push(semi);
                    console.log(main);
                                $('#attachmentsTable tbody').append('<tr>'+
                                                '<td>'+files[i]['name']+'</td>'+
                                                '<td>'+files[i]['type']+'</td>'+
                                                // '<td>'+files[i]['size']+'</td>'+
                                                '<td>'+tmppath+'</td>'+
                                                "<td><a href='"+tmppath+"' target='_blank' class='btn btn-secondary'>View</a></td>"+
                                                '</tr>'
                                );
    
                                //add code to copy to public folder in erp-web
                }
          });
        });
        $("#attachmentsTable").on('click', '.btnDelete', function () {
        $(this).closest('tr').remove();
    });
</script>
@endsection





<script>
    function showDetails(id) {
        if (window.XMLHttpRequest) {
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

    function showReference() {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var txt = xmlhttp.responseText.replace("[", "");
                txt = txt.replace("]", "");
                var res = JSON.parse(txt);
                document.getElementById("referenceNumber").value = res.REF;
            }
        }
        xmlhttp.open("GET","/get-reference/RFP", true);
        xmlhttp.send();

        return true;
    }

    function getRMName(sel) {
        var rm_txt = sel.options[sel.selectedIndex].text;
        document.getElementById("RMName").value = rm_txt;
    }
</script>


{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
