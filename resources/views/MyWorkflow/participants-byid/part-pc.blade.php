@extends('layouts.base')
@section('title', 'Petty Cash Request') 
@section('content')
    <div class="row">
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
        
        <div class="col-md-12" style="margin: -20px 0 20px 0 " >
            <div class="form-group" style="margin: 0 -5px 0 -5px;">
                    <div class="col-md-1 float-left"><a href="/participants" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                    <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>   
            </div> 
        </div> 


        

 
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">Petty Cash Request</h3>
                </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="referenceNumber">Reference Number</label>
                                    <input type="text" class="form-control" value="{{ $post->REQREF }} " readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateRequested">Requested Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateRequested" name="dateRequested" value="{{ date('m/d/Y') }}"  class="form-control datetimepicker-input" readonly/>
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
                                    <input id="projectName" name="projectName" type="text" class="form-control" value="{{ $post->PROJECT }}" readonly >
                                </div>
                            </div>
                            
                            <input id="clientID" name="clientID" type="hidden" class="form-control" placeholder="" readonly>
                            <input id="mainID" name="mainID" type="hidden" class="form-control" placeholder="" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>
                                    <input id="clientName" name="clientName" type="text" class="form-control" value="{{ $post->CLIENT_NAME }}" readonly >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payeeName">Payee Name</label>
                                    <input id="payeeName" name="payeeName" type="text" class="form-control" value="{{ $post->PAYEE }}" readonly >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dateNeeded">Date Needed</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input type="text" id="dateNeeded" name="dateNeeded" class="form-control datetimepicker-input" value="{{ $post->TRANS_DATE }}" readonly/>
                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>         
                            </div>

                


                            @php
                            $foo = $post->REQUESTED_AMT;
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
                                    <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4"  readonly>{{ $post->DESCRIPTION }}</textarea>                              
                                </div>
                                
                            </div>
                        </div>











                        {{-- Expense Details --}}
                        @if (!empty($expenseDetails))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Expense Details</div>                                          
                                            {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#expenseDetail">Add Record</a></div> --}}
                        
                                        </div>                                       
                                    </div> 
                        
                                    <div class="card-body table-responsive p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-hover text-nowrap" id="xdTable">
                                            <thead>
                                                <tr>
                                                    <th style="position: sticky; top: 0; background: white; ">Date</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Client Name</th>

                                                    <th style="position: sticky; top: 0; background: white; ">Expense Type</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Remarks</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Amount</th>
                                                    <th style="position: sticky; top: 0; background: white; ">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="xdTbody">
                                                @forelse ($expenseDetails as $xdData)
                                                    <tr>
                                                        <td>{{ $xdData->date_ }}</td>
                            <td>{{ $xdData->CLIENT_NAME }}</td>

                                                        <td>{{ $xdData->EXPENSE_TYPE }}</td>
                                                        <td>{{ $xdData->DESCRIPTION }}</td>
@php
$foo = $xdData->AMOUNT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp

                                                        <td>{{ $myAMount }}</td>
                                                        <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                    </tr>
                                                @empty
                                                <tr><td colspan="5" style="padding-left: 25px;">no data</td></tr>                                                  
                                                @endforelse
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                    <div class="container">
                                    <div class="row float-right" style="margin-right: 50px;">
                                    {{-- <span >Total Amount:</span> --}}
                                    </div>
                                    </div>
                    @php
                    $foo = $subtotalExpenseDetails[0]->total ;
                    $myAMount = number_format((float)$foo, 2, '.', ''); 
                    @endphp

                    <div class="container">
                    <h6  class="text-right">Subtotal Amount: <span id ="xdTotalAmount">{{ $myAMount }}</span></h6>
                    </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        @endif
                        {{-- Expense Details --}}

                    
                        {{-- Transportation Details --}}
                        @if (!empty($transpoDetails))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-gray">
                                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">

                                        <div class="row">
                                            <div class="col" style="font-size:18px; padding-top:5px;">Transportation Details</div>                                          
                                            {{-- <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target="#transpoDetails">Add Record</a></div> --}}

                                        </div>
                                    </div>

                                    <div class="card-body table-responsive p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                        <table class="table table-hover text-nowrap" id="tdTable" >
                                            <thead>
                                                <tr>
                                                    <th style="position: sticky; top: 0; background: white;" >Date</th>
                        <th style="position: sticky; top: 0; background: white; ">Client Name</th>

                                                    <th style="position: sticky; top: 0; background: white;" >Destination From</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Destination To</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Mode of Transportation</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Remarks</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Amount</th>
                                                    <th style="position: sticky; top: 0; background: white;" >Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tdTbody">
                                                @forelse ($transpoDetails as $tdData)
                                                    <tr>
                                                        <td>{{ $tdData->date_ }}</td>
                            <td>{{ $tdData->CLIENT_NAME }}</td>

                                                        <td>{{ $tdData->DESTINATION_FRM }}</td>
                                                        <td>{{ $tdData->DESTINATION_TO }}</td>
                                                        <td>{{ $tdData->MOT }}</td>
                                                        <td>{{ $tdData->DESCRIPTION }}</td>
@php
$foo = $tdData->AMT_SPENT;
$myAMount = number_format((float)$foo, 2, '.', ''); 
@endphp
                                                        <td>{{ $myAMount }}</td>
                                                        <td><button type="button"  class="btn btn-danger " disabled>Delete</button></td>
                                                    </tr>
                                                @empty
                                                <tr><td colspan="7" style="padding-left: 25px;">no data</td></tr>                                                  
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- footer /Pagination part --}}
                                    <div class="card-footer clearfix">
                                        <div class="container">
                                        <div class="row float-right" style="margin-right: 50px;">
                                        {{-- <span >Total Amount:</span> --}}
                                        </div>
                                        </div>
            @php
            $foo = $subtotalTranspoDetails[0]->total ;
            $myAMount = number_format((float)$foo, 2, '.', ''); 
            @endphp
            <div class="container">
            <h6  class="text-right">Subtotal Amount: <span id ="tdTotalAmount">{{ $myAMount }}</span></h6>
            </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                        @endif
                        {{-- Transportation details --}}



                
                        {{-- Attachments --}}
                        @if (!empty($attachmentsDetails))
                        <div class="row">
                        <div class="col-md-12">
                            <div class="card card-gray">
                                <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                <div class="row">
                                    <div class="col" style="font-size:18px; padding-top:5px;">Attachments</div>                                          
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
                                                @foreach ($attachmentsDetails as $file )
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
                        {{-- End Attachments --}}
                            {{-- End Attachments --}}


                    </div> 
            </div>
        </section>
    </div>

    
@endsection
{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



