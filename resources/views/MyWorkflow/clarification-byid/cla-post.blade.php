@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')
    <div class="row">   
        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div> 
                    {{-- CHECKER CLARIFICATION --}}
   
                    <?php 
                        if($initCheck == True){
                            ?>

                            <?php
                            if($recipient == session('LoggedUser')){
                            ?>
                            {{-- enable because you are the recipient in editable fields --}}
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" data-target="#replyModal"  >Reply</button></div>     
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal">Withdraw</button></div>        
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div> 
                            <?php
                            }else{
                            ?>
                            {{-- Button all disabled coz of you are not the recipient --}}
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled>Withdraw</button></div>        
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div> 
                            <?php
                            }
                            ?>
                       


                        <?php
                        }else{
                        ?>


                            @if ($recipient == session('LoggedUser'))
                                {{-- <span>Reply</span> --}}
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" data-target="#replyModedModalRecipient">Reply</button></div>     
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right "disabled >Withdraw</button></div>        
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled >Reject</button></div>      
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled >Approve</button></div>   
                                
                            @else
                                {{-- <span>approve</span> --}}
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right "disabled >Withdraw</button></div>        
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" data-toggle="modal" data-target="#rejectModal" >Reject</button></div>      
                                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal">Approve</button></div>   
                                
                            @endif
 
                         
                        <?php
                        } 
                    ?>
                    {{-- END CHECKER CLARIFICATION --}}
            </div> 
        </div> 
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
                window.location.href = "/clarifications";
                }});
            </Script>
        @endif



