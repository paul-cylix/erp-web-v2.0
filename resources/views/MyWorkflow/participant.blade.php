@extends('layouts.base')
@section('title', 'Request Participated') 
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title">@yield('title')</h3>
            </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">


 



                           

   

                                        <table id="example" class="table table-striped table-bordered" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Request Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Client Name</th>
                                                    <th>Project Name</th>
                                                    <th>Payee</th>
                                                    <th>Initiator</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>                                               
                                                </tr>
                                            </thead>
                                            <tbody>


                                            @foreach ($posts as $post)
                                            <tr>
                                                <td><a href="/participants/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}">{{$post->REFERENCE}}</a></td>
                                                <td>{{$post->RequestType}}</td>
                                                <td>{{$post->Date}}</td>
                                                <td>{{$post->Client}}</td>
                                                <td>{{$post->Project}}</td>
                                                <td>{{$post->Payee}}</td>
                                                <td>{{$post->Initiator}}</td>
                                                <td class="text-right">{{ number_format($post->Amount,2)}} </td>
                                                <td>
                                                    <a href="/participants/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}" class="btn btn-info btn-sm"><i class="fas fa-book-open" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-sm" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus('{{$post->FRM_CLASS}}',{{ $post->ID }})"><i class="fas fa-clipboard-list" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-warning btn-sm " data-target="#viewMessagesModal" data-toggle="modal" onclick="viewClaComments('{{ $post->RequestType }}',{{ $post->ID }})"><i class="fas fa-comments" aria-hidden="true" style="color: white !important;"></i></a>

                                                </td>
                                            </tr>
                                            @endforeach
                                   
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Request Type</th>
                                                    <th>Date Requested</th>
                                                    <th>Client Name</th>
                                                    <th>Project Name</th>
                                                    <th>Payee</th>
                                                    <th>Initiator</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>  
                                                </tr>
                                            </tfoot>
                                        </table>





                                    <!-- Modal Messages-->
                                     <div class="modal fade" id="viewMessagesModal" tabindex="-1" role="dialog"  aria-labelledby="viewMessagesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog modal-xl" role="document" >
                                        <div class="modal-content" >
                                            <div class="modal-body" id="viewMessagesModal_detail" >

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="container">
                                                            <H6 id="messagesLabelForm" ></H6>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                </div>   

                                                <div class="row">
                                                    <div class="col-md-12" id="messagecontainer">
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="deleteComments()">Close</button>                                    
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    {{-- end messages --}}

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


                        </div>                                    
                    </div>
                </div>                            
        </div>
    </div>
</div>
        
@endsection

