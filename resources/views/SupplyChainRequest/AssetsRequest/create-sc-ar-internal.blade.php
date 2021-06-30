@extends('layouts.base')
@section('title', 'Assets Request - Internal') 
@section('content')


{{-- 
<div class="row" style="margin-top: -20px;">    
    <div class="col-md-1">
        <div class="form-group">
            <a style="width:100%" href="/dashboard" class="btn btn-secondary">Cancel</a> 
            <a href="/create-ar-internal-details">Full Details</a>
        </div>
    </div> 
</div> --}}

<div class="row" style="margin-top: -20px;">
    <div class="col">
        <div class="input-group mb-3 d-flex justify-content-between">
            <div class="d-flex">
                <input type="text" class="form-control" placeholder="Search" aria-describedby="searchLive">
                <div class="input-group-append mx-1">
                    <span class="input-group-text" id="searchLive"><i class="fas fa-search"></i></span>
                        <a href="/mr-cart">MR CART</a>

                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="itemfulldetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="addDnoneToDiv()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- Content --}}

          <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          </div>

            <div class="container d-none" id="scarinternalcontentcontainer">
                <div style="background-color: rebeccapurple;" class="d-flex justify-content-center">
                    <img id="mainItemImage" class="rounded" src="{{ asset('images/scenery-15300634651616377457.jpg') }}">
                </div>

                <div>
                  
                    <div style="padding-top: 10px; text-align: justify;text-justify: inter-word;"class="h5" id="maintitledesc" >Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni ex dolorem animi?</div>
                    <div >
                        <div class="mainItemDetail">Description:</div>
                        <p id="mainItemDescription">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptate totam ducimus odit nostrum optio libero ratione porro labore eum dicta et quam temporibus, placeat magnam, ex culpa minima cumque nobis. Error laboriosam doloremque molestiae beatae, perspiciatis sapiente officia sed temporibus veniam rem reprehenderit alias, commodi exercitationem natus? A, adipisci ut!</p>
                    </div>

                  
                    
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="mainItemDetail">Item Code:<span class="itemDetailSpan" id="itemcodeListItems">Lorem ipsum dolor sit amet.</span></div>
                            <div class="mainItemDetail">Brand:<span class="itemDetailSpan" id="brandListItems">Generic</span></div>
                            <div class="mainItemDetail">Model:<span class="itemDetailSpan" id="modelListItems">Lorem ipsum dolor sit.</span></div>
                            <div class="mainItemDetail">Category:<span class="itemDetailSpan" id="categoryListItems">Lorem</span></div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="mainItemDetail">SKU:<span class="itemDetailSpan" id="skuListItems">Lorem ipsum dolor sit amet consectetur adipisicing.</span></div>
                            <div class="mainItemDetail">Qty on hand:<span class="itemDetailSpan" id="qtyListItems">5 Piece(s)</span></div>
                            <div class="mainItemDetail">UoM:<span class="itemDetailSpan" id="unitmeasureListItems">Meter</span></div>
                            <div class="mainItemDetail">Sub Category:<span class="itemDetailSpan" id="subcategoryListItems">IT Materials</span></div>
                        </div>

                    </div>
                  
                    <div class="row py-4 d-flex justify-content-end" >
                        
                        <div class="col-md-6" >
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="h6">Quantity</div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-light" id="minusQtyBtn"><i class="fas fa-minus"></i></button>
                                    <input type="number" style="width: 100px;" id="qtyOfItemform" class="form-control input-number mx-1"  onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57">
                                    <button class="btn btn-light" id="plusQtyBtn"><i class="fas fa-plus"></i></button>
                                </div>
                              
                                <div>
                                <button class="btn btn-success" id="addtocartbtn">Add</button>
                                </div>
                            </div>
                        </div>
                 
                    </div>
                
                </div>
            </div>
          {{-- Content --}}
        </div>
  
      </div>
    </div>
  </div>
{{-- Modal --}}






    <div class="row">
        <div class="col-md-12">
            <div class="card card-gray">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">@yield('title')</h3>
                </div>

                    <div class="card-body py-0" >
                                                       
                    <div class="col text-center" style=" padding: 25px 0px;" id="myListItemsDiv">
                    
                        <div id="myrealcontainer">
                
                         

                         
                            @foreach ($listItems as $item )
                                {{-- Card --}}  
                         
                              
                                <a href="javascript:void(0)" onclick="viewListItemDetails({{$item->group_detail_id}},{{ $loop->index }})" data-toggle="modal" data-target="#itemfulldetails" >                                         
                                <div style="max-width: 32%; height: auto;  padding:10px; margin:10px 4px; " class=" d-inline-block" id="listItemCard">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-3 d-flex" style="padding:3px; margin:0px;">
                                            <img src="{{ asset('images/scenery-15300634651616377457.jpg') }}"  style="width: 100%; max-height: 100%; object-fit: cover;">
                                            </div>
                                            {{-- <div class="col-md-8"> --}}
                                            <div class="col-md-9 px-1" style="font-size: 12px;">

                                            <div class="text-left h6" id="itemNameDiv">{{ $item->specification }}</div>
                                                <div class="row text-left" style="color: grey;">
                                                    <div class="col-md-6">
                                                        <div class="">Item Code:<span class="font-weight-normal ml-1">{{ $item->item_code }}</span></div>
                                                        <div class="">Brand:<span class="font-weight-normal ml-1">{{ $item->brand }}</span></div>
                                                        <div class="">Model:<span class="font-weight-normal ml-1">{{ $item->description }}</span></div>
                                                        <div class="">SKU:<span class="font-weight-normal ml-1">{{ $item->SKU }}</span></div>             
                                                    </div>
                                                    <div class="col-md-6" id="rightContentListItems">
                                                        <div class="">Qty on hand:<span class="font-weight-normal ml-1" >{{ $item->OnHand }}</span></div>
                                                        <div class="">UoM:<span class="font-weight-normal ml-1">{{ $item->unit_measure }}</span></div>
                                                        <div class="">Category:<span class="font-weight-normal ml-1">{{ $item->Category }}</span></div>
                                                        {{-- <div class="">Sub Category:<span class="font-weight-normal ml-1">{{ $item->SubCategory }}</span></div> --}}

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>                             
                                </div>
                                </a>
                                {{-- Card --}}
                            @endforeach

                                {{-- Pagination --}}                                            
                                <div class="row d-flex justify-content-end px-4 pt-4" id="myListItemPagination" >
                                    {{ $listItems->links() }}
                                </div>
                                {{-- Pagination --}}                                                                       

                        </div>
                    </div>
                               
                    </div>   
                    {{-- End Card Boady --}}                                           
                       
                   
            </div>
        </div>
    </div>















