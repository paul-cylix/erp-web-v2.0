@extends('layouts.base')
@section('title', 'Requests For Approval') 
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
                                                        <td><a href="#">{{$post->REFERENCE}}</a></td>
                                                        <td>{{$post->WebpageLink}}</td>
                                                        <td>{{$post->Date}}</td>
                                                        <td>{{$post->Project}}</td>
                                                        <td>{{$post->Initiator}}</td>
                                                        <td>{{$post->Amount}}</td>
                                                        <td>
                                                            <a href="#" class="btn btn-info">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
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