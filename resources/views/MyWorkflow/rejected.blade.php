@extends('layouts.base')
@section('title', 'Rejected Requests') 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                <form action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-default"> 
                                    <div class="card-body table-responsive p-0"> 
                                        <table class="table table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Request Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Project Name</th>
                                                    <th>Initiator</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($posts as $post)
                                                <tr>
                                                    <td><a href="/rejected/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}">{{$post->REFERENCE}}</a></td>
                                                    <td>{{$post->RequestType}}</td>
                                                    <td>{{$post->Date}}</td>
                                                    <td>{{mb_strimwidth($post->Project, 0, 50, "...")}}</td>
                                                    <td>{{$post->Initiator}}</td>
                                                    <td class="text-right">{{ number_format($post->Amount,2)}} </td>
                                                    <td>                       
                                                        {{-- <a href="/rejected/{{ $post->ID }}" class="btn btn-info">Open</a> --}}
                                                        <a href="/rejected/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}" class="btn btn-info">Open</a>                                                   
                                                        {{-- <a href="#" class="btn btn-secondary">View Status</a> --}}
                                                        {{-- <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus({{ $post->ID }})">View Status</a> --}}
                                                        <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus('{{$post->FRM_CLASS}}',{{ $post->ID }})">View Status</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="viewStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body" id="employee_detail">
                    
                                                        <table class="table table-striped table-responsive-xl" id="myTableId">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                <th scope="col">Approver</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Approved By</th>
                                                                <th scope="col">Approved Date</th>
                                                                <th scope="col">Comments</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tdata">
                                                            </tbody>
                                                            </table>
                                                
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" onclick="toBeDelete()" data-dismiss="modal">Close</button>                                    
                                            </div>
                                        </div>
                                        </div>
                                    </div>



                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <div>{{ $posts->links() }}</div>
                                            {{-- <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li> --}}
                                        </ul>
                                        </div>
                                </div>
                            </div>                                    
                        </div>
                    </div>                            
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>