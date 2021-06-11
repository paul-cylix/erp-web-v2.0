@extends('layouts.base')
@section('title', 'Sales Order - POC') 
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
                    <div class="col-md-1 float-left"><a href="/clarifications" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" data-toggle="modal" data-target="#replyModal" >Reply</button></div>     
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled  >Withdraw</button></div>        
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled >Approve</button></div>
            </div> 
        </div> 





        <!-- Modal Reply-->
        <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark" >
                <h5 class="modal-title" id="replyModalLabel">Reject Request </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
    <form action="{{ route('reply.sof') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">                     
                                <label for="replyRemarks">Remarks </label>
                                <div class="card-body">
                                    <div class="form-floating">
                                        <input type="hidden" value="{{ $salesOrder->id }}" name="soID" id="soID">
                                        <input type="hidden" value="" name="deleteAttached" id="deleteAttached">
                                        <input type="hidden" value="{{ $checkOrder[0]->checker }}" name="checkOrder">
                                        <input type="hidden" name="checkInit" value="{{ $checkInit[0]->checker }}" >
                                        <input type="hidden" name="frmname" value="@yield('title')">
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
            </div>
            </div>
        </div>
        {{-- End Reply Modal --}}

        @if (!empty( $checkInit[0]->checker ) && !empty($checkOrder[0]->checker))
        
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
                                                            <input type="text" class="form-control" name="sofNumber"  value="{{ $salesOrder->soNum }}" readonly>
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
                                                            <input type="text" name="poNumber" value="{{ $salesOrder->poNum }}" id="poNumber" onclick="poNumberGet()" class="form-control"  >

                                                            <span class="text-danger">@error('poNumber'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                             
                                                            <label for="exampleInputEmail1">PO Date</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" name="poDate" value="{{date("m-d-Y", strtotime( $salesOrder->podate)) }}" class="form-control datetimepicker-input" data-target="#reservationdate"/>
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
                                                            <textarea style="resize:none" class="form-control"   name="scopeOfWork" rows="3" >{{ $salesOrder->remarks }}</textarea>
                                                            <span class="text-danger">@error('scopeOfWork'){{ $message }}@enderror</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Accounting Remarks</label> 
                                                            <textarea style="resize:none" class="form-control"   name="accountingRemarks" rows="3" >{{ $salesOrder->Remarks2 }}</textarea>
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
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Customer Name</label>
                                                            <select class="form-control select2 select2-default" name="clientID" data-dropdown-css-class="select2-default" id="clientID" style="width: 100%;" onchange="customerNameSelect(this)">
                                                                <option selected="selected" value="{{ $salesOrder->clientID }}">{{ $salesOrder->client }}</option>
                                                                
                                                                @foreach ($businesslist as $business )
                                                                    <option value="{{ $business->Business_Number }}">{{ $business->business_fullname }}</option>
                                                                @endforeach
                                                            </select>


                                                            <span class="text-danger">@error('clientID'){{ $message }}@enderror</span>
                                                            
                                                        </div>      

                                                    </div>

                                                    {{-- Hidden Elements --}}
                                                    <input type="hidden" name="client" id="client" value="{{ $salesOrder->client }}">
                                                    <input type="hidden" name="clientIDHidden" id="clientIDHidden" value="{{ $salesOrder->clientID }}">
                                                    <input type="hidden" name="contactPersonName" id="contactPersonName" value="{{ $salesOrder->Contact }}">

                                        
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="add customer">Add Customer</label>
                                                            <a href="javascriptvoid(0)" class="btn btn-success" style="width: 100%;"  data-toggle="modal" data-target="#transpoDetails" >Add  </a>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Add customer Details -->
                                                    <div class="modal fade" id="transpoDetails" tabindex="-1" aria-labelledby="transpoDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                                                        <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="transpoDetailsLabel">Add Customer</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            {{-- START ADD MODAL--}}
                                                            


                                                            {{-- END ADD--}}
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" >Insert</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    {{-- End Modal Add customer Details --}}
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Code</label>
                                                            <input type="text" list="suggestions" name="projectCode" class="form-control " value="{{ $salesOrder->pcode }}" id="projectCode" style="width: 100%;" >
                                                            <datalist id="suggestions" >                                                
                                                            </datalist>
                                                            <span class="text-danger">@error('projectCode'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div>     
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Short Text</label>
                                                            <input type="text" class="form-control" id="projectShortText" name="projectShortText" value="{{ $salesOrder->project_shorttext }}" onclick="prjShortTxt()" placeholder="">
                                                            <span class="text-danger">@error('projectShortText'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>                                                            
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Name</label>
                                                            <input type="text" class="form-control" readonly id="projectName" name="projectName" value="{{ $salesOrder->project }}">
                                                            <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
                                                            
                                                            <input type="hidden" class="form-control" readonly id="projectNameHidden" value="{{ date("Ym_M") }}">
                                                        </div>                            
                                                    </div> 

                                                     

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Person</label>
                                                            <select class="form-control select2 select2-default" name="contactPerson" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getContactPersonName()" id="contactPerson">                                                                                                                         
                                                                <option value="{{ $salesOrder->Contactid }}"  selected="selected" >{{ $salesOrder->Contact }}</option>
                                                            </select>
                                                            <span class="text-danger">@error('contactPerson'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Number</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" name="contactNumber" id="contactNumber">                                                       
                                                                <option value="{{ $salesOrder->ContactNum }}"  selected="selected" >{{ $salesOrder->ContactNum }}</option>                                                         
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
                                                                <option value="{{ $salesOrder->DeliveryAddress }}"  selected="selected" >{{ $salesOrder->DeliveryAddress }}</option>                                                                                                                   
                                                            </select>
                                                            <span class="text-danger">@error('deliveryAddress'){{ $message }}@enderror</span>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Billing Address</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" name="billingAddress" id="billingAddress">                                                                                             
                                                                <option value="{{ $salesOrder->BillTo }}"  selected="selected" >{{ $salesOrder->BillTo }}</option>                                                                                                                                                       
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
                                                            <input type="text" class="form-control" name="paymentTerms" value="{{ $salesOrder->Terms }}" disabled id="paymentTerms">
                                                            <span class="text-danger">@error('paymentTerms'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>
               
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="projectStart">Project Start</label>
                                                            <div class="input-group date" id="projectStart" data-target-input="nearest" data-date-format='YYYY-MM-DD'>
                                                                <input type="text" class="form-control datetimepicker-input" disabled name="projectStart" value="{{ $setupProject->project_effectivity }}"  data-target="#projectStart"/>
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

                                                            <div class="input-group date" id="projectEnd" data-target-input="nearest" data-date-format='YYYY-MM-DD'>
                                                                <input type="text" class="form-control datetimepicker-input" disabled name="projectEnd" value="{{ $salesOrder->DateAndTimeNeeded }}"  data-target="#projectEnd"/>
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
                                                            <input type="text"  class="form-control" value="{{ $salesOrder->warranty }}" disabled name="warranty" placeholder="">
                                                            <span class="text-danger">@error('warranty'){{ $message }}@enderror</span>

                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1">Currency</label>
                                                                <select class="form-control select2 select2-default" name="currency" disabled data-dropdown-css-class="select2-default"  style="width: 100%;">
                                                                    <option value="{{ $salesOrder->currency }}" hidden  selected="selected">{{ $salesOrder->currency }}</option>
                                                                    <option value="PHP">PHP</option>                                                                  
                                                                </select>
                                                            <span class="text-danger">@error('currency'){{ $message }}@enderror</span>
                                                                
                                                            </div>    
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Project Cost</label>
                                                            <input data-type="currency" min="0" style="text-align: right" disabled type="text" class="form-control" name="projectCost" value="{{ $salesOrder->amount }}"  placeholder="">
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
                                                                    <th> <a href="javascriptvoid(0)" data-toggle="modal" data-target="#systemDetails"></a></th>                                                                                                                  
                                                                </tr>
                                                            </thead>
                                                            <tbody>    

                                                                @foreach ( $systemNameChecked as $system )
                                                                    <tr>
                                                                        <td><input type="checkbox" name="systemname[]" value="{{ $system->sysID }}" @if ( $system->ID == 'True' ) checked @endif> </td>
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
                                                                    <th> <a href="javascriptvoid(0)" data-toggle="modal" data-target="#documentDetails"></a></th>                                                         
                                                                </tr>
                                                            </thead>
                                                            <tbody> 
                                                                                                                              
                                                                @foreach ( $documentNameChecked as $docs )
                                                                    <tr>
                                                                        <td><input type="checkbox" name="documentname[]" value="{{ $docs->DocID }}" @if ( $docs->ID == 'True' ) checked @endif> </td>
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
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" name="coordinator" id="coordinator" value="{{ $setupProject->Coordinator }}" disabled style="width: 100%;">
             
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
                                                                
                                                                @if ($salesOrder->dp_required == 1)
                                                                <option selected disabled hidden value="1">Yes</option>
                                                                @else
                                                                <option value="0">No</option>
                                                                @endif

                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>

                                                            </select>
                                                        </div> 
                                                      <span class="text-danger">@error('downpaymentrequired'){{ $message }}@enderror</span>

                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Down Payment Percentage</label>

                                                            @if (!empty($salesOrder->dp_percentage))
                                                            <input min="0" style="text-align:right" maxlength="3" value="{{ $salesOrder->dp_percentage }}" id="downPaymentPercentageForm" type="text" class="form-control" name="downPaymentPercentage"  placeholder="">                                                                                    
                                                            @else
                                                            <input min="0" style="text-align:right" maxlength="3" disabled id="downPaymentPercentageForm" type="text" class="form-control" name="downPaymentPercentage"  placeholder="">                                                        
                                                            @endif



                                                            {{-- <input min="0" style="text-align:right" maxlength="3" disabled id="downPaymentPercentageForm" type="text" class="form-control" name="downPaymentPercentage"  placeholder="">                                                         --}}
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
                                                            <select class="form-control select2 select2-default" disabled name="invoicerequired" id="invoiceRequiredForm" onchange="irSet(this)" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                                                                         
                                                                @if ($salesOrder->IsInvoiceRequired == 1)
                                                                <option selected disabled hidden value="1">Yes</option>
                                                                @else
                                                                <option value="0">No</option>
                                                                @endif
                                                                
                                                                <option value="1">Yes</option>
                                                                <option  value="0">No</option>
                                                            </select>
                                                        </div>   
                                                      <span class="text-danger">@error('invoicerequired'){{ $message }}@enderror</span>

                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Date Needed</label>
                                                            <div class="input-group date" id="invoiceDateNeeded" data-target-input="nearest">
                                                                
                                                                {{-- @if (!empty($salesOrder->invDate))
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


                    {{-- Attachments --}}
                    <label class="btn btn-primary" style="font-weight:normal;">
                        Attach files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                    </label>

                    <span class="text-danger">@error('file'){{ $message }}@enderror</span>
</form>

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
                                        @foreach ($attachmentsDetails as $file)
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
    </div>
        @else

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
                                                    <div class="col-md-5">
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

                                        
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="add customer">Add Customer</label>
                                                            <button class="btn btn-success" style="width: 100%" disabled>Add</button>
                                                        </div>
                                                    </div>

                                                               
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
                                                                {{-- @if (!empty($salesOrder->invDate))
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
                                            @empty
                                            <span style="margin-left: 12px;">no attachments</span>
                                            @endforelse
                                        </div>   
                                    </div>
                                    </div>
                            </div>
                        </div>
                        {{-- End Attachments --}}
                                              

                </div>                            
            </div>
        </div>
    </div>
            
        @endif


<script>
    function getContactPersonName(){
        let contactPersonName = $( "#contactPerson option:selected" ).text();
        console.log(contactPersonName);

        $('#contactPersonName').val(contactPersonName);

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
            url: '/getaddress/' + categoryID,
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
            url: '/getcontacts/' + categoryID,
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
            url: '/getdelegates/' + categoryID,
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
            url: '/getsetupproject/' + categoryID,
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
            url: '/getbusinesslist/' + categoryID,
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
            var projectName = gclientCode+'-'+prjValue+'-PRJ-'+gpoNumber+'-'+gprjShortText;
            // console.log(projectName);
            $('#projectName').val(projectName);
    
        }
        
    </script>






<script>
    let clientID = $('#clientIDHidden').val();
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
            $('#accountmanager').val(element['PMName']);
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
</script>


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

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>

