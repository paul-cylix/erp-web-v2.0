@extends('layouts.base')
@section('title', 'Reimbursement Request') 
@section('content')

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
<div class="row">

        {{-- Editable part order < 3 --}}
        <?php if (!empty($replyEditChecker)) {
        ?>

            {{-- Initiator Check --}}
            <?php if (!empty($initChecker)) {
              ?>  
            <div class="col-md-12" style="margin: -20px 0 20px 0 " >
                <div class="form-group" style="margin: 0 -5px 0 -5px;">
                        <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right"  data-toggle="modal" data-target="#replyModal" onclick="submitAllDataInTables()" >Reply</button></div>     
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal"  >Withdraw</button></div>        
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>
                </div> 
            </div> 
    
        <!-- Modal Withdraw-->
        <div class="modal fade"  id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark" >
                <h5 class="modal-title" id="withdrawModalLabel">Withdraw Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('inp.withdraw.re') }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">                     
                                <label for="withdrawRemarks">Remarks</label>
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="hidden" value="{{ $post->id }}" name="reID">
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
        {{-- End Withdraw Modal --}}



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
<form action="{{ route('cla.reply.re') }}" method="POST" enctype="multipart/form-data">
    @csrf
                    <div class="modal-body">
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">                     
                                    <label for="replyRemarks">Remarks</label>
                                    <div class="card-body">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Proceed" id="replyEditableForm">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
            {{-- End Reply Modal --}}
            
             
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header">
                        <h3 class="card-title">Reimbursement Request</h3>
                    </div>
    
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" class="form-control" value="{{ $post->REQREF }} " readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateRequested">Requested Date</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateRequested" name="dateRequested" value="{{ date('m/d/Y') }}"  class="form-control datetimepicker-input" readonly>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <input id="RMName" name="RMName" type="hidden" value="{{ $post->REPORTING_MANAGER }}" class="form-control" placeholder="" >
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reportingManager">Reporting Manager</label>
                                        <select id="reportingManager" name="reportingManager" class="form-control select2 select2-default"  data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getRMName(this)">
                                            <option selected value='{{ $mgrsId }}' >{{ $post->REPORTING_MANAGER }}</option>
                                            @foreach ($mgrs as $rm)
                                                <option value="{{$rm->RMID}}">{{$rm->RMName}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">@error('reportingManager'){{ $message }}@enderror</span>
    
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="initiator">Initiator</label>
                                        <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}"  readonly >
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectName">Project Name</label>
                                        {{-- <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $post->PROJECT }}"  > --}}
                                        <select id="projectName" name="projectName" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                            <option selected value='{{ $post->PRJID }}' >{{ $post->PROJECT }}</option>
                                            @foreach ($projects as $prj)
                                                 <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
    
                                    </div>
                                </div>
                                
                                {{-- Hidden elements --}}
    
                                <?php 
                                if (isset($expenseDetails[0]->CLIENT_ID)) {
                                    ?>
                                    {{-- <input type="hidden" name="xdClientID" value="{{ $expenseDetails[0]->CLIENT_ID }}"> --}}
                                    <?php
                                } else {
                                    
                                }
                                ?>
                                   
                                <?php 
                                if (isset($transpoDetails[0]->CLIENT_ID)) {
                                    ?>
                                    {{-- <input type="hidden" name="tdClientID" value="{{ $transpoDetails[0]->CLIENT_ID }}"> --}}
                                    <?php
                                } else {
                                    
                                }
                                ?>
                                {{-- To delete --}}

                                <input type="hidden" name="guid" value="{{ $post->GUID }}">
                                <input type="hidden" name="reID" value="{{ $post->id }}">
                                <input type="hidden" name="xdData" id="xdData">
                                <input type="hidden" name="tdData" id="tdData">
                                <input type="hidden" name="xdSubTotalAmt" id="xdSubTotalAmt">
                                <input type="hidden" name="tdSubTotalAmt" id="tdSubTotalAmt">
                                <input type="hidden" value="" name="deleteAttached" id="deleteAttached">
                                {{-- <input type="text" id="prjName" name="prjNamea"> --}}
                                <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" value="{{ $post->CLIENTID }}" >
                                <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" value="{{ $post->MAINID }}" >
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clientName">Client Name</label>
                                        <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $post->CLIENT_NAME }}" readonly >
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payeeName">Payee Name</label>
                                        <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $post->PAYEE }}"  >
                                        <span class="text-danger">@error('payeeName'){{ $message }}@enderror</span>
    
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateNeeded">Date Needed</label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true" data-date-format='YYYY-MM-DD'>
                                                <input type="input" id="dateNeeded"  name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $post->TRANS_DATE }}" />
                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker"  >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>                         
                                        </div>
                                        <span class="text-danger">@error('dateNeeded'){{ $message }}@enderror</span>
    
                                    </div>         
                                </div>
    
                 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @php
                                        $foo = $post->TOTAL_AMT_SPENT;
                                        $myAMount = number_format((float)$foo, 2, '.', ''); 
                                        @endphp
                                        <label for="amount">Total Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control text-right" readonly value="{{ $myAMount }}">
                                        <span class="text-danger">@error('amount'){{ $message }}@enderror</span>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" >{{ $post->DESCRIPTION }}</textarea>
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
                                                    @foreach ($expenseDetails as $xdData)
                                                        <tr>
                                                            <td>{{ $xdData->date_ }}</td>
                                                            <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                                            <td>{{ $xdData->DESCRIPTION }}</td>
