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

{{-- Check if the status in first level --}}
    @if ( $actualSignData->STATUS  == "For Clarification")
        <input type="hidden" name="" id="statusChecker" value="true">
    @else
        <input type="hidden"  id="statusChecker" >
    @endif
{{-- Check if the status in first level --}}


{{-- Initiator Check --}}
    @if ($actualSignData->INITID == session('LoggedUser'))
        
{{-- Modal Add OT Details Start --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Overtime Details</h5>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Added Successfully</div>                                             
                
                        <div class="row">
                            <input type="hidden" name="clientID" id="clientID">
                            <input type="hidden" name="clientName" id="clientName">
                            <input type="hidden" name="projectName" id="projectName">
                            <input type="hidden" name="employeeName" id="employeeName">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Name</label>
                                    <select class="form-control select2 select2-default" id="projectID" data-dropdown-css-class="select2-default"  onchange="getProjectName(this)">
                                        <option value="0">Select Project Name</option>
                                        @foreach ($project as $prj )
                                            <option value="{{ $prj->project_id }}">{{ $prj->project_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="projectIDErr"></span>                                                  
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="row"> 


                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Overtime Date</label>
                                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="overtimeDate" data-target="#datetimepicker4"/>
                                        <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="overtimeDateErr"></span>                                                  
                                </div>
                            </div>  

                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Employee Name</label>
                                    <select class="form-control select2 select2-default" id="employeeID" data-dropdown-css-class="select2-default" onchange="getEmployeeName(this)">
                                        <option value="0">Select Employee Name</option>
                                        @foreach ($employee as $emp )
                                            <option value="{{ $emp->SysPK_Empl }}">{{ $emp->Name_Empl }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="employeeIDErr"></span>                                                  

                                </div>                            
                            </div>
                            
                 
                        </div>
    
                        <div class="row">   
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time Start</label>
                                    <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeStart" data-target="#datetimepicker7"/>
                                        <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeStartErr"></span>                                                  

                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time End</label>
                                    <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeEnd" data-target="#datetimepicker8"/>
                                        <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeEndErr"></span>                                                  

                                </div>
                            </div>     
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. OT Hrs</label>
                                    <input type="text" class="form-control" id="authTotalHrs" readonly placeholder="">
                                    <span class="text-danger" id="authTotalHrsErr"></span>                                                                                   
                                </div>
                            </div>
                        </div>

                        <div class="row tdid">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time Start</label>
                                    <div class="input-group date" id="datetimepicker77" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeStart" data-target="#datetimepicker77"/>
                                        <div class="input-group-append" data-target="#datetimepicker77" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeStartErr"></span>

                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time End</label>
                                    <div class="input-group date" id="datetimepicker88" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeEnd" data-target="datetimepicker88"/>
                                        <div class="input-group-append" data-target="#datetimepicker88" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeEndErr"></span>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total OT Hrs</label>
                                    <input type="text" class="form-control" id="actualOThrs" readonly placeholder="">
                                    <span class="text-danger" id="actualOThrsErr"></span>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message-text" id="purposeLabel" class="col-form-label">Purpose</label>
                            <textarea class="form-control" id="purposeTwo" rows="3"></textarea>
                            <span class="text-danger" id="purposeTwoErr"></span>                                                  
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="insertOTDetails">Add</button>
                </div>
            </div>
        </div>
    </div>
{{-- Modal Add OT Details End --}}


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
                    <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv1">Updated Successfully</div>   

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Name</label>
                                    <select class="form-control select2 select2-default" id="projectID1" data-dropdown-css-class="select2-default"  onchange="getProjectName1(this)">
                                
                                        @foreach ($project as $prj )
                                            <option value="{{ $prj->project_id }}">{{ $prj->project_name }}</option>
                                        @endforeach
                                    </select>

                                </div>                            
                            </div>
                        </div>

                        <div class="row"> 
                    
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Overtime Date</label>
                                    <div class="input-group date" id="datetimepicker41" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="overtimeDate1" data-target="#datetimepicker41"/>
                                        <div class="input-group-append" data-target="#datetimepicker41" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="overtimeDateErr1"></span>
                                </div>
                            </div>  

                            <input type="hidden" name="employeeName1" id="employeeName1">
                            <input type="hidden" name="projectName1" id="projectName1">

                            <input type="hidden" name="clientID1" id="clientID1">
                            <input type="hidden" name="clientName1" id="clientName1">

                            


                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Employee Name</label>
                                    <select class="form-control select2 select2-default" id="employeeID1" data-dropdown-css-class="select2-default" onchange="getEmployeeName1(this)">
                                        @foreach ($employee as $emp )
                                            <option value="{{ $emp->SysPK_Empl }}">{{ $emp->Name_Empl }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="employeeIDErr1"></span>

                                </div>                            
                            </div>                  
                
                        </div>

                        <div class="row">   
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time Start</label>
                                    <div class="input-group date" id="datetimepicker71" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeStart1" data-target="#datetimepicker71"/>
                                        <div class="input-group-append" data-target="#datetimepicker71" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeStartErr1"></span>   
                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. Time End</label>
                                    <div class="input-group date" id="datetimepicker81" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="authTimeEnd1" data-target="#datetimepicker81"/>
                                        <div class="input-group-append" data-target="#datetimepicker81" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="authTimeEndErr1"></span>   
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Auth. OT Hrs</label>
                                    <input type="text" class="form-control" id="authTotalHrs1" readonly placeholder="">
                                    <span class="text-danger" id="authTotalHrsErr1"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row tdid">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time Start</label>
                                    <div class="input-group date" id="datetimepicker711" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeStart1" data-target="#datetimepicker711"/>
                                        <div class="input-group-append" data-target="#datetimepicker711" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeStartErr1"></span>

                                </div> 
                            </div>
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Actual Time End</label>
                                    <div class="input-group date" id="datetimepicker811" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="actualTimeEnd1" data-target="#datetimepicker811"/>
                                        <div class="input-group-append" data-target="#datetimepicker811" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="actualTimeEndErr1"></span>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Total OT Hrs</label>
                                    <input type="text" class="form-control" id="actualOTHrs1" readonly placeholder="">
                                    <span class="text-danger" id="actualOTHoursErr1"></span>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message-text" id="purposeLabel1"  class="col-form-label">Purpose</label>
                            <textarea class="form-control" id="purposeTwo1"  rows="3"></textarea>
                            <span class="text-danger" id="purposeTwoErr1"></span>


                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save">Save changes</button>
                    <input type="hidden" name="btnID1" id="btnID1">

                </div>
            </div>
        </div>
    </div>
{{-- Modal Update End --}}


{{-- Row Start --}}
    <div class="row">
        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                    <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" id="replyModalHR" data-target="#replyModal">Reply</button></div>     
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled >Clarify</button></div>                    
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal" >Withdraw</button></div>        
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>   
            </div> 
        </div> 
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                        <!-- Modal Reply-->
                        <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark" >
                                <h5 class="modal-title" id="replyModalLabel">Reply Request </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>

                                {{-- Konrad --}}
                                <form action="{{ route('reply.hr') }}" method="POST">
                                    @csrf
                                <div class="modal-body">
                                <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>
                                <input type="hidden" name="jsonOTdata" id="jsonOTdata">

                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="approvedRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        <input type="hidden" name="main_id"  value="{{ $post[0]->main_id }}">
                                                        <input type="hidden" value="@yield('title')" name="frmName">
                                                        <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" id="submit-all" value="Proceed">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                            </div>
                        </div>
                        {{-- End Reply Modal --}}

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
                                    <input type="hidden" name="rmName" id="rmName" value="{{ $actualSignData->REPORTING_MANAGER }}">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Reporting Manager</label>
                                    <select class="form-control select2 select2-default" name="rmID" id="rmID" data-dropdown-css-class="select2-default" onchange="getReportingManagerName(this)">
                                        <option value="{{ $actualSignData->RM_ID }}" selected>{{  $actualSignData->REPORTING_MANAGER }}</option>
                                        @foreach ($managers as $mgr )
                                            <option value="{{ $mgr->RMID }}">{{ $mgr->RMName }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                                </div>                            
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">                                            
                                    <label for="purpose">Purpose</label> 
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4"  placeholder="">{{ $post[0]->remarks }}</textarea>
                                    <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>
    <span class="text-danger">@error('jsonOTdata'){{ $message }}@enderror</span>

                    </form>




        {{-- Overtime Details Table Start--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gray">
                        <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                            <div class="row">
                                <div class="col" style="font-size:18px; padding-top:5px;">Overtime Details</div>
                                <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</a></div>
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
                                        <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                        <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                        <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual OT Hours</th>
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

                                            @if (!empty($p->ot_in_actual) && !empty($p->ot_out_actual) && !empty($p->ot_totalhrs_actual))
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
                                                <script>$('.tdid').addClass('filler');</script>

                                            @else
                                                <td class="d-none">{{ $p->ot_in_actual }}</td>
                                                <td class="d-none">{{ $p->ot_out_actual }}</td>
                                                <td class="d-none">{{ $p->ot_totalhrs_actual }}</td>
                                                <script>$('.tdid').addClass('d-none');</script>
                                            @endif
                                            <td class="col-3">{{ $p->purpose }}</td>
                                            <td class="d-none">{{ $p->employee_id }}</td>
                                            <td class="d-none">{{ $p->PRJID }}</td>
                                            <td class="d-none">{{ $p->cust_id }}</td>
                                            <td class="d-none">{{ $p->cust_name }}</td>
                                            <td class="d-none">{{ $p->id }}</td>
                                            <td class="col-1 text-center px-0">
                                                <button  class="btn btn-success editRowBtn" id="newID{{ $p->id }}" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-edit"></i></button>
                                                <button  class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
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
{{-- Row End --}}


    @else

{{-- Approver Read Only Start--}}
    <div class="row">
        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
       
                    @if ( $recipientCheck == session('LoggedUser'))
                        <div class="col-md-1 float-left"><a href="/in-progress" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" data-target="#approveModalCopy" >Reply</button></div>     
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right" disabled >Withdraw</button></div>
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled >Reject</button></div>      
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled >Approve</button></div>   
                    @else
                        <div class="col-md-1 float-left"><a href="/in-progress" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right" disabled >Withdraw</button></div>
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" data-toggle="modal" data-target="#rejectedModal" >Reject</button></div>      
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>   
                    @endif        

            </div> 
        </div> 


        {{-- Modal Approve --}}
            <div class="modal fade"  id="approveModalCopy" tabindex="-1" role="dialog" aria-labelledby="approveModalCopy" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark" >
                    <h5 class="modal-title" id="approveModalCopyLabel">Reply Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('approvedApprvr.hr') }}" method="POST">
                        @csrf
                    <div class="modal-body">
                    <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">                     
                                    <label for="approvedRemarks">Remarks</label>
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                            <input type="hidden" value="@yield('title')" name="frmName">
                                            <textarea class="form-control" placeholder="Leave a comment here" name="approveRemarks" id="approveRemarksCopy" style="height: 100px"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" id="ProceedCopy"value="Proceed">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>

                </div>
                </div>
            </div>

            <script>
                $('#ProceedCopy').on('click',function(){
                    if ($.trim($("#approveRemarksCopy").val()) === "") {
                    $('#myError').removeClass('d-none');
                    $('#myError').text('Reply remarks is required.');
                    return false;
                    }
                })
            </script>
        {{-- End Approved Modal --}}


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
                    <form action="{{ route('approvedApprvr.hr') }}" method="POST">
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


        {{-- Modal Reject --}}
            <div class="modal fade"  id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark" >
                    <h5 class="modal-title" id="rejectedModalLabel">Reject Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('rejectedApprvr.hr') }}" method="POST">
                        @csrf
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">                     
                                    <label for="rejectedRemarks">Remarks</label>
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                            <input type="hidden" value="@yield('title')" name="frmName">
                                            <textarea class="form-control" placeholder="Leave a comment here" name="rejectedRemarks" id="rejectedRemarks" style="height: 100px"></textarea>
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
        {{-- End Reject Modal --}}


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
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" readonly placeholder="">{{ $post[0]->remarks }}</textarea>
        
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
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual OT Hours</th>
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
    
                                        @if (!empty($p->ot_in_actual) && !empty($p->ot_out_actual) && !empty($p->ot_totalhrs_actual))
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
                                        @else
                                            <script>
                                                $('.tdid').addClass('d-none');
                                            </script>
                                        @endif
    
                                        <td class="col-3">{{ $p->purpose }}</td>
                                        <td class="col-1 text-center px-0">
                                            <button disabled class="btn btn-success"><i class="fas fa-edit"></i></button>
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
                </form>    
            </div>
        </div>
    </div>
    {{-- Row END --}}
    
    
{{-- Approver Read Only Start--}}

    @endif

{{-- Initiator Check End --}}




{{-- Delete ROw Start --}}
    <script>
        $('.deleteRow').on('click',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        })
    </script>
{{-- Delete Row End --}}



{{-- get data to json start --}}
    <script>
        $('#replyModalHR').on('click', function(){
            getdatainOTTable();
        })
    </script>
{{-- get data to json End --}}


{{-- subit all data start --}}
    <script>
        $('#submit-all').on('click',function(e){
            // e.preventDefault();
            getdatainOTTable();

            if ($.trim($("#rmID").val()) === "0") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Reporting Manager is Required.');
            return false;
            }

            if ($.trim($("#purpose").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Purpose is Required.');
            return false;
            }

            if ($.trim($("#jsonOTdata").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Overtime Details is required.');
            return false;
            }

            if ($.trim($("#replyRemarks").val()) === "") {
            $('#myError').removeClass('d-none');
            $('#myError').text('Reply remarks is required.');
            return false;
            }

            // console.log("test");


        })
    </script>

{{-- subit all data end --}}


{{-- Get all data in table Start --}}
    <script>
        function getdatainOTTable(){
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
            var employeeID = $(this).find('td').eq(10).text();
            var projectID = $(this).find('td').eq(11).text();
            var clientID = $(this).find('td').eq(12).text();
            var clientName = $(this).find('td').eq(13).text();
            var btnID = $(this).find('td').eq(14).text();


            var listTD = [];
            listTD.push(employeeName,projectName,overtimeDate,authTimeStart,authTimeEnd,authTotalHrs,actualTimeStart,actualTimeEnd,actualOTHours,purposeTwo,employeeID,projectID,clientID,clientName,btnID);
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

        }
    </script>
{{-- Get all data in table End --}}


{{-- close modal start --}}
    <script>
        $('.closeBtn').on('click', function(){
            $('#successDiv').addClass('d-none');
            $('#successDiv1').addClass('d-none');
        })

    </script>
{{-- close modal end --}}


{{-- Add OT Details Start--}}
    <script>
        function getEmployeeName(){
        let employeeName = $( "#employeeID option:selected" ).text();
        $('#employeeName').val(employeeName);
        console.log(employeeName);
    }

        function getProjectName(){
                let projectName = $( "#projectID option:selected" ).text();
                let projectID = $( "#projectID").val();

                $('#projectName').val(projectName);
                console.log(projectName);
                console.log(projectID);

                $.ajax({
                type: 'GET',
                url: '/getClientNameAnd/' + projectID,
                success: function (response) {
                var response = JSON.parse(response);

                response.forEach(element => {
                        console.log(element['clientID']);
                        console.log(element['clientName']);

                        $('#clientID').val(element['clientID'])
                        $('#clientName').val(element['clientName'])

                    });

                    }
                }); 
                
                
            }

        var btnID = 0;
        // Add data function //Set Data
        $('#insertOTDetails').on('click', function(e){
            e.preventDefault();
            btnID += 1;
            console.log(btnID)
            
            var employeeID = $('#employeeID').val();
            var employeeName = $('#employeeName').val();
            var projectID = $('#projectID').val();
            var projectName = $('#projectName').val();
            var overtimeDate = $('#overtimeDate').val();
            var authTimeStart = $('#authTimeStart').val();
            var authTimeEnd = $('#authTimeEnd').val();
            var authTotalHrs = $('#authTotalHrs').val();


            var actualTimeStart = $('#actualTimeStart').val();
            var actualTimeEnd = $('#actualTimeEnd').val();
            var actualOThrs = $('#actualOThrs').val();

            
            var purposeTwo = $('#purposeTwo').val();
            var clientID = $('#clientID').val();
            var clientName = $('#clientName').val();

            var statusChecker = $('#statusChecker').val();




            if (statusChecker) {
                

                var employeeIDChecker = false;
                var projectIDChecker = false;
                var overtimeDateChecker = false;
                var authTimeStartChecker = false;
                var authTimeEndChecker = false;
                var purposeTwoChecker = false;

            // Validation
                if(employeeID === "0"){
                    $('#employeeIDErr').text('Employee Name is required!');
                    $('#successDiv').addClass('d-none')
                }else{
                    employeeIDChecker = true;
                    $('#employeeIDErr').text('');
                }

                if(projectID === "0"){
                    $('#projectIDErr').text('Project Name is required!');
                    $('#successDiv').addClass('d-none')
                }else{
                    projectIDChecker = true;
                    $('#projectIDErr').text('');
                }

                if(overtimeDate){
                    overtimeDateChecker = true;
                    $('#overtimeDateErr').text('');

                }else{
                    $('#overtimeDateErr').text('Overtime Date is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(authTimeStart){
                    authTimeStartChecker = true;
                    $('#authTimeStartErr').text('');

                }else{
                    $('#authTimeStartErr').text('Auth. Time Start is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(authTimeEnd){
                    authTimeEndChecker = true;
                    $('#authTimeEndErr').text('');

                }else{
                    $('#authTimeEndErr').text('Auth. Time End is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(purposeTwo){
                    purposeTwoChecker = true;
                    $('#purposeTwoErr').text('');

                }else{
                    $('#purposeTwoErr').text('Purpose is required!');
                    $('#successDiv').addClass('d-none');
                }


                // Insert Data to table
                if(employeeIDChecker && projectIDChecker && overtimeDateChecker && authTimeStartChecker && authTimeEndChecker && purposeTwoChecker){
                $("#overtimeDetailsTable #overtimeDetailsTbody").append(`
                    <tr class="d-flex" style="font-size: 13px;">
                        <td class="col-2">${employeeName}</td>
                        <td class="col-2">${projectName}</td>
                        <td class="col-1">${overtimeDate}</td>
                        <td class="col-1">${authTimeStart}</td>
                        <td class="col-1">${authTimeEnd}</td>
                        <td class="col-1">${authTotalHrs}</td>
                        <td class="col-1 d-none"></td>
                        <td class="col-1 d-none"></td>
                        <td class="col-1 d-none"></td>
                        <td class="col-3">${purposeTwo}</td>
                        <td class="col-1 d-none">${employeeID}</td>
                        <td class="col-1 d-none">${projectID}</td>
                        <td class="col-1 d-none">${clientID}</td>
                        <td class="col-1 d-none">${clientName}</td>
                        <td class="col-1 d-none">${btnID}</td>
                        <td class="col-1 text-center px-0">
                            <button  class="btn btn-success editRowBtnCopy" id="newID${btnID}" data-toggle="modal" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
                            <button  class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `);

                $('#successDiv').removeClass('d-none');
                $('#overtimeDate').val('');
                $('#authTimeStart').val('');
                $('#authTimeEnd').val('');
                $('#authTotalHrs').val('');
                $('#purposeTwo').val('');

                };



            } else {

                var employeeIDChecker = false;
                var projectIDChecker = false;
                var overtimeDateChecker = false;
                var authTimeStartChecker = false;
                var authTimeEndChecker = false;
                var purposeTwoChecker = false;
                var actualTimeStartChecker = false;
                var actualTimeEndChecker = false;


                // Validation
                if(employeeID === "0"){
                    $('#employeeIDErr').text('Employee Name is required!');
                    $('#successDiv').addClass('d-none')
                }else{
                    employeeIDChecker = true;
                    $('#employeeIDErr').text('');
                }

                if(projectID === "0"){
                    $('#projectIDErr').text('Project Name is required!');
                    $('#successDiv').addClass('d-none')
                }else{
                    projectIDChecker = true;
                    $('#projectIDErr').text('');
                }

                if(overtimeDate){
                    overtimeDateChecker = true;
                    $('#overtimeDateErr').text('');

                }else{
                    $('#overtimeDateErr').text('Overtime Date is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(authTimeStart){
                    authTimeStartChecker = true;
                    $('#authTimeStartErr').text('');

                }else{
                    $('#authTimeStartErr').text('Auth. Time Start is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(authTimeEnd){
                    authTimeEndChecker = true;
                    $('#authTimeEndErr').text('');

                }else{
                    $('#authTimeEndErr').text('Auth. Time End is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(actualTimeStart){
                    actualTimeStartChecker = true;
                    $('#actualTimeStartErr').text('');

                }else{
                    $('#actualTimeStartErr').text('Actual Time End is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(actualTimeEnd){
                    actualTimeEndChecker = true;
                    $('#actualTimeEndErr').text('');

                }else{
                    $('#actualTimeEndErr').text('Actual Time End is required!');
                    $('#successDiv').addClass('d-none');
                }

                if(purposeTwo){
                    purposeTwoChecker = true;
                    $('#purposeTwoErr').text('');

                }else{
                    $('#purposeTwoErr').text('Purpose is required!');
                    $('#successDiv').addClass('d-none');
                }








                // Insert Data to table
                if(employeeIDChecker && projectIDChecker && overtimeDateChecker && authTimeStartChecker && authTimeEndChecker && purposeTwoChecker && actualTimeEndChecker && actualTimeStartChecker){
                $("#overtimeDetailsTable #overtimeDetailsTbody").append(`
                    <tr class="d-flex" style="font-size: 13px;">
                        <td class="col-2">${employeeName}</td>
                        <td class="col-2">${projectName}</td>
                        <td class="col-1">${overtimeDate}</td>
                        <td class="col-1">${authTimeStart}</td>
                        <td class="col-1">${authTimeEnd}</td>
                        <td class="col-1">${authTotalHrs}</td>
                        <td class="col-1">${actualTimeStart}</td>
                        <td class="col-1">${actualTimeEnd}</td>
                        <td class="col-1">${actualOThrs}</td>
                        <td class="col-3">${purposeTwo}</td>
                        <td class="col-1 d-none">${employeeID}</td>
                        <td class="col-1 d-none">${projectID}</td>
                        <td class="col-1 d-none">${clientID}</td>
                        <td class="col-1 d-none">${clientName}</td>
                        <td class="col-1 d-none">${btnID}</td>
                        <td class="col-1 text-center px-0">
                            <button  class="btn btn-success editRowBtnCopy" id="newID${btnID}" data-toggle="modal" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
                            <button  class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `);

                $('#successDiv').removeClass('d-none');
                $('#overtimeDate').val('');
                $('#authTimeStart').val('');
                $('#authTimeEnd').val('');
                $('#authTotalHrs').val('');
                $('#purposeTwo').val('');
                $('#actualTimeStart').val('');
                $('#actualTimeEnd').val('');
                $('#actualOThrs').val('');


                };


            }


     


        $('.editRowBtnCopy').on('click',function(e){
            e.preventDefault();


            e.preventDefault();
            var employeeName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var projectName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var overtimeDate = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeEnd = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTotalHrs = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualTimeEnd = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualOTHours = $(this).parent().prev().prev().prev().prev().prev().prev().prev().text();
            var purposeTwo = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            var employeeID = $(this).parent().prev().prev().prev().prev().prev().text();
            var projectID = $(this).parent().prev().prev().prev().prev().text();
            var clientID = $(this).parent().prev().prev().prev().text();
            var clientName = $(this).parent().prev().prev().text();
            var btnID = $(this).parent().prev().text();

            const myvar = [];
                myvar.push(
                    employeeName,
                    projectName,
                    overtimeDate,
                    authTimeStart,
                    authTimeEnd,
                    authTotalHrs,
                    actualTimeStart,
                    actualTimeEnd,
                    actualOTHours,
                    purposeTwo,

                    employeeID,
                    projectID,

                    clientID,
                    clientName,
                    btnID
                ); 
            console.log(myvar)

            $('#overtimeDate1').val(overtimeDate);
            $('#employeeID1').val(employeeID).select2();
            $('#projectID1').val(projectID).select2();
            $('#authTimeStart1').val(authTimeStart);
            $('#authTimeEnd1').val(authTimeEnd);
            $('#authTotalHrs1').val(authTotalHrs);
            $('#actualTimeStart1').val(actualTimeStart);
            $('#actualTimeEnd1').val(actualTimeEnd);
            $('#actualOTHours1').val(actualOTHours);
            $('#purposeTwo1').val(purposeTwo);
            $('#employeeName1').val(employeeName);
            $('#projectName1').val(projectName);
            $('#clientID1').val(clientID);
            $('#clientName1').val(clientName);
            $('#btnID1').val(btnID);

            
        })


        $('.deleteRow').on('click',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        })

        });

        </script>

{{-- Add OT Details End --}}


{{-- save Start --}}
    <script>
        $('#save').on('click',function(e){
            e.preventDefault();

            // alert('test');

            var overtimeDate    = $('#overtimeDate1').val();
            var employeeID      = $('#employeeID1').val();
            var projectID       = $('#projectID1').val();
            var authTimeStart   = $('#authTimeStart1').val();
            var authTimeEnd     = $('#authTimeEnd1').val();
            var authTotalHrs    = $('#authTotalHrs1').val();
            var actualTimeStart = $('#actualTimeStart1').val();
            var actualTimeEnd   = $('#actualTimeEnd1').val();
            var actualOTHours   = $('#actualOTHours1').val();
            var purposeTwo      = $('#purposeTwo1').val();
            var employeeName    = $('#employeeName1').val();
            var projectName     = $('#projectName1').val();
            var clientID        = $('#clientID1').val();
            var clientName      = $('#clientName1').val();
            var btnID           = $('#btnID1').val();

            const myvar = [];

            myvar.push(
                    overtimeDate,
                    employeeID,
                    projectID,
                    authTimeStart,
                    authTimeEnd,
                    authTotalHrs,
                    actualTimeStart,
                    actualTimeEnd,
                    actualOTHours,
                    purposeTwo,
                    employeeName,
                    projectName,
                    clientID,
                    clientName,
                    btnID
                    ); 
            console.log(myvar)

        var overtimeDateChecker = false;
        var authTimeStartChecker = false;
        var authTimeEndChecker = false;
        var purposeTwoChecker = false;

        if(overtimeDate){
            overtimeDateChecker = true;
            $('#overtimeDateErr1').text('');

        }else{
            $('#overtimeDateErr1').text('Overtime Date is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(authTimeStart){
            authTimeStartChecker = true;
            $('#authTimeStartErr1').text('');

        }else{
            $('#authTimeStartErr1').text('Auth. Time Start is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(authTimeEnd){
            authTimeEndChecker = true;
            $('#authTimeEndErr1').text('');

        }else{
            $('#authTimeEndErr1').text('Auth. Time End is required!');
            $('#successDiv1').addClass('d-none');
        }

        if(purposeTwo){
            purposeTwoChecker = true;
            $('#purposeTwoErr1').text('');

        }else{
            $('#purposeTwoErr1').text('Purpose is required!');
            $('#successDiv1').addClass('d-none');
        }

            // Insert Data to table
            if(overtimeDateChecker && authTimeStartChecker && authTimeEndChecker && purposeTwoChecker){

                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(employeeName);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(projectName);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(overtimeDate);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(authTimeStart);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(authTimeEnd);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(authTotalHrs);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(actualTimeStart);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text(actualTimeEnd);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().prev().prev().text(actualOTHours);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().prev().text(purposeTwo);
                $('#newID'+btnID).parent().prev().prev().prev().prev().prev().text(employeeID);
                $('#newID'+btnID).parent().prev().prev().prev().prev().text(projectID);
                $('#newID'+btnID).parent().prev().prev().prev().text(clientID);
                $('#newID'+btnID).parent().prev().prev().text(clientName);
                


                $('#successDiv1').removeClass('d-none');
                $('#overtimeDate1').val('');
                $('#authTimeStart1').val('');
                $('#authTimeEnd1').val('');
                $('#authTotalHrs1').val('');
                $('#purposeTwo1').val('');


            }

        })


    </script>
{{-- save End --}}


{{-- Edit Row Start --}}
    <script>
            $('.editRowBtn').on('click',function(e){
            e.preventDefault();
            var employeeName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var projectName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var overtimeDate = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTimeEnd = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var authTotalHrs = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualTimeEnd = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var actualOTHours = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
            var purposeTwo = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            var employeeID = $(this).parent().prev().prev().prev().prev().prev().text();
            var projectID = $(this).parent().prev().prev().prev().prev().text();
            var clientID = $(this).parent().prev().prev().prev().text();
            var clientName = $(this).parent().prev().prev().text();
            var btnID = $(this).parent().prev().text();

            const myvar = [];
                myvar.push(
                    employeeName,
                    projectName,
                    overtimeDate,
                    authTimeStart,
                    authTimeEnd,
                    authTotalHrs,
                    actualTimeStart,
                    actualTimeEnd,
                    actualOTHours,
                    purposeTwo,

                    employeeID,
                    projectID,

                    clientID,
                    clientName,
                    btnID
                ); 
            console.log(myvar)

            $('#overtimeDate1').val(overtimeDate);
            $('#employeeID1').val(employeeID).select2();
            $('#projectID1').val(projectID).select2();
            $('#authTimeStart1').val(authTimeStart);
            $('#authTimeEnd1').val(authTimeEnd);
            $('#authTotalHrs1').val(authTotalHrs);
            $('#actualTimeStart1').val(actualTimeStart);
            $('#actualTimeEnd1').val(actualTimeEnd);
            $('#actualOTHrs1').val(actualOTHours);
            $('#purposeTwo1').val(purposeTwo);
            $('#employeeName1').val(employeeName);
            $('#projectName1').val(projectName);
            $('#clientID1').val(clientID);
            $('#clientName1').val(clientName);
            $('#btnID1').val(btnID);
            
 

        })
    </script>
{{-- Edit Row End --}}


{{-- Auth Time Date Start --}}
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

            $('#datetimepicker77').datetimepicker({
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

            $('#datetimepicker88').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
            });

            $('#datetimepicker71').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
                
            });
            $('#datetimepicker81').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
            });

            $('#datetimepicker711').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
                
            });
            $('#datetimepicker811').datetimepicker({
                stepping: 5,
                useCurrent: false,
                icons: {
                            time:"fas fa-clock"
                        }
            });
            


            $("#datetimepicker7").on("change.datetimepicker", function (e) {
                // alert($('#authTimeStart').val());
                start_date = $('#authTimeStart').val();

                start_date = new Date(start_date)
                authDateStart =start_date;
                console.log(authDateStart);

                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;

                console.log(total);
            
                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#authTotalHrs').val(total);
                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#authTotalHrs').val(total);
                }                                 
        

                $('#datetimepicker8').datetimepicker('minDate', e.date);
            
            });

            $("#datetimepicker8").on("change.datetimepicker", function (e) {
                end_date = $('#authTimeEnd').val();

                end_date = new Date(end_date)
                authDateEnd =end_date;
                console.log(authDateEnd);
                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;
                console.log(total);

                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#authTotalHrs').val(total);

                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#authTotalHrs').val(total);
                }
                
                $('#datetimepicker7').datetimepicker('maxDate', e.date);
            });


            $("#datetimepicker77").on("change.datetimepicker", function (e) {
                // alert($('#authTimeStart').val());
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
                    $('#actualOThrs').val(total);
                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#actualOThrs').val(total);
                }                                 
        

                $('#datetimepicker88').datetimepicker('minDate', e.date);
            
            });

            $("#datetimepicker88").on("change.datetimepicker", function (e) {
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
                    $('#actualOThrs').val(total);

                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#actualOThrs').val(total);
                }
                
                $('#datetimepicker77').datetimepicker('maxDate', e.date);
            });


            $("#datetimepicker71").on("change.datetimepicker", function (e) {
                // alert($('#authTimeStart').val());
                start_date = $('#authTimeStart1').val();

                start_date = new Date(start_date)
                authDateStart =start_date;
                console.log(authDateStart);

                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;

                console.log(total);
            
                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#authTotalHrs1').val(total);
                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#authTotalHrs1').val(total);
                }                                 
        

                $('#datetimepicker81').datetimepicker('minDate', e.date);
            
            });

            $("#datetimepicker81").on("change.datetimepicker", function (e) {
                end_date = $('#authTimeEnd1').val();

                end_date = new Date(end_date)
                authDateEnd =end_date;
                console.log(authDateEnd);
                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;
                console.log(total);

                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#authTotalHrs1').val(total);

                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#authTotalHrs1').val(total);
                }
                
                $('#datetimepicker71').datetimepicker('maxDate', e.date);
            });




            $("#datetimepicker711").on("change.datetimepicker", function (e) {
                // alert($('#authTimeStart').val());
                start_date = $('#actualTimeStart1').val();

                start_date = new Date(start_date)
                authDateStart =start_date;
                console.log(authDateStart);

                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;

                console.log(total);
            
                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#actualOTHrs1').val(total);
                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#actualOTHrs1').val(total);
                }                                 
        

                $('#datetimepicker811').datetimepicker('minDate', e.date);
            
            });

            $("#datetimepicker811").on("change.datetimepicker", function (e) {
                end_date = $('#actualTimeEnd1').val();

                end_date = new Date(end_date)
                authDateEnd =end_date;
                console.log(authDateEnd);
                var total = (authDateEnd - authDateStart)  / 1000 / 60 / 60;
                console.log(total);

                let f = Math.floor(total);
                if(total-f < 0.5){
                    total = Math.floor(total)
                    console.log(total)
                    $('#actualOTHrs1').val(total);

                } else {
                    total = f+0.5;
                    console.log(total)
                    $('#actualOTHrs1').val(total);
                }
                
                $('#datetimepicker711').datetimepicker('maxDate', e.date);
            });









        });


    </script>
{{-- Auth Time Date End --}}


