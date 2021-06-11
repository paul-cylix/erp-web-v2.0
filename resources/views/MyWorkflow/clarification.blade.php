@extends('layouts.base')
@section('title', 'Request With Clarification') 
@section('content')

<div class="row" style="margin-top: -20px;">
    <div class="col-md-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search" aria-describedby="searchLive">
            <div class="input-group-append">
              <span class="input-group-text" id="searchLive"><i class="fas fa-search"></i></span>
            </div>
        </div>
    </div>
</div>

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
                                                    <td><a href="/clarifications/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}">{{$post->REFERENCE}}</a></td>
                                                    <td>{{$post->RequestType}}</td>
                                                    <td>{{$post->Date}}</td>
                                                    <td>{{$post->Project}}</td>
                                                    <td>{{$post->Initiator}}</td>
                                                    <td class="text-right">{{ number_format($post->Amount,2)}} </td>
                                                    <td>
                                                        {{-- <a href="/clarifications/{{ $post->ID }}" class="btn btn-info">Open</a> --}}
                                                        <a href="/clarifications/{{$post->FRM_CLASS}}/{{ $post->ID }}/{{ $post->RequestType }}" class="btn btn-info">Open</a>
                                                        {{-- <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus({{ $post->ID }})">View Status</a> --}}
                                                        <a href="javascript:void(0)" class="btn btn-secondary" data-target="#viewStatusModal" data-toggle="modal" onclick="viewStatus('{{$post->FRM_CLASS}}',{{ $post->ID }})">View Status</a>

                                                        {{-- <a href="javascript:void(0)" class="btn btn-warning" data-target="#viewMessagesModal" data-toggle="modal" onclick="viewClaComments({{ $post->ID }})">Comments</a> --}}

                                                        <a href="javascript:void(0)" class="btn btn-warning" data-target="#viewMessagesModal" data-toggle="modal" onclick="viewClaComments('{{$post->FRM_CLASS}}',{{ $post->ID }})">Comments</a>

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


                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <div>{{ $posts->links() }}</div>

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