@php
$foo = $xdData->AMOUNT ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                                            <td>{{ $myAMount }}</td>
                                                            <td><a class="btn btn-danger removeXDRow" onClick ="deleteXDRow()" >Delete</a></td>
                                                        </tr>
                                                                         
                                                    @endforeach
                                                    
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

@php
$foo = $subtotalExpenseDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                            <div class="container">
                                            <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount">{{ $myAMount }}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
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
                                                    @foreach ($transpoDetails as $tdData)
                                                        <tr>
                                                            <td>{{ $tdData->date_ }}</td>
                                                            <td>{{ $tdData->DESTINATION_FRM }}</td>
                                                            <td>{{ $tdData->DESTINATION_TO }}</td>
                                                            <td>{{ $tdData->MOT }}</td>
                                                            <td>{{ $tdData->DESCRIPTION }}</td>
@php
$foo = $tdData->AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                                            <td>{{ $myAMount }}</td>
                                                            <td><a  class="btn btn-danger removeTDRow" onClick ="deleteTDRow()" >Delete</a></td>
                                                        </tr>
                                                    {{-- @empty
                                                    <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                   --}}
                                                    @endforeach
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
                                            @php
$foo = $subtotalTranspoDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                            <div class="container">
                                            <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount">{{ $myAMount }}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                            {{-- Transportation details --}}
    
                            {{-- Attachments --}}
                            <label class="btn btn-primary" style="font-weight:normal;">
                                Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                            </label>
                        
