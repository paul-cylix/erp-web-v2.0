@extends('layouts.base')
@section('title', 'Sales Order - Demo') 
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




    <div class="row">

        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                    <div class="col-md-1 float-left"><a href="/approved" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled  >Withdraw</button></div>        
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled >Approve</button></div>   
                    {{-- <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>    --}}

            </div> 
        </div> 

        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color: #6c757D;" class="card-header">
                                                <h3 class="card-title" style="color: #ffffff;">Request Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">SOF Number {{ $salesOrder->purpose }}</label>
                                                            <input type="text" class="form-control"  value="{{ $salesOrder->soNum }}" readonly>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="dateCreated">Date Created</label>
                                                            <div class="input-group date" id="dateCreated" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" value="{{ $salesOrder->sodate }}" disabled name="dateCreated" data-target="#dateCreated"/>
                                                                <div class="input-group-append" data-target="#dateCreated" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                             
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Number</label>
                                                            <input type="text" name="poNumber" value="{{ $salesOrder->poNum }}" id="poNumber" disabled class="form-control"  >
                                                            <span class="text-danger">@error('poNumber'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Date</label>
                                                            <div class="input-group date" id="reservationDate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" value="{{ $salesOrder->podate }}" disabled name="reservationDate" data-target="#reservationDate"/>
                                                                <div class="input-group-append" data-target="#reservationDate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">@error('poDate'){{ $message }}@enderror</span>

                                                        </div>         
                                                    </div>
                                                    
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Scope of Work</label> 
                                                            <textarea style="resize:none" class="form-control" disabled  name="scopeOfWork" rows="3" >{{ $salesOrder->remarks }}</textarea>
                                                            <span class="text-danger">@error('scopeOfWork'){{ $message }}@enderror</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Accounting Remarks</label> 
                                                            <textarea style="resize:none" class="form-control" disabled  name="accountingRemarks" rows="3" >{{ $salesOrder->Remarks2 }}</textarea>
                                                            <span class="text-danger">@error('accountingRemarks'){{ $message }}@enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                                                    
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color:#6c757D" class="card-header">
                                                <h3 class="card-title" style="color: #ffffff;">Project Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Customer Name</label>
                                                            <input type="text" value="{{ $salesOrder->client }}" disabled class="form-control" style="width: 100%;"  > 
                                                            
                                                            <input type="hidden" value="{{ $salesOrder->clientID }}" id="clientID">

                                                            <span class="text-danger">@error('clientID'){{ $message }}@enderror</span>                                           
                                                        </div>      
                                                    </div>

                                                    {{-- Hidden Elements --}}
                                                    <input type="hidden" name="client" id="client">
                                                    <input type="hidden" name="contactPersonName" id="contactPersonName">

                                        
                                             

                                                               
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Code</label>
                                                            <input type="text" list="suggestions" disabled name="projectCode" class="form-control" value="{{ $salesOrder->pcode }}" id="projectCode" style="width: 100%;" >
                                                            <datalist id="suggestions" >                                                
                                                            </datalist>
                                                            <span class="text-danger">@error('projectCode'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>     


                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Short Text</label>
                                                            <input type="text" class="form-control" id="projectShortText" disabled name="projectShortText" value="{{ $salesOrder->project_shorttext }}" placeholder="">
                                                            <span class="text-danger">@error('projectShortText'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>                                                            
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Name</label>
                                                            <input type="text" class="form-control" disabled readonly value="{{ $salesOrder->project }}" id="projectName" name="projectName" value="">
                                                            <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>                                                           
                                                            <input type="hidden" class="form-control" readonly id="projectNameHidden" value="{{ date("Ym_M") }}">
                                                        </div>                            
                                                    </div> 

                                                     

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Person</label>
                                                            <input type="text"  class="form-control"  value="{{ $salesOrder->Contact }}" disabled style="width: 100%;" >
                                                          
                                                            <span class="text-danger">@error('contactPerson'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Number</label>
                                                            <input type="text"  class="form-control" disabled value="{{ $salesOrder->ContactNum }}" style="width: 100%;" >
                                                            
                                                            <span class="text-danger">@error('contactNumber'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delivery Address</label>
                                                            <input type="text"  class="form-control" value="{{ $salesOrder->DeliveryAddress }}" disabled style="width: 100%;" >
                                                            
                                                            <span class="text-danger">@error('deliveryAddress'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Billing Address</label>
                                                            <input type="text" disabled class="form-control" style="width: 100%;" value="{{ $salesOrder->BillTo }}">                                                       
                                                            <span class="text-danger">@error('billingAddress'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 
                                                </div>
                                            </div>                                                                                                    
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color:#6c757D" class="card-header">
                                                <h3 class="card-title" style="color: #ffffff;">Payment & Delivery Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Payment Terms</label>
                                                            <input type="text" disabled class="form-control" name="paymentTerms" value="{{ $salesOrder->Terms }}" id="paymentTerms">
                                                        </div>                            
                                                    </div>
               
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="projectStart">Project Start</label>
                                                            <div class="input-group date" id="projectStart" data-target-input="nearest" data-date-format='YYYY-MM-DD'>
                                                                <input type="text" class="form-control datetimepicker-input" name="projectStart" value="{{ $setupProject->project_effectivity }}" disabled data-target="#projectStart"/>
                                                                <div class="input-group-append" data-target="#projectStart" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>

                                                        </div>         
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="projectEnd">Project End</label>

                                                            <div class="input-group date" id="projectEnd" data-target-input="nearest" data-date-format='YYYY-MM-DD'>
                                                                <input type="text" class="form-control datetimepicker-input" name="projectEnd" value="{{ $salesOrder->DateAndTimeNeeded }}" disabled data-target="#projectEnd"/>
                                                                <div class="input-group-append" data-target="#projectEnd" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>

                                                        </div>         
                                                    </div> 


                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Warranty</label>
                                                            <input type="text" disabled class="form-control" value="{{ $salesOrder->warranty }}" name="warranty" placeholder="">
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">Currency</label>
                                                                <input type="text" class="form-control" placeholder="" value="{{ $salesOrder->currency }}" disabled>
                                                    
                                                            </div>    
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Project Cost</label>
                                                            <input  style="text-align: right" type="text" value="{{ $salesOrder->amount }}" class="form-control" disabled placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                                                    
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div style="background-color:#6c757D" class="card-header">
                                        <h3 class="card-title" style="color: #ffffff;">System & Document Details</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card card-default">
                                                    <div style="background-color:#6c757D" class="card-header">
                                                        <h3 class="card-title" style="color: #ffffff;">System Details</h3> 
                                                    </div> 
                
                                                    <div class="card-body" style="max-height: 300px; overflow: auto; display:inline-block;" >
                                                        <table class="table table-hover text-nowrap" id="systemsTable">
                                                            <thead>
                                                                <tr>     
                                                              
                                                                    <th>System Name</th>
                                                                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>    
                                                                @foreach ($salesOrderSystem as $systemName )
                                                                    <tr><td>{{ $systemName->systemType }}</td></tr>
                                                                @endforeach                                                                                                           
                                                            </tbody>
                                                        </table>

                                                    </div>
                    
                                                </div>
                                                <span class="text-danger">@error('systemname'){{ $message }}@enderror</span>

                                            </div>            
                                            
                                            <div class="col-md-6">
                                                <div class="card card-default">
                                                    <div style="background-color:#6c757D" class="card-header">
                                                        <h3 class="card-title" style="color: #ffffff;">Document Details</h3> 
                                                        
                                                    </div>               
                                                    <div class="card-body" style="max-height: 300px; overflow: auto; display:inline-block;">
                                                        <table class="table table-hover text-nowrap" id="documentTable">
                                                            <thead>
                                                                <tr>                                                                                                                      
                                                                    <th>Document Name</th>                                                          
                                                                </tr>
                                                            </thead>
                                                            <tbody>                                                                                                                        
                                                                @foreach ($salesOrderDocs as $systemDocs )
                                                                <tr><td>{{ $systemDocs->DocName }}</td></tr>
                                                                @endforeach                     
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                </div>
                                                <span class="text-danger">@error('documentname'){{ $message }}@enderror</span>

                                            </div>       
                                        </div>
                                    </div>

                                </div> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color:#6c757D" class="card-header">
                                                <h3 class="card-title" style="color: #ffffff;">Project Personnel</h3>
        
                                            

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Account Manager</label>
                                                            <input  ype="text" class="form-control" id="accountManager" disabled placeholder="">

                                                            <span class="text-danger">@error('accountmanager'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Coordinator</label>
                                                            <input  type="text" id="coordinator" class="form-control" value="{{ $setupProject->Coordinator }}"disabled placeholder="">
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delegates</label>
                                                            <input   type="text" class="form-control" disabled placeholder="" id="delegates">

                                                        </div>                            
                                                    </div>
                                                </div> 
                                            </div>                                                                                                    
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color:#6c757D" class="card-header">
                                                <h3 class="card-title" style="color: #ffffff;">Accounting Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">                                
                                                            <label for="exampleInputEmail1">Down Payment Required</label>
                                                            @if (empty($salesOrder->dp_required))    
                                                            <input type="text" value="No" class="form-control" disabled placeholder="">
                                                            @else
                                                            <input type="text" value="Yes" class="form-control" disabled placeholder="">                                                     
                                                            @endif
                                                    
                                                        </div> 
                                                      <span class="text-danger">@error('downpaymentrequired'){{ $message }}@enderror</span>

                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Down Payment Percentage</label>
                                                            <input  disabled id="downPaymentPercentageForm" value="{{ $salesOrder->dp_percentage }}%"type="text" class="form-control" name="downPaymentPercentage"  placeholder="">                                                        
                                                        </div>
                                                      <span class="text-danger">@error('downPaymentPercentage'){{ $message }}@enderror</span>

                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Down Payment Date Received</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" disabled>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>
                                                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Number</label>
                                                            <input type="text" class="form-control" disabled placeholder="">
                                                        </div>                            
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Required</label>
                                                            @if (empty($salesOrder->IsInvoiceRequired))    
                                                            <input type="text" value="No" class="form-control" disabled placeholder="">
                                                            @else
                                                            <input type="text" value="Yes" class="form-control" disabled placeholder="">                                                     
                                                            @endif
                                                        </div>   
                                                      <span class="text-danger">@error('invoicerequired'){{ $message }}@enderror</span>
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Date Needed</label>
                                                            <div class="input-group date" id="dateCreated" data-target-input="nearest">
                                                                {{-- <input type="text" class="form-control datetimepicker-input" value="{{ $salesOrder->invDate }}" disabled name="dateCreated" data-target="#dateCreated"/> --}}
                                                                {{-- @if ($salesOrder->invDate != '0000-00-00')
                                                                <input type="text" class="form-control datetimepicker-input" value="{{date("m-d-Y", strtotime( $salesOrder->invDate)) }}" id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>                                                             
                                                                @else
                                                                <input type="text" class="form-control datetimepicker-input" disabled id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>                                                             
                                                                @endif --}}

                                                                @if (!empty($salesOrder->invDate) && $salesOrder->invDate == '0000-00-00')
                                                                <input type="text" class="form-control datetimepicker-input" disabled id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>                                                               
                                                                @elseif(!empty($salesOrder->invDate))
                                                                <input type="text" class="form-control datetimepicker-input" value="{{date("m-d-Y", strtotime( $salesOrder->invDate)) }}" id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>                                                             
                                                                @else
                                                                <input type="text" class="form-control datetimepicker-input" disabled id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>                                                             
                                                                @endif
                                                                
                                                                <div class="input-group-append" data-target="#dateCreated" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Sales Invoice Released</label>
                                                            <select class="form-control select2 select2-default" disabled data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                {{-- <option>Yes</option>
                                                                <option selected="selected">No</option> --}}
                                                            </select>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Date of Invoice</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" disabled data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>
                                                </div> 
                                            </div>                                                                                                    
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>


                        {{-- Attachments of no edit --}}
                        @if (!empty($attachmentsDetails))
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
                                              

                </div>                            
            </div>
        </div>
    </div>


<script>
    let clientID = $('#clientID').val();
    console.log(clientID);

    let soID = $('#soID').val();
    console.log(soID);


    let coordID = $('#coordinator').val();
    console.log(coordID);


    $.ajax({
        type: 'GET',
        url: '/getbusinesslist/' + clientID,
        success: function (response) {
        var response = JSON.parse(response);
        // console.log(response);
        response.forEach(element => {
            $('#accountManager').val(element['PMName']);
            });
            }
        }); 


    $.ajax({
        type: 'GET',
        url: '/getdelegates/' + clientID,
        success: function (response) {

        var response = JSON.parse(response);

        response.forEach(element => {
            // console.log(element);
            $('#delegates').val(element['DelegatesName']);
            });
            }
    });

    $.ajax({
        type: 'GET',
        url: '/getcoordinator/'+soID+'/'+coordID,
        success: function (response) {

        var response = JSON.parse(response);

// Error Handler for Null object
        if (!$.trim(response)){   
        // alert("What follows is blank: " + response);
        $('#coordinator').val('');

        }
        else{   
        // alert("What follows is not blank: " + response);
        response.forEach(element => {
            // console.log(element);
            $('#coordinator').val(element['CoordinatorName']);
            });
        }

            }
    });





</script>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>

