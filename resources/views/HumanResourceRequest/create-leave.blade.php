@extends('layouts.base')
@section('title', 'Leave Request') 
@section('content')

<div class="content-wrapper">

    <br>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-gray">
                        <div class="card-header">
                            <h3 class="card-title">@yield('title')</h3>
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
                                            <label for="exampleInputEmail1">Employee Name</label>
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
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Medium of Report</label>
                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                <option selected="selected">Call</option>
                                                <option>Call</option>
                                                <option>Email</option>
                                                <option>Text</option>  
                                                <option>MS Teams</option>
                                                <option>Skype</option>
                                                <option>Viber</option>
                                            </select>
                                        </div>                            
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Report Time</label>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="message-text" class="col-form-label">Reason</label>
                                            <textarea class="form-control" id="message-text"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-header">
                                                <h3 class="card-title">Leave Details</h3>
        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                      <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
        
                                            <div class="card-body table-responsive p-0">
                                                <div class="col-md-1" style="padding-top:5px">
                                                    <div class="form-group">
                                                        <button style="width:100%" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</button>
                                                        {{-- <button style="width:100%" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" data-whatever="@mdo">Add Record</button> --}}
                                                    </div>
                                                </div>
                                                
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Leave Date</th>
                                                            <th>Leave Type</th>
                                                            <th>Half Day</th>
                                                            <th>AM/PM</th>
                                                            <th>Count</th>
                                                            <th>With Pay</th>
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
                                            <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
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

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Overtime Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Leave Type</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    <option selected="selected">Vacation Leave</option> 
                                    <option>Sick Leave</option>
                                    <option>Maternity Leave</option>
                                    <option>Paternity Leave</option>  
                                    <option>Special Leave</option>
                                </select>
                            </div>                            
                        </div> 
                    </div> 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Leave Date Start</label>
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
                                <label for="exampleInputEmail1">Leave Date End</label>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Pay Type</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    <option selected="selected">With Pay</option> 
                                    <option>Without Pay</option>
                                </select>
                            </div>                            
                        </div> 
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>