</form>
                            {{-- Attachments --}}    
                            
                   

                            {{-- Attachments --}}
                         
                            <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                    <div class="row">
                                        <div class="col" style="font-size:18px; padding-top:5px;">Attachments</div>                                          
                                    </div>
                                    </div>
                                    <div class="card-body" >
                                        <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                            <table id= "attachmentsTable"class="table table-hover" >
                                                <thead >
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Temporary Path</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody >
                                                    @foreach ($attachmentsDetails as $file )
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td>{{ $file->fileExtension }}</td>
                                                        <td>{{ $file->filepath }}</td>
                                                        <td><a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
                                                            <a class="btn btn-danger" onclick="removedAttach(this)"  >Delete<input type="hidden" value="{{ $file->id }}"><input type="hidden" value="{{ $file->filepath }}"><input type="hidden" value="{{ $file->filename }}"></a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                  
                            {{-- End Attachments --}}



    
            {{-- End of Editable --}}




            {{-- for Approver not editable inside < 3 order --}}
            <?php
            } else {
            ?>

                    {{-- Recipient is the approver --}}
                    <?php if (!empty($recipientCheck)) {
                        ?>
                        {{-- Recipient is the approver Editable --}}
                        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
                            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                                    <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right"  data-toggle="modal" data-target="#replyModal" onclick="submitAllDataInTables()" >Reply</button></div>     
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>
                            </div> 
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
                                <form action="{{ route('cla.approved.re') }}" method="POST">
                                    @csrf
                                <div class="modal-body">
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="approvedRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        <input type="hidden" value="{{ $post->id }}" name="reID">
                                                        <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks" id="approvedRemarks" style="height: 100px"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" id="replyBtnRecipientApprover" value="Proceed">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                             </form>

                            </div>
                            </div>
                        </div>
                        {{-- End Reply Modal --}}
                        
                         
                        <div class="col-md-12">
                            <div class="card card-gray">
                                <div class="card-header">
                                    <h3 class="card-title">Reimbursement Request</h3>
                                </div>
                
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="referenceNumber">Reference Number</label>
                                                    <input type="text" class="form-control" value="{{ $post->REQREF }} " readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRequested">Requested Date</label>
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input type="text" id="dateRequested" name="dateRequested" value="{{ date('m/d/Y') }}"  class="form-control datetimepicker-input" readonly>
                                                        <div class="input-group-append" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <input id="RMName" name="RMName" type="hidden" value="{{ $post->REPORTING_MANAGER }}" class="form-control" placeholder="" >
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="reportingManager">Reporting Manager</label>
                                                    <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post->REPORTING_MANAGER }}" readonly >
                                                </div>
                                            </div>
                
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="initiator">Initiator</label>
                                                    <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}"  readonly >
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="projectName">Project Name</label>
                                                    <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $post->PROJECT }}" readonly >
                                                </div>
                                            </div>
                                       
                

                                            {{-- Hidden elements --}}
                                            
                                            <input type="hidden" name="guid" value="{{ $post->GUID }}">
                                            <input type="hidden" name="reID" value="{{ $post->id }}">
                                            <input type="hidden" name="xdData" id="xdData">
                                            <input type="hidden" name="tdData" id="tdData">
                                            <input type="hidden" value="" name="deleteAttached" id="deleteAttached">
                                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" value="{{ $post->CLIENTID }}" >
                                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" value="{{ $post->MAINID }}" >
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="clientName">Client Name</label>
                                                    <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $post->CLIENT_NAME }}" readonly >
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payeeName">Payee Name</label>
                                                    <input id="payeeName" readonly name="payeeName" type="text" class="form-control" value="{{ $post->PAYEE }}"  >
                                                    <span class="text-danger">@error('payeeName'){{ $message }}@enderror</span>
                
                                                </div>
                                            </div>
                
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateNeeded">Date Needed</label>
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $post->TRANS_DATE }}" readonly/>
                                                        <div class="input-group-append" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>         
                                            </div>
                
                
                             
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input id="amount" name="amount" type="text" class="form-control" readonly value="{{ $post->TOTAL_AMT_SPENT }}">
                                                    <span class="text-danger">@error('amount'){{ $message }}@enderror</span>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="purpose">Purpose</label>
                                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose"  readonly rows="4" >{{ $post->DESCRIPTION }}</textarea>
                                                    <span class="text-danger">@error('purpose'){{ $message }}@enderror</span>
                                                </div>                              
                                            </div>
                                        </div>
                
                               
                                    
                              
                
                
                                  
                                    
                
{{-- Expense Details --}}
@if (!empty($expenseDetails))
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                <div class="row">
                    <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                    {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</a></div> --}}

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
                        @forelse ($expenseDetails as $xdData)
                            <tr>
                                <td>{{ $xdData->date_ }}</td>
                                <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                <td>{{ $xdData->DESCRIPTION }}</td>

@php
$foo = $xdData->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

                                <td>{{ $myAMount }}</td>
                                <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                            </tr>
                        @empty
                        <tr><td colspan="5" style="padding-left: 25px;">no data</td></tr>                                                  
                        @endforelse
                        
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
            
@php
$foo = $subtotalExpenseDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

            <div class="container">
                <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount">{{ $myAMount }}</span></h6>
            </div>
            </div>
        </div>
    </div>                                    
</div>
@endif



{{-- Expense Details --}}






{{-- Transportation Details --}}
@if (!empty($transpoDetails))
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">

            <div class="card-header" style="padding: 5px 20px 5px 20px; ">

                <div class="row">
                    <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                    {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</a></div> --}}

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
                        @forelse ($transpoDetails as $tdData)
                            <tr>
                                <td>{{ $tdData->date_ }}</td>
                                <td>{{ $tdData->DESTINATION_FRM }}</td>
                                <td>{{ $tdData->DESTINATION_TO }}</td>
                                <td>{{ $tdData->MOT }}</td>
                                <td>{{ $tdData->DESCRIPTION }}</td>
@php
$foo = $tdData->AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                <td>{{ $myAMount }}</td>
                                <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                            </tr>
                        @empty
                        <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                  
                        @endforelse
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

@php
$foo = $subtotalTranspoDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                <div class="container">
                    <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount">{{ $myAMount }}</span></h6>
                </div>
            </div>
        </div>
    </div>                                    
