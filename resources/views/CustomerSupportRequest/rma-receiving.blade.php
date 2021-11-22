@extends('layouts.base')
@section('title', 'Update RMA') 
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
        window.location.href = "/list-rma-receiving";
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

<form action="{{ route('update.rma.rcvd') }}" enctype="multipart/form-data" method="POST">
  @csrf
{{-- Submit Button --}}
<div class="row" style="margin-top: -20px;"> 
  <div class="col-md-1">
      <div class="form-group">
          <input style="width:100%;"  type="submit" class="btn btn-primary" id="submit-all" value="Submit"/>                                
      </div>
  </div>

  <div class="col-md-1">
      <div class="form-group">
          <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
      </div>
  </div> 
</div>
{{-- End Submit Button --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                
                    <div class="card-body">    
                        <div class="p-3 mb-2 bg-danger text-white d-none" id="myError"></div>

                      <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectID">Project Name</label>
                                    <select id="projectID" name="projectID" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;" onchange="showDetails(this.value)">
                                      @foreach ($projects as $prj )
                                      <option value="{{$prj->project_id}}"  @if ( $prj->option == 'True' ) selected  @endif>{{$prj->project_name}}</option>
                                      @endforeach
                                    </select>
                                    <span class="text-danger">@error('projectName'){{ $message }}@enderror</span>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ $rma_details->id }}">
                            <input type="hidden" id="projectName" name="projectName" value="{{ $rma_details->PROJECT }}">
                            <input type="hidden" id="clientID" name="clientID" value="{{ $rma_details->CLIENTID }}">
                            <input type="hidden" id="mainID" name="mainID">


                              <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clientName">Client Name</label>
                                    <input type="text" class="form-control" id="clientName" value="{{ $rma_details->CLIENT }}" name="clientName" placeholder="" readonly>
                                </div>                            
                            </div>
                      </div>

                    <div class="row">
                        

 

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="brand">Brand</label>
                              <input type="text" class="form-control" id="brand" value="{{ $rma_details->BRAND }}" name="brand" placeholder="" >
                              <span class="text-danger">@error('brand'){{ $message }}@enderror</span>
                          </div>                            
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="model">Model</label>
                              <input type="text" class="form-control" id="model" value="{{ $rma_details->MODEL }}" name="model" placeholder="" >
                              <span class="text-danger">@error('model'){{ $message }}@enderror</span>

                          </div>                            
                      </div>

                    </div>

                    <div class="row">


                    @php
                            $rma_receiving_details_TS = $rma_details->TS; 
                            $rma_receiving_details_TS = date('m/d/Y h:i A', strtotime($rma_receiving_details_TS));

                            $rma_receiving_TS = $rma_details->mainTs; 
                            $rma_receiving_TS = date('m/d/Y h:i A', strtotime($rma_receiving_TS));
                    @endphp
                    

                      
             

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">Date and Time created</label>
                            <div class="input-group date" id="datetimepicker25" data-target-input="nearest">
                                <input type="text"  class="form-control datetimepicker-input" id="dateTimeCreated" value="{{ $rma_receiving_TS }}" disabled data-target="#datetimepicker25"/>
                                <div class="input-group-append" data-target="#datetimepicker25" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            
                        {{-- <span class="text-danger" id="dateTimeCreatedErr"></span>    --}}
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('#datetimepicker25').datetimepicker(
                              {
                      
                                ignoreReadonly: true,
                                useCurrent: true,
                                icons: {
                                time:"fas fa-clock"
                                }
                              }
                            );
                        });
                    </script>

                    


                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Date Received</label>
                            <div class="input-group date" id="datetimepicker26" data-target-input="nearest">
                                <input type="text"  class="form-control datetimepicker-input" value="{{ $rma_receiving_details_TS }}" name="dateReceived" id="dateReceived" data-target="#datetimepicker26"/>
                                <div class="input-group-append" data-target="#datetimepicker26" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        <span class="text-danger">@error('dateReceived'){{ $message }}@enderror</span>
                        <span class="text-danger" id="dateReceivedErr"></span>   
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $('#datetimepicker26').datetimepicker(
                              {
                                useCurrent: true,
                                icons: {
                                time:"fas fa-clock"
                                }
                              }
                            );
                        });
                    </script>
        

                    
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="location">Location</label>
                            @if ($rma_details->isLocation)
                            <input type="text" class="form-control" id="location" value="{{ $rma_details->LOCATION }}" name="location" placeholder="" >
                            
                            @else
                            <input type="text" class="form-control" id="location" name="location" placeholder="" >
                            @endif
                            <span class="text-danger">@error('location'){{ $message }}@enderror</span>

                        </div>                            
                    </div>


                  </div>

                    

                    <div class="row">
               

                      <div class="col-md-2">
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="number" class="form-control" value="{{ $rma_details->QTY }}" step=".01" id="qty" name="qty" placeholder="" >

                            {{-- <input id="qty" class="form-control" value="{{ $rma_details->QTY }}" onkeypress="return isNumberKey(event)" type="text" name="qty"> --}}

                            <span class="text-danger">@error('qty'){{ $message }}@enderror</span>
                            
                        </div>                            
                    </div>
        
                    <div class="col-md-2">
                      <div class="form-group">
                          <label for="uom">UoM</label>
                          <select class="form-control select2 select2-default"  id="uom" name="uom" data-dropdown-css-class="select2-default">
                            @foreach ($uom as $data )
                              <option value="{{ $data->id }}"  @if ( $data->option == 'True' ) selected  @endif>{{ $data->UoM }}</option>
                            @endforeach
                          </select>       
                          <span class="text-danger">@error('uom'){{ $message }}@enderror</span>

                      </div>         
                    </div>  
        
                    <div class="col-md-2">
                      <div class="form-group">
                          <label for="status">Status</label>
                          <select class="form-control select2 select2-default"  id="status" name="status" data-dropdown-css-class="select2-default">
                      
                            @if ($rma_details->STATUS === 'Active')
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        
                            @else
                            <option value="Active" >Active</option>
                            <option value="Inactive"selected>Inactive</option>
                            @endif
                          </select>   
                          
                          <span class="text-danger">@error('status'){{ $message }}@enderror</span>
                          
                      </div>         
                    </div>


                    <div class="col-md-3">
                      <div class="form-group">
                          <label for="rmaStatus">RMA Status</label>
                          <select class="form-control select2 select2-default"  id="rmaStatus" name="rmaStatus" data-dropdown-css-class="select2-default">
                            @foreach ($rmaStatus as $status )
                              <option value="{{ $status->id }}"  @if ( $data->option == 'True' ) selected  @endif >{{ $status->rma_status }}</option>
                            @endforeach
                          </select>       
                          <span class="text-danger">@error('rmaStatus'){{ $message }}@enderror</span>

                      </div>         
                    </div>  

                    <div class="col-md-3">
                      <div class="form-group">
                          <label for="serialNumber">Serial Number</label>
                          <input type="text" class="form-control" id="serialNumber" value="{{ $rma_details->SERIALNUMBER }}" name="serialNumber" placeholder="" >
                          <span class="text-danger">@error('serialNumber'){{ $message }}@enderror</span>

                      </div>                            
                  </div>
 


                  </div>
                 

                        <input type="hidden" value="{{ $rma_details->GUID }}" name="guid" id="guid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">                                            
                                    <label for="issue">Issue</label> 
                                    <textarea style="resize:none" class="form-control" id="issue" name="issue" rows="4" placeholder="">{{ $rma_details->ISSUE }}</textarea>
                                    <span class="text-danger">@error('issue'){{ $message }}@enderror</span>

                                </div>
                            </div>
                        </div>


                        {{-- Attachments --}}
                      <label class="btn btn-primary" style="font-weight:normal;">
                          Upload Image <input type="file" name="image" accept="image/png, image/jpg, image/jpeg" class="form-control-file" id="customFile"  hidden>
                      </label>

                      {{-- Sample<input type="file" name="file[]" id="" multiple> --}}

                    
                      

                      {{-- Attachments of no edit --}}
                      <div class="row">
                          <div class="col-md-12">
                              <div class="card card-gray">
          
                                  <div class="card-header" style="height:50px;">
                                      <div class="row ">
                                          <div  style="padding: 0 3px; 10px 3px; font-size:18px;"><h3 class="card-title">Image</h3></div>
                                      </div>
                                  </div>
                                  {{-- Card body --}}
                                  <div class="card-body" >


                                      {{-- Table attachments --}}
                                      <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;"  >
                                          <table id= "attachmentsTable"class="table table-hover" >
                                              <thead >
                                              <tr>
                                                  <th>Name</th>
                                                  <th>Type</th>
                                                  <th>Size</th>
                                    
                                                  <th>Actions</th>

                                              </tr>
                                              </thead>
                                              <tbody >
                                              @if (count($image) > 0)
                                                <input type="hidden" name="filepath" value="{{ $image[0]->filepath }}">

                                                @foreach ( $image as $img )          
                                                    <tr>
                                                        <td>{{ $img->complete_filename }}</td>
                                                        <td>{{ $img->extension }}</td>
                                                        <td>{{ $img->filesize }}</td>
                                                        <td><a class="btn btn-secondary" href="{{ asset("$img->filepath") }}" target="_blank" >View</a></td>
                                                    </tr>
                                                @endforeach
                                              @endif
                                 
                                         
                                              </tbody>
                                          </table>
                                      </div>
                                      {{-- Table attachments End--}}

          
                                      
                                  </div>
                                  {{-- Card body END --}}
          
                              </div>
                          </div>
                      </div>
                      {{-- End Attachments --}}


                    </div>   
                </form>
            </div>
        </div>
    </div>

