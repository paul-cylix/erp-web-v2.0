@extends('layouts.base')
@section('title', 'Petty Cash Request') 
@section('content')

<div class="content-wrapper"> 

    <br>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gray">
                        <div class="card-header">
                            <h3 class="card-title">Petty Cash Request</h3>
                        </div>

                        <form action="">
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Reference Number</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                        </div>                            
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Requested Date</label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>         
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Reporting Manager</label>
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Project Name</label>
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
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Client Name</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Payee Name</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Date Needed</label>
                                            <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate1"/>
                                                <div class="input-group-append" data-target="#reservationdate1" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>         
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="amount">Total Amount</label>
                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">                                            
                                            <label for="purpose">Purpose</label> 
                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-header">
                                                <h3 class="card-title">Expense Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                      <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div> 
        
                                            <div class="card-body table-responsive p-0">
                                                <div class="col-md-1" style="padding-top:5px">
                                                    <div class="form-group">
                                                        <button style="width:100%" type="submit" class="btn btn-primary">Add Record</button>
                                                    </div>
                                                </div>
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Expense Type</th>
                                                            <th>Remarks</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
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


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-header">
                                                <h3 class="card-title">Transportation Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                      <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
        
                                            <div class="card-body table-responsive p-0">
                                                <div class="col-md-1" style="padding-top:5px">
                                                    <div class="form-group">
                                                        <button style="width:100%" type="submit" class="btn btn-primary">Add Record</button>
                                                    </div>
                                                </div>
                                                
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Destination From</th>
                                                            <th>Destination To</th>
                                                            <th>Mode of Transportation</th>
                                                            <th>Remarks</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
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
                                            <button style="width:100%" type="submit" class="btn btn-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
<script>
    $(function () {
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
    })
</script>