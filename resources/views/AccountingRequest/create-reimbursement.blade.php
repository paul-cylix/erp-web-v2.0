@extends('layouts.base')
@section('title', 'Reimbursement Request') 
@section('content')

<div class="row" style="margin-top: -20px;"> 
    <div class="col-md-1">
        <div class="form-group">
            <input style="width:100%;"  type="submit" class="btn btn-primary"  value="Submit"/>                                
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
                    <h3 class="card-title">Reimbursement Request</h3>
                </div>

                <form action="">


                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" value="{{ $ref1 }}" readonly>
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
                                    <select id="reportingManager" name="reportingManager" class="form-control select2 select2-default"  data-dropdown-css-class="select2-default" style="width: 100%;" onchange="getRMName(this)">
                                        <option selected disabled hidden style='display: none' value=''></option>
                                        @foreach ($mgrs as $rm)
                                            <option value="{{$rm->RMID}}">{{$rm->RMName}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">@error('reportingManager'){{ $message }}@enderror</span>
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
                                    <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
                                </div>
                            </div>
                            
                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>                              
                                    <input id="clientName" name="clientName" type="text" class="form-control" placeholder="" readonly >
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
                                    <label for="dateNeeded">Date Needed</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" />
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Total Amount</label>
                                    <input data-type="currency" min="0" style="text-align: right" type="number" placeholder="0.00" class="form-control" name="amount" id="amount" placeholder="">
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
                    </form>

                        {{-- Expense Details --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        {{-- <h3 class="card-title">Expense Details</h3> --}}
                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                                         <div class="col"><button class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</button></div>
                                        </div>
                                        
                                        {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}

                                    </div> 

                                    <div class="card-body table-responsive p-0">
                                        {{-- <div class="col-md-1" style="padding-top:5px">
                                            <div class="form-group">
                                                <button style="width:100%" type="submit" class="btn btn-primary">Add Record</button>
                                            </div>
                                        </div> --}}
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

<!-- Modal Expense Detail -->
<div class="modal fade" id="expenseDetail" tabindex="-1" aria-labelledby="expenseDetail" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="expenseDetailLabel">Expense Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        {{-- START ADD MODAL--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
              
                        <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" aria-describedby="helpId" id="addDateXD">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Expense Type</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addExpTypeXD">

                                </select>

                                
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Currency</label>
                                <select  class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addCurrXD">

                                </select>

                                
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Amount</label>
                                <input type="number" class="form-control" placeholder="0.00" aria-describedby="helpId" required id="addAmntXD">

                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" rows="5"  placeholder="input text here" required id="addDescXD"></textarea>

                                </div>
                            </div>
                        </div>
                   
                </div>
            </div>
        </div>
        {{-- END ADD--}}
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="insertDataExpenseDetails()">Insert</button>

        </div>
    </div>
    </div>
</div>
{{-- End Modal Expense Detail --}}



                                    {{-- Pagination Expense Details --}}
                                    {{-- <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                        </ul>
                                    </div> --}}
                                    {{-- End Pagination --}}
                                </div>
                            </div>                                    
                        </div>
                        {{-- Expense Details --}}



                        {{-- Transportation Details --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        {{-- <h3 class="card-title">Transportation Details</h3> --}}

                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                                            <div class="col"><button class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</button></div>
                                        </div>

                                        {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}
                                    </div>

                                    <div class="card-body table-responsive p-0">
                                        {{-- <div class="col-md-1" style="padding-top:5px">
                                            <div class="form-group">
                                                <button style="width:100%" type="submit" class="btn btn-primary">Add Record</button>
                                            </div>
                                        </div> --}}
                                        
                                        <table class="table table-hover text-nowrap" id="tableXD">
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

<!-- Modal Transportation Details -->
<div class="modal fade" id="transpoDetails" tabindex="-1" aria-labelledby="transpoDetails" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="transpoDetailsLabel">Transportation Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        {{-- START ADD MODAL--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
              
                        <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" aria-describedby="helpId" id="addDate">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Expense Type</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addExpType">

                                </select>

                                
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Currency</label>
                                <select  class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" required id="addCurr">
 
                                </select>

                                
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Amount</label>
                                <input type="number" class="form-control" placeholder="0.00" aria-describedby="helpId" required id="addAmnt">

                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea class="form-control" rows="5"  placeholder="input text here" required id="addDesc"></textarea>

                                </div>
                            </div>
                        </div>
      
                </div>
            </div>
        </div>
        {{-- END ADD--}}
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Insert</button>

        </div>
    </div>
    </div>
</div>
{{-- End Modal Transportation Details --}}

                                    {{-- Pagination Transportation Details --}}
                                    {{-- <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                        </ul>
                                    </div> --}}
                                    {{-- End Pagination --}}
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

                        {{-- Upload --}}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><strong>Upload Files</strong></label>
                                    <div class="custom-file">
                                    <input type="file" name="file[]" multiple class="custom-file-input form-control" id="customFile" style="cursor:pointer;">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- buttons send --}}
                        {{-- <div class="row"> 
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
                        </div> --}}

                    </div>                            
            </div>
        </div>
    </div>
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
        function insertDataExpenseDetails(){
            var addDateXD = $('#addDateXD').val();
            var addExpTypeXD = $('#addExpTypeXD').val(); 
            var addCurrXD = $('#addCurrXD').val(); 
            var addAmntXD = $('#addAmntXD').val(); 
            var addDescXD = $('#addDescXD').val(); 
    
            if(addCurrXD === ""){
                alert('tes');
            }else{
            console.log(typeof (addDateXD),addExpTypeXD,addCurrXD,addAmntXD,addDescXD);
    
            }
        }
    </script>
@endsection



<script>
    function showDetails(id) {
        if (window.XMLHttpRequest) {
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
                document.getElementById("mainID").value = res.mainID;
            }
        }
        xmlhttp.open("GET","/get-client/"+id,true);
        xmlhttp.send();
    }

    function showReference() {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }
        else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var txt = xmlhttp.responseText.replace("[", "");
                txt = txt.replace("]", "");
                var res = JSON.parse(txt);
                document.getElementById("referenceNumber").value = res.REF;
            }
        }
        xmlhttp.open("GET","/get-reference/RFP", true);
        xmlhttp.send();

        return true;
    }

    function getRMName(sel) {
        var rm_txt = sel.options[sel.selectedIndex].text;
        document.getElementById("RMName").value = rm_txt;
    }
</script>