<script>
    // Cart
    var sampletextcart = null;
    console.log(sampletextcart);

    $('#minusQtyBtn').on('click',function(){
        var itemQty = $('#qtyOfItemform').val();
        var iItemQty = parseInt(itemQty);
        if(iItemQty){
            var total = (iItemQty - 1)
            $('#qtyOfItemform').val(total);
        } else {
            $('#qtyOfItemform').val('0');

        }
    });

    $('#plusQtyBtn').on('click',function(){
        var itemQty = $('#qtyOfItemform').val();
        var iItemQty = parseInt(itemQty);

        if(iItemQty){
            var total = (iItemQty + 1)
            $('#qtyOfItemform').val(total);
        } else {
            $('#qtyOfItemform').val('1');

        }
    });

    $('#addtocartbtn').on('click',function(){
        sampletextcart = 20;
    })


</script>

<script>
    function viewListItemDetails(id,index){

        setTimeout(function() {
            $('#scarinternalcontentcontainer').removeClass('d-none');
            $('.spinner-border').addClass('d-none');

        }, 2000);

        // alert(id);
      $.get('/ar-internal-listitem-details/'+id,function(data){
        console.log(data);
        // console.log(data['SKU']);
        $('#skuListItems').text(data['SKU']);
        $('#brandListItems').text(data['brand']);
        $('#itemcodeListItems').text(data['item_code']);
        $('#categoryListItems').text(data['Category']);
        $('#unitmeasureListItems').text(data['unit_measure']);
        $('#subcategoryListItems').text(data['Sub Category']);
        $('#mainItemDescription').text(data['specification']);
        $('#qtyListItems').text(data['OnHand']);
        $('#maintitledesc').text(data['specification']);
        $('#modelListItems').text(data['description']);

      })

    }

    function addDnoneToDiv(){
        // alert('te');
        $('#scarinternalcontentcontainer').addClass('d-none');
        $('.spinner-border').removeClass('d-none');


    }
</script>






 
    
{{-- <script>
    // var BASE_URL =""

    function fetch_data(page){
        $.ajax({
            url:BASE_URL+"/create-ar-internal-ajax?page="+page,
            success:function(data){
                $('#myListItemsDiv #myrealcontainer').html(data);
            }
        })
    }

    var x = $('#myListItemPagination a');

    x.on('click',function(e){
        e.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);

    })
</script>
    

  
    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script> --}}
    

@endsection

