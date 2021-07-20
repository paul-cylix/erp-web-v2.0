@extends('layouts.base')
@section('title', 'Itinerary Request') 
@section('content')
<div class="row">
    <div class="col-md-12" style="margin: -20px 0 20px 0 " >
        <div class="form-group" style="margin: 0 -5px 0 -5px;">
                <div class="col-md-1 float-left"><a href="/inputs" ><button type="button" style="width: 100%;" class="btn btn-dark" >Back</button></a></div>  
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-primary float-right" disabled>Restart</button></div>                   
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-warning float-right" disabled>Reply</button></div>     
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-info float-right" disabled>Clarify</button></div>                    
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-secondary float-right " data-toggle="modal" data-target="#withdrawModal" >Withdraw</button></div>        
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-danger float-right" disabled>Reject</button></div>      
                <div class="col-md-1 float-right"><button type="button" style="width: 100%;" class="btn btn-success float-right" data-toggle="modal" data-target="#approveModal" >Approve</button></div>   
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
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                <th class="col-4 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Action</th>
                            </tr>

                        </thead>
                        <tbody id="itineraryDetailsTbody">
                            @foreach ($postDetails as $detail )
                                <tr class="d-flex" style="font-size: 13px;">
                                    <td class="d-none">{{ $detail->client_id }}</td>
                                    <td class="col-md-3">{{ $detail->client_name }}</td>
                                    @php                
                                         $date = date_create($detail->time_start);
                                         $date = date_format($date,"n/d/Y");             
                                    @endphp
                                    <td class="col-md-1">{{ $date }}</td>
                                    @php                
                                        $date = date_create($detail->time_end);
                                        $date = date_format($date,"n/d/Y");
                                    @endphp
                                    <td class="col-md-1">{{ $date }}</td>
                                    @php
                                        $date = date_create($detail->actual_start);
                                        $date = date_format($date,"n/d/Y");
                                    @endphp
                                    <td class="col-md-1">{{ $date }}</td>
                                    @php
                                        $date = date_create($detail->actual_end);
                                        $date = date_format($date,"n/d/Y");
                                    @endphp
                                    <td class="col-md-1">{{ $date }}</td>
                                    <td class="col-md-4">{{ $detail->purpose }}</td>
                                    <td class="d-none">{{ $detail->id }}</td>
                                    <td class="col-1 text-center px-0">
                                        <button class="btn btn-success editTriggerBtn" data-toggle="modal" id="btnID{{ $detail->id }}" data-target="#exampleModal" ><i class="fas fa-edit"></i></button>
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