</div>
@endif
{{-- Transportation details --}}






                 
{{-- Attachments --}}
@if (!empty($attachmentsDetails))
<div class="row">
<div class="col-md-12">
    <div class="card card-gray">
        <div class="card-header" style="padding: 5px 20px 5px 20px; ">
        <div class="row">
            <div class="col" style="font-size:18px; padding-top:5px;">Attachments</div>                                          
        </div>
        </div>
        <div class="card-body" >
            <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                <table id= "attachmentsTable"class="table table-hover" >
                    <thead >
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Temporary Path</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody >
                        @foreach ($attachmentsDetails as $file )
                        <tr>
                            <td>{{ $file->filename }}</td>
                            <td>{{ $file->fileExtension }}</td>
                            <td>{{ $file->filepath }}</td>
                            <td><a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endif
{{-- End Attachments --}}
                                        {{-- End Attachments --}}
                        {{-- End of Recipient is the approver Editable --}}
                       <?php 
                    } 
                    
                    
                                 
              
                    // {{-- Recipeient is the approver Not Editable --}}
                    else {
                    ?>
                    
                    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
                        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                                <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled >Reply</button></div>     
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>
                        </div> 
                    </div> 
            
                    <!-- Modal Approved-->
                    <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark" >
                            <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form action="{{ route('cla.approved.re') }}" method="POST">
                                @csrf
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">                     
                                            <label for="approvedRemarks">Remarks</label>
                                            <div class="card-body">
                                                <div class="form-floating">
                                                    <input type="hidden" value="{{ $post->id }}" name="reID">
                                                    <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks" id="approvedRemarks" style="height: 100px"></textarea>
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
                
                        <div class="col-md-12">
                            <div class="card card-gray">
                                <div class="card-header">
                                    <h3 class="card-title">Reimbursement Request</h3>
                                </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="referenceNumber">Reference Number</label>
                                                    <input type="text" class="form-control" value="{{ $post->REQREF }} " readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateRequested">Requested Date</label>
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input type="text" id="dateRequested" name="dateRequested" value="{{ date('m/d/Y') }}"  class="form-control datetimepicker-input" readonly/>
                                                        <div class="input-group-append" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="" readonly>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="reportingManager">Reporting Manager</label>
                                                    <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post->REPORTING_MANAGER }}" readonly >
                                                </div>
                                            </div>
                
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="initiator">Initiator</label>
                                                    <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}" readonly >
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="projectName">Project Name</label>
                                                    <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $post->PROJECT }}" readonly >
                                                </div>
                                            </div>
                                            
                                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="clientName">Client Name</label>
                                                    <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $post->CLIENT_NAME }}" readonly >
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payeeName">Payee Name</label>
                                                    <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $post->PAYEE }}" readonly >
                                                </div>
                                            </div>
                
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="dateNeeded">Date Needed</label>
                                                    <div class="input-group date" data-target-input="nearest">
                                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $post->TRANS_DATE }}" readonly/>
                                                        <div class="input-group-append" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>         
                                            </div>
                
                                
@php
$foo = $post->TOTAL_AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Total Amount</label>
                                    <input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"  readonly >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="purpose">Purpose</label>
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $post->DESCRIPTION }}" readonly></textarea>                              
                                </div>
                                
                            </div>
                        </div>

{{-- Expense Details --}}
@if (!empty($expenseDetails))
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                <div class="row">
                    <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                    {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</a></div> --}}

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
                        @forelse ($expenseDetails as $xdData)
                            <tr>
                                <td>{{ $xdData->date_ }}</td>
                                <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                <td>{{ $xdData->DESCRIPTION }}</td>

@php
$foo = $xdData->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

                                <td>{{ $myAMount }}</td>
                                <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                            </tr>
                        @empty
                        <tr><td colspan="5" style="padding-left: 25px;">no data</td></tr>                                                  
                        @endforelse
                        
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
            
@php
$foo = $subtotalExpenseDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

            <div class="container">
                <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount">{{ $myAMount }}</span></h6>
            </div>
            </div>
        </div>
    </div>                                    
</div>
@endif
                    {{-- Transportation Details --}}
