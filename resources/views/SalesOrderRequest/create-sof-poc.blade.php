@extends('layouts.base')
@section('title', 'Sales Order - POC') 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                <form action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div style="background-color:lightgray" class="card-header">
                                                <h3 class="card-title">Request Details</h3>
        
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
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Date Created</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Number</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">PO Date</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Scope of Work</label> 
                                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="3" placeholder=""></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">                                            
                                                            <label for="purpose">Accounting Remarks</label> 
                                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="3" placeholder=""></textarea>
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
                                            <div style="background-color:lightgray" class="card-header">
                                                <h3 class="card-title">Project Details</h3>
        
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
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">Alabama</option>
                                                                <option>Alaska</option>
                                                                <option>California</option>
                                                                <option>Delaware</option>
                                                                <option>Tennessee</option>
                                                                <option>Texas</option>
                                                                <option>Washington</option>
                                                            </select>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Code</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">SC.CPA01.03.001</option>
                                                                <option>CPOLX01.01.001</option>
                                                                <option>OP.CNC01.01.001</option>
                                                                <option>CPA01.20.001</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Short Text</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                                        </div>                            
                                                    </div>                                                            
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Name</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Person</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">Rosevir Ceballos Jr.</option>
                                                                <option>Jay Ceballos</option>
                                                                <option>Leonard Dee</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Contact Number</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">09199212069</option>
                                                                <option>09171731237</option>
                                                                <option>09561822091</option>
                                                                <option>09128291102</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delivery Address</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">234 Haig St., Brgy. Daang Bakal, Mandaluyong City</option>
                                                                <option>204 E. Dela Paz, Brgy. Addition Hills, Mandaluyong City</option>
                                                                <option>74A Valenzuela St., Brgy. Batis, San Juan City</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Billing Address</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">234 Haig St., Brgy. Daang Bakal, Mandaluyong City</option>
                                                                <option>204 E. Dela Paz, Brgy. Addition Hills, Mandaluyong City</option>
                                                                <option>74A Valenzuela St., Brgy. Batis, San Juan City</option>
                                                            </select>
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
                                            <div style="background-color:lightgray" class="card-header">
                                                <h3 class="card-title">Payment & Delivery Details</h3>
        
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
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project Start</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Project End</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
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
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Currency</label>
                                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                                <option selected="selected">PHP</option>
                                                                <option>AUD</option>
                                                                <option>CAD</option>
                                                                <option>EUR</option>
                                                                <option>PHP</option>
                                                                <option>USD</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Project Cost</label>
                                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
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
                                    <div style="background-color:lightgray" class="card-header">
                                        <h3 class="card-title">System & Document Details</h3>

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
                                                    <div style="background-color:lightgray" class="card-header">
                                                        <h3 class="card-title">System Details</h3> 
                                                    </div> 
                
                                                    <div class="card-body">
                                                        <table class="table table-hover text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>...</th>
                                                                    <th>System Name</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
        
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                                        </ul>
                                                        </div>
                                                </div>
                                            </div>            
                                            
                                            <div class="col-md-6">
                                                <div class="card card-default">
                                                    <div style="background-color:lightgray" class="card-header">
                                                        <h3 class="card-title">Document Details</h3> 
                                                    </div> 
                
                                                    <div class="card-body">
                                                        <table class="table table-hover text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>...</th>
                                                                    <th>Document Name</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
        
                                                    <div class="card-footer clearfix">
                                                        <ul class="pagination pagination-sm m-0 float-right">
                                                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                                        </ul>
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
                                            <div style="background-color:lightgray" class="card-header">
                                                <h3 class="card-title">Project Personnel</h3>
        
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
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">Ceballos, Rosevir Jr.</option>
                                                                <option>Bonifacio, Andres</option>
                                                                <option>Ceballos, Rosevir Jr.</option>
                                                                <option>Dela Cruz, Juan</option>  
                                                                <option>Mabini, Andres</option>
                                                                <option>Rizal, Jose</option>
                                                                <option>Washington, George</option>
                                                            </select>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Coordinator</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">Ceballos, Rosevir Jr.</option>
                                                                <option>Bonifacio, Andres</option>
                                                                <option>Ceballos, Rosevir Jr.</option>
                                                                <option>Dela Cruz, Juan</option>  
                                                                <option>Mabini, Andres</option>
                                                                <option>Rizal, Jose</option>
                                                                <option>Washington, George</option>
                                                            </select>
                                                        </div>                            
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Delegates</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option selected="selected">Ceballos, Rosevir Jr.</option>
                                                                <option>Bonifacio, Andres</option>
                                                                <option>Ceballos, Rosevir Jr.</option>
                                                                <option>Dela Cruz, Juan</option>  
                                                                <option>Mabini, Andres</option>
                                                                <option>Rizal, Jose</option>
                                                                <option>Washington, George</option>
                                                            </select>
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
                                            <div style="background-color:lightgray" class="card-header">
                                                <h3 class="card-title">Accounting Details</h3>
        
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
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option>Yes</option>
                                                                <option selected="selected">No</option>
                                                            </select>
                                                        </div>                            
                                                    </div>
                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="amount">Down Payment Percentage</label>
                                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Down Payment Date Received</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>
                                                
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Number</label>
                                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                                        </div>                            
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Required</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option>Yes</option>
                                                                <option selected="selected">No</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Invoice Date Needed</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>         
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Sales Invoice Released</label>
                                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                                <option>Yes</option>
                                                                <option selected="selected">No</option>
                                                            </select>
                                                        </div>                            
                                                    </div> 

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Date of Invoice</label>
                                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
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

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="purpose">Attachments</label>
                                    <span class="btn btn-success col fileinput-button">
                                        <i class="fas fa-plus"></i>
                                        <span>Browse files</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button style="width:100%" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
                                </div>
                            </div> 
                        </div>
                    </div>                            
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>