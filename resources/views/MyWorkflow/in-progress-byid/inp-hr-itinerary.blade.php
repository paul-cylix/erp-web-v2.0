@extends('layouts.base')
@section('title', 'Itinerary Request') 
@section('content')
<div class="row">
    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/in-progress" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal" >Withdraw</button></div>        
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" disabled>Approve</button></div>   
        </div> 
    </div> 
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
                                <input type="text" value="{{ $post->reference }}" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
            
                            </div>                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Requested Date</label>
                                <div class="input-group date" data-target-input="nearest">
                                    <input type="text" id="dateRequested" name="dateRequested" disabled class="form-control datetimepicker-input" value="{{ $post->request_date }}" readonly/>
                                    <div class="input-group-append" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>         
                        </div>  

   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reporting Manager</label>
                                <input id="reportingManager" name="reportingManager" type="text" class="form-control" value="{{ $post->reporting_manager }}" readonly >
                                <span class="text-danger">@error('rmID'){{ $message }}@enderror</span>
                            </div>                            
                        </div>

                    </div>






{{-- Overtime Details Table Start--}}
      <div class="row  mt-4">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                    <div class="row">
                        <div class="col" style="font-size:18px; padding-top:5px;">Itinerary Details</div>
                    </div>                                       
                </div> 
                <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">     
                    <table class="table table-bordered " id="itineraryDetailsTable">
                        <thead>
                            <tr class="d-flex text-center" style="font-size: 13px;">
                                <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Client Name</th>
                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                <th class="col-4 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Action</th>
                            </tr>

                        </thead>
                        <tbody id="itineraryDetailsTbody">
                            @foreach ($postDetails as $detail )
                                <tr class="d-flex" style="font-size: 13px;">
                                    <td class="col-md-3">{{ $detail->client_name }}</td>
                                    @php                
                                         $date = date_create($detail->time_start);
                                         $date = date_format($date,"n/d/Y");             
                                    @endphp
                                    <td class="col-md-2">{{ $date }}</td>
                                    @php                
                                        $date = date_create($detail->time_end);
                                        $date = date_format($date,"n/d/Y");
                                    @endphp
                                    <td class="col-md-2">{{ $date }}</td>
                                    <td class="col-md-4">{{ $detail->purpose }}</td>
                                    <td class="col-1 text-center px-0">
                                        <button class="btn btn-success" disabled><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger" disabled><i class="fas fa-trash-alt"></i></button>
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
</div>
{{-- Row END --}}


{{-- Withdraw Modal Start--}}
    <div class="modal fade"  id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark" >
            <h5 class="modal-title" id="withdrawModalLabel">Withdraw Request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('withdraw.itinerary') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">                     
                            <label for="withdrawRemarks">Remarks</label>
                            <div class="card-body">
                                <div class="form-floating">
                                    <input type="hidden" name="main_id" id="" value="{{ $post->id }}">
                                    <input type="hidden" value="@yield('title')" name="frmName">
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
{{-- Withdraw Modal End--}}

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



@endsection

{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
