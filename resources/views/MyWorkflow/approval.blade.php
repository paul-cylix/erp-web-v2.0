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
                                                <tr id ="sid{{ $post->ID }}">
                                                    <td><a href="/approvals/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}">{{$post->REFERENCE}}</a></td>
                                                    <td>{{$post->RequestType}}</td>
                                                    <td>{{$post->Date}}</td>
                                                    <td>{{$post->Project}}</td>
                                                    <td>{{$post->Initiator}}</td>
                                                    <td class="text-right">{{ number_format($post->Amount,2)}} </td>
                                                    <td>
                                                        {{-- <a href="/approvals/{{ $post->ID }}" class="btn btn-info">Open</a> --}}
                                                        <a href="/approvals/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}" class="btn btn-info">Open</a>

                                                        <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus('{{$post->FRM_CLASS}}',{{ $post->ID }})">View Status</a>

                                                        {{-- <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus({{ $post->ID }})">View Status</a> --}}
                                                        {{-- <a href="javascript:void(0)" class="btn btn-warning" data-target="#viewMessagesModal" data-toggle="modal" onclick="viewClaComments({{ $post->ID }})">Comments</a> --}}

                                                        <a href="javascript:void(0)" class="btn btn-warning" data-target="#viewMessagesModal" data-toggle="modal" onclick="viewClaComments('{{$post->RequestType}}',{{ $post->ID }})">Comments</a>

                                                    </td>
                                                </tr>                                              
                                            @endforeach                                         
                                        </tbody>
                                    </table>
                                </div>

                                    <!-- Modal Messages-->
                                    <div class="modal fade" id="viewMessagesModal" tabindex="-1" role="dialog"  aria-labelledby="viewMessagesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-dialog modal-xl" role="document" >
                                        <div class="modal-content" >
                                            <div class="modal-body" id="viewMessagesModal_detail" >

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="container">
                                                            <H6 id="messagesLabelForm"></H6>
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
                                    {{-- End Modal --}}

                                {{-- Pagination --}}    
                                <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <div>{{ $posts->links() }}</div>
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- <script>
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
</script> --}}

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

{{-- <script>
    function viewClaComments(id){
  
    $.get('/clarifications-comments/'+id,function(comments){
        
      var asd = document.getElementById('messagecontainer');
      
      for (var i = 0; i<comments.length; i++){
      var claMessage= comments[i]['MESSAGE'];
      var claSender= comments[i]['UserFullName'];
      var claRecipient= comments[i]['SENDERNAME'];
      var claTs= new Date(comments[i]['TS']);
      claTs = claTs.toString().slice(0, 24);
      var claParentID= comments[i]['ParentID'];
      var claUserLevel= comments[i]['USERLEVEL'];
      

      if(claParentID == 0){

          $('#messagecontainer').append('<div class="container" style="margin-bottom: 20px;">'+
                                          '<div class="row">'+
                                          
                                              '<div class="col text-center">'+  
                                              '<img src="http://dummyimage.com/60" alt="" style="box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">'+
                                              '</div>'+
                                              
                                              '<div class="col-11" >'+
                                              '<div class="container">'+

                                                  '<div class="row">'+

                                                      '<div class="col main-content" style="background-color: #dee1e3;  border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); padding:10px 15px 10px 15px; ">'+
                                                          
                                                          '<div class="row">'+

                                                              '<div class="sender-name col" style="font-size: 14px; font-weight:bold;">'+claSender+
                                                              
                                                              '</div>'+
                                                              '<div class="col text-right"style="font-size: 14px;">'+claTs+
                                                              
                                                              '</div>'+
                                                              
                                                          '</div>'+

                                                          '<div class="row">'+
                                                              '<div class="recipeint-name col"style="font-size: 14px;" >To: '+claRecipient+
                                                                  
                                                              '</div>'+
                                                              '<div class="col text-right"style="font-size: 14px;">'+claUserLevel+
                                                                  
                                                              '</div>'+

                                                          '</div>'+
                                                          '<div class="row" >'+
                                                              '<div class="comment-content col" style="margin-top: 10px;">'+claMessage+
                                                                  
                                                              '</div>'+
                                                          '</div>'+

                                                          
                                                      '</div>'+
                                                  '</div>'+

                                          
                                              '</div>'+
                                              '</div>'+

                                          '</div>'+
                                      '</div>'
           );

      }else{

          $('#messagecontainer').append('<div class="container"  style="margin-bottom: 20px;">'+
                                          '<div class="row">'+
                                          
                                              '<div class="col text-right" style="padding-right:16px;">'+
                                              '<img src="http://dummyimage.com/60" alt="" style="box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">'+
                                              '</div>'+
                                              
                                              '<div class="col-10" >'+
                                              '<div class="container">'+

                                                  '<div class="row">'+
                                                      '<div class="col main-content" style="background-color: #dee1e3;  border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23); padding:10px 15px 10px 15px; ">'+
                                                          '<div class="row">'+

                                                              '<div class="sender-name col" style="font-size: 14px; font-weight:bold;">'+claSender+
                                                              
                                                              '</div>'+
                                                              '<div class="col text-right"style="font-size: 14px;">'+claTs+
                                                  
                                                              '</div>'+
                                                              
                                                          '</div>'+
                                                          '<div class="row">'+
                                                              '<div class="recipeint-name col"style="font-size: 14px;" >To: '+claRecipient+
                                                                  
                                                              '</div>'+
                                                              '<div class="col text-right"style="font-size: 14px;">'+claUserLevel+
                                                                  
                                                              '</div>'+

                                                          '</div>'+
                                                          '<div class="row" >'+
                                                              '<div class="comment-content col" style="margin-top: 10px;">'+claMessage+
                                                                  
                                                              '</div>'+
                                                          '</div>'+
                                                          
                                                      '</div>'+
                                                  '</div>'+

                              
                                              '</div>'+
                                              '</div>'+

                                          '</div>'+
                                      '</div>'
           );

     
      }   
  }
})
}


function deleteComments(){
  // console.log('test');
  $('#messagecontainer').empty();
}

</script> --}}