@if (!empty($transpoDetails))
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">

            <div class="card-header" style="padding: 5px 20px 5px 20px; ">

                <div class="row">
                    <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                    {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</a></div> --}}

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
                        @forelse ($transpoDetails as $tdData)
                            <tr>
                                <td>{{ $tdData->date_ }}</td>
                                <td>{{ $tdData->DESTINATION_FRM }}</td>
                                <td>{{ $tdData->DESTINATION_TO }}</td>
                                <td>{{ $tdData->MOT }}</td>
                                <td>{{ $tdData->DESCRIPTION }}</td>
@php
$foo = $tdData->AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                <td>{{ $myAMount }}</td>
                                <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                            </tr>
                        @empty
                        <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                  
                        @endforelse
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

@php
$foo = $subtotalTranspoDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                <div class="container">
                    <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount">{{ $myAMount }}</span></h6>
                </div>
            </div>
        </div>
    </div>                                    
</div>
@endif
{{-- Transportation details --}}






                 
{{-- Attachments --}}
@if (!empty($attachmentsDetails))
<div class="row">
<div class="col-md-12">
    <div class="card card-gray">
        <div class="card-header" style="padding: 5px 20px 5px 20px; ">
        <div class="row">
            <div class="col" style="font-size:18px; padding-top:5px;">Attachments</div>                                          
        </div>
        </div>
        <div class="card-body" >
            <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                <table id= "attachmentsTable"class="table table-hover" >
                    <thead >
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Temporary Path</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody >
                        @foreach ($attachmentsDetails as $file )
                        <tr>
                            <td>{{ $file->filename }}</td>
                            <td>{{ $file->fileExtension }}</td>
                            <td>{{ $file->filepath }}</td>
                            <td><a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endif
{{-- End Attachments --}}
                