{{-- Get Client Start --}}
    <script>
            function getEmployeeName1(){
                let employeeName = $( "#employeeID1 option:selected" ).text();
                $('#employeeName1').val(employeeName);
                console.log(employeeName);
            }

        function getProjectName1(){
                let projectName = $( "#projectID1 option:selected" ).text();
                let projectID = $( "#projectID1").val();

                $('#projectName1').val(projectName);
                console.log(projectName);
                console.log(projectID);

                $.ajax({
                type: 'GET',
                url: '/getClientNameAnd/' + projectID,
                success: function (response) {
                var response = JSON.parse(response);

                response.forEach(element => {
                        console.log(element['clientID']);
                        console.log(element['clientName']);

                        $('#clientID1').val(element['clientID'])
                        $('#clientName1').val(element['clientName'])

                    });

                    }
                }); 
            }
    </script>
{{-- Get Client End --}}


{{-- Date Time Picker Options Start --}}
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'L'
            });

            $('#datetimepicker41').datetimepicker({
            format: 'L'
            });
        });
    </script>
{{-- Date Time Picker Options End --}}


{{-- Get Reporting Manager Name Start --}}
    <script>
        function getReportingManagerName(){
            let rmName = $( "#rmID option:selected" ).text();
            $('#rmName').val(rmName);
            console.log(rmName);
        }
    </script>
{{-- Get Reporting Manager Name End --}}


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
                                    <input type="hidden" name="main_id"  value="{{ $post[0]->main_id }}">
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


@endsection

{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
