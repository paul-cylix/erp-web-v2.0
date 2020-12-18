@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">Request For Payment</h3>
                </div>

                @if (Session::has('form_submitted'))
                    <div class="alert alert-success" role="alert">
                        {{Session::get('form_submitted')}}
                    </div>
                @endif
                <form method="POST" action="{{route('wfm.createRFP')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" placeholder="" value=<?php echo "RFP-" . date('Y') . "-"?> readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateRequested">Requested Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{date('m/d/Y')}}" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reportingManager">Reporting Manager</label>
                                    <select id="reportingManager" name="reportingManager" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getRMName(this)">
                                        <option selected disabled hidden style='display: none' value=''></option>
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
                                    <label for="projectName">Project Name</label>
                                    <select id="projectName" name="projectName" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                        <option selected disabled hidden style='display: none' value=''></option>
                                        @foreach ($projects as $prj)
                                            <option value="{{$prj->project_id}}">{{$prj->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>
                                    <input id="clientName" name="clientName" type="text" class="form-control" placeholder="" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateNeeded">Date Needed</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" readonly/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payeeName">Payee Name</label>
                                    <input type="text" class="form-control" id="payeeName" name="payeeName" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select id="currency" name="currency" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
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
                                    <label for="modeOfPayment">Mode of Payment</label>
                                    <select id="modeOfPayment" name="modeOfPayment" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        <option selected="selected">Cash</option>
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
                                    <input style="width:100%" type="submit" class="btn btn-primary" value="Submit"/>
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
    $(function () {
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
    })
</script>

<script>
    function showDetails(id) {
        console.log('hello');
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
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
                //document.getElementById("clientName").value = 'hello';
            }
        }
        xmlhttp.open("GET","/get-client/"+id,true);
        xmlhttp.send();
    }

    function getRMName(sel) {
        var rm_txt = sel.options[sel.selectedIndex].text;
        document.getElementById("RMName").value = rm_txt;
    }
</script>