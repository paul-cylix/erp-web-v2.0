@extends('layouts.base')
@section('title', 'Incident Report') 
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
                                            <label for="exampleInputEmail1">Date Submitted</label>
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
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Customer Address</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Date and Time of Incident</label>
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
                                            <label for="exampleInputEmail1">Incident Happened During</label>
                                            <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                                <option selected="selected">Regular Working Hours</option>
                                                <option>Overtime Hours</option>
                                            </select>
                                        </div>                            
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Date From</label>
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
                                            <label for="exampleInputEmail1">Date To</label>
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
                                            <label for="purpose">Brief Description of Incident</label> 
                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <div class="form-group">                                            
                                            <label for="purpose">Extent Damage</label> 
                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">                                            
                                            <label for="purpose">Action Taken</label> 
                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <div class="form-group">                                            
                                            <label for="purpose">Recommended Preventive Measures</label> 
                                            <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
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

@endsection