<?php 
if($editableChecker){
?>

    <?php 
        if ($recipient == session('LoggedUser')){
            ?>
 
            {{-- you are the recipient editable --}}
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header">
                        <h3 class="card-title">{{ $payeeDetails->FRM_NAME }}</h3>
                    </div>
<form action="{{ route('cla.reply.post') }}" method="POST" id="replyForm" enctype="multipart/form-data">
                        @csrf
            
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="{{ $post->REQREF }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateRequested">Requested Date</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateRequested" name="dateRequested" value="{{ $post->DATE }}"  class="form-control datetimepicker-input" readonly/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <input type="hidden" value="{{ $post->ID }}" name="idName"> --}}
                                <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="dasd" readonly>
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
                                        <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}" readonly >
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectName">Project Name</label>
                                        <select id="projectName" name="projectName" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                            <option  value='{{ $postDetails->PROJECTID }}' >{{ $postDetails->PROJECT }}</option>
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
                                        <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $postDetails->CLIENTNAME }}" readonly >
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateNeeded">Date Needed</label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true" data-date-format='YYYY-MM-DD'>
                                                <input type="input" id="dateNeeded"  name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $postDetails->DATENEEDED }}" />
                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker"  >
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                    <span class="text-danger">@error('dateNeeded'){{ $message }}@enderror</span>
                                        </div>
                                    </div>         
                                </div>
            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payeeName">Payee Name</label>
                                        <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}"  >
                                    <span class="text-danger">@error('payeeName'){{ $message }}@enderror</span>

                                    </div>
                                </div>
            
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select id="currency" name="currency" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                              <option value="{{ $postDetails->CURRENCY }}" selected>{{ $postDetails->CURRENCY }}</option>  
                                            @foreach ($currencyType as $cuType)
                                            <option value="{{$cuType->CurrencyName}}">{{$cuType->CurrencyName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
            
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="modeOfPayment">Mode of Payment</label>
                                        <select id="modeOfPayment" name="modeOfPayment" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                            <option value="{{ $postDetails->MOP }}"selected>{{ $postDetails->MOP }}</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Check">Check</option>
                                            <option value="Credit to Account">Credit to Account</option>
                                        </select>
                                    </div>
                                </div>
@php
$foo = $post->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

<div class="col-md-3">
<div class="form-group">
<label for="amount">Amount</label>
<input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"   >
                                

                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}"   > --}}
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4">{{ $postDetails->PURPOSED }}</textarea>
                                    <span class="text-danger">@error('purpose'){{ $message }}@enderror</span>

                                    </div>                   
                                </div>
                            </div>

                           
                        

                            {{-- Attachments --}}
                            <label class="btn btn-primary" style="font-weight:normal;">
                                Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                            </label>
                            <input type="hidden" value="" name="toDelete" id="toDelete">
                            <span class="text-danger">@error('file')<br>{{ $message }}@enderror</span>
                        
                        
                        
                         
                            <div class="row">
                            <div class="col-md-12">
                            <div class="card card-gray">
                                <div class="card-header" style="height:50px;">
                                    <div class="row ">
                                        <div class="col"  ><h3 class="card-title">Attachments</h3></div>
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
                                                <th >Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody >
                                                @foreach ($filesAttached as $file )
                                                <tr>
                                                    <td>{{ $file->filename }}</td>
                                                    <td>{{ $file->fileExtension }}</td>
                                                    <td>{{ $file->filepath }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
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
                           
            
                            <!-- Modal Reply EDIT-->
                            <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark" >
                                    <h5 class="modal-title" id="replyModalLabel">Reply Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        
                                    <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">                     
                                                    <label for="replyRemarks">Remarks</label>
                                                    <div class="card-body">
                                                        <div class="form-floating">
                                                            <input type="hidden" name="refNumberReply"  value="{{ $post->REQREF }}">
                                                            <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                            {{-- <input type="text" value="" name="toDelete" id="toDelete"> --}}
                                                            <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="modalreplyRemarks" style="height: 100px"></textarea>
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
</form>
                                </div>
                                </div>
                            </div>
                            




     
                        </div> 
            
                </div>
             
            </div>



        <?php    
        } else {
        ?>
            {{-- View --}}
            {{-- You are not the recipient - editable side - but you cannot edit this form --}}
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header">
                        <h3 class="card-title">{{ $payeeDetails->FRM_NAME }}</h3>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="referenceNumber">Reference Number</label>
                                        <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="{{ $post->REQREF }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateRequested">Requested Date</label>
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateRequested" name="dateRequested" value="{{ $post->DATE }}"  class="form-control datetimepicker-input" readonly/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="dasd" readonly>
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
                                        <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $postDetails->PROJECT }}" readonly >
                                    </div>
                                </div>
                                
                                <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                                <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clientName">Client Name</label>
                                        <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $postDetails->CLIENTNAME }}" readonly >
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dateNeeded">Date Needed</label>
            
                                        <div class="input-group date" data-target-input="nearest">
                                            <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $postDetails->DATENEEDED }}"  readonly/>
                                            <div class="input-group-append" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
            
                    
                                    </div>         
                                </div>
            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="payeeName">Payee Name</label>
                                        <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}" readonly >
                                    </div>
                                </div>
            
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <input id="currency" name="currency" type="text" class="form-control" value="{{ $postDetails->CURRENCY }}" readonly >
                                    </div>
                                </div>
            
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="modeOfPayment">Mode of Payment</label>
                                        <input id="modeOfPayment" name="modeOfPayment" type="text" class="form-control" value="{{ $postDetails->MOP }}" readonly >
                                    </div>
                                </div>
            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input id="amount" name="amount" type="text" class="form-control" value="{{ $post->AMOUNT }}"  readonly >
                                    </div>
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $postDetails->PURPOSED }}" readonly></textarea>                              
                                    </div>
                                    
                                </div>
                            </div>

                            @if (!empty($filesAttached))
<div class="row">
<div class="col-md-12">
    <div class="card card-gray">
        <div class="card-header" style="height:50px;">
            <div class="row ">
                <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
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
                        @foreach ($filesAttached as $file )
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

                        </div> 
                </div>
            
            </div>


        <?php
        }
    ?>

<?php
} else {
?> 
{{-- RFP Main Table --}}
<div class="col-md-12">
    <div class="card card-gray">
        <div class="card-header">
            <h3 class="card-title">{{ $payeeDetails->FRM_NAME }}</h3>
        </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="referenceNumber">Reference Number</label>
                            <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="{{ $post->REQREF }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dateRequested">Requested Date</label>
                            <div class="input-group date" data-target-input="nearest">
                                <input type="text" id="dateRequested" name="dateRequested" value="{{ $post->DATE }}"  class="form-control datetimepicker-input" readonly/>
                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="dasd" readonly>
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
                            <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $postDetails->PROJECT }}" readonly >
                        </div>
                    </div>
                    
                    <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                    <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clientName">Client Name</label>
                            <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $postDetails->CLIENTNAME }}" readonly >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dateNeeded">Date Needed</label>

                            <div class="input-group date" data-target-input="nearest">
                                <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $postDetails->DATENEEDED }}"  readonly>
                                <div class="input-group-append" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>

        
                        </div>         
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payeeName">Payee Name</label>
                            <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}" readonly >
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input id="currency" name="currency" type="text" class="form-control" value="{{ $postDetails->CURRENCY }}" readonly >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modeOfPayment">Mode of Payment</label>
                            <input id="modeOfPayment" name="modeOfPayment" type="text" class="form-control" value="{{ $postDetails->MOP }}" readonly >
                        </div>
                    </div>
