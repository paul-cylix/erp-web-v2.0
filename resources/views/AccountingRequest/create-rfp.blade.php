@extends('layouts.base')
@section('title', 'Request For Payment') 
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




<form id="formfield " method="POST" action="{{ route('save.rfp') }}"  class="form-horizontal"  enctype="multipart/form-data" > 

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
                    <h3 class="card-title">Request For Payment</h3>
              
                </div>


                    @csrf
                    <div class="card-body">
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" placeholder="RFP-{{ date("Y") }}-" value= "" readonly>
                                    <span class="text-danger">@error('referenceNumber'){{ $message }}@enderror</span>

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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateNeeded">Date Needed</label>

                                    <div class="input-group date" id="reservationdate" data-target-input="nearest" aria-readonly="true">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" data-target="#reservationdate" />
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>

                                    <span class="text-danger">@error('dateNeeded'){{ $message }}@enderror</span>
                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payeeName">Payee Name</label>
                                    <input type="text" class="form-control" id="payeeName" name="payeeName" placeholder="">
                                    <span class="text-danger">@error('payeeName'){{ $message }}@enderror</span>
                                </div>
                                
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select id="currency" name="currency" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        <option value="PHP" selected="selected">PHP</option>
                                        <option value="AUD">AUD</option>
                                        <option value="CAD">CAD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option> 
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="modeOfPayment">Mode of Payment</label>
                                    <select id="modeOfPayment" name="modeOfPayment" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                        <option value="Cash" selected="selected">Cash</option>
                                        <option value="Check">Check</option>
                                        <option value="Credit to Account">Credit to Account</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" style="text-align: right" type="text" class="form-control currency" step="any" name="amount" id="amount" placeholder="" >
                                    <span class="text-danger">@error('amount'){{ $message }}@enderror</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="purpose">Purpose</label>
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder=""></textarea>
                                    <span class="text-danger">@error('purpose'){{ $message }}@enderror</span>
                                    {{-- Json Array --}}
               
                                </div>
                                
                            </div>
                        </div>

                        
        


                        {{-- Attachments --}}
                        <label class="btn btn-primary" style="font-weight:normal;">
                            Attach Files <input type="file" name="file[]" class="form-control-file" id="customFile" multiple hidden>
                        </label>

                        {{-- Sample<input type="file" name="file[]" id="" multiple> --}}

                 
                        

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

 





        </section>
    </div>




<script>
    var main = [];
    var semi = [];

        $(document).ready(function() {
        $('input[type="file"]').on("change", function() {
            let files = this.files;
            // console.log(files);
            // console.dir(this.files[0]);
            $('#attachmentsTable tbody tr').remove();
                
            for(var i = 0; i<files.length; i++){
                var tmppath = URL.createObjectURL(files[i]);
               
                semi.push(files[i]['name'], files[i]['type'], files[i]['size'], tmppath);
                //main.push(semi);
                //console.log(main);

                $('#attachmentsTable tbody').append('<tr>'+
                                '<td>'+files[i]['name']+'</td>'+
                                '<td>'+files[i]['type']+'</td>'+
                                // '<td>'+files[i]['size']+'</td>'+
                                '<td>'+tmppath+'</td>'+
                                "<td><a href='"+tmppath+"' target='_blank' class='btn btn-secondary'>View</a></td>"+
                                // "<td><button  class='btn btn-danger'>Remove</></td>"+
                                '</tr>'
                );
                            //add code to copy to public folder in erp-web
            }
        });
    });

</script>


{{-- Create BLOB --}}
<script>
    $('#i_file').change( function(event) {
    var tmppath = URL.createObjectURL(event.target.files[0]);
    $("img").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
    
    $("#disp_tmp_path").html("Temporary Path(Copy it and try pasting it in browser address bar) --> <strong>["+tmppath+"]</strong>");
});
</script>

{{-- latest add attach --}}




{{-- validation --}}
<script>

