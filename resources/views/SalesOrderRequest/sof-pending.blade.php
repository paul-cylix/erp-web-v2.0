@extends('layouts.base')
@section('title', 'Sales Order - Accounting Approval') 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                {{-- <a href="{{ route('datatable.get') }}">get data</a> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

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
                                                            
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>PO Number</th>
                                                    <th>SOF Number</th>
                                                    <th>Customer Name</th>
                                                    <th>Project Name</th>
                                                    <th>Project Cost</th>
                                                    <th>Outstanding Balance</th>
                                                    <th>Status</th>
                                                    <th>Date Needed</th>
                                                    <th>Initiator</th>
                                                    <th>Action</th>                                                   
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($pendings as $data )
                                                    <tr>
                                                        <td>{{ $data->PO }}</td>
                                                        <td>{{ $data->REF }}</td>
                                                        <td>{{ $data->Client }}</td>
                                                        <td>{{ $data->Project }}</td>
                                                        <td>{{ $data->ProjectCost }}</td>
                                                        <td>{{ $data->Outstanding }}</td>
                                                        <td>{{ $data->Status }}</td>
                                                        <td>{{ $data->Deadline }}</td>
                                                        <td>{{ $data->Initiator }}</td>
                                                        <td>
                                                            {{-- <button class="btn btn-success btn-sm btn-block">Approve</button> --}}
                                                            <a href="/pending-approved/{{ $data->SOID }}" class="btn btn-success btn-sm btn-block">Approve</a>
                                                            {{-- <button class="btn btn-danger btn-sm btn-block">Removed</button> --}}

                                                        </td>

                                                    </tr>
                                                @endforeach
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>PO Number</th>
                                                    <th>SOF Number</th>
                                                    <th>Customer Name</th>
                                                    <th>Project Name</th>
                                                    <th>Project Cost</th>
                                                    <th>Outstanding Balance</th>
                                                    <th>Status</th>
                                                    <th>Date Needed</th>
                                                    <th>Initiator</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                
                            </div>                                    
                        </div>
                    </div>                            
            </div>
        </div>
    </div>







{{-- Datatables
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#example').DataTable();
</script> --}}


<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>

@endsection

{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>