{{--                 
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input id="amount" name="amount" type="text" class="form-control" value="{{ $post->TOTAL_AMT_SPENT }}"  readonly >
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="purpose">Purpose</label>
                                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $post->DESCRIPTION }}" readonly></textarea>                              
                                                </div>
                                                
                                            </div>
                                        </div>
                
                                   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card card-default">
                                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                                        <div class="row">
                                                            <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                                                          
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
                                                                @forelse ($expenseDetails as $xdData)
                                                                    <tr>
                                                                        <td>{{ $xdData->date_ }}</td>
                                                                        <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                                                        <td>{{ $xdData->DESCRIPTION }}</td>
                                                                        <td>{{ $xdData->AMOUNT }}</td>
                                                                        <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                                    </tr>
                                                                @empty
                                                                <tr><td colspan="5" style="padding-left: 25px;">no data</td></tr>                                                  
                                                                @endforelse
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                     
                                                    <div class="card-footer clearfix">
                                                    <div class="container">
                                                    <div class="row float-right" style="margin-right: 50px;">
                                                 
                                                    </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>                                    
                                        </div>
                                   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card card-default">
                                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                
                                                        <div class="row">
                                                            <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                                                           
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
                                                                @forelse ($transpoDetails as $tdData)
                                                                    <tr>
                                                                        <td>{{ $tdData->date_ }}</td>
                                                                        <td>{{ $tdData->DESTINATION_FRM }}</td>
                                                                        <td>{{ $tdData->DESTINATION_TO }}</td>
                                                                        <td>{{ $tdData->MOT }}</td>
                                                                        <td>{{ $tdData->DESCRIPTION }}</td>
                                                                        <td>{{ $tdData->AMT_SPENT }}</td>
                                                                        <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                                    </tr>
                                                                @empty
                                                                <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                  
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                  
                                                    <div class="card-footer clearfix">
                                                        <div class="container">
                                                        <div class="row float-right" style="margin-right: 50px;">
                                                     
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                    
                                        </div>
                                     
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card card-gray">
                                                    <div class="card-header" style="height:50px;">
                                                        <div class="row ">
                                                            <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
                                                        </div>
                                                    </div>
                
                                                    <div class="card-body" >
                                                        <div class="row">       
                                                            @forelse ($attachmentsDetails as $file)
                                                            <div class="col-sm-2" >
                
                                                                <div class="dropdown show" >
                                                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute; right: 0px; top: 0px; z-index: 999; "></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
                                                                        <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" download="{{ $file->filename }}" >Download</a>
                                                                    </div>
                                                                </div>
                                                                <div class="card">
                
                                                              
                                                
                                                                    <div class="card-body" style="padding: 5px; ">
                                                                    <p class="card-text text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $file->filename }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            @empty
                                                            <span style="margin-left: 12px;">no attachments</span>
                                                            @endforelse
                                                        </div>   
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>
                                       --}}

                    {{-- End Recipient is the approver Not Editable --}}
                    <?php
                    }
                    ?>


            {{-- End of Recipient is the approver --}}
            <?php
            }          
            ?>
            {{-- End Initiator Check --}}
       
        <?php
        }
        // end Editable part order < 3
        




        // No edit Tesst >= 3 approval of manament Start
        else {
        ?>


        

            @if ((session('LoggedUser')) === $post->UID)
            <div class="col-md-12" style="margin: -20px 0 20px 0 " >
                <div class="form-group" style="margin: 0 -5px 0 -5px;">
                        <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" data-target="#replyModal" >Reply</button></div>     
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled >Approve</button></div>
                </div> 
            </div> 
            @else
            <div class="col-md-12" style="margin: -20px 0 20px 0 " >
                <div class="form-group" style="margin: 0 -5px 0 -5px;">
                        <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled >Reply</button></div>     
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                        <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>
                </div> 
            </div> 
            @endif




        <!-- Modal Reply Moded-->
        <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark" >
                <h5 class="modal-title" id="replyModalLabel">Reply Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form action="{{ route('cla.approved.re') }}" method="POST">
                    @csrf
                <div class="modal-body">

                    <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">                     
                                <label for="approvedRemarks">Remarks</label>
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="hidden" value="{{ $post->id }}" name="reID">
                                        <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks" id="approvedRemarksModed" style="height: 100px"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary" id="replyEditableFormModed" value="Proceed">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        {{-- End Modal Reply Moded --}}



<script>
    $('#replyEditableFormModed').on('click', function(){
        if ($.trim($("#approvedRemarksModed").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
    })
</script>







        <!-- Modal Approved-->
        <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('cla.approved.re') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="approvedRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" value="{{ $post->id }}" name="reID">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks" id="approvedRemarks" style="height: 100px"></textarea>
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










    
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header">
                        <h3 class="card-title">Reimbursement Request</h3>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" class="form-control" value="{{ $post->REQREF }} " readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateRequested">Requested Date</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateRequested" name="dateRequested" value="{{ date('m/d/Y') }}"  class="form-control datetimepicker-input" readonly/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="" readonly>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="reportingManager">Reporting Manager</label>
                                        <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post->REPORTING_MANAGER }}" readonly >
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="initiator">Initiator</label>
                                        <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}" readonly >
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectName">Project Name</label>
                                        <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $post->PROJECT }}" readonly >
                                    </div>
                                </div>
                                
                                <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                                <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clientName">Client Name</label>
                                        <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $post->CLIENT_NAME }}" readonly >
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payeeName">Payee Name</label>
                                        <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $post->PAYEE }}" readonly >
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateNeeded">Date Needed</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $post->TRANS_DATE }}" readonly/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>         
                                </div>
    
                                @php
                                $foo = $post->TOTAL_AMT_SPENT;
                                $myAMount = number_format((float)$foo, 2, '.', ''); 
                                @endphp
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Total Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"  readonly >
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $post->DESCRIPTION }}" readonly></textarea>                              
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
                                                {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</a></div> --}}
    
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
                                                    @forelse ($expenseDetails as $xdData)
                                                        <tr>
                                                            <td>{{ $xdData->date_ }}</td>
                                                            <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                                            <td>{{ $xdData->DESCRIPTION }}</td>
@php
$foo = $xdData->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

                                <td>{{ $myAMount }}</td>
                                                            <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                        </tr>
                                                    @empty
                                                    <tr><td colspan="5" style="padding-left: 25px;">no data</td></tr>                                                  
                                                    @endforelse
                                                    
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
                                        @php