@php
$foo = $post->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount}}"  readonly >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="purpose">Purpose</label>
                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $postDetails->PURPOSED }}" readonly></textarea>                              
                        </div>
                        
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="addNewData" tabindex="-1" aria-labelledby="addNewDataLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="addNewDataLabel">Liquidation Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        {{-- START ADD MODAL--}}
                        <div class="container-fluid">
                        <div class="p-3 mb-2 bg-success text-white d-none" id="liqsuccessdiv">Added Successfully</div>                                             

                            <div class="row">
                                <div class="col-md-12">
                                    <form action="#">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" aria-describedby="helpId" id="addDate">
                                                <script>
                                                    var today = new Date();
                                                    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                                                    var data = date;
                                                    document.getElementById("addDate").valueAsDate = new Date(data);
                                                </script>
                                            </div>
                                        </div>


                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="">Client Name</label>
                                                <select id="liqclientid" name="liqclientid" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" onchange="getLiqClientName(this)"  >
                                                    <option selected="selected" hidden disabled value="0">Select Client Name</option>
                                                    {{-- @foreach ($projects as $prj)
                                                         <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                                    @endforeach --}}

                                                    @foreach ($businesslist as $business )
                                                    <option value="{{ $business->Business_Number }}">{{ $business->business_fullname }}</option>
                                                    @endforeach
                                                </select>
                                            <span class="text-danger" id="liqclientErr"></span>                                                  
    
                                            </div>
                                        </div>   

                                    </div>
                                    <div class="row"> 

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Expense Type</label>
                                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;"  id="addExpType">
                                                    @foreach ($expenseType as $xpType)
                                                    <option value="{{$xpType->type}}">{{$xpType->type}}</option>
                                                    @endforeach
                                                </select>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Currency</label>
                                                <select  class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;"  id="addCurr">
                                                    @foreach ($currencyType as $cuType)
                                                    <option value="{{$cuType->CurrencyName}}">{{$cuType->CurrencyName}}</option>
                                                    @endforeach
                                                </select>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input type="number" class="form-control" placeholder="0.00" aria-describedby="helpId"  id="addAmnt" >
                                                <span class="text-danger" id="addAmntErr"></span>                                                  

                                            </div>
                                        </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Description</label>
                                                    <textarea class="form-control" rows="5"  placeholder="input text here" id="addDesc"></textarea>
                                                    <span class="text-danger" id="addDescErr"></span>                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- END ADD--}}
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="addDataInTbl()">Insert</button>
            
                        </div>
                    </div>
                    </div>
                </div>
                {{-- End Modal --}}

@if ( $post->UID == session('LoggedUser'))
{{-- <span>Logged user</span> --}}

                             {{-- Reply not editable forms--}}
                <!-- Modal Reply-->
                <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark" >
                        <h5 class="modal-title" id="replyModalLabel">Reply Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
