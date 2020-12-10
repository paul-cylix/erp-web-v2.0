@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">Request For Payment</h3>
                </div>

                <form action="">
                    <div class="card-body">                                
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" placeholder="" readonly>
                                </div>                            
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateRequested">Requested Date</label>
                                    <div class="input-group date" id="dateRequested" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#dateRequested" value="{{date('m/d/Y')}}" readonly/>
                                        <div class="input-group-append" data-target="#dateRequested" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>         
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Reporting Manager</label>
                                    <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        @foreach ($mngrs as $rm)
                                            <option value="{{$rm->RMID}}">{{$rm->RMName}}</option>
                                        @endforeach
                                    </select>
                                </div>                            
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project Name</label>
                                    <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        @foreach ($projects as $prj)
                                            <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                        @endforeach
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
                                    <label for="exampleInputEmail1">Payee Name</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-1">
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

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mode of Payment</label>
                                    <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                        <option selected="selected">Cash</option>
                                        <option>Cash</option>
                                        <option>Check</option>
                                        <option>Credit to Account</option>
                                    </select>
                                </div>                            
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Amount</label> 
                                    <input type="text" min="0.01" style="text-align: right" type="text" class="form-control currency" name="amount" id="amount" placeholder="" value="0.00">
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
        </section>
    </div>
@endsection

<script> 
    $(document).ready(function(){
        $('#yn').bootstrapToggle();

        $('input.currency').keyup(function(event) {

            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
            });
        });
    });
</script> 