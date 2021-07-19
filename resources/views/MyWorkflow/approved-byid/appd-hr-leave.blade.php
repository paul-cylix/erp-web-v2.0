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


{{-- Row Start --}}
<div class="row">
    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/approved" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>   
        </div> 
    </div> 
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

                        <input type="hidden" name="rmName" id="rmName">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reporting Manager</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post[0]->reporting_manager }}" readonly >
            
                    
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>
                    </div>

                    <input type="hidden" name="employeeName" id="employeeName">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Employee Name</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post[0]->employee_name }}" readonly >
                        
                            </div>       
            <span class="text-danger">@error('employeeID'){{ $message }}@enderror</span>

                        </div>
                        
                        <input type="hidden" name="mediumofreportName" id="mediumofreportName">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Medium of Report</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post[0]->medium_of_report }}" readonly >
                       
                            </div>            
            <span class="text-danger">@error('mediumofreportid'){{ $message }}@enderror</span>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Report Time</label>
                                <div class="input-group date" id="datetimepicker75" data-target-input="nearest">
                                    @php
                                    $report_time = date_create($post[0]->report_time); 
                                    $report_time = date_format($report_time, 'm/d/Y g:i A');
                                    @endphp

                                    <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{ $report_time }}" disabled readonly/>
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
                                <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" readonly placeholder="">{{ $post[0]->reason }}</textarea>
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
                                                @foreach ($post as $p)
                                                <tr class="d-flex" style="font-size: 13px;">
                                                    <td class="col-2">{{ $p->leave_date }}</td>
                                                    <td class="col-3">{{ $p->leave_type }}</td>
                                                    @if ($p->leave_halfday == 'Wholeday')
                                                        @php
                                                            $day = 'No';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $day = 'Yes';
                                                        @endphp
                                                    @endif
                                                    <td class="col-1 text-center p-0 m-0 pt-3">{{ $day }}</td>
                                                    <td class="col-2">{{ $p->leave_halfday }}</td>
                                                    <td class="col-2">{{ $p->num_days }}</td>
                                                    @if ( $p->leave_paytype == 'wp' )
                                                       @php
                                                           $pay = 'With Pay';
                                                       @endphp
                                                    @else
                                                        @php
                                                           $pay = 'Without Pay';
                                                        @endphp
                                                    @endif
                                                    <td class="col-1 text-center">{{ $pay }}</td>
                                                    <td class="col-1 text-center"><button class="btn btn-danger deleteRow" disabled><i class="fas fa-trash-alt"></i></button></td>
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
        <form action="{{ route('withdraw.leave') }}" method="POST">
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












@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
