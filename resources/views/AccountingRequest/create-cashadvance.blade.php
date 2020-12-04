@extends('layouts.base')
@section('title', 'Cash Advance Request') 
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
                                            <label for="exampleInputEmail1">Payable Date From</label> 
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
                                            <label for="exampleInputEmail1">Payable Date To</label> 
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Date Needed</label> 
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
                                            <label for="amount">Total Advance Amount</label>
                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="amount">Installment Amount</label>
                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
                                        </div>
                                    </div>  

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="amount">Total Aprpoved Amount</label>
                                            <input data-type="currency" min="0" style="text-align: right" type="text" class="form-control" name="amount" id="amount" placeholder="">
                                        </div>
                                    </div>  
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">                                            
                                            <label for="purpose">Reason</label> 
                                            <textarea style="resize:none" class="form-control" id="reason" name="reason" rows="4" placeholder=""></textarea>
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

                                {{-- <div class="row">
                                    <div class="col-md-3">
                                        <div class="card card-default">
                                            <div class="card-header">
                                                <h3 class="card-title">Attachments</h3>
                                            </div>
                                            <div class="card-body">
                                                <div id="actions" class="row">
                                                    <div class="col-lg-3">
                                                        <span class="btn btn-success col fileinput-button">
                                                            <i class="fas fa-plus"></i>
                                                            <span>Browse files</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

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