@extends('layouts.base')
@section('title', 'RMA Receiving List') 
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
        window.location.href = "list-rma-receiving";
        }});
</Script>
@endif


<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title">@yield('title')</h3>
            </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- Start --}}




                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                  <tr>
                                     
                                      <th>Project Name</th>
                                      <th>Client Name</th>
                                      <th>Item Code</th>
                                      <th>Brand</th>
                                      <th>Model</th>
                                      <th>Serial Number</th>
                                      <th>Date Received</th>
                                      <th>Qty</th>
                                      <th>UoM</th>
                                      <th>Status</th>
                                      <th>Project Manager</th>
                                      <th>Action</th>
                                                                        
                                  </tr>
                              </thead>
                              <tbody>

                                  @foreach ($rma_receiving_details as $post)
                                  <tr>
                                      
                                  <td>{{ $post->PROJECT }}</td>
                                  <td>{{ $post->CLIENT }}</td>
                                  <td>{{ $post->ISSUE }}</td>
                                  <td>{{ $post->BRAND }}</td>
                                  <td>{{ $post->MODEL }}</td>
                                  <td>{{ $post->SERIALNUMBER }}</td>
                                  <td>{{ $post->DATERECEIVED }}</td>
                                  <td>{{ $post->QTY }}</td>
                                  <td>{{ $post->UOM }}</td>
                                  <td>{{ $post->STATUS }}</td>
                                  <td>{{ $post->RMName }}</td>
                                 
                                  <td class="text-center">
                                    <a href="/edit-rma-receiving/{{ $post->id }}" class="btn btn-info btn-sm "><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-success btn-sm qrButton" id="{{ $post->GUID }}"  data-toggle="modal" data-target="#exampleModal1">
                                      <i class="fas fa-qrcode"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm modalButton" id="{{ $post->id }}" data-toggle="modal" data-target="#exampleModal">
                                      <i class="fas fa-trash-alt"></i>
                                    </button>
                                  </td>
                                
                                  </tr>
                              @endforeach
                                  
                              </tbody>
                              <tfoot>
                                  <tr>
                                    <th>Project Name</th>
                                    <th>Client Name</th>
                                    <th>Item Code</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Serial Number</th>
                                    <th>Date Received</th>
                                    <th>Qty</th>
                                    <th>UoM</th>
                                    <th>Status</th>
                                    <th>Project Manager</th>    
                                    <th>Action</th>

                                  </tr>
                              </tfoot>
                          </table>
                                                         

                          {{-- End --}}
                        </div>                                    
                    </div>
                </div>                            
        </div>
    </div>
</div>
















<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete the item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('del.rma.rcvd') }}" method="POST">
          @csrf
            Are you sure you want to delete this item?
          <input type="hidden" name="rcvdID" id="rcvdID">

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </form>

      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModal1Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModal1Label">QR Code</h5>
        <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body d-flex justify-content-center" id="myModal">
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary " onclick="printDiv()">Print</button>
        <button type="button" class="btn btn-secondary closeModal"  data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>









<script type="text/javascript">
$('.qrButton').on('click',function(e){
  $('#myModal').append(`<div id="qrcode"></div>`);
  const qrcodeval = e.currentTarget.id;
  var qrcode = new QRCode(document.getElementById("qrcode"), {
	text: 'http://10.0.9.46/rma/'+qrcodeval,
	width: 96,
	height: 96,
	// colorDark : "#5868bf",
	// colorLight : "#ffffff",
	correctLevel : QRCode.CorrectLevel.H
});
});
</script>



  <!-- Script to print the content of a div -->
  <script>
    function printDiv() {        
        var divContents = document.getElementById("qrcode").innerHTML;
        var a = window.open('', '', "width="+screen.availWidth+",height="+screen.availHeight);
        a.document.write('<html>');
        a.document.write('<body>');
        a.document.write('<div style="margin-left: 44%;">');
        a.document.write(divContents);
        a.document.write('</div>');
        a.document.write('</body></html>');
        a.document.close();
        a.print();
    }
</script>











<script>
  $('.closeModal').on('click',function(e){
  $('#qrcode').remove();
});
</script>




<script>
  $('.modalButton').on('click',function(e){
    const myrcvdID = e.currentTarget.id;
    $("#rcvdID").val(myrcvdID);
    const mytest= $("#rcvdID").val();

    console.log(mytest);
  });
  </script>



@endsection

{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
