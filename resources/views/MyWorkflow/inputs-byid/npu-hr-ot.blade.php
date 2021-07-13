@extends('layouts.base')
@section('title', 'Overtime Request') 
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
        window.location.href = "/in-progress";
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

<div class="row">
    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/in-progress" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal"  >Withdraw</button></div>        
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>   
        </div> 
    </div> 
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
                                <input type="text" value="{{ $post[0]->reference }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
            
                            </div>                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Requested Date</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{ $post[0]->request_date }}" readonly/>
                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>         
                        </div>  

   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reporting Manager</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post[0]->reporting_manager }}" readonly >
      
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">                                            
                                <label for="purpose">Purpose</label> 
                                <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" readonly placeholder="">{{ $post[0]->purpose }}</textarea>
    
                            </div>
                        </div>
                    </div>




{{-- Overtime Details Table Start--}}
      <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                    <div class="row">
                        <div class="col" style="font-size:18px; padding-top:5px;">Overtime Details</div>
                    </div>                                       
                </div> 
                <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">

                    
                    <table class="table table-bordered " id="overtimeDetailsTable">
                        <thead>
                            <tr class="d-flex text-center" style="font-size: 13px;">
                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Employee Name</th>
                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Project Name</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Overtime Date</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. OT Hours</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual OT Hours</th>
                                <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                <th class="col-1" style="position: sticky; top: 0; background: white; ">Action</th>
                            </tr>
                        </thead>
                        <tbody id="overtimeDetailsTbody">
                            @foreach ($post as $p )
                                <tr class="d-flex" style="font-size: 13px;">
                                    <td class="col-2">{{ $p->employee_name }}</td>
                                    <td class="col-2">{{ $p->Project_Name }}</td>
                                    <td class="col-1">{{ $p->overtime_date }}</td>

@php
$date = date_create($p->ot_in);
$date = date_format($date,"n/d/Y  h:i A");
@endphp
                                    <td class="col-1">{{ $date }}</td>
@php
$date = date_create($p->ot_out);
$date = date_format($date,"n/d/Y  h:i A");
@endphp
                                    <td class="col-1">{{ $date}}</td>
                                    <td class="col-1">{{ $p->ot_totalhrs }}</td>
@php
$date = date_create($p->ot_in_actual);
$date = date_format($date,"n/d/Y  h:i A");
@endphp  
                                    <td class="col-1">{{ $date }}</td>
@php
$date = date_create($p->ot_out_actual);
$date = date_format($date,"n/d/Y  h:i A");
@endphp  
                                    <td class="col-1">{{ $date}}</td>
                                    <td class="col-1">{{ $p->ot_totalhrs_actual }}</td>
                                    <td class="col-3">{{ $p->purpose }}</td>
                                    <td class="d-none">{{ $p->id }}</td>
                                    <td class="col-1 text-center px-0">
                                        <button class="btn btn-success editTriggerBtn" data-toggle="modal" id="btnID{{ $p->id }}" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
                                        <button disabled class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            @endforeach
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
        </div>
    </div>
</div>
{{-- Row END --}}



{{-- Withdraw Modal Start--}}
    <div class="modal fade"  id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="withdrawModalLabel">Withdraw Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('withdraw.hr') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="withdrawRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                    <input type="hidden" value="@yield('title')" name="frmName">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="withdrawRemarks" id="withdrawRemarks" style="height: 100px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Proceed">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
        </div>
    </div>
{{-- Withdraw Modal End--}}

{{-- Modal Approve --}}
    <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('approved.init') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="approvedRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                    <input type="hidden" value="@yield('title')" name="frmName">
                                    <input type="hidden" name="jsonOTdata" id="jsonOTdata">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="approveRemarks" id="approveRemarks" style="height: 100px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Proceed">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>    
        </div>
        </div>
    </div>
{{-- End Approved Modal --}}