$foo = $subtotalExpenseDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

            <div class="container">
                <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount">{{ $myAMount }}</span></h6>
            </div>
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                            {{-- Expense Details --}}
                        
                            {{-- Transportation Details --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-gray">
                                        <div class="card-header" style="padding: 5px 20px 5px 20px; ">
    
                                            <div class="row">
                                                <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                                                {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</a></div> --}}
    
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
                                                    @forelse ($transpoDetails as $tdData)
                                                        <tr>
                                                            <td>{{ $tdData->date_ }}</td>
                                                            <td>{{ $tdData->DESTINATION_FRM }}</td>
                                                            <td>{{ $tdData->DESTINATION_TO }}</td>
                                                            <td>{{ $tdData->MOT }}</td>
                                                            <td>{{ $tdData->DESCRIPTION }}</td>
@php
$foo = $tdData->AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                                            <td>{{ $myAMount }}</td>
                                                            <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                        </tr>
                                                    @empty
                                                    <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                  
                                                    @endforelse
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
                                            @php
$foo = $subtotalTranspoDetails[0]->total ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                <div class="container">
                    <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount">{{ $myAMount }}</span></h6>
                </div>
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                            {{-- Transportation details --}}
    
                            {{-- Attachments of no edit --}}

                            @if (!empty($attachmentsDetails))
                            <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                    <div class="row">
                                        <div class="col" style="font-size:18px; padding-top:5px;">Attachments</div>                                          
                                    </div>
                                    </div>
                                    <div class="card-body" >
                                        <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                            <table id= "attachmentsTable"class="table table-hover" >
                                                <thead >
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Temporary Path</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody >
                                                    @foreach ($attachmentsDetails as $file )
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td>{{ $file->fileExtension }}</td>
                                                        <td>{{ $file->filepath }}</td>
                                                        <td><a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @endif

                            {{-- End Attachments --}}
            

        <?php
        }
        ?>
        {{-- End of No edit --}}
        



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
                                                        <input type="number" class="form-control" id="amountTD" placeholder="0.00" aria-describedby="helpId" >
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
            </section>
        </div>

<script>
    function submitAllDataInTables(){
        xdUpdateData();
        tdUpdateData();
    }
</script>