{{-- view status --}}
{{-- <script>

    function viewStatus(id){
        // alert(id);
        $.get('/approval-status/'+id,function(status){

          
           
        // var countKey = Object.keys(status).length;

        // console.log(countKey);

        for (var i = 0; i<status.length; i++){
            var uidChecker= status[i]['Approved_By'];
            var signDateChecker= status[i]['SIGNDATETIME'];
            var approveChecker= status[i]['ApprovedRemarks'];

            // Convert UID Null to empty string
            if(uidChecker == 'NULL'){
                uidChecker = "";
            }else if(uidChecker == ''){
                uidChecker = "";
            }else if(uidChecker == null){
                uidChecker = "";
            }else{
                uidChecker
            }

            // Convert UID Null to empty string
            if(signDateChecker == 'NULL'){
                signDateChecker = "";
            }else if(signDateChecker == ''){
                signDateChecker = "";
            }else if(signDateChecker == null){
                signDateChecker = "";
            }else{
                signDateChecker
            }

            // Convert UID Null to empty string
            if(approveChecker == 'NULL'){
                approveChecker = "";
            }else if(approveChecker == ''){
                approveChecker = "";
            }else if(approveChecker == null){
                approveChecker = "";
            }else{
                approveChecker
            }

        $('#tdata').append("<tr id ='toBeDeleted'>"+
            "<td>"+status[i]['USER_GRP_IND']+"</td>"+ 
            "<td>"+status[i]['STATUS']+"</td>"+   

            // "<td>"+status[i]['UID_SIGN']+"</td>"+   
            "<td>"+ uidChecker +"</td>"+   

            
            // "<td>"+status[i]['SIGNDATETIME']+"</td>"+
            "<td>"+signDateChecker+"</td>"+      
            // "<td>"+status[i]['ApprovedRemarks']+"</td>"+
            "<td>"+approveChecker+"</td>"+       
            "</tr>");
        }
    
        console.log(status);

        // mindex.splice(0, mindex.length, status);
        // console.log(mindex);
        // console.log(status)
        // console.log(status.length)

        // console.log(status[0]);
        // console.log(status[0]['FNAME']);
        // $('#row1').html(status[0]['USER_GRP_IND']);


        // OK
        // $('#tdata').append("<tr>"+
        //     "<td>"+mindex[i]['FNAME']+"</td>"+   
        //     +"</tr>");
        //     console.log(i);

})






// console.log(status[0]);
// console.log(status[0]['FNAME']);
// $('#firstname2').html(status[0]['PROCESSID']);







        // var xhr = new XMLHttpRequest();

        // xhr.open('get','/approvals', true);

        // xhr.onprogress = function(){
        //     console.log('readystate:' ,xhr.readyState);
        // }

        // xhr.onload = function(){
        //     if(this.status == 200){
        //         console.log(this.responseText);
        //     }
        // }

        // xhr.send();
    }

    function toBeDelete(){
        // $('#toBeDeleted > tr').remove();
        $('#myTableId tbody').empty();
        console.log('test');
    }


    function nullChecker(){
        if ($('#person_data[document_type]').val() != ''){}
    }
</script> --}}

