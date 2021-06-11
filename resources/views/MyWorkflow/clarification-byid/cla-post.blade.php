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
 
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right "disabled >Withdraw</button></div>        
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" data-toggle="modal" data-target="#rejectModal" >Reject</button></div>      
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal">Approve</button></div>   
                            
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
            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}"   >
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

                            {{-- Upload --}}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>Upload Files</strong></label>
                                        <div class="custom-file">
                                        <input type="file" name="file[]" multiple class="custom-file-input form-control" id="customFile" style="cursor: pointer;">
                                        <label class="custom-file-label" >Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Attachments --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-gray">
                                        <h5 class="card-header">Attachmentssss</h5>
                                        <div class="card-body" >
    
                                            <div class="row">
                                                @php($count=0)
                                                @foreach ($filesAttached as $file)
                                                @php($count++)
                                               
                                                <div class="col-sm-2" id="{{ $count }}">
    
                                                    <div class="dropdown show" >
                                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute; right: 0px; top: 0px; z-index: 999; "></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                          <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
                                                          <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" download="{{ $file->filename }}" >Download</a>
                                                          <a class="dropdown-item" onclick="removedAttach(this)" style="cursor: pointer;" >Delete<input type="hidden" value="{{ $file->id }}"><input type="hidden" value="{{ $file->filepath }}"><input type="hidden" value="{{ $file->filename }}"></a>
                                                        </div>
                                                    </div>
                                                    <div class="card">
    
                                                        <?php
                                                            if ($file->fileExtension == 'jpg' or $file->fileExtension == 'JPG' or $file->fileExtension == 'png' or $file->fileExtension == 'PNG') { ?>
                                                                <a href="#" style="padding: 10px;"><img src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" class="card-img-top"  style="width:100%; height:200px; object-fit: cover" alt="..."></a>
                                                        <?php
                                                            }if ($file->fileExtension == 'pdf' or $file->fileExtension == 'PDF' or $file->fileExtension == 'log' or $file->fileExtension == 'LOG' or $file->fileExtension == 'txt' or $file->fileExtension == 'TXT') { ?>
                                                            <a href="#" style="padding: 10px;"><iframe class="embed-responsive-item" src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" frameborder="0" scroll="no" style="height:200px; width:100%;"></iframe></a>
                                                        <?php
                                                            }if ($file->fileExtension == 'PDF' or $file->fileExtension == 'pdf') {
                                                                # code...
                                                            } 
                                                        ?>
                                    
                                                      <div class="card-body" style="padding: 5px; ">
                                                        <p class="card-text text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $file->filename }}</p>
                                                      </div>
                                                    </div>
                                                </div>  

                                                @endforeach
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
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">                     
                                                    <label for="replyRemarks">Remarks</label>
                                                    <div class="card-body">
                                                        <div class="form-floating">
                                                            <input type="hidden" name="refNumberReply"  value="{{ $post->REQREF }}">
                                                            <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                            <input type="hidden" value="" name="toDelete" id="toDelete">
                                                            <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <input type="submit" class="btn btn-primary" value="Proceed" onclick="deleteAttached()" >
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

                <!-- Modal -->
                <div class="modal fade" id="addNewData" tabindex="-1" aria-labelledby="addNewDataLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="addNewDataLabel">Luquidation Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        {{-- START ADD MODAL--}}
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="#">
                                        <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" aria-describedby="helpId" id="addDate">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Expense Type</label>
                                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addExpType">
                                                    @foreach ($expenseType as $xpType)
                                                    <option value="{{$xpType->type}}">{{$xpType->type}}</option>
                                                    @endforeach
                                                </select>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Currency</label>
                                                <select  class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addCurr">
                                                    @foreach ($currencyType as $cuType)
                                                    <option value="{{$cuType->CurrencyName}}">{{$cuType->CurrencyName}}</option>
                                                    @endforeach
                                                </select>

                                                
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input type="number" class="form-control" placeholder="0.00" aria-describedby="helpId" required id="addAmnt" value="0.00">

                                            </div>
                                        </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Description</label>
                                                    <textarea class="form-control" rows="5"  placeholder="input text here" required id="addDesc"></textarea>

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
                                                <input type="hidden" value="" name="deleteAttached" id="deleteAttached">

                                                <textarea class="form-control" placeholder="Leave a comment here" name="replyRemarks" id="replyRemarks" style="height: 100px"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Proceed" onclick="proceedSubm()">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        
                    </div>
                    </div>
                </div>
                {{-- end Reply clarity not editable --}}

                {{-- Upload No edit--}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Upload Files</strong></label>
                            <div class="custom-file">
                            <input type="file" name="file[]" multiple class="custom-file-input form-control" id="customFile" style="cursor: pointer;">
                            <label class="custom-file-label" >Choose file</label>
                            </div>
                        </div>
                    </div>
                </div>

</form>
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
                                            <td >{{ $mLiqData->expense_type }}</td>
                                            <td >{{ $mLiqData->description }}</td>
                                            <td >{{ $mLiqData->currency }}</td>
                                            <td >{{ $mLiqData->Amount }}</td>
                                            
                                            <td>
                                                <a class="btn btn-danger removeRow" onclick="sampDel()">Delete</a>
                                            </td>
                                        </tr>   
                                        @endforeach


                                    </tbody>
                                </table>
                                    <div class="container">
                                        <div class="float-right">
                                            <h6 style="margin-right:140px;">Total Amount: <span id ="tableLiqCLaAmount"></span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Liqtable --}}

                
                {{-- Attachments of no edit --}}
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
                                    @foreach ($filesAttached as $file)
                                    <div class="col-sm-2" >

                                        <div class="dropdown show" >
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute; right: 0px; top: 0px; z-index: 999; "></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
                                                <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" download="{{ $file->filename }}" >Download</a>
                                                <a class="dropdown-item" onclick="removedAttached(this)" style="cursor: pointer;" >Delete<input type="hidden" value="{{ $file->id }}"><input type="hidden" value="{{ $file->filepath }}"><input type="hidden" value="{{ $file->filename }}"></a>
                                            </div>
                                        </div>
                                        <div class="card">

                                            <?php
                                                if ($file->fileExtension == 'jpg' or $file->fileExtension == 'JPG' or $file->fileExtension == 'png' or $file->fileExtension == 'PNG') { ?>
                                                    <a href="#" style="padding: 10px;"><img src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" class="card-img-top"  style="width:100%; height:200px; object-fit: cover" alt="..."></a>
                                            <?php
                                                }if ($file->fileExtension == 'pdf' or $file->fileExtension == 'PDF' or $file->fileExtension == 'log' or $file->fileExtension == 'LOG' or $file->fileExtension == 'txt' or $file->fileExtension == 'TXT') { ?>
                                                <a href="#" style="padding: 10px;"><iframe class="embed-responsive-item" src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" frameborder="0" scroll="no" style="height:200px; width:100%;"></iframe></a>
                                            <?php
                                                }if ($file->fileExtension == 'PDF' or $file->fileExtension == 'pdf') {
                                                    # code...
                                                } 
                                            ?>
                        
                                            <div class="card-body" style="padding: 5px; ">
                                            <p class="card-text text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $file->filename }}</p>
                                            </div>
                                        </div>
                                    </div>  

                                    @endforeach
                                </div>   
                            </div>
                            
                            </div>
                    </div>
                </div>
                {{-- End Attachments --}}


                
            
       
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