{{-- Submit-all --}}
    <script>
        $('#approveModal').on('click',function(){
            var oTData = [];

            $("#overtimeDetailsTable > #overtimeDetailsTbody > tr").each(function () {
            var employeeName = $(this).find('td').eq(0).text();
            var projectName = $(this).find('td').eq(1).text();
            var overtimeDate = $(this).find('td').eq(2).text();
            var authTimeStart = $(this).find('td').eq(3).text();
            var authTimeEnd = $(this).find('td').eq(4).text();
            var authTotalHrs = $(this).find('td').eq(5).text();
            var actualTimeStart = $(this).find('td').eq(6).text();
            var actualTimeEnd = $(this).find('td').eq(7).text();
            var actualOTHours = $(this).find('td').eq(8).text();
            var purposeTwo = $(this).find('td').eq(9).text();
            var id = $(this).find('td').eq(10).text();
        
            var listTD = [];
            listTD.push(employeeName,projectName,overtimeDate,authTimeStart,authTimeEnd,authTotalHrs,actualTimeStart,actualTimeEnd,actualOTHours,purposeTwo,id);
            oTData.push(listTD);
        
            });

            console.log(oTData);

                var jsonOTdata = JSON.stringify(oTData);

                if (jsonOTdata.length == 2) {
                    $( "#jsonOTdata" ).val('');
                } else {
                    $( "#jsonOTdata" ).val(jsonOTdata);
                }
            
                console.log('this is data of jsonot',$('#jsonOTdata').val());
        })
    </script>
{{-- submit all end --}}

<!-- Modal Update Start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Overtime Details</h5>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Updated Successfully</div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Name</label>
                                    <input type="text" class="form-control" id="projectName" readonly placeholder="">
    
                                </div>                            
                            </div>
                        </div>

                        <div class="row"> 
                    
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Overtime Date</label>
                                    <div class="input-group date" id="datetimepicker41" data-target-input="nearest">
                                    <input type="text" class="form-control" id="overtimeDate" readonly placeholder="">
                                    </div>
                                </div>
                            </div>  

                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Employee Name</label>
                                    <input type="text" class="form-control" id="employeeName" readonly placeholder="">

                                </div>                            
                            </div>                  
                
                        </div>

                        <div class="row">   
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time Start</label>
                                    <div class="input-group date" id="datetimepicker71" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeStart" disabled data-target="#datetimepicker71"/>
                                        <div class="input-group-append" data-target="#datetimepicker71" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
    
                    

                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time End</label>
                                    <div class="input-group date" id="datetimepicker81" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeEnd" disabled data-target="#datetimepicker81"/>
                                        <div class="input-group-append" data-target="#datetimepicker81" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. OT Hrs</label>
                                    <input type="text" class="form-control" id="authTotalHrs" readonly placeholder="">
                                    <span class="text-danger" id="authTotalHrsErr1"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time Start</label>
                                    <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeStart" data-target="#datetimepicker7"/>
                                        <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeStartErr"></span>

                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time End</label>
                                    <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeEnd" data-target="#datetimepicker8"/>
                                        <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeEndErr"></span>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total OT Hrs</label>
                                    <input type="text" class="form-control" id="actualOTHours" readonly placeholder="">
                                    <span class="text-danger" id="actualOTHoursErr"></span>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message-text" id="purposeLabel1"  class="col-form-label">Purpose</label>
                            <textarea class="form-control" id="purposeTwo" disabled rows="3"></textarea>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save">Save changes</button>
                    <input type="hidden" name="btnID" id="btnID">

                </div>
            </div>
        </div>
    </div>
{{-- Modal Update End --}}


