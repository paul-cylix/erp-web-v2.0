@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')

    <div class="row" >

        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                    <div class="col-md-1 float-left"><a href="/approvals" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <?php 
                        if($initCheckAppr == True){
                            ?>
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                            <div class="col-md-1 float-right" ><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled >Clarify</button></div>                    
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right"  data-toggle="modal" data-target="#initApproveMdl">Approve</button></div>

                        <?php
                        }else{
                        ?>
                        
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                            <div class="col-md-1 float-right" ><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" data-toggle="modal" data-target="#clarityModal">Clarify</button></div>                    
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" data-toggle="modal" data-target="#declineModal">Reject</button></div>      
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right"  data-toggle="modal" data-target="#approveModal">Approve</button></div>

                        <?php
                        } 
                    ?>
            </div> 
        </div> 


        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">{{ $payeeDetails->FRM_NAME }}</h3>
                </div>
                        <div class="col-md-12">
                            @if(Session::has('form_submitteds'))
                            <div class="alert alert-danger col-md-12" style="margin-top: 5px;" role="alert">{{ Session::get('form_submitteds') }}     
                            @endif
                        </div>

                <div class="card-body">




{{-- Condition for Initiator --}}
<?php
    if ($liqTableCondition == 1 ) {
        ?>
    
{{-- start --}}

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

                <input type="hidden" value="{{ $post->ID }}" name="idName">
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
                        <div class="input-group date" data-target-input="nearest" >
                            <input type="input" id="dateNeeded" name="dateNeeded"  class="form-control datetimepicker-input" value="{{ $postDetails->DATENEEDED }}" readonly />
                            <div class="input-group-append" data-toggle="datetimepicker">

                                <div class="input-group-text" ><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>

                    </div>         
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payeeName">Payee Name</label>
                        {{-- <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}"  > --}}
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
                        <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}" readonly   >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4"  readonly>{{ $postDetails->PURPOSED }}</textarea>                              
                    </div>

                </div>
            </div>

{{-- end --}}



    <div class="row">
        <div class="col-md-12">

            <!-- Modal -->
            <div class="modal fade" id="liquidationModal" tabindex="-1" aria-labelledby="liquidationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="liquidationModalLabel">Add Liquidation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>

                
                        <div class="modal-body"> 
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="#">
                                            <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" placeholder="" aria-describedby="helpId" id="liqdate">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Expense Type</label>
                                                    <select id="liqtype" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                    @foreach ($expenseType as $xpType)
                                                    <option value="{{$xpType->type}}">{{$xpType->type}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Currency</label>
                                                    <select id="liqcurr" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                    @foreach ($currencyType as $cuType)
                                                    <option value="{{$cuType->CurrencyName}}">{{$cuType->CurrencyName}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Amount</label>
                                                    <input type="number" name="amount"class="form-control" placeholder="0.00" aria-describedby="helpId" id="liqamnt">
                                                </div>
                                            </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Description</label>
                                                        <textarea class="form-control" rows="5" id="liqdesc" placeholder="input text here"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="addRow()">Insert</button>
                            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Upload --}}
    <div class="row" >
        <div class="col-md-3">
            <div class="form-group">
<form action="{{ route('save.table.attachment') }}" method="POST" enctype="multipart/form-data" >
    @csrf
                <label><strong>Upload Files</strong></label>
                <div class="custom-file">
                <input type="file" name="file[]" multiple class="custom-file-input form-control" value="2" id="customFile" style="cursor: pointer;">
                <label class="custom-file-label" for="customFile">Choose file</label>
                {{-- <input type="hidden" name="files" id="file" value=""> --}}
                <input type="hidden" value="" name="toDelete" id="toDelete">

        
                </div>
    <span class="text-danger">@error('file')<br>{{ $message }}@enderror</span>

            </div>
        </div>
    </div>
    {{-- Upload --}}



    {{-- Attachments --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">

                <div class="card-header">
                    <h3 class="card-title">Attachments</h3>
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
    

    
    {{-- Liq table ng Initiator --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray" style="padding: 0px;" >
                <div class="card-header col " style="height:50px;">
                    <div class="row ">
                        <div class="col" style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Liquidation Table</h3>  </div>
                        <button type="button" class="btn btn-success" style="width: 120px;  font-size: 13px;"  data-toggle="modal" data-target="#liquidationModal"><i class="fa fa-plus-circle" style="margin-right: 10px;" aria-hidden="true"></i>Add</button>
                    </div>
                </div>
                
                
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="myTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Expense Type</th>
                                <th>Description</th>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($qeLiquidationTable as $qeData)
                                <tr>
                                    <td>{{ $qeData->trans_date }}</td>
                                    <td>{{ $qeData->expense_type }}</td>
                                    <td>{{ $qeData->description }}</td>
                                    <td>{{ $qeData->currency }}</td>
                                    <td>{{ $qeData->Amount }}</td>
                                    <td><button class="btn btn-danger" disabled>Delete</button></td>
                                </tr>   
                                @endforeach
                            </tbody>

                        </table>
                            <div class="container">
                                <div class="float-right">
                                    <h6 style="margin-right:140px;">Total Amount: <span id ="spTotalAmount"></span></h6>                               
                                </div>
                            </div>
            <span class="text-danger">@error('liquidationTable'){{ $message }}@enderror</span>
                        </div>
                    </div>   
            </div>
        </div>
    </div>



    

    {{-- Sweet Alert for Required field --}}
    @error('file')              
    <Script>
        swal({
            text: "Complete all the Required forms!",
            icon: "error",
            closeOnClickOutside: false,
            closeOnEsc: false,        
            })
    </Script>
    @enderror

    @error('liquidationTable')              
    <Script>
        swal({
            text: "Complete all the Required forms!",
            icon: "error",
            closeOnClickOutside: false,
            closeOnEsc: false,        
            })

        </Script>
    @enderror


    


        <!-- Modal Approve initiator-->
        <div class="modal fade"  id="initApproveMdl" tabindex="-1" role="dialog" aria-labelledby="initApproveMdl" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark" >
                <h5 class="modal-title" id="initApproveMdlLabel">Approve Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">                     
                                <label for="approvedRemarks">Remarks</label>
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="hidden" name="refClientName" value="{{ $postDetails->CLIENTNAME }}">
                                        <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}">
                                        <input type="hidden" name="liquidationTable" value="" id="liquidationTable">
                                        <input type="hidden" value="<?php echo $liqTableCondition ?>" name="liqTableCondition">
                                        <input type="hidden" value="{{ $post->ID }}" name="idName">
                                        <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks"  style="height: 100px"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-primary"  onclick="submitAll()" value="Proceed">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
</form>
            </div>
            </div>
        </div>



{{-- Approver Part --}}
<?php
    }else { ?>
        
                    {{-- start --}}
                    <form action="#" id="form-id">
                  
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Numbers</label>
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

                            <input type="hidden" value="{{ $post->ID }}" name="idName">
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
                                    <div class="input-group date" data-target-input="nearest" >
                                        <input type="input" id="dateNeeded" name="dateNeeded"  class="form-control datetimepicker-input" value="{{ $postDetails->DATENEEDED }}" readonly />
                                        <div class="input-group-append" data-toggle="datetimepicker">

                                            <div class="input-group-text" ><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>

                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payeeName">Payee Name</label>
                                    {{-- <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}"  > --}}
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
                                    <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}" readonly   >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="purpose">Purpose</label>
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4"  readonly>{{ $postDetails->PURPOSED }}</textarea>                              
                                </div>
            
                            </div>
                        </div>




                    </form>
                    {{-- end --}}

                        <?php 
                        // SHOW LIQUIDATION TABLE TO APPROVER 
                                if($initiatorCheck == true || $acknowledgeCheck == true){
                                    ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card card-gray" style="padding: 0px;">
                                                        <div class="card-header col" style="height: 48px;">
                                                            <div class="row ">
                                                                <div class="col" style=" font-size:18px;">Liquidation Table</div>
                                                            </div>
                                                        </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                <table id="myTable" class="table table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Date</th>
                                                                        <th>Expense Type</th>
                                                                        <th>Description</th>
                                                                        <th>Currency</th>
                                                                        <th>Amount</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($qeLiquidationTable as $qeData)
                                                                        <tr>
                                                                            <td>{{ $qeData->trans_date }}</td>
                                                                            <td>{{ $qeData->expense_type }}</td>
                                                                            <td>{{ $qeData->description }}</td>
                                                                            <td>{{ $qeData->currency }}</td>
                                                                            <td>{{ $qeData->Amount }}</td>
                                                                            <td><button class="btn btn-danger" disabled>Delete</button></td>
                                                                        </tr>   
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                    <div class="container">
                                                                        <div class="float-right">       
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                    <?php
                    }
                    ?>


                        {{-- Attachments --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="height:50px;">
                                        <div class="row ">
                                            <div  style=" font-size:18px;"><h3 class="card-title">Attachments</h3></div>
                                        </div>
                                    </div>
                                    <div class="card-body" >

                                        <div class="row">
                                            @foreach ($filesAttached as $file)                                      
                                            <div class="col-sm-2">
                                                <div class="dropdown show" >
                                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute; right: 0px; top: 0px; z-index: 999; "></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                      <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a>
                                                      <a class="dropdown-item" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" download="{{ $file->filename }}" >Download</a>
                                     
                                                    </div>
                                                </div>

                                                <div class="card">

                                                    <?php
                                                        if ($file->fileExtension == 'jpg' or $file->fileExtension == 'JPG' or $file->fileExtension == 'png' or $file->fileExtension == 'PNG') { ?>
                                                            <a href="#"><img src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" class="card-img-top"  style="width:100%; height:200px; object-fit: cover" alt="..."></a>
                                                    <?php
                                                        }if ($file->fileExtension == 'pdf' or $file->fileExtension == 'PDF' or $file->fileExtension == 'log' or $file->fileExtension == 'LOG' or $file->fileExtension == 'txt' or $file->fileExtension == 'TXT') { ?>
                                                        <iframe class="embed-responsive-item" src="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" frameborder="0" scroll="no" style="height:200px;"></iframe>
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
                        {{-- Attachments --}}

                        
                    <?php
                    }
                    ?>
                    
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
            window.location.href = "/approvals";
            }});
        </Script>
    @endif

{{-- Reporting Manager --}}
  <!-- Modal Approve Approver -->
  <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
<form action="{{ route('app.approved.post') }}" method="POST" >
            @csrf
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">                     
                        <label for="approvedRemarks">Remarks</label>
                        <div class="card-body">
                            <div class="form-floating">
                                <input type="hidden" name="refClientName" value="{{ $postDetails->CLIENTNAME }}">
                                <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}">
            
                                <input type="hidden" value="<?php echo $liqTableCondition ?>" name="liqTableCondition">
                                <input type="hidden" value="{{ $post->ID }}" name="idName">
                                <textarea class="form-control" placeholder="Leave a comment here" name="approvedRemarks"  style="height: 100px"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <input type="submit" class="btn btn-primary" onclick="submitAll()" value="Proceed">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
</form>
      </div>
    </div>
  </div>




  {{-- Modal Rejected --}}
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-dark" >
              <h5 class="modal-title" id="declineModalLabel">Decline Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <form action="{{ route('app.rejected.post') }}" method="POST">
                @csrf
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="rejectedRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" value="{{ $post->ID }}" name="idName">
                                    <input type="hidden" name="refNumberApp"  value="{{ $post->REQREF }}">

                                    <textarea class="form-control" placeholder="Leave a comment here" name="rejectedRemarks" id="rejectedRemarks" style="height: 100px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Proceed"></input>
            {{-- <button type="button" class="btn btn-primary">Proceed</button> --}}
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
      </div>
     

    {{-- Modal Clarity with message--}}
    <div class="modal fade" id="clarityModal" tabindex="-1" role="dialog" aria-labelledby="clarityModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
            <h5 class="modal-title" id="clarityModalLabel">Clarity Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>


            <form action="{{ route('app.clarification.post') }}" method="POST">
                @csrf
            </div>
            <div class="modal-body">
                <div class="container-fluid">


                    {{-- new --}}
                    <div class="row">
                        <div class="col-md-12">
                            <label for="clarityRecipient">Choose Recipient</label>
                            <select id="clarityRecipient" name="clarityRecipient" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                            
                                @foreach($getRecipientName as $recipientName)
                                    <option value="{{ $recipientName->uid }}">{{ $recipientName->Name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" name="refNumberApp" value="{{ $post->REQREF }}">
                    <input type="hidden" name="inProgressID" value="{{ $qeInProgressID }}">
                    <input type="hidden" name="proccessID" value="{{ $id }}">
                    <input type="hidden" name="frmName" value="{{ $payeeDetails->FRM_NAME }}">
                  
                    {{-- new --}}
                   

                    <div class="row" style="margin-top: 7px;">
                        <div class="col-md-12">                     
                            <label for="clarificationRemarks">Message</label>
                            {{-- <div class="card-body"> --}}
                                <div class="form-floating">
                                    <input type="hidden" value="{{ $post->ID }} " name="idName">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="clarificationRemarks" id="clarificationRemarks" style="height: 100px"></textarea>
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Proceed"></input>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
        </div>
    </div>
    {{-- End Modal --}}






{{-- end of form cards --}}
                    </div> 
            </div>
        </div>

    </div>




<script>
    $(document).ready(function() {
        $('input[type="file"]').on("change", function() {
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
            $('#files').val(files.length);
            console.log(files.length);
        });
    });
</script>



@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
{{-- Modal JS --}}
{{-- <script>
$(document).on("click", ".open-approveModal", function () {
     var myapprovedRemarks = $(this).data('id');
     $(".modal-body #approvedRemarks").val( myapprovedRemarks );
     // As pointed out in comments, 
     // it is unnecessary to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});
</script> --}}


{{-- attachments --}}
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

            var attachmentJson = JSON.stringify(objectAttachment);
            document.getElementById("toDelete").value = attachmentJson;
            var sa = document.getElementById("toDelete");
            console.log(sa);
        }
</script>



{{-- Liquidation Table --}}
<script>
    var myTableArr = [];
    let totalAmnt = [];
    let liqid = 0;

   var qeSubTotal= "<?php echo $qeSubTotal ?>";
   var checker = false;

   qeSubTotal = parseInt(qeSubTotal);
//    console.log(typeof qeSubTotal);
   if (qeSubTotal > 0){
       checker = true;
    //    console.log(checker)
   } else {
       checker = false;
    //    console.log(checker)
   }

 

    // var myTableBody = document.getElementById('myTable').getElementsByTagName('tbody')[0],sumVal = 0;

    function addRow(){
         //Identifier
        var tbodyRef = document.getElementById('myTable').getElementsByTagName('tbody')[0];

        var liqdate = document.getElementById('liqdate').value;
        var liqtype = document.getElementById('liqtype').value;
        var liqdesc = document.getElementById('liqdesc').value;
        var liqcurr = document.getElementById('liqcurr').value;
        var liqamnt = document.getElementById('liqamnt').value;

        var x = myTableArr.length;
        
        if (x > 0){
            liqid = liqid + 1;
        } else {
            liqiq = 0;
        }
    
        var newRow = tbodyRef.insertRow();
        
        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
        var cell5 = newRow.insertCell(4);
        var cell6 = newRow.insertCell(5);
        
        var z = newRow.id =liqid;

        cell1.innerHTML = liqdate;
        cell2.innerHTML = liqtype;
        cell3.innerHTML = liqdesc;
        cell4.innerHTML = liqcurr;
        cell5.id = 'myliqAmnt'
        cell5.innerHTML = liqamnt;
        cell6.id = liqid;
        cell6.innerHTML = '<input type="button" value="Delete" id='+liqid+' onclick="deleteRow(this)" class="btn btn-danger">';

       
        var listArr = [];
        listArr.push(liqdate,liqtype,liqdesc,liqcurr,liqamnt);
        myTableArr.push(listArr);

        // console.log(myTableArr);
        calculate();
    }

    function deleteRow(e){
        var row = document.getElementById(e.id);
        row.parentElement.removeChild(row); 
        console.log(e.id);
        myTableArr[e.id].splice(0,5);
        calculate();
        
    }

    function calculate (){
        var newArr = [];

        for(var i = 0; i < myTableArr.length; i++)
        {
        newArr = newArr.concat(myTableArr[i][4]);
        }

        var listAmount = newArr.map((i) => Number(i));
        console.log(listAmount);
        console.log(newArr);
        console.log(myTableArr);




        const quickSum = (listAmount) => {
        const sum = listAmount.reduce((acc, val) => {
        return acc + (val || 0);
        }, 0);
        return sum;
        };

        var quickTotalAmount = quickSum(listAmount);
        if(checker == true){
        var myTotalAmount = document.getElementById('spTotalAmount').innerHTML = quickTotalAmount + qeSubTotal;
        }else{
        var myTotalAmount = document.getElementById('spTotalAmount').innerHTML = quickTotalAmount;
        }
        

    }

    function submitAll(){
        var arrFiltered = myTableArr.filter(el => {
        return el != null && el != '';
        });

        if(arrFiltered.length){
        var myJSON = JSON.stringify(arrFiltered);
        document.getElementById("liquidationTable").value = myJSON;
        console.log(myJSON);
    }
    }
</script>

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

<script type="text/javascript">
    $(function () {
        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });
    });
 </script>

 <script>
    // function editForm(){
    //     var form = document.getElementById("form-id");
    //     form.submit();
    // }

    function getRMName(sel) {
        var rm_txt = sel.options[sel.selectedIndex].text;
        document.getElementById("RMName").value = rm_txt;
    }
 </script>