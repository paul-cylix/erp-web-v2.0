@extends('layouts.base')
@section('title', 'Overtime Request') 
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
        window.location.href = "/approvals";
        }});
</Script>
@endif

@if(Session::get('form_error'))
<Script>
    swal({
        text: "{!! Session::get('form_error') !!}",
        icon: "error",
        closeOnClickOutside: false,
        closeOnEsc: false,               
        })
</Script>
@endif

{{-- Row Start --}}
<div class="row">
    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/approvals" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" data-toggle="modal" data-target="#clarityModal" >Clarify</button></div>                    
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " disabled >Withdraw</button></div>        
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" data-toggle="modal" data-target="#rejectedModal" >Reject</button></div>      
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>   
        </div> 
    </div> 
{{-- col-md-12 start --}}
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title">@yield('title')</h3>
            </div>


                <div class="card-body">
                    <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>
                                                   
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reference Number</label>
                                <input type="text" value="{{ $post[0]->reference }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
            
                            </div>                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Requested Date</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" id="dateRequested" name="dateRequested" class="form-control datetimepicker-input" value="{{ $post[0]->request_date }}" readonly/>
                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>         
                        </div>  

   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reporting Manager</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post[0]->reporting_manager }}" readonly >
      
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">                                            
                                <label for="purpose">Purpose</label> 
                                <textarea style="resize:none" class="form-control" id="purpose" name="purpose" rows="4" readonly placeholder="">{{ $post[0]->remarks }}</textarea>
    
                            </div>
                        </div>
                    </div>




    {{-- Overtime Details Table Start--}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-gray">
                    <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                        <div class="row">
                            <div class="col" style="font-size:18px; padding-top:5px;">Overtime Details</div>
                        </div>                                       
                    </div> 
                    <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">

                        
                        <table class="table table-bordered " id="overtimeDetailsTable">
                            <thead>
                                <tr class="d-flex text-center" style="font-size: 13px;">
                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Employee Name</th>
                                    <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Project Name</th>
                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Overtime Date</th>
                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. OT Hours</th>
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                    <th class="col-1 tdid" style="position: sticky; top: 0; background: white; ">Actual OT Hours</th>
                                    <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                    <th class="col-1" style="position: sticky; top: 0; background: white; ">Action</th>
                                </tr>
                            </thead>
                            <tbody id="overtimeDetailsTbody">
                                @foreach ($post as $p )
                                    <tr class="d-flex" style="font-size: 13px;">
                                        <td class="col-2">{{ $p->employee_name }}</td>
                                        <td class="col-2">{{ $p->Project_Name }}</td>
                                        <td class="col-1">{{ $p->overtime_date }}</td>
                                                @php
                                                $date = date_create($p->ot_in);
                                                $date = date_format($date,"n/d/Y  h:i A");
                                                @endphp
                                                <td class="col-1">{{ $date }}</td>
                                                @php
                                                $date = date_create($p->ot_out);
                                                $date = date_format($date,"n/d/Y  h:i A");
                                                @endphp
                                                <td class="col-1">{{ $date}}</td>
                                                <td class="col-1">{{ $p->ot_totalhrs }}</td>

                                            @if (!empty($p->ot_in_actual) && !empty($p->ot_out_actual) && !empty($p->ot_totalhrs_actual))
                                                @php
                                                $date = date_create($p->ot_in_actual);
                                                $date = date_format($date,"n/d/Y  h:i A");
                                                @endphp  
                                                <td class="col-1">{{ $date }}</td>
                                                @php
                                                $date = date_create($p->ot_out_actual);
                                                $date = date_format($date,"n/d/Y  h:i A");
                                                @endphp  
                                                <td class="col-1">{{ $date}}</td>
                                                <td class="col-1">{{ $p->ot_totalhrs_actual }}</td>
                                            @else
                                                <script>
                                                    $('.tdid').addClass('d-none');
                                                </script>
                                            @endif

                                        <td class="col-3">{{ $p->purpose }}</td>
                                        <td class="col-1 text-center px-0">
                                            <button disabled class="btn btn-success"><i class="fas fa-edit"></i></button>
                                            <button disabled class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                    </div>
                    {{-- footer /Pagination part --}}
                    <div class="card-footer clearfix">
                    </div>
                </div>
            </div>                                    
        </div>
    {{-- Overtime Details Table End --}}

                </div>
        {{-- Card Body END --}}
            </form>    
        </div>
    </div>
{{-- Col-md-12 end --}}
</div>
{{-- Row END --}}





{{-- Modal Clarity with message--}}
    <div class="modal fade" id="clarityModal" tabindex="-1" role="dialog" aria-labelledby="clarityModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
            <h5 class="modal-title" id="clarityModalLabel">Clarity Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>


            <form action="{{ route('clarify.hr') }}" method="POST">
                @csrf
            </div>
            <div class="modal-body">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="clarityRecipient">Choose Recipient</label>
                            <select id="clarityRecipient" name="clarityRecipient" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                            
                                @foreach($getRecipientName as $recipientName)
                                    <option value="{{ $recipientName->uid }}">{{ $recipientName->Name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                    <input type="hidden" value="@yield('title')" name="frmName">
                   

                    <div class="row" style="margin-top: 7px;">
                        <div class="col-md-12">                     
                            <label for="clarificationRemarks">Message</label>
                            {{-- <div class="card-body"> --}}
                                <div class="form-floating">
                                    <input type="hidden" value=" " name="idName">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="clarificationRemarks" id="clarificationRemarks" style="height: 100px"></textarea>
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Proceed"></input>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
        </div>
    </div>
{{-- End Modal --}}



{{-- Modal Approve --}}
    <div class="modal fade"  id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('approved.hr') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="approvedRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                    <input type="hidden" value="@yield('title')" name="frmName">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="approveRemarks" id="approveRemarks" style="height: 100px"></textarea>
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
{{-- End Approved Modal --}}



{{-- Modal Reject --}}
    <div class="modal fade"  id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="rejectedModalLabel">Reject Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('rejected.hr') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="rejectedRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" name="main_id" id="" value="{{ $post[0]->main_id }}">
                                    <input type="hidden" value="@yield('title')" name="frmName">
                                    <textarea class="form-control" placeholder="Leave a comment here" name="rejectedRemarks" id="rejectedRemarks" style="height: 100px"></textarea>
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
{{-- End Reject Modal --}}

















@endsection

{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