<script>
      function showDetails(id) {
        if (window.XMLHttpRequest) {
            let projectName = $("#projectID option:selected").text();
            $('#projectName').val(projectName);
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var txt = xmlhttp.responseText.replace("[", "");
                txt = txt.replace("]", ""); 
                var res = JSON.parse(txt);
                document.getElementById("clientName").value = res.clientName;
                document.getElementById("clientID").value = res.clientID;
                document.getElementById("mainID").value = res.mainID;
            }
        }
        xmlhttp.open("GET","/get-client/"+id,true);
        xmlhttp.send();
    }

</script>

<script>
  var main = [];
  var semi = [];

      $(document).ready(function() {
      $('input[type="file"]').on("change", function() {
          let files = this.files;
          // console.log(files);
          // console.dir(this.files[0]);
          $('#attachmentsTable tbody tr').remove();
              
          for(var i = 0; i<files.length; i++){
              var tmppath = URL.createObjectURL(files[i]);
             
              semi.push(files[i]['name'], files[i]['type'], files[i]['size'], tmppath);
              //main.push(semi);
              //console.log(main);

              $('#attachmentsTable tbody').append('<tr>'+
                              '<td>'+files[i]['name']+'</td>'+
                              '<td>'+files[i]['type']+'</td>'+
                              '<td>'+files[i]['size']+'</td>'+
                      
                              "<td><a href='"+tmppath+"' target='_blank' class='btn btn-secondary'>View</a></td>"+
                              // "<td><button  class='btn btn-danger'>Remove</></td>"+
                              '</tr>'
              );
                          //add code to copy to public folder in erp-web
          }
      });
  });

</script>

<script>
    $('#submit-all').on('click',function(e){

        if ($.trim($("#brand").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert Brand.');
        return false;
        }

        if ($.trim($("#model").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert Model.');
        return false;
        }

        if ($.trim($("#dateReceived").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert Date Received.');
        return false;
        }
        
        if ($.trim($("#location").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert Location.');
        return false;
        }

        if ($.trim($("#serialNumber").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert a Serial Number.');
        return false;
        }

        if ($.trim($("#issue").val()) === "") {
        $('#myError').removeClass('d-none');
        $('#myError').text('Please Insert Issue.');
        return false;
        }


    })
  </script>












@endsection

{{-- Dropzone start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
{{-- Sweet ALert --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>
