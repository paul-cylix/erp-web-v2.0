@extends('layouts.base')
@section('title', 'Requests For Approval') 
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
                            <div class="card card-default"> 
                                <div class="card-body table-responsive p-0">
                                    {{-- <input style="background-position: 10px 12px; background-repeat: no-repeat; width: 100%; font-size: 16px; padding: 12px 20px 12px 25px; border: 1px solid #ddd; margin-bottom: 12px;" type="text" id="myInput" onkeyup="myFunction()" placeholder="Search..."> --}}
                                    <table id="myTable" class="table table-hover text-nowrap">
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
                                                    <td>{{$post->RequestType}}</td>
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

                                {{-- <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script>

<script>
    function myFunction() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
    
      // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>