{{-- Expense Details
<script>
    function getExpenseData(){
        var dateXD = $('#dateXD').val();
        var typeXD = $('#typeXD').val(); 
        var amountXD = $('#amountXD').val(); 
        var remarksXD = $('#remarksXD').val(); 
        // console.log(dateXD,typeXD,amountXD,remarksXD);
    
        var dateXDChecker = false;
        // var typeXDChecker = false;
        var amountXDChecker = false;
        var remarksXDChecker = false;
    
    
    if(dateXD){
        dateXDChecker = true;
        $('#dateErrXD').text('');
    
    }else{
        $('#dateErrXD').text('Date is required!');
        
    }
    
    if(amountXD){
        amountXDChecker = true;
        $('#amountErrXD').text('');
    
    }else{
        $('#amountErrXD').text('Amount is required!');
    }
    
    if(remarksXD){
        remarksXDChecker = true;
        $('#remarksErrXD').text('');
    
    }else{
        $('#remarksErrXD').text('Remarks is required!');
    }
    
    
    if(dateXDChecker && amountXDChecker && remarksXDChecker ){
    
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
            $('#dateErrXD').text('');
            $('#amountErrXD').text('');
            $('#remarksErrXD').text('');
    
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
        var myAmt = 0 ;
    
      
        

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

            console.log(objectXD);

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
{{-- doge --}}
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
        console.log($( "#tdData" ).val());
    
    }
    
    </script>
    
    
    
    
    <script>
    function getTotalAmount(){
        var getXDSubTotalChecker = false;
        var getTDSubTotalChecker = false;
    
        var getXDSubTotal = parseFloat($('#xdTotalAmount').text());
        var getTDSubTotal = parseFloat($('#tdTotalAmount').text());
    
        if (getXDSubTotal){
            $('#xdSubTotalAmt').val(getXDSubTotal);
            getXDSubTotalChecker = true; 
        } else {
            $('#xdSubTotalAmt').val('');
        }
    
         if (getTDSubTotal){
            $('#tdSubTotalAmt').val(getTDSubTotal);
            getTDSubTotalChecker = true;
        }  else {
            $('#tdSubTotalAmt').val('');
        }
        
        
      
    if (getXDSubTotalChecker && getTDSubTotalChecker) {
        $('#amount').val(getXDSubTotal+getTDSubTotal);
    } else if (getXDSubTotalChecker === true) {
        $('#amount').val(getXDSubTotal);
    } else if (getTDSubTotalChecker === true) {
        $('#amount').val(getTDSubTotal);
    } else {
        $('#amount').val(0.00);
    }
    }
    </script>
    




{{-- <script>
    objectAttached = [];
        function removedAttached(elem){
            var attachedArray = [];
    
            var x =  $(elem).parent("div").parent("div").parent("div").fadeOut(300);
            var idAttached = $(elem).children("input").val();
            var pathAttached = $(elem).children("input").next().val();
            var fileNameAttached = $(elem).children("input").next().next().val();
    
            
            attachedArray.push(idAttached,pathAttached,fileNameAttached);
    
            objectAttached.push(attachedArray);
            console.log(attachedArray);
            console.log(objectAttached);

            var attachedJson = JSON.stringify(objectAttached);
            document.getElementById("deleteAttached").value = attachedJson;

        }
</script> --}}





{{-- Transportation Details --}}
{{-- <script>
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
    
    
        if(dateTD){
            dateTDChecker = true;
            $('#dateErrTD').text('');
        }else{
            $('#dateErrTD').text('Date is required!');
        }
    
    
        if(amountTD){
            amountTDChecker = true;
            $('#amountErrTD').text('');
    
        }else{
            $('#amountErrTD').text('Amount is required!');
        }
    
    
        if(fromTD){
            fromTDChecker = true;
            $('#fromErrTD').text('');
        }else{
            $('#fromErrTD').text('Destination from is required!');
        }
    
        if(toTD){
            toTDChecker = true;
            $('#toErrTD').text('');
        }else{
            $('#toErrTD').text('Destination to is required!');
        }
    
        if(remarksTD){
            remarksTDChecker = true;
            $('#remarksErrTD').text('');
        }else{
            $('#remarksErrTD').text('Remarks is required!');
        }
    
        if(dateTDChecker && amountTDChecker && fromTDChecker && toTDChecker && remarksTDChecker){
    
    
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
            $('#dateErrTD').text('');
            $('#amountErrTD').text('');
            $('#fromErrTD').text('');
            $('#toErrTD').text('');
            $('#remarksErrTD').text('');
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
    var myAmt = 0 ;
    
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

        
            var tdJsonData = JSON.stringify(objectTD);
            $( "#tdData" ).val(tdJsonData);


        });

        console.log(objectTD);

    }
</script> --}}


{{-- Attachments --}}
{{-- <script>
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
</script> --}}

<script>
    var main = [];
        $(document).ready(function() {
          $('input[type="file"]').on("change", function() {
            let files = this.files;
            console.log(files);
            console.dir(this.files[0]);
            $('#attachmentsTable tbody .keytodeleteattachedfile').remove();
                for(var i = 0; i<files.length; i++){
                var tmppath = URL.createObjectURL(files[i]);
                    var semi = [];
                    semi.push(files[i]['name'],files[i]['type'],files[i]['size'],tmppath);
                    main.push(semi);
                    console.log(main);
                                $("#attachmentsTable tbody:last-child").append('<tr class="keytodeleteattachedfile">'+
                                                '<td>'+files[i]['name']+'</td>'+
                                                '<td>'+files[i]['type']+'</td>'+
                                                '<td>'+tmppath+'</td>'+
                                                "<td><a href='"+tmppath+"' target='_blank' class='btn btn-secondary'>View</a></td>"+
                                                '</tr>'
                                );
                }
          });
        });
        $("#attachmentsTable").on('click', '.btnDelete', function () {
        $(this).closest('tr').remove();
    });
</script>


<script>
    objectAttachment = [];
        function removedAttach(elem){
            var attachmentArray = [];
            var x =  $(elem).parent("td").parent("tr").fadeOut(300);
            var idAttachment = $(elem).children("input").val();
            var pathAttachment = $(elem).children("input").next().val();
            var fileNameAttachment = $(elem).children("input").next().next().val();

            attachmentArray.push(idAttachment,pathAttachment,fileNameAttachment);
    
            objectAttachment.push(attachmentArray);
            console.log(attachmentArray);
            console.log(objectAttachment);

            var attachmentJson = JSON.stringify(objectAttachment);
            document.getElementById("deleteAttached").value = attachmentJson;
            var sa = document.getElementById("deleteAttached");
            console.log(sa);
        }
</script>

<script>
    $('#replyEditableForm').on('click', function(){
        if ($.trim($("#replyRemarks").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
    })

</script>

<script>
     $('#replyBtnRecipientApprover').on('click', function(){
        if ($.trim($("#approvedRemarks").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
    })
</script>
    
@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

{{-- Get Client --}}
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

            
            // var prj_txt = sel.options[sel.selectedIndex].text;
            // document.getElementById("prjNamea").value = prj_txt;
        }
    }
    xmlhttp.open("GET","/get-client/"+id,true);
    xmlhttp.send();



}
</script>
{{-- Reporting Manager Name --}}
<script>
    function getRMName(sel) {
        var rm_txt = sel.options[sel.selectedIndex].text;
        document.getElementById("RMName").value = rm_txt;
    }
</script>