{{-- Edit Button --}}
    <script>
        $('.editTriggerBtn').on('click',function(){
        
            var employeeName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var projectName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var overtimeDate = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeEnd = $(this).parent().prev().prev().prev().prev().prev().prev().prev().text();
            var authTotalHrs = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            var actualTimeStart = $(this).parent().prev().prev().prev().prev().prev().text();
            var actualTimeEnd = $(this).parent().prev().prev().prev().prev().text();
            var actualOTHours = $(this).parent().prev().prev().prev().text();
            var purposeTwo = $(this).parent().prev().prev().text();
            var id = $(this).parent().prev().text();

            $('#projectName').val(projectName);
            $('#overtimeDate').val(overtimeDate);
            $('#employeeName').val(employeeName);
            $('#authTimeStart').val(authTimeStart);
            $('#authTimeEnd').val(authTimeEnd);
            $('#authTotalHrs').val(authTotalHrs);
            $('#actualTimeStart').val(actualTimeStart);
            $('#actualTimeEnd').val(actualTimeEnd);
            $('#actualOTHours').val(actualOTHours);
            $('#purposeTwo').val(purposeTwo);
            $('#btnID').val(id);


        })
    </script>
{{-- Edit button end --}}

{{-- close button --}}
    <script>
        $('.closeBtn').on('click',function(){
            // alert('test');
        $('#successDiv').addClass('d-none');

        })
    </script>
{{-- close buttin end --}}

{{-- Date Time Picker --}}
<script type="text/javascript">
    $(function () {

        var authDateStart = 0;
        var authDateEnd = 0;

            $('#datetimepicker7').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
                
            });
            $('#datetimepicker8').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
            });

        $('#datetimepicker7').datetimepicker();
        $('#datetimepicker8').datetimepicker({
            useCurrent: false
        });
        $("#datetimepicker7").on("change.datetimepicker", function (e) {

            start_date = $('#actualTimeStart').val();

            start_date = new Date(start_date)
            authDateStart =start_date;
            console.log(authDateStart);

            var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;

            console.log(total);

            let f = Math.floor(total);
            if(total-f < 0.5){
                total = Math.floor(total)
                console.log(total)
                $('#actualOTHours').val(total);
            } else {
                total = f+0.5;
                console.log(total)
                $('#actualOTHours').val(total);
            }                                 


            $('#datetimepicker8').datetimepicker('minDate', e.date);

        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {

            end_date = $('#actualTimeEnd').val();

            end_date = new Date(end_date)
            authDateEnd =end_date;
            console.log(authDateEnd);
            var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;
            console.log(total);

            let f = Math.floor(total);
            if(total-f < 0.5){
                total = Math.floor(total)
                console.log(total)
                $('#actualOTHours').val(total);

            } else {
                total = f+0.5;
                console.log(total)
                $('#actualOTHours').val(total);
            }

            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });
    });
</script>


{{-- Update / Save Actual Time --}}
<script>
    $('#save').on('click',function(e){
        e.preventDefault();

        var btnID = $('#btnID').val();

        var actualTimeStart = $('#actualTimeStart').val();
        var actualTimeEnd = $('#actualTimeEnd').val();
        var actualOTHours = $('#actualOTHours').val();

        var actualTimeStartChecker  = false;
        var actualTimeEndChecker  = false;

        if(actualTimeStart){
            actualTimeStartChecker = true;
            $('#actualTimeStartErr').text('');

        }else{
            $('#actualTimeStartErr').text('Auth. Time Start is required!');
            $('#successDiv').addClass('d-none');
        }

        if(actualTimeEnd){
            actualTimeEndChecker = true;
            $('#actualTimeEndErr').text('');

        }else{
            $('#actualTimeEndErr').text('Auth. Time End is required!');
            $('#successDiv').addClass('d-none');
        }

        

        if(actualTimeStart && actualTimeEnd){
        $('#btnID'+btnID).parent().prev().prev().prev().text(actualOTHours);
        $('#btnID'+btnID).parent().prev().prev().prev().prev().text(actualTimeEnd);
        $('#btnID'+btnID).parent().prev().prev().prev().prev().prev().text(actualTimeStart);

        $('#successDiv').removeClass('d-none');
        }

    })
</script>
   

@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