var mlist = [];

    function validationUpload() {
    
    var checker = false;
      var a = document.getElementById("reportingManager").value;
      console.log(a);
      
      var b = document.getElementById("projectName").value;
      console.log(b);
    
      var c = document.getElementById("dateNeeded").value;
      console.log(c);
    
      var d = document.getElementById("payeeName").value;
      console.log(d);
    
      var e = document.getElementById("amount").value;
      console.log(e);
    
      var f = document.getElementById("purpose").value;
      console.log(f);


    
      if (a !== null && a != "" && b !== null && b != "" && c !== null && c != "" && d !== null && d != "" && e !== null && e != "" && f !== null && f != ""){
      console.log('may laman');
      
        mlist.push(a,b,c,d,e,f);
        console.log(mlist.length);
        var h = mlist.length;
        console.log(h);

        var g = document.getElementById("validationUploadDz").value = h;
        
    
    } else {
        console.log('walang laman');
        console.log(mlist.length);
    
    }
    }
</script>
    


<script>
    $('#submit-all').on('click',function(){
        
        var amountF = $("#amount").val();
        amountF = parseFloat(amountF);


        if ($.trim($("#reportingManager").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#projectName").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#dateNeeded").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#payeeName").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if (amountF <= 0) {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

        if ($.trim($("#purpose").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please complete required fields.');
        return false;
        }

    })
</script>











@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

{{-- @if(Session::has('form_submitted'))
<Script>
  swal({
    text: "{!! Session::get('form_submitted') !!}",
    icon: "success",
    closeOnEsc: false,        
    })
    .then(okay => {
    if (okay) {
    window.location.href = "in-progress";
    }});
</Script>
@endif --}}
{{-- End of Sweet Alert --}}






{{-- New Get Ref --}}
{{-- <script>
    function getRef(){
        var elemRef = document.getElementById('referenceNumber');
        var ourRequest = new XMLHttpRequest();
        ourRequest.open('GET', '/get-ref' , true);
        ourRequest.onload = function(){
            var referenceNumber = JSON.parse(ourRequest.responseText);

        console.log(referenceNumber);
        elemRef.value=referenceNumber;
        console.log(elemRef.value);

        };
        ourRequest.send();
    }
</script> --}}
















<script>
    $(function () {
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    $('#submit').click(function(){
        alert('submitting');
        $('#formfield').submit();
    });
</script>

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

<script type="text/javascript">
    let jsreqref = $myReqRef;
    console.log(JSON.parse(jsreqref))
</script>


{{-- Liquidation Table --}}
{{-- <script>
    var myTableArr = [];
    let totalAmnt = [];
    let liqid = 0;

    // var myTableBody = document.getElementById('myTable').getElementsByTagName('tbody')[0],sumVal = 0;

    function addRow(){
         //Identifier
        var tbodyRef = document.getElementById('myTable').getElementsByTagName('tbody')[0];

        var liqdate = document.getElementById('liqdate').value;
        var liqtype = document.getElementById('liqtype').value;
        var liqdesc = document.getElementById('liqdesc').value;
        var liqcurr = document.getElementById('liqcurr').value;
        var liqamnt = document.getElementById('liqamnt').value;

 

        var x = myTableArr.length;
        
        if (x > 0){
            liqid = liqid + 1;
        } else {
            liqiq = 0;
        }
    

        var newRow = tbodyRef.insertRow();
        

        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);
        var cell5 = newRow.insertCell(4);
        var cell6 = newRow.insertCell(5);
        
        var z = newRow.id =liqid;


        cell1.innerHTML = liqdate;
        cell2.innerHTML = liqtype;
        cell3.innerHTML = liqdesc;
        cell4.innerHTML = liqcurr;
        cell5.id = 'myliqAmnt'
        cell5.innerHTML = liqamnt;
        cell6.id = liqid;
        cell6.innerHTML = '<input type="button" value="Delete" id='+liqid+' onclick="deleteRow(this)" class="btn btn-danger">';

       
        var listArr = [];
        listArr.push(liqdate,liqtype,liqdesc,liqcurr,liqamnt);
        myTableArr.push(listArr);

        console.log(myTableArr);
        calculate();
    }

    

    function deleteRow(e){
        var row = document.getElementById(e.id);
        row.parentElement.removeChild(row); 
        console.log(e.id);
        myTableArr[e.id].splice(0,5);
        console.log(myTableArr);

        calculate();
        
    }




    // var computeTable = document.getElementById('myTable'), sumVal = 0;
    // var samp = [];
    function calculate (){
        var newArr = [];

        for(var i = 0; i < myTableArr.length; i++)
        {
        newArr = newArr.concat(myTableArr[i][4]);
        }

        // Convert to int
        var listAmount = newArr.map((i) => Number(i));
        console.log(listAmount);
        console.log(newArr);


        // console.log(nuevo.reduce((a, b) => a + b, 0));


        const quickSum = (listAmount) => {
        const sum = listAmount.reduce((acc, val) => {
        return acc + (val || 0);
        }, 0);
        return sum;
        };
        // console.log(quickSum(listAmount));


        var quickTotalAmount = quickSum(listAmount);
        // console.log(quickTotalAmount);

        var myTotalAmount = document.getElementById('spTotalAmount').innerHTML = quickTotalAmount;


    }

    function submitAll(){
        var arrFiltered = myTableArr.filter(el => {
        return el != null && el != '';
        });

        console.log(arrFiltered);

        var myJSON = JSON.stringify(arrFiltered);
        
        document.getElementById("liquidationTable").value = myJSON;
    }





</script> --}}

{{-- AJAX Dynamic --}}
{{-- <script src="http://code.jquery.com/jquery-3.4.1.js"></script>
        
<script>
            $(document).ready(function () {
            $('#sub_category_name').on('change', function () {
            let id = $(this).val();
            $('#sub_category').empty();
            $('#sub_category').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
            type: 'GET',
            url: 'getClientsRFP/' + id,
            success: function (response) {
            var response = JSON.parse(response);
            console.log(response);   
            $('#sub_category').empty();
            $('#sub_category').append(`<option value="0" disabled selected>Select Sub Category*</option>`);
            response.forEach(element => {
                $('#sub_category').append(`<option value="${element['id']}">${element['name']}</option>`);
                });
            }
        });
    });
});
</script> --}}


{{-- Dropzone paul --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.8.1/min/dropzone.min.js" integrity="sha512-OTNPkaN+JCQg2dj6Ht+yuHRHDwsq1WYsU6H0jDYHou/2ZayS2KXCfL28s/p11L0+GSppfPOqwbda47Q97pDP9Q==" crossorigin="anonymous"></script>
<script>
    var segments = location.href.split('/');
    var action = segments[4];
    console.log(action);
    if (action == 'dropzone') {
        var acceptedFileTypes = "image/*, .psd"; //dropzone requires this param be a comma separated list
        var fileList = new Array;
        var i = 0;
        var callForDzReset = false;
        $("#dropzonewidget").dropzone({
      
            url: "dropzone-store",
            addRemoveLinks: true,
            maxFiles: 4,
            acceptedFiles: 'image/*',
            maxFilesize: 5,
            init: function () {
                this.on("success", function (file, serverFileName) {
                    file.serverFn = serverFileName;
                    fileList[i] = {
                        "serverFileName": serverFileName,
                        "fileName": file.name,
                        "fileId": i
                    };
                    i++;
                });
            }
        });
    }
    </script> --}}





{{-- // DropzoneJS Demo Code Start --}}
{{-- <script>
    var segments = location.href.split('/');
    var action = segments[4];
    console.log(action);
    if (action == 'dropzone') {
        var acceptedFileTypes = "image/*, .psd"; //dropzone requires this param be a comma separated list
        var fileList = new Array;
        var i = 0;
        var callForDzReset = false;
        $("#dropzonewidget").dropzone({
      
            url: "dropzone-store",
            addRemoveLinks: true,
            maxFiles: 4,
            acceptedFiles: 'image/*',
            maxFilesize: 5,
            init: function () {
                this.on("success", function (file, serverFileName) {
                    file.serverFn = serverFileName;
                    fileList[i] = {
                        "serverFileName": serverFileName,
                        "fileName": file.name,
                        "fileId": i
                    };
                    i++;
                });
            }
        });
    }
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.8.1/min/dropzone.min.js" integrity="sha512-OTNPkaN+JCQg2dj6Ht+yuHRHDwsq1WYsU6H0jDYHou/2ZayS2KXCfL28s/p11L0+GSppfPOqwbda47Q97pDP9Q==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

 --}}



    
{{-- Dropzone end --}}