{{-- Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Itinerary Details</h5>
                <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3 mb-2 bg-success text-white d-none" id="successDiv">Updated Successfully</div>   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Client Name</label>
                                <input type="text" class="form-control" id="clientName" readonly placeholder="">
                            </div>                            
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Authorized Time Start</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" disabled id="authTimeStart" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                
                                
                                
                            </div> 
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Authorized Time End</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" disabled id="authTimeEnd" class="form-control datetimepicker-input" data-target="#reservationdate"/>
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
                                <label for="exampleInputEmail1">Actual Time Start</label>
                                <div class="input-group date" id="datetimepicker71" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="actualTimeStart" data-target="#datetimepicker71"/>
                                    <div class="input-group-append" data-target="#datetimepicker71"  data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                <span class="text-danger" id="actualTimeStartErr"></span>                                                  
                            </div> 
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Time End</label>
                                <div class="input-group date" id="datetimepicker81" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="actualTimeEnd" data-target="#datetimepicker81"/>
                                    <div class="input-group-append" data-target="#datetimepicker81" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                <span class="text-danger" id="actualTimeEndErr"></span>                                                  

                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Purpose</label>
                        <textarea class="form-control" id="purpose" disabled rows="3" id="message-text"></textarea>
                    </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save">Save Changes</button>
                <input type="hidden" name="btnID" id="btnID">
            </div>
        </div>
    </div>
</div>

























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
                                    <input type="hidden" name="main_id" " value="{{ $post->id }}">
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
        <form action="{{ route('approved.itinerary.init') }}" method="POST">
            @csrf
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">                     
                        <label for="approvedRemarks">Remarks</label>
                        <div class="card-body">
                            <div class="form-floating">
                                <input type="hidden" name="main_id" value="{{ $post->id }}">
                                <input type="hidden" value="@yield('title')" name="frmName">
                                <input type="hidden" name="jsonItineraryData" id="jsonItineraryData">
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



{{-- Swal --}}
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
            window.location.href = "/inputs";
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


{{-- Submit-all --}}
<script>
    $('#approveModal').on('click',function(){
        var itineraryData = [];

        $("#itineraryDetailsTable > #itineraryDetailsTbody > tr").each(function () {
        var clientID = $(this).find('td').eq(0).text();
        var clientName = $(this).find('td').eq(1).text();
        var authTimeStart = $(this).find('td').eq(2).text();
        var authTimeEnd = $(this).find('td').eq(3).text();
        var actualTimeStart = $(this).find('td').eq(4).text();
        var actualTimeEnd = $(this).find('td').eq(5).text();
        var purpose = $(this).find('td').eq(6).text();
        var id = $(this).find('td').eq(7).text();
    
        var listTD = [];
        listTD.push(clientID,clientName,authTimeStart,authTimeEnd,actualTimeStart,actualTimeEnd,purpose,id);
        itineraryData.push(listTD);
    
        });

        console.log(itineraryData);

            var jsonItineraryData = JSON.stringify(itineraryData);

            if (jsonItineraryData.length == 2) {
                $( "#jsonItineraryData" ).val('');
            } else {
                $( "#jsonItineraryData" ).val(jsonItineraryData);
            }
        
            console.log('this is data of jsonot',$('#jsonItineraryData').val());
    })
</script>
{{-- submit all end --}}


















<script type="text/javascript">
    $(function () {
   
        $('#datetimepicker71').datetimepicker({
            format: 'L'
        });
        $('#datetimepicker81').datetimepicker({
            useCurrent: false,
            format: 'L'
        });
        $("#datetimepicker71").on("change.datetimepicker", function (e) {
            $('#datetimepicker81').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker81").on("change.datetimepicker", function (e) {
            $('#datetimepicker71').datetimepicker('maxDate', e.date);
        });

    });
</script>
{{-- Edit Button --}}
<script>
    $('.editTriggerBtn').on('click',function(){
    
    // alert('test');
        var clientID = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().text();
        var clientName = $(this).parent().prev().prev().prev().prev().prev().prev().prev().text();
        var authTimeStart = $(this).parent().prev().prev().prev().prev().prev().prev().text();
        var authTimeEnd = $(this).parent().prev().prev().prev().prev().prev().text();
        var actualTimeStart = $(this).parent().prev().prev().prev().prev().text();
        var actualTimeEnd = $(this).parent().prev().prev().prev().text();
        var purpose = $(this).parent().prev().prev().text();
        var btnID = $(this).parent().prev().text();


        const myvar = [];
            myvar.push(
                clientID,
                clientName,
                authTimeStart,
                authTimeEnd,
                actualTimeStart,
                actualTimeEnd,
                purpose,
                btnID
            ); 
        console.log(myvar)


        $('#clientID').val(clientID);
        $('#clientName').val(clientName);
        $('#authTimeStart').val(authTimeStart);
        $('#authTimeEnd').val(authTimeEnd);
        $('#actualTimeStart').val(actualTimeStart);
        $('#actualTimeEnd').val(actualTimeEnd);
        $('#purpose').val(purpose);
        $('#btnID').val(btnID);


    })
</script>
{{-- Edit button end --}}


{{-- Update / Save Actual Time --}}
<script>
    $('.save').on('click',function(e){
        e.preventDefault();

        var btnID = $('#btnID').val();

        var actualTimeStart = $('#actualTimeStart').val();
        var actualTimeEnd = $('#actualTimeEnd').val();

        var actualTimeStartChecker  = false;
        var actualTimeEndChecker  = false;

        if(actualTimeStart){
            actualTimeStartChecker = true;
            $('#actualTimeStartErr').text('');

        }else{
            $('#actualTimeStartErr').text('Actual Time Start is required!');
            $('#successDiv').addClass('d-none');
        }

        if(actualTimeEnd){
            actualTimeEndChecker = true;
            $('#actualTimeEndErr').text('');

        }else{
            $('#actualTimeEndErr').text('Actual Time End is required!');
            $('#successDiv').addClass('d-none');
        }

        

        if(actualTimeStart && actualTimeEnd){
        $('#btnID'+btnID).parent().prev().prev().prev().text(actualTimeEnd);
        $('#btnID'+btnID).parent().prev().prev().prev().prev().text(actualTimeStart);
        $('#successDiv').removeClass('d-none');
        $('#actualTimeStart').val('');
        $('#actualTimeEnd').val('');
        }

    })
</script>
   

{{-- close button --}}
<script>
    $('.closeBtn').on('click',function(){
        // alert('test');
    $('#successDiv').addClass('d-none');
    $('#actualTimeEndErr').text('');
    $('#actualTimeStartErr').text('');

    })
</script>
{{-- close buttin end --}}

@endsection
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
