@extends('layouts.base')
@section('title', 'Leave Request') 
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

<form action="{{ route('save.leave.post') }}" method="POST">
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

{{-- Row Start --}}
    <div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title">@yield('title')</h3>
            </div>
            <input type="hidden" name="frmName" value="@yield('title')" id="frmName">

                <div class="card-body">
                    <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>
                                                   
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reference Number</label>
                                <input type="text" value="LAF-{{ date("Y") }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
            
                            </div>                            
                        </div>

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
                                 <option value="0" selected>Select Reporting Manager</option>
                                        @foreach ($managers as $mgr )
                                            <option value="{{ $mgr->RMID }}">{{ $mgr->RMName }}</option>
                                        @endforeach
                                </select>
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>
                    </div>

                    <input type="hidden" name="employeeName" id="employeeName">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Employee Name</label>
                                <select class="form-control select2 select2-default" id="employeeID" name="employeeID" data-dropdown-css-class="select2-default" onchange="getEmployeeName(this)" style="width: 100%;">
                                    <option value="0" selected>Select Employee Name</option>
                                    @foreach ($employee as $emp )
                                    <option value="{{ $emp->SysPK_Empl }}">{{ $emp->Name_Empl }}</option>
                                    @endforeach
                                </select>
                            </div>       
            <span class="text-danger">@error('employeeID'){{ $message }}@enderror</span>

                        </div>
                        
                        <input type="hidden" name="mediumofreportName" id="mediumofreportName">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Medium of Report</label>
                                <select class="form-control select2 select2-default" id="mediumofreportid" name="mediumofreportid" data-dropdown-css-class="select2-default" onchange="getmediumofreportName(this)" style="width: 100%;">
                                 <option value="0" selected>Select Medium of Report</option>
                                    @foreach ($mediumofreport as $report )
                                        <option value="{{ $report->id }}">{{ $report->item }}</option>
                                    @endforeach
                                </select>
                            </div>            
            <span class="text-danger">@error('mediumofreportid'){{ $message }}@enderror</span>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Report Time</label>
                                <div class="input-group date" id="datetimepicker75" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"  id="reportTime" name="reportTime" data-target="#datetimepicker75"/>
                                    <div class="input-group-append" data-target="#datetimepicker75" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                    
            <span class="text-danger">@error('reportTime'){{ $message }}@enderror</span>

                            </div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">                                            
                                <label for="purpose">Reason</label> 
                                <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>
                        </div>
                    </div>
            <span class="text-danger">@error('purpose'){{ $message }}@enderror</span>


                    <input type="hidden" name="jsonLeaveData" id="jsonLeaveData">

            <span class="text-danger">@error('jsonLeaveData'){{ $message }}@enderror</span>

            </form>

                {{-- Overtime Details Table Start--}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Leave Details</div>                                          
                                            <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">Add Record</a></div>
                                        </div>                                       
                                    </div> 
                                    <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-bordered " id="leaveTable">
                                            <thead>
                                                <tr class="d-flex text-center" style="font-size: 13px;">
                                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Leave Date</th>
                                                    <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Leave Type</th>
                                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Half Day</th>
                                                    <th class="col-2 text-left px-4" style="position: sticky; top: 0; background: white; ">AM/PM</th>
                                                    <th class="col-2 text-left px-4" style="position: sticky; top: 0; background: white; ">Count</th>
                                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">With Pay?</th>
                                      
                                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="leaveTableBody">
                                                {{-- <tr class="d-flex" style="font-size: 13px;">
                                                    <td class="col-2">7/14/2021</td>
                                                    <td class="col-3">Vacation Leave</td>
                                                    <td class="col-1 text-center p-0 m-0 pt-3"><input type="checkbox" name="" id=""></td>
                                                    <td class="col-2">
                                                        <select class="form-control form-control-sm" >               
                                                            <option value="AM">AM</option>
                                                            <option value="PM">PM</option>
                                                        </select>
                                                    </td>
                                                    <td class="col-2">1</td>
                                                    <td class="col-1 text-center">With Pay</td>
                                                    <td class="col-1 text-center"><button class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button></td>
                                                </tr>  --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                {{-- Overtime Details Table End --}}

                            </div>
                            {{-- Card Body END --}}
                        </form>    
                    </div>
                </div>
            </div>
{{-- Row END --}}





{{-- Modal add start --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Leave Dates</h5>
            <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Added Successfully</div>   
             
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Leave Type</label>
                            <select class="form-control select2 select2-default" id="leavetype" data-dropdown-css-class="select2-default" onchange="getLeaveType(this)" style="width: 100%;">
                                @foreach ($leavetype as $leave )
                                    <option value="{{ $leave->id }}">{{ $leave->item }}</option>
                                @endforeach
                            </select>
                        </div>   
                    </div>
                </div>

                <input type="hidden" name="leaveName" value="Vacation Leave" id="leaveName">

                <input type="hidden" name="jsonLeaveData" id="jsonLeaveData">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Leave Date From</label>
                            <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="leaveDateFrom" data-target="#datetimepicker7"/>
                                <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <span class="text-danger" id="leaveDateFromErr"></span>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Leave Date To</label>
                            <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" id="leaveDateTo" data-target="#datetimepicker8"/>
                                <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <span class="text-danger" id="leaveDateToErr"></span>

                        </div>
                    </div>

                </div>

                <input type="hidden" name="payName" id="payName" value="With Pay">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Pay Type</label>
                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" id="paytype" onchange="getPayType(this)"  style="width: 100%;">
                                <option value="wp">With Pay</option> 
                                <option value="wop">Without Pay</option>
                            </select>
                        </div>   
                    </div>
                </div>
            </div>



            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
            <button type="button" id="addRowBtn" class="btn btn-primary">Add</button>
            </div>
        </div>
        </div>
    </div>
{{-- Modal add end --}}

{{-- Get Reporting Manager Name Start --}}
<script>
    function getReportingManagerName(){
        let rmName = $( "#rmID option:selected" ).text();
        $('#rmName').val(rmName);
        console.log(rmName);
    }

    function getEmployeeName(){
            let employeeName = $( "#employeeID option:selected" ).text();
            $('#employeeName').val(employeeName);
            console.log(employeeName);
        }

    function getmediumofreportName(){
            let mediumofreportName = $( "#mediumofreportid option:selected" ).text();
            $('#mediumofreportName').val(mediumofreportName);
            console.log(mediumofreportName);
        }


    $('.closeBtn').on('click',function(){
        $('#successDiv').addClass('d-none');
        $('#leaveDateFromErr').text('');
        $('#leaveDateToErr').text('');

    });


</script>
{{-- Get Reporting Manager Name End --}}






{{-- Add Leave Details --}}
<script>
    $('#addRowBtn').on('click', function(){
        $('#successDiv').addClass('d-none');
        var leaveDateFrom = $('#leaveDateFrom').val();
        var leaveDateTo = $('#leaveDateTo').val();
        var paytype = $('#paytype').val();
        var leaveName = $('#leaveName').val();
        var payName = $('#payName').val();

        var startChecker = false;
        var endChecker = false;

        if(leaveDateFrom){
            startChecker = true;
            $('#leaveDateFromErr').text('');

        }else{
            $('#leaveDateFromErr').text('Leave Date from is required!');
            $('#successDiv').addClass('d-none');
        }

        if(leaveDateTo){
            endChecker = true;
            $('#leaveDateToErr').text('');

        }else{
            $('#leaveDateToErr').text('Leave Date to is required!');
            $('#successDiv').addClass('d-none');
        }


        if(startChecker && endChecker ){


        var start = new Date(leaveDateFrom)
        var end = new Date(leaveDateTo)

            // While loop
            var newend = end.setDate(end.getDate()+1);
            end = new Date(newend);
            while(start < end){
       
            $("#leaveTable #leaveTableBody").append(`
                <tr class="d-flex" style="font-size: 13px;">
                    <td class="col-2">${start.getMonth() + 1 +'/'+ start.getDate() +'/'+ start.getFullYear()}</td>
                    <td class="col-3">${leaveName}</td>
                    <td class="col-1 text-center p-0 m-0 pt-3"><input type="checkbox" class="checkedbox" ></td>
                    <td class="col-2">
                        <select class="form-control form-control-sm" >               
                            <option value="Wholeday">N/A</option>
                        </select>
                    </td>
                    <td class="col-2">1</td>
                    <td class="col-1 text-center">${payName}</td>
                    <td class="d-none">${paytype}</td>
                    <td class="col-1 text-center"><button class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button></td>
                </tr> 
            `);

            var newDate = start.setDate(start.getDate() + 1);
            start = new Date(newDate);
            }
          


            // Checked the checkbox function
            $('.checkedbox').on('change', function() {
            if ($(this).is(':checked')) {
                // alert("checked");
            $(this).parent().next().children().remove();
            $(this).parent().next().append(`<select class="form-control form-control-sm" >               
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>`);
            $(this).parent().next().next().text('0.5');


            } else {
                // alert("unchecked");
            $(this).parent().next().children().remove();
            $(this).parent().next().append(`<select class="form-control form-control-sm" >               
                                <option value="N/A">N/A</option>
                        </select>`);
            $(this).parent().next().next().text('1');

            }
            });


            $('#successDiv').removeClass('d-none');
            $('#leaveDateFrom').val('');
            $('#leaveDateTo').val('');

        };

        $('.deleteRow').on('click',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        })  

    });




</script>


{{-- Get Leave Type --}}
<script>
     function getLeaveType(){
        let leaveName = $( "#leavetype option:selected" ).text();
            $('#leaveName').val(leaveName);
            console.log(leaveName);
    }


    function getPayType(){
        let payName = $( "#paytype option:selected" ).text();
            $('#payName').val(payName);
            console.log(payName);
    }
</script>


{{-- Submit All --}}
<script>
    $('#submit-all').on('click',function(){
        // alert('test');
        getdatainLeaveTable();
        $('#myError').addClass('d-none');


        if ($.trim($("#rmID").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

        if ($.trim($("#employeeID").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

        if ($.trim($("#mediumofreportid").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

        if ($.trim($("#reportTime").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

        if ($.trim($("#purpose").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

        if ($.trim($("#jsonLeaveData").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Please complete required fields.');
            return false;
        }

    })
</script>



{{-- Get Data in table --}}
<script>
    function getdatainLeaveTable(){
            var leaveData = [];

            $("#leaveTable > #leaveTableBody > tr").each(function () {
            var leaveDate = $(this).find('td').eq(0).text();
            var leaveType = $(this).find('td').eq(1).text();
            // var overtimeDate = $(this).find('td').eq(2).text();
            var leaveHalfDay = $(this).find('td').eq(3).children().val();
            var numDays = $(this).find('td').eq(4).text();
            // var authTotalHrs = $(this).find('td').eq(5).text();
            var leavePayType = $(this).find('td').eq(6).text();

            var listTD = [];
            listTD.push(leaveDate,leaveType,leaveHalfDay,numDays,leavePayType);

            leaveData.push(listTD);
        
            });

            console.log(leaveData);

                var jsonLeaveData = JSON.stringify(leaveData);

                if (jsonLeaveData.length == 2) {
                    $( "#jsonLeaveData" ).val('');
                } else {
                    $( "#jsonLeaveData" ).val(jsonLeaveData);
                }
            
                console.log('this is data of jsonleave',$('#jsonLeaveData').val());

        }
</script>


<script type="text/javascript">
    $(function () {
        $('#datetimepicker75').datetimepicker({
            useCurrent: false,
            icons: {
            time:"fas fa-clock"
            }
        });
        $('#datetimepicker7').datetimepicker({
            format: 'L'
        });
        $('#datetimepicker8').datetimepicker({
            useCurrent: false,
            format: 'L'
        });
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });
    });
</script>


@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
