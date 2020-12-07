@extends('layouts.base')
@section('title', 'Sales Order - Accounting Approval') 
@section('content')

<div class="content-wrapper">

    <br>

    <section class="content">
        <div class="container-fluid">
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
        </div>
    </section>
</div> 

@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>