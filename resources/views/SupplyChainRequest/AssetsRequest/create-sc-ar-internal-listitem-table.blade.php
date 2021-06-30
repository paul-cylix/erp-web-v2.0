
@foreach ($listItems as $item )
{{-- Card --}}                                           
<div style="max-width: 32%; height: auto;  padding:10px; margin:10px 4px; " class=" d-inline-block" id="listItemCard">
    <div class="container">
        <div class="row">
            <div class="col-md-3 d-flex" style="padding:3px; margin:0px;">
            <img src="{{ asset('images/scenery-15300634651616377457.jpg') }}"  style="width: 100%; max-height: 100%; object-fit: cover;">
            </div>
            {{-- <div class="col-md-8"> --}}
            <div class="col-md-9 px-1" style="font-size: 12px;">

            <h6 class="text-left">{{ $item->description }}</h6>
                <div class="row text-left" style="color: grey;">
                    <div class="col-md-6">
                        <div class="">Item Code:<span class="font-weight-normal ml-1">{{ $item->item_code }}</span></div>
                        <div class="">Brand:<span class="font-weight-normal ml-1">{{ $item->brand }}</span></div>
                        <div class="">Model:<span class="font-weight-normal ml-1">DPS-250AB-47A</span></div>
                        <div class="">SKU:<span class="font-weight-normal ml-1">DPS-250AB-47A</span></div>             
                    </div>
                    <div class="col-md-6" id="rightContentListItems">
                        <div class="">Qty on hand:<span class="font-weight-normal ml-1">46</span></div>
                        <div class="">UoM:<span class="font-weight-normal ml-1">Piece(s)</span></div>
                        <div class="">Category:<span class="font-weight-normal ml-1">IT Materials</span></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>                             
</div>
{{-- Card --}}
@endforeach

{{-- Pagination --}}                                            
<div class="row d-flex justify-content-end px-4 pt-4" id="myListItemPagination" >
    {{ $listItems->links() }}
</div>
{{-- Pagination --}}