@endsection

{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Delete attachment --}}
<script>
objectAttachment = [];

function removedAttach(elem){
    var attachmentArray = [];

    var x =  $(elem).parent("div").parent("div").parent("div").fadeOut(300);
    var idAttachment = $(elem).children("input").val();
    var pathAttachment = $(elem).children("input").next().val();
    var fileNameAttachment = $(elem).children("input").next().next().val();

    
    attachmentArray.push(idAttachment,pathAttachment,fileNameAttachment);

    objectAttachment.push(attachmentArray);
    console.log(attachmentArray);
    console.log(objectAttachment);

}


function deleteAttached(){
    var attachmentJson = JSON.stringify(objectAttachment);
    document.getElementById("toDelete").value = attachmentJson;

}
</script>




{{-- attachments no edit main table --}}
<script>
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
            var sa = document.getElementById("deleteAttached");
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

        var addDate = $('#addDate').val();
        var addExpType = $('#addExpType').val(); 
        var addCurr = $('#addCurr').val(); 
        var addAmnt = $('#addAmnt').val(); 
        var addDesc = $('#addDesc').val(); 

        var rowCount = $('#dataTableLiq tr').length;
        console.log(rowCount);

        var rowCounts = $('#dataTableLiq tr');
        console.log(rowCounts);

        $('#tableLiqCLa tbody').append('<tr>'+
                                            '<td>'+addDate+'</td>'+
                                            '<td>'+addExpType+'</td>'+
                                            '<td>'+addDesc+'</td>'+
                                            '<td>'+addCurr+'</td>'+
                                            '<td>'+addAmnt+'</td>'+
                                            '<td>'+
                                                '<a class="btn btn-danger removeRow" onclick="sampDel()">Delete</a>'+
                                            '</td>'+
                                        '</tr>'
        );


        getDataInit();
        
    }

    function getDataInit(){

        var tblArry = [];
        var myAmt = 0 ;


        $("#tableLiqCLa > #dataTableLiq > tr").each(function () {
            var dateInit = $(this).find('td').eq(0).text();
            var xpType = $(this).find('td').eq(1).text();
            var descInit = $(this).find('td').eq(2).text();
            var currInit = $(this).find('td').eq(3).text();
            var amtInit = $(this).find('td').eq(4).text();


            var listArry = [];
            listArry.push(dateInit,xpType,descInit,currInit,amtInit);
            tblArry.push(listArry);
                     
        });

        for(var i = 0; i<tblArry.length; i++){
                var numAmt = tblArry[i]['4'];
                myAmt += parseInt(numAmt);
        
            }

        console.log(tblArry);
        console.log(myAmt);
        document.getElementById('tableLiqCLaAmount').innerHTML = myAmt;



        var jsonLiqTbl = JSON.stringify(tblArry);
        document.getElementById("jsonData").value = jsonLiqTbl;

        return false;

    }

    function proceedSubm(){
        getDataInit();
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