<form action="{{ route('cla.replynoedit.post') }}" method="POST" id="replyForm" enctype="multipart/form-data">
                            @csrf
                        <div class="modal-body">
                            <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>
                            
                            <div class="container-fluid">
                            
                                <div class="row">
                                    <div class="col-md-12">                     
                                        <label for="replyRemarks">Remarks</label>
                                        <div class="card-body">
                                            <div class="form-floating">
                                                <input type="hidden" name="refNumberReply"  value="{{ $post->REQREF }}">
                                                <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                <input type="hidden" name="jsonData" id="jsonData" value="">
                                                <input type="hidden" name="clientName" id="clientName" value="{{ $postDetails->CLIENTNAME }}">
                                                <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                                                {{-- <input type="hidden" value="" name="deleteAttached" id="deleteAttached"> --}}
                                                <input type="hidden" value="" name="deleteAttached" id="toDelete">
                                                <input type="hidden" value="" name="liqclientname" id="liqclientname">


                                                <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Proceed" id="modalReplyClarityInit" >
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        
                    </div>
                    </div>
                </div>
                {{-- end Reply clarity not editable --}}

                

                {{-- liqTable --}}
                <div class="row">
                    <div class="col-md-12">
                        {{-- <label for="tableLiqCLaLabel" style="margin-left: 15px;">Liquidation Table</label> --}}
                        <div class="card card-gray" style="padding: 0px;" >

                        <div class="card-header col " style="height:50px;">
                            <div class="row ">
                                <div class="col" style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Liquidation Table</h3>  </div>
                                <button type="button" class="btn btn-success" style="width: 120px;  font-size: 13px;"  data-toggle="modal" data-target="#addNewData"><i class="fa fa-plus-circle" style="margin-right: 10px;" aria-hidden="true"></i>Add</button>
                            </div>
                        </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="tableLiqCLa" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Client Name</th>
                                        <th>Expense Type</th>
                                        <th>Description</th>
                                        <th>Currency</th>
                                        <th id="customerIDCell">Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="dataTableLiq">
                                        @foreach ($queLiquidatedT as $mLiqData)
                                        <tr>
                                            <td >{{ $mLiqData->trans_date }}</td>
                                            <td class="d-none">{{ $mLiqData->client_id }}</td>
                                            <td>{{ $mLiqData->client_name }}</td>
                                            <td >{{ $mLiqData->expense_type }}</td>
                                            <td >{{ $mLiqData->description }}</td>
                                            <td >{{ $mLiqData->currency }}</td>
@php
$foo = $mLiqData->Amount;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                            <td >{{ $myAMount }}</td>
                                                        
                                            <td>
                                                <a class="btn btn-danger removeRow" onclick="sampDel()">Delete</a>
                                            </td>
                                        </tr>   
                                        @endforeach


                                    </tbody>
                                </table>
                                    <div class="container">
                                        <div class="float-right">

@php
$foo = $qeSubTotal[0]->subTotalAmount ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                            <h6 style="margin-right:140px;">Total Amount: <span id ="spTotalAmount">{{ $myAMount }}</span></h6>
                                            {{-- <h6 style="margin-right:140px;">Total Amount: <span id ="tableLiqCLaAmount"></span></h6> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Liqtable --}}

                
                {{-- Attachments of no edit --}}


                                            {{-- Attachments --}}
                                            <label class="btn btn-primary" style="font-weight:normal;">
                                                Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                                            </label>
                                            <input type="hidden" value="" name="toDelete" id="toDelete">
                                            <span class="text-danger">@error('file')<br>{{ $message }}@enderror</span>
                                        
                                        
</form>

                                
                                            @if (!empty($filesAttached))
                                            <div class="row">
                                            <div class="col-md-12">
                                            <div class="card card-gray">
                                                <div class="card-header" style="height:50px;">
                                                    <div class="row ">
                                                        <div class="col"  ><h3 class="card-title">Attachments</h3></div>
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
                                                                <th >Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody >
                                                                @foreach ($filesAttached as $file )
                                                                <tr>
                                                                    <td>{{ $file->filename }}</td>
                                                                    <td>{{ $file->fileExtension }}</td>
                                                                    <td>{{ $file->filepath }}</td>
                                                                    <td>
                                                                        <a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
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
                                            @endif
                               
                                            {{-- End Attachments --}}
                {{-- End Attachments --}}
@else
{{-- <span>not logged user</span>                     --}}
@if (!empty($queLiquidatedT))
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray" style="padding: 0px;" >
            <div class="card-header col " style="height:50px;">
                <div class="row ">
                    <div class="col" style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Liquidation Table</h3>  </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="myTable" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client Name</th>
                        <th>Expense Type</th>
                        <th>Description</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($queLiquidatedT as $mLiqData)
                        <tr>
                            <td>{{ $mLiqData->trans_date }}</td>
                            <td>{{ $mLiqData->client_name }}</td>
                            
                            <td>{{ $mLiqData->expense_type }}</td>
                            <td>{{ $mLiqData->description }}</td>
                            <td>{{ $mLiqData->currency }}</td>
