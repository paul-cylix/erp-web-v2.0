@extends('layouts.base')
@section('title', 'Request For Payment') 
@section('content')
    <div class="row">
        
        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                    <div class="col-md-1 float-left"><a href="/participants" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right "  disabled >Withdraw</button></div>        
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>   
            </div> 
        </div> 

        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">{{ $payeeDetails->FRM_NAME }}</h3>
                </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="{{ $post->REQREF }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateRequested">Requested Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateRequested" name="dateRequested" value="{{ $post->DATE }}"  class="form-control datetimepicker-input" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <input id="RMName" name="RMName" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="reportingManager">Reporting Manager</label>
                                    <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post->REPORTING_MANAGER }}" readonly >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="initiator">Initiator</label>
                                    <input id="initiator" name="initiator" type="text" class="form-control" value="{{ $initName }}" readonly >
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectName">Project Name</label>
                                    <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $postDetails->PROJECT }}" readonly >
                                </div>
                            </div>
                            
                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>
                                    <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $postDetails->CLIENTNAME }}" readonly >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateNeeded">Date Needed</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $postDetails->DATENEEDED }}" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>         
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payeeName">Payee Name</label>
                                    <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $payeeDetails->Payee }}" readonly >
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <input id="currency" name="currency" type="text" class="form-control" value="{{ $postDetails->CURRENCY }}" readonly >
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="modeOfPayment">Mode of Payment</label>
                                    <input id="modeOfPayment" name="modeOfPayment" type="text" class="form-control" value="{{ $postDetails->MOP }}" readonly >
                                </div>
                            </div>

                            @php
                            $foo = $post->AMOUNT;
                            $myAMount = number_format((float)$foo, 2, '.', ''); 
                            @endphp
                            
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="amount">Amount</label>
                                                                <input id="amount" name="amount" type="text" class="form-control text-right" value="{{ $myAMount }}"  readonly >
                            
                                                                
                            
                                                            </div>
                                                        </div>
                                                    </div>
                            
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="purpose">Purpose</label>
                                                                <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" placeholder="{{ $postDetails->PURPOSED }}" readonly></textarea>                              
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                            
                            
                            @if (!empty($qeLiquidationTable))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-gray" style="padding: 0px;" >
                                        <div class="card-header col " style="height:50px;">
                                            <div class="row ">
                                                <div class="col" style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Liquidation Table</h3>  </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                            <table id="myTable" class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Expense Type</th>
                                                    <th>Description</th>
                                                    <th>Currency</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                            
                                                <tbody>
                                                    @foreach ($qeLiquidationTable as $qeData)
                                                    <tr>
                                                        <td>{{ $qeData->trans_date }}</td>
                                                        <td>{{ $qeData->expense_type }}</td>
                                                        <td>{{ $qeData->description }}</td>
                                                        <td>{{ $qeData->currency }}</td>
                            @php
                            $foo = $qeData->Amount;
                            $myAMount = number_format((float)$foo, 2, '.', ''); 
                            @endphp
                                                        <td>{{ $myAMount }}</td>
                                                        <td><button class="btn btn-danger" disabled>Delete</button></td>
                                                    </tr>   
                                                    @endforeach
                            
                                                
                            
                                                </tbody>
                                            </table>
                                                <div class="container">
                                                    <div class="float-right">
                            @php
                            $foo = $qeSubTotal[0]->subTotalAmount ;
                            $myAMount = number_format((float)$foo, 2, '.', ''); 
                            @endphp
                                                        <h6 style="margin-right:140px;">Total Amount: <span id ="spTotalAmount">{{ $myAMount }}</span></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                                
                            @if (!empty($filesAttached))
                            <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="height:50px;">
                                        <div class="row ">
                                            <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Attachments</h3></div>
                                        </div>
                                    </div>
                                    <div class="card-body" >
                                        <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                            <table id= "attachmentsTable"class="table table-hover" >
                                                <thead >
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Temporary Path</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody >
                                                    @foreach ($filesAttached as $file )
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td>{{ $file->fileExtension }}</td>
                                                        <td>{{ $file->filepath }}</td>
                                                        <td><a class="btn btn-secondary" href="{{ asset('/'.$file->filepath.'/'.$file->filename) }}" target="_blank" >View</a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @endif


                        {{-- <div class="row"> 

                            <div class="col-md-1">
                                <div class="form-group">
                             
                                    <button type="button" style="width:100%" class="btn btn-primary" disabled>Approve</button>                         
                                </div>
                            </div> 

                            <div class="col-md-1">
                                <div class="form-group">
                           
                                    <button type="button" style="width:100%" class="btn btn-secondary" disabled>Decilne</button>   
                                </div>
                            </div> 

                            <div class="col-md-1">
                                <div class="form-group">
                          
                                    <button type="button" style="width:100%" class="btn btn-success" data-toggle="modal" data-target="#withdrawModal">Withdraw</button>   
                                </div>
                            </div> 

                       

                            <div class="col-md-1">
                                <div class="form-group">
         
                                    <button type="button"  style="width:100%" class="btn btn-danger" disabled>Clarify</button>                        
                                </div>
                            </div> 

                            <div class="col-md-1" >
                                <div class="form-group" >
                                   
                                    <button type="button"  style="width:100%" class="btn btn-warning" data-toggle="modal" data-target="#replyModal" >Reply</button>  
                                </div>
                            </div> 

                            <div class="col-md-1" style="font-size: 12.2px">
                                <div class="form-group" >
               
                                    <button type="button" style="width:100%" class="btn btn-info"  disabled>Restart</button>   
                                </div>
                            </div> 
                        </div> --}}


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

                          <!-- Modal Withdraw-->
                            <div class="modal fade"  id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModal" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark" >
                                    <h5 class="modal-title" id="withdrawModalLabel">Withdraw Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <form action="{{ route('cla.withdraw.post') }}" method="POST">
                                        @csrf
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">                     
                                                    <label for="withdrawRemarks">Remarks</label>
                                                    <div class="card-body">
                                                        <div class="form-floating">
                                                            <input type="hidden" value="{{ $post->ID }}" name="idName">
                                                            <textarea class="form-control" placeholder="Leave a comment here" name="withdrawRemarks" id="withdrawRemarks" style="height: 100px"></textarea>
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
                                    </form>
                                </div>
                                </div>
                            </div>


                            <!-- Modal Reply-->
                            <div class="modal fade"  id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-dark" >
                                <h5 class="modal-title" id="replyModalLabel">Reply Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <form action="" method="POST">
                                    @csrf
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">                     
                                                <label for="replyRemarks">Remarks</label>
                                                <div class="card-body">
                                                    <div class="form-floating">
                                                        <input type="hidden" value="{{ $post->ID }}" name="idName">
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
                                </form>
                            </div>
                            </div>
                        </div>








                    </div> 
            </div>
        </section>
    </div>

    
@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



