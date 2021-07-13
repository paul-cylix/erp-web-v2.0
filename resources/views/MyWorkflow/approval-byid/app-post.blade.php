@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')

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
                            <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" onclick="approvedbyInit()"  data-toggle="modal" data-target="#initApproveMdl">Approve</button></div>

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




@if (!empty($liqTableCondition))  {{-- Initiator --}}
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
<input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"  readonly >

{{--     
    <div class="col-md-3">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}" readonly   > --}}
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





    <!-- Modal Liquidation--> 
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
                        <div class="p-3 mb-2 bg-success text-white d-none" id="liqsuccessdiv">Added Successfully</div>                                             

                        <div class="row">
                            <div class="col-md-12">
                                <form action="#">
                            <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date</label>
                            
                                            <input type="date" class="form-control"   aria-describedby="helpId" id="liqdate">
                                            <script>
                                                var today = new Date();
                                                var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                                                var data = date;
                                                document.getElementById("liqdate").valueAsDate = new Date(data);
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
                                        <select id="liqtype" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        @foreach ($expenseType as $xpType)
                                        <option value="{{$xpType->type}}">{{$xpType->type}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Currency</label>
                                        <select id="liqcurr" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        @foreach ($currencyType as $cuType)
                                        <option value="{{$cuType->CurrencyName}}">{{$cuType->CurrencyName}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Amount</label>
                                        <input type="number" name="amount"class="form-control" placeholder="0.00" aria-describedby="helpId" id="liqamnt">
                                        <span class="text-danger" id="liqamntErr"></span>                                                  

                                    </div>
                                </div>
                            </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea class="form-control" rows="5" id="liqdesc" placeholder="input text here"></textarea>
                                                <span class="text-danger" id="liqdescErr"></span>                                                  

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




    {{-- Attachments --}}
    <form action="{{ route('save.table.attachment') }}" method="POST" enctype="multipart/form-data" >
        @csrf
    <label class="btn btn-primary" style="font-weight:normal;">
        Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
    </label>
    <input type="hidden" value="" name="toDelete" id="toDelete">
    <input type="hidden" value="" name="liqclientname" id="liqclientname">
    {{-- <input type="hidden" value="" name="liqclientid" id="liqclientid"> --}}

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
  



    {{-- Liq table ng Initiator --}}
    <div class="row">
    <div class="col-md-12">
    <div class="card card-gray" style="padding: 0px;" >
        <div class="card-header col " style="height:50px;">
            <div class="row ">
                <div class="col" >
                    <h3 class="card-title">Liquidation Table</h3>  </div>
                <button type="button" class="btn btn-success" style="width: 120px;  font-size: 13px;"  data-toggle="modal" data-target="#liquidationModal"><i class="fa fa-plus-circle" style="margin-right: 10px;" aria-hidden="true"></i>Add</button>
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
                        @foreach ($qeLiquidationTable as $qeData)
                        <tr>
                            <td>{{ $qeData->trans_date }}</td>
                            <td>{{ $qeData->client_name }}</td>
                            <td>{{ $qeData->expense_type }}</td>
                            <td>{{ $qeData->description }}</td>
                            <td>{{ $qeData->currency }}</td>

@php
$foo = $qeData->Amount;
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
                            <h6 style="margin-right:140px;">Total Amount: <span id ="spTotalAmount"></span></h6>                               
                        </div>
                    </div>
    <span class="text-danger">@error('liquidationTable'){{ $message }}@enderror</span>
                </div>
            </div>   
    </div>
    </div>
    </div>


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

@else   {{--  Approver  --}}
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
    <input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"  readonly >




        {{-- <div class="col-md-3">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input id="amount" name="amount" type="number" class="form-control" value="{{ $post->AMOUNT }}" readonly   > --}}
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


    {{-- @if (!empty($qeLiquidationTable))
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
    @endif --}}

@if (!empty($qeLiquidationTable))
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
                        @foreach ($qeLiquidationTable as $qeData)
                        <tr>
                            <td>{{ $qeData->trans_date }}</td>
                            <td>{{ $qeData->client_name }}</td>
                            <td>{{ $qeData->expense_type }}</td>
                            <td>{{ $qeData->description }}</td>
                            <td>{{ $qeData->currency }}</td>
@php
$foo = $qeData->Amount;
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
$foo = $qeSubTotal;
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
        function getLiqClientName(){
            let liqclientname = $( "#liqclientid option:selected" ).text();
            // alert(liqclientname);
            $('#liqclientname').val(liqclientname);
        }
    </script>






    
<script>
// Paul
    function addRow(){

        var liqamntChecker = false;
        var liqdescChecker = false;
        var liqclientidChecker = false;


        
        var liqamnt = $('#liqamnt').val();
        var liqdesc = $('#liqdesc').val();
        var liqdate = $('#liqdate').val();
        var liqtype = $('#liqtype').val();
        var liqcurr = $('#liqcurr').val();
        var liqclientid = $('#liqclientid').val();
        var liqclientnamedata = $('#liqclientname').val();

        console.log(liqclientid)
        console.log(typeof(liqclientid))


        if(liqclientid){
            liqclientidChecker = true;
            $('#liqclientErr').text('');
        }else{
            $('#liqclientErr').text('Client Name is required!');
            $('#liqsuccessdiv').addClass('d-none')
        }

        if(liqamnt){
            liqamntChecker = true;
            $('#liqamntErr').text('');
        }else{
            $('#liqamntErr').text('Amount is required!');
            $('#liqsuccessdiv').addClass('d-none')
        }

        if(liqdesc){
            liqdescChecker = true;
            $('#liqdescErr').text('');
        }else{
            $('#liqdescErr').text('Description is required!');
            $('#liqsuccessdiv').addClass('d-none')
        }

        if (liqamntChecker && liqdescChecker && liqclientidChecker){

            $('#myTable tbody').append('<tr>'+
                                        '<td>'+liqdate+'</td>'+
                                        '<td class="d-none">'+liqclientid+'</td>'+
                                        '<td>'+liqclientnamedata+'</td>'+
                                        '<td>'+liqtype+'</td>'+
                                        '<td>'+liqdesc+'</td>'+
                                        '<td >'+liqcurr+'</td>'+
                                        '<td class="tdliqamnt">'+liqamnt+'</td>'+
                                        '<td>'+
                                            '<a class="btn btn-danger removeliqRow" onClick ="liqdeleteRow()" >Delete</a>'+
                                        '</td>'+
                                    '</tr>'
            );

        $('#liqsuccessdiv').removeClass('d-none')
        compute();
            
        $('#liqamnt').val('');
        $('#liqdesc').val('');

        }

        


    }

    function liqdeleteRow(){
        // alert('test');
        $('#myTable').on('click','tr a.removeliqRow',function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        // xdUpdateData()
        compute();
        });
    }

    function compute(){
        var sum = 0;
        $('.tdliqamnt').each(function()
        {
            sum += parseFloat($(this).text());
        });
        $('#spTotalAmount').text(sum);
        liqUpdateData();
    }
    function liqUpdateData(){

    var objectListData = [];

    $("#myTable > tbody > tr").each(function () {
            var liqDateUpdate = $(this).find('td').eq(0).text();
            var liqClientIDUpdate = $(this).find('td').eq(1).text();
            var liqClientNameUpdate = $(this).find('td').eq(2).text();
            var liqTypeUpdate = $(this).find('td').eq(3).text();
            var liqDescUpdate = $(this).find('td').eq(4).text();
            var liqCurrUpdate = $(this).find('td').eq(5).text();
            var liqAmntUpdate = $(this).find('td').eq(6).text();

            

        
            var listLiqData = [];
            listLiqData.push(liqDateUpdate,liqClientIDUpdate,liqClientNameUpdate,liqTypeUpdate,liqDescUpdate,liqCurrUpdate,liqAmntUpdate);
            objectListData.push(listLiqData);

      

            // console.log($('#liquidationTable').val());
        });
        var liqJsonData = JSON.stringify(objectListData);
            console.log(liqJsonData);
            $( "#liquidationTable" ).val(liqJsonData);
    }



    function approvedbyInit(){
        liqUpdateData();
    }



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

@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
{{-- Modal JS --}}



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
 