@php
$foo = $mLiqData->Amount;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                            <td>{{ $myAMount }}</td>
                            <td><button class="btn btn-danger" disabled>Delete</button></td>
                        </tr>   
                        @endforeach

                    

                    </tbody>
                </table>
                    <div class="container">
                        <div class="float-right">
@php
$foo = $qeSubTotal[0]->subTotalAmount ;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                            <h6 style="margin-right:140px;">Total Amount: <span id ="spTotalAmount">{{ $myAMount }}</span></h6>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endif


    
@if (!empty($filesAttached))
<div class="row">
<div class="col-md-12">
    <div class="card card-gray">
        <div class="card-header" style="height:50px;">
            <div class="row ">
                <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
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
                        @foreach ($filesAttached as $file )
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

                @endif

       


                
            
       
            </div> 
    </div>
</div>

{{-- End EDIT --}}

<?php
}
?>
                        {{-- Initiator Modal --}}
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
<form action="{{ route('cla.withdraw.post') }}" method="POST">
                                    @csrf
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="withdrawRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}">
                                                        <input type="hidden" value="{{ $post->ID }}" name="idName">
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

                        {{-- Approver Modal --}}


                        <!-- Approve Modal-->
                        <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark" >
                                <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
<form action="{{ route('cla.approve.post') }}" method="POST" >
                                    @csrf
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="approveRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        {{-- <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}"> --}}
                                                        <input type="hidden" value="{{ $post->ID }}" name="idName">
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






                    <!-- Reply(Approve Moded) Modal-->
                    <div class="modal fade"  id="replyModedModalRecipient" tabindex="-1" role="dialog" aria-labelledby="replyModedModalRecipient" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark" >
                            <h5 class="modal-title" id="replyModedModalRecipientLabel">Reply Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
