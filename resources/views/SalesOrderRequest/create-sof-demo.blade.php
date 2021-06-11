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
        window.location.href = "in-progress";
        }});
</Script>
@endif




<form id="formfield " method="POST" action="{{ route('post.saveDMO') }}"  class="form-horizontal"  enctype="multipart/form-data" > 
@csrf

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

    <div class="row">
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
                                                            <label for="exampleInputEmail1">SOF Number</label>
                                                            <input type="text" class="form-control"  placeholder="SOF-{{ date("Y") }}" readonly>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="dateCreated">Date Created</label>
                                                            <div class="input-group date" data-target-input="nearest">
                                                                <input type="text" id="dateCreated" name="dateCreated" class="form-control datetimepicker-input" value="{{date('m/d/Y')}}" readonly>
                                                                <div class="input-group-append" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                             
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Number</label>
                                                            <input type="text" name="poNumber" id="poNumber" onclick="poNumberGet()" class="form-control"  >
                                                            <span class="text-danger">@error('poNumber'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Date</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" name="poDate" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
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
                                                            <textarea style="resize:none" class="form-control"  name="scopeOfWork" rows="3" placeholder=""></textarea>
                                                            <span class="text-danger">@error('scopeOfWork'){{ $message }}@enderror</span>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Accounting Remarks</label> 
                                                            <textarea style="resize:none" class="form-control"  name="accountingRemarks" rows="3" placeholder=""></textarea>
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
                                                            <select class="form-control select2 select2-default" name="clientID" data-dropdown-css-class="select2-default" id="clientID" style="width: 100%;" onchange="customerNameSelect(this)">
                                                                <option selected="selected" hidden disabled value="0">Select Customer Name</option>
                                                                @foreach ($businesslist as $business )
                                                                    <option value="{{ $business->Business_Number }}">{{ $business->business_fullname }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger">@error('clientID'){{ $message }}@enderror</span>
                                             
                                                        </div>      

                                                    </div>

                                                    {{-- Hidden Elements --}}
                                                    <input type="hidden" name="client" id="client">
                                                    <input type="hidden" name="contactPersonName" id="contactPersonName">

                                        
                                                    {{-- <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="add customer">Add Customer</label>
                                                            <a href="javascriptvoid(0)" class="btn btn-success" style="width: 100%;"  data-toggle="modal" data-target="#transpoDetails" >Add  </a>
                                                        </div>
                                                    </div> --}}

                                                    <!-- Modal Add customer Details -->
                                                    {{-- <div class="modal fade" id="transpoDetails" tabindex="-1" aria-labelledby="transpoDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                                                        <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="transpoDetailsLabel">Add Customer</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body"> --}}
                                                            {{-- START ADD MODAL--}}
                                                            


                                                            {{-- END ADD--}}
                                                            {{-- </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" >Insert</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div> --}}
                                                    {{-- End Modal Add customer Details --}}
                                                    
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Code</label>
                                                            <input type="text" list="suggestions" name="projectCode" class="form-control " id="projectCode" style="width: 100%;" >
                                                            <datalist id="suggestions" >                                                
                                                            </datalist>
                                                            <span class="text-danger">@error('projectCode'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>     


                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Short Text</label>
                                                            <input type="text" class="form-control" id="projectShortText" name="projectShortText" onclick="prjShortTxt()" placeholder="">
                                                            <span class="text-danger">@error('projectShortText'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>                                                            
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Name</label>
                                                            <input type="text" class="form-control" readonly id="projectName" name="projectName" value="">
                                                            <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
                                                            
                                                            <input type="hidden" class="form-control" readonly id="projectNameHidden" value="{{ date("Ym_M") }}">
                                                        </div>                            
                                                    </div> 

                                                     

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Person</label>
                                                            <select class="form-control select2 select2-default" name="contactPerson" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getContactPersonName()" id="contactPerson">                                                                                                                         
                                                            </select>
                                                            <span class="text-danger">@error('contactPerson'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Number</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" name="contactNumber" id="contactNumber">                                                       
                                                            </select>
                                                            <span class="text-danger">@error('contactNumber'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delivery Address</label>
                                                            <select class="form-control select2 select2-default" name="deliveryAddress" data-dropdown-css-class="select2-default" id="deliveryAddress" style="width: 100%;">                                                                                                                    
                                                            </select>
                                                            <span class="text-danger">@error('deliveryAddress'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Billing Address</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" name="billingAddress" id="billingAddress">                                                                                             
                                                            </select>
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
                                                            <input type="text" class="form-control" name="paymentTerms" value="" id="paymentTerms" disabled>
                                                            <span class="text-danger">@error('paymentTerms'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>




                
                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <label for="projectStart">Project Start</label>
                                                            <div class="input-group date" id="projectStart" data-target-input="nearest">
                                                                <input type="text" name="projectStart" class="form-control datetimepicker-input" disabled data-target="#projectStart"/>
                                                                <div class="input-group-append" data-target="#projectStart" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">@error('projectStart'){{ $message }}@enderror</span>

                                                        </div>         
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="projectEnd">Project End</label>

                                                            <div class="input-group date" id="projectEnd" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" name="projectEnd" disabled data-target="#projectEnd"/>
                                                                <div class="input-group-append" data-target="#projectEnd" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">@error('projectEnd'){{ $message }}@enderror</span>

                                                        </div>         
                                                    </div> 


                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Warranty</label>
                                                            <input type="text" class="form-control" name="warranty" disabled placeholder="">
                                                            <span class="text-danger">@error('warranty'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">Currency</label>
                                                                <select class="form-control select2 select2-default" disabled name="currency" data-dropdown-css-class="select2-default"  style="width: 100%;">
                                                                    <option value="PHP">PHP</option>                                                                  
                                                                </select>
                                                            <span class="text-danger">@error('currency'){{ $message }}@enderror</span>

                                                            </div>    
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Project Cost</label>
                                                            <input data-type="currency" min="0" style="text-align: right" disabled type="text" class="form-control" name="projectCost" value="0.00"  placeholder="">
                                                            <span class="text-danger">@error('projectCost'){{ $message }}@enderror</span>
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
                                                                    <th></th>
                                                                    <th>System Name</th>
                                                                    <th> <a href="javascriptvoid(0)" data-toggle="modal" data-target="#systemDetails"> Add Row</a></th>                                                               
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                             
                                                                @foreach ($systemName as $system )
                                                                    <tr>
                                                                        <td><input type="checkbox" name="systemname[]" value="{{ $system->id }}" > </td>
                                                                        <td style="width: 93%;">{{ $system->type_name }}</td>
                                                                        <td></td>
                                                                    </tr>
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
                                                                    <th></th>                                                               
                                                                    <th>Document Name</th>
                                                                    <th> <a href="javascriptvoid(0)" data-toggle="modal" data-target="#documentDetails"> Add Row</a></th>                                                               
                                                                </tr>
                                                            </thead>
                                                            <tbody>                                                                                                                        
                                                                @foreach ($documentlist as $docs )
                                                                    <tr>
                                                                        <td><input type="checkbox" name="documentname[]" value="{{ $docs->ID }}" > </td>
                                                                        <td style="width: 93%;">{{ $docs->DocumentName }}</td>
                                                                        <td></td>
                                                                    </tr>
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
                                          
                                                            <input type="text" class="form-control" name="accountmanager" disabled value="" id="accountmanager">
                                
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Coordinator</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" disabled style="width: 100%;">
             
                                                            </select>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delegates</label>

                                                            <input type="text" class="form-control" name="delegates" disabled value="" id="delegates">

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
                                                            <select class="form-control select2 select2-default" name="downpaymentrequired" disabled id="downPaymentRequiredForm" onchange="dprSet(this)" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                
                                                                <option  value="1">Yes</option>
                                                                <option selected="selected" value="0">No</option>

                                                            </select>
                                                        </div> 
                                                      <span class="text-danger">@error('downpaymentrequired'){{ $message }}@enderror</span>

                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Down Payment Percentage</label>
                                                            <input min="0" style="text-align:right" maxlength="3" disabled id="downPaymentPercentageForm" type="text" class="form-control" name="downPaymentPercentage"  placeholder="">                                                        
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
                                                            <select class="form-control select2 select2-default" name="invoicerequired" disabled id="invoiceRequiredForm" onchange="irSet(this)" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option value="1">Yes</option>
                                                                <option selected="selected" value="0">No</option>
                                                            </select>
                                                        </div>   
                                                      <span class="text-danger">@error('invoicerequired'){{ $message }}@enderror</span>

                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Date Needed</label>
                                                            <div class="input-group date" id="invoiceDateNeeded" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" disabled id="invoiceDateNeededForm" name="invoiceDateNeeded" data-target="#invoiceDateNeeded"/>
                                                                <div class="input-group-append" data-target="#invoiceDateNeeded" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                                <span class="text-danger">@error('invoiceDateNeeded'){{ $message }}@enderror</span>
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

                        {{-- <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="purpose">Attachments</label>
                                    <span class="btn btn-success col fileinput-button">
                                        <i class="fas fa-plus"></i>
                                        <span>Browse files</span>
                                    </span>
                                </div>
                            </div>
                        </div> --}}


                        {{-- Attachments --}}
                        <label class="btn btn-primary" style="font-weight:normal;">
                            Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                        </label>

                        <span class="text-danger">@error('file'){{ $message }}@enderror</span>
                        
                        

                        {{-- Attachments of no edit --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
            
                                    <div class="card-header" style="height:50px;">
                                        <div class="row ">
                                            <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
                                        </div>
                                    </div>
                                    {{-- Card body --}}
                                    <div class="card-body" >


                                        {{-- Table attachments --}}
                                        <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                            <table id= "attachmentsTable"class="table table-hover" >
                                                <thead >
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    {{-- <th>Size</th> --}}
                                                    <th>Temporary Path</th>
                                                    <th>Actions</th>

                                                </tr>
                                                </thead>
                                                <tbody >
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Table attachments End--}}
                                     
                                    </div>
                                    {{-- Card body END --}}
            
                                </div>
                            </div>
                        </div>
                        {{-- End Attachments --}}

                       

                    </div>                            
                </form>

            </div>
        </div>
    </div>


{{-- Modal --}}

    <!-- Modal Add System Details -->
    <div class="modal fade" id="systemDetails" tabindex="-1" aria-labelledby="systemDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="systemDetails">Add System Details</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            {{-- START ADD MODAL--}}
            <form id="systemForm"  >
                @csrf
            <div class="form-group">
                <label for="">System Name</label>
                <input type="text" name="systemname" id="systemname" class="form-control" placeholder="Insert system name here" aria-describedby="helpId">
                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
            </div>
            </form>

            {{-- END ADD--}}
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="addSystemName()" >Insert</button>

            </div>
        </div>
        </div>
    </div>


    <!-- Modal Add Document Details -->
    <div class="modal fade" id="documentDetails" tabindex="-1" aria-labelledby="documentDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="documentDetails">Add Document Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            {{-- START ADD MODAL--}}
            <div class="form-group">
            <form id="documentForm"  >
                @csrf
                <label for="">Document Name</label>
                <input type="text" name="documentname" id="documentname" class="form-control" placeholder="Insert document name here" aria-describedby="helpId">
                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                </div>
            </form>

            {{-- END ADD--}}
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="addDocumentName()" >Insert</button>
            </div>
        </div>
        </div>
    </div>


{{-- End Modal --}}










<script>
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
</script>

<script>
    
    function dprSet(sel){
        if(sel.value === '0'){
            // console.log(sel.value);
            $('#downPaymentPercentageForm').val('');
            $( "#downPaymentPercentageForm" ).prop( "disabled", true );

        } else {
            $('#downPaymentPercentageForm').val('');

            $( "#downPaymentPercentageForm" ).prop( "disabled", false );
        }

        
    }

    function irSet(sel){
        
        if(sel.value === '0'){
            $('#invoiceDateNeededForm').val('');
            $( "#invoiceDateNeededForm" ).prop( "disabled", true );
        } else {
            $('#invoiceDateNeededForm').val('');
            $( "#invoiceDateNeededForm" ).prop( "disabled", false );
        }
    }
</script>

<script>
    function getContactPersonName(){
        let contactPersonName = $( "#contactPerson option:selected" ).text();
        console.log(contactPersonName);

        $('#contactPersonName').val(contactPersonName);

    }
</script>



<script>
function addDocumentName(){

let documentname = $('#documentname').val();
let _token = $("input[name=_token]").val();

$.ajax({
    url: "{{ route('post.documentname') }}",
    type: "POST",
    data:{
        documentname:documentname,
       _token:_token
    },
    success:function(response){ 
        // console.log(typeof response);
        $('#documentTable tbody').prepend('<tr><td><input type="checkbox" name="documentname[]" value="'+response+'" ></td><td style="width: 93%;">'+documentname+'</td><td></td></tr>')
    }
});

}
</script>

<script>
function addSystemName(){

     let systemname = $('#systemname').val();
     let _token = $("input[name=_token]").val();

     $.ajax({
         url: "{{ route('post.systemname') }}",
         type: "POST",
         data:{
            systemname:systemname,
            _token:_token
         },
         success:function(response){
             console.log(response);
                 $('#systemsTable tbody').prepend('<tr><td><input type="checkbox" name="systemname[]" value="'+response+'" ></td><td style="width: 93%;">'+systemname+'</td><td></td></tr>')
         }
     });

}
</script>




<script>

var gclientCode = '';
var gprjShortText = '';
var gpoNumber = '';

        function customerNameSelect(sel){
        let client = $( "#clientID option:selected" ).text();
        console.log(client);

        
        let categoryID = sel.value;

        // sel.text();

        $('#deliveryAddress').empty();
        $('#deliveryAddress').append(`<option value="0" disabled selected>Processing...</option>`);

        $('#billingAddress').empty();
        $('#billingAddress').append(`<option value="0" disabled selected>Processing...</option>`);

        $('#contactPerson').empty();
        $('#contactPerson').append(`<option value="0" disabled selected>Processing...</option>`);

        $('#contactNumber').empty();
        $('#contactNumber').append(`<option value="0" disabled selected>Processing...</option>`);

        $('#client').val(client);
        
        


        $.ajax({
        type: 'GET',
        url: 'getaddress/' + categoryID,
        success: function (response) {

        var response = JSON.parse(response);
        // console.log(response);   
        $('#deliveryAddress').empty();
        $('#deliveryAddress').append(`<option value="0" disabled selected>Select Delivery Address</option>`);

        $('#billingAddress').empty();
        $('#billingAddress').append(`<option value="0" disabled selected>Select Billing Address</option>`);

        response.forEach(element => {
            $('#deliveryAddress').append(`<option value="${element['ADDRESS']}">${element['ADDRESS']}</option>`);

            $('#billingAddress').append(`<option value="${element['ADDRESS']}">${element['ADDRESS']}</option>`);
            });

            }
        }); 

     
        $.ajax({
        type: 'GET',
        url: 'getcontacts/' + categoryID,
        success: function (response) {


        var response = JSON.parse(response);
        // console.log(response);   
        $('#contactPerson').empty();
        $('#contactPerson').append(`<option value="0" disabled selected>Select Contact Person</option>`);

        $('#contactNumber').empty();
        $('#contactNumber').append(`<option value="0" disabled selected>Select Contact Number</option>`);

        response.forEach(element => {
            // console.log(element);
            $('#contactPerson').append(`<option value="${element['ID']}">${element['ContactName']}</option>`);
            $('#contactNumber').append(`<option value="${element['Number']}">${element['Number']}</option>`);
            });
            }
        });



// Error Handler for Null object
// if (!$.trim(data)){   
// alert("What follows is blank: " + data);
// }
// else{   
// alert("What follows is not blank: " + data);
// }


        $.ajax({
        type: 'GET',
        url: 'getdelegates/' + categoryID,
        success: function (response) {

        var response = JSON.parse(response);

            if (!$.trim(response)){   
            // alert("What follows is blank: " + response);
                $('#delegates').val(''); 

            }
            else{   
            // alert("What follows is not blank: " + response);
                $('#delegates').val(''); 

                response.forEach(element => {
                // console.log(element);
                $('#delegates').val(element['DelegatesName']);

                // $('#delegates').append(`<option value="${element['DelegatesID']}">${element['DelegatesName']}</option>`);
                });

            }

            }
        });
        

        $.ajax({
        type: 'GET',
        url: 'getsetupproject/' + categoryID,
        success: function (response) {
        
        var response = JSON.parse(response);


        response.forEach(element => {
            // $('#projectCode').val(".....");
            $('#suggestions').append(`<option value="${element['project_no']}">`);

            });
            }
        });


        $.ajax({
        type: 'GET',
        url: 'getbusinesslist/' + categoryID,
        success: function (response) {
        var response = JSON.parse(response);
        // console.log(response);

        // $('#accountmanager').empty();
        // $('#accountmanager').append(`<option value="0" disabled selected>.....</option>`);

        $('#accountmanager').val(''); 




        response.forEach(element => {
            // console.log(element);
            // console.log(element['CLIENTCODE']);
            var clientCode = element['CLIENTCODE'];
            
            $('#paymentTerms').val(element['term_type']);
            $('#accountmanager').val(element['PMName']);

            // $('#accountmanager').append(`<option value="${element['PMID']}">${element['PMName']}</option>`);

            
            gclientCode = clientCode;
            generate();
            });
            }
        }); 

      
    }


    function prjShortTxt(){
        $('#projectShortText').keyup(function(e){
            // console.log(e.target.value);
            var prjShortText = e.target.value;
            // console.log(prjShortText);
            gprjShortText = prjShortText;
            generate();
        }
        )
    }

    function poNumberGet(){
        $('#poNumber').keyup(function(e){
            var poNumber = e.target.value;
            // console.log(poNumber);
            gpoNumber = poNumber;
            generate();
        }
        ) 
    }

    function generate(){
        var prjValue = $('#projectNameHidden').val();
        var projectName = gclientCode+'-'+prjValue+'-DMO-'+gpoNumber+'-'+gprjShortText;
        // console.log(projectName);
        $('#projectName').val(projectName);

    }
    
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

