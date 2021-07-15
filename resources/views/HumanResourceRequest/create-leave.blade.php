@extends('layouts.base')
@section('title', 'Leave Request') 
@section('content')
  
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
                                <input type="text" value="LRF-{{ date("Y") }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
            
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
                                 
                                    @foreach ($managers as $mgr )
                                    <option value="{{ $mgr->RMID }}">{{ $mgr->RMName }}</option>
                                @endforeach
                            </select>
                                </select>
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>
                    </div>

                    
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Employee Name</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    @foreach ($employee as $emp )
                                    <option value="{{ $emp->SysPK_Empl }}">{{ $emp->Name_Empl }}</option>
                                    @endforeach
                                </select>
                            </div>                            
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Medium of Report</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    @foreach ($mediumofreport as $report )
                                        <option value="{{ $report->id }}">{{ $report->item }}</option>
                                    @endforeach
    
                                </select>
                            </div>                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Report Time</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
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

                    <input type="hidden" name="jsonOTdata" id="jsonOTdata">

            <span class="text-danger">@error('jsonOTdata'){{ $message }}@enderror</span>
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
             

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
                        </div>
                    </div>

                </div>

                <input type="hidden" name="payName" value="wp" id="payName">


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Pay Type</label>
                            <select class="form-control select2 select2-default" onchange="getPayType(this)" data-dropdown-css-class="select2-default" id="paytype" style="width: 100%;">
                                <option value="wp">With Pay</option> 
                                <option value="wop">Without Pay</option>
                            </select>
                        </div>   
                    </div>
                </div>
            </div>



            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="addRowBtn" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>
{{-- Modal add end --}}








{{-- Add Leave Details --}}
<script>
    $('#addRowBtn').on('click', function(){
        var leaveDateFrom = $('#leaveDateFrom').val();
        var leaveDateTo = $('#leaveDateTo').val();
        var payID = $('#paytype').val();
        var payName = $('#payName').val();

        var leaveName = $('#leaveName').val();
        var leaveID = $('#leavetype').val();
        
        
        var start = new Date(leaveDateFrom)
        var end = new Date(leaveDateTo)

            // While loop
            var newend = end.setDate(end.getDate()+1);
            end = new Date(newend);
            while(start < end){
       
            $("#leaveTable #leaveTableBody").append(`
                <tr class="d-flex" style="font-size: 13px;">
                    <td class="col-2">${start.getMonth() + 1 +'/'+ start.getDate() +'/'+ start.getFullYear()}</td>
                    <td class="d-none">${leaveID}</td>
                    <td class="d-none">${payID}</td>
                    <td class="col-3">${leaveName}</td>
                    <td class="col-1 text-center p-0 m-0 pt-3"><input type="checkbox" class="checkedbox" ></td>
                    <td class="col-2">
                        <select class="form-control form-control-sm" >               
                            <option value="N/A">N/A</option>
                        </select>
                    </td>
                    <td class="col-2">1</td>
                    <td class="col-1 text-center">${payName}</td>
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
                                <option value="Wholeday">N/A</option>
                        </select>`);
            $(this).parent().next().next().text('1');

            }
            });

    })


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
        alert('test');
        getdatainLeaveTable();
    })
</script>



{{-- Get Data in table --}}
<script>
    function getdatainLeaveTable(){
            var leaveData = [];

            $("#leaveTable > #leaveTableBody > tr").each(function () {
            var leaveDate = $(this).find('td').eq(0).text();
            var leaveID = $(this).find('td').eq(1).text();
            var payID = $(this).find('td').eq(2).text();
            var leaveName = $(this).find('td').eq(3).text();
            var leaveHalfday = $(this).find('td').eq(4).children().val();
            var numDays = $(this).find('td').eq(5).text();
            var payName = $(this).find('td').eq(6).text();
            // var employeeID = $(this).find('td').eq(7).text();
            // var projectID = $(this).find('td').eq(8).text();
            // var clientID = $(this).find('td').eq(9).text();
            // var clientName = $(this).find('td').eq(10).text();
        
            var listTD = [];
            listTD.push(leaveDate,leaveID,payID,leaveName,leaveHalfday,numDays,payName);
            // listTD.push(authTimeStart);

            leaveData.push(listTD);
        
            });

            console.log(leaveData);

                // var jsonOTdata = JSON.stringify(oTData);

                // if (jsonOTdata.length == 2) {
                //     $( "#jsonOTdata" ).val('');
                // } else {
                //     $( "#jsonOTdata" ).val(jsonOTdata);
                // }
            
                // console.log('this is data of jsonot',$('#jsonOTdata').val());

        }
</script>


<script type="text/javascript">
    $(function () {
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