<form action="{{ route('cla.approve.post') }}" method="POST" >
                                @csrf
                            <div class="modal-body">
                            <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">                     
                                            <label for="approveRemarks">Remarks</label>
                                            <div class="card-body">
                                                <div class="form-floating">
                                                    {{-- <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}"> --}}
                                                    <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                    <textarea class="form-control" placeholder="Leave a comment here" name="approveRemarks" id="replyModedModalRecipientRemarks" style="height: 100px"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Proceed" id="replyBtnModedModalRecipientRemarks">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
                            </div>
</form>
                        </div>
                        </div>
                    </div>

<script>
    $('#replyBtnModedModalRecipientRemarks').on('click', function(){
        if ($.trim($("#replyModedModalRecipientRemarks").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
    })
</script>











                        <!-- Reject Modal-->
                        <div class="modal fade"  id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark" >
                                <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
<form action="{{ route('cla.reject.post') }}" method="POST" >
                                    @csrf
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="replyRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                        <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
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
                        
    {{-- UI Attachment --}}
<script>
    $(document).ready(function() {
      $('#customFile').on("change", function() {
        let filenames = [];
        let files = this.files;
        if (files.length > 1) {
          filenames.push("Total Files (" + files.length + ")");
        } else {
          for (let i in files) {
            if (files.hasOwnProperty(i)) {
              filenames.push(files[i].name);
            }
          }
        }
        $(this)
          .next(".custom-file-label")
          .html(filenames.join(","));
      });
    });
</script>

<script>
    $('#replyEditableForm').on('click', function(){
        if ($.trim($("#modalreplyRemarks").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
    })
</script>

<script>
        $('#modalReplyClarityInit').on('click', function(){
        if ($.trim($("#replyRemarks").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Reply remarks is required.');
        return false;
        }
        
        if ($.trim($("#jsonData").val()) == "0") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Liquidation table is required.');
        return false;
        }
        
         else {
            getDataInit();
        }
    })
</script>

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
    function getLiqClientName(){
        let liqclientname = $( "#liqclientid option:selected" ).text();
        // alert(liqclientname);
        $('#liqclientname').val(liqclientname);

        console.log(liqclientname);
    }
</script>

@endsection

{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Delete attachment --}}

{{-- attachments --}}
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
            document.getElementById("toDelete").value = attachmentJson;
            var sa = document.getElementById("toDelete");
            console.log(sa);
        }
</script>


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

 



{{-- LiqTable --}}
<script>
    function totalSum(){
    var rowCount = $('#dataTableLiq tr').length;
    console.log(rowCount);
    }
    
    var tableDataList = [];
    var tableDataObj = [];


    function addDataInTbl(){

        var addAmntChecker = false;
        var addDescChecker = false;
        var liqclientidChecker = false;


        var addDate = $('#addDate').val();
        var addExpType = $('#addExpType').val(); 
        var addCurr = $('#addCurr').val(); 
        var addAmnt = $('#addAmnt').val(); 
        var addDesc = $('#addDesc').val(); 
        var liqclientid = $('#liqclientid').val();
        var liqclientnamedata = $('#liqclientname').val();

        console.log(addDesc);
        console.log(liqclientid)
        console.log(liqclientnamedata)


        if(liqclientid){
            liqclientidChecker = true;
            $('#liqclientErr').text('');
        }else{
            $('#liqclientErr').text('Client Name is required!');
            $('#liqsuccessdiv').addClass('d-none')
        }

        if(addAmnt){
            addAmntChecker = true;
            $('#addAmntErr').text('');
        }else{
            $('#addAmntErr').text('Amount is required!');
            $('#liqsuccessdiv').addClass('d-none')

        }

        if(addDesc){
            addDescChecker = true;
            $('#addDescErr').text('');
        }else{
            $('#addDescErr').text('Description is required!');
            $('#liqsuccessdiv').addClass('d-none')
        }

        if (addAmntChecker && addDescChecker && liqclientidChecker){
            $('#tableLiqCLa tbody').append('<tr>'+
                                            '<td>'+addDate+'</td>'+
                                            '<td class="d-none">'+liqclientid+'</td>'+
                                            '<td>'+liqclientnamedata+'</td>'+
                                            '<td>'+addExpType+'</td>'+
                                            '<td>'+addDesc+'</td>'+
                                            '<td>'+addCurr+'</td>'+
                                            '<td class="">'+addAmnt+'</td>'+
                                            '<td>'+
                                                '<a class="btn btn-danger removeRow" onclick="sampDel()">Delete</a>'+
                                            '</td>'+
                                        '</tr>'
        );

        $('#liqsuccessdiv').removeClass('d-none')
        // compute();
        getDataInit();
        $('#addAmnt').val('');
        $('#addDesc').val('');
        }


   

        
    }

    // function compute(){
    //     var sum = 0;
    //     $('#tableLiqCLa tbody tr td').eq(6).each(function()
    //     {
    //         sum += parseFloat($(this).text());
    //     });
    //     $('#spTotalAmount').text(sum);
    //     // liqUpdateData();
    // }



    function getDataInit(){

        var tblArry = [];
        var myAmt = 0 ;


        $("#tableLiqCLa > #dataTableLiq > tr").each(function () {
            var dateInit = $(this).find('td').eq(0).text();
            var clientIDInit = $(this).find('td').eq(1).text();
            var clientNameInit = $(this).find('td').eq(2).text();
            var xpType = $(this).find('td').eq(3).text();
            var descInit = $(this).find('td').eq(4).text();
            var currInit = $(this).find('td').eq(5).text();
            var amtInit = $(this).find('td').eq(6).text();

            var listArry = [];
            listArry.push(dateInit,clientIDInit,clientNameInit,xpType,descInit,currInit,amtInit);
            tblArry.push(listArry);
                     
        });

        
        var jsonLiqTbl = JSON.stringify(tblArry);
        console.log(jsonLiqTbl);


   


        for(var i = 0; i<tblArry.length; i++){
                var numAmt = tblArry[i]['6'];
                myAmt += parseFloat(numAmt);
        
            }

        console.log(tblArry);
        console.log(myAmt);
        document.getElementById('spTotalAmount').innerHTML = myAmt;

       

        if (jsonLiqTbl.length === 2) {
            jsonLiqTbl = 0;
        document.getElementById("jsonData").value = jsonLiqTbl;

        } else {
            // alert('else');
        document.getElementById("jsonData").value = jsonLiqTbl;

        }


        return false;

    }



  

    

  
    function sampDel(){

    $('#tableLiqCLa').on('click','tr a.removeRow',function(e){
    e.preventDefault();
    $(this).closest('tr').remove();

    getDataInit();

    });
    

    }

</script>



{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



