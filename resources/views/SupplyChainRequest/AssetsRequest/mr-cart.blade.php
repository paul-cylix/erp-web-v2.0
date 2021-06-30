@extends('layouts.base')
@section('title', 'Material Request - Cart') 
@section('content')

{{-- Template --}}

<div class="row">
<div class="col-md-12">
<div class="card card-gray">
<div class="card-header d-flex align-items-center">
<h3 class="card-title ">@yield('title')</h3>
</div>

    <div class="card-body" >                       
        
        <div class="row" >
            {{-- Left --}}
            <div class="col-md-8 px-0 " >
               <div class="container">
                   
                    {{-- Card --}}
                     <div class="row" style="padding: 10px 0;">
        
                        <div class="container d-flex " >
                            <div class="col-md-3 d-flex px-0" >
                                <img src="{{ asset('images/bird-thumbnail-11629211231616049145.jpg') }}"  style="width: 100%; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="h6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores sequi est blandit</div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-8">
                                        <div >Item Code</div>
                                        <div>Model</div>
                                        <div>Category</div>
                                        <div>Brand</div>    
                                    </div>
        
                                    <div class="col-md-4">
                                        <div>Quantity</div>
                                    </div>
                                </div>
                             
                                
                            </div>
                        </div>
        
                    </div>
                    {{-- End Card --}}

                    {{-- Card --}}
                    <div class="row" style="padding: 10px 0;">
        
                        <div class="container d-flex " >
                            <div class="col-md-3 d-flex px-0" >
                                <img src="{{ asset('images/bird-thumbnail-11629211231616049145.jpg') }}"  style="width: 100%; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="h6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores sequi est blandit</div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-8">
                                        <div >Item Code</div>
                                        <div>Model</div>
                                        <div>Category</div>
                                        <div>Brand</div>    
                                    </div>
        
                                    <div class="col-md-4">
                                        <div>Quantity</div>
                                    </div>
                                </div>
                             
                                
                            </div>
                        </div>
        
                    </div>
                    {{-- End Card --}}

                    {{-- Card --}}
                    <div class="row" style="padding: 10px 0;">
        
                        <div class="container d-flex " >
                            <div class="col-md-3 d-flex px-0" >
                                <img src="{{ asset('images/bird-thumbnail-11629211231616049145.jpg') }}"  style="width: 100%; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="h6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores sequi est blandit</div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-8">
                                        <div >Item Code</div>
                                        <div>Model</div>
                                        <div>Category</div>
                                        <div>Brand</div>    
                                    </div>
        
                                    <div class="col-md-4">
                                        <div>Quantity</div>
                                    </div>
                                </div>
                             
                                
                            </div>
                        </div>
        
                    </div>
                    {{-- End Card --}}

                    {{-- Card --}}
                    <div class="row" style="padding: 10px 0;">
        
                        <div class="container d-flex " >
                            <div class="col-md-3 d-flex px-0" >
                                <img src="{{ asset('images/bird-thumbnail-11629211231616049145.jpg') }}"  style="width: 100%; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="h6">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores sequi est blandit</div>
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-8">
                                        <div >Item Code</div>
                                        <div>Model</div>
                                        <div>Category</div>
                                        <div>Brand</div>    
                                    </div>
        
                                    <div class="col-md-4">
                                        <div>Quantity <span>5</span></div>
                                    </div>
                                </div>
                             
                                
                            </div>
                        </div>
        
                    </div>
                    {{-- End Card --}}

                    


                    
                </div>
            </div>
        
        
        
            {{-- Right --}}
            <div class="col-md-4  border-left" >
                <div class="container">
                    <div class="h5">Cart Summary</div>
                    <br>
                    <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Brand</th>
                            <th scope="col">Qty</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Router</td>
                            <td>AMD-123</td>
                            <td>AMD-123</td>

                            <td>123</td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>RJ45</td>
                            <td>DMA-241</td>
                            <td>DMA-241</td>
                            <td>13</td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td>Rj46</td>
                            <td>ABC-5151</td>
                            <td>ABC-5151</td>
                            <td>11</td>
                          </tr>
                          <tr>
                            <th scope="row">4</th>
                            <td >Ethernet Cable</td>
                            <td>AESP-143</td>
                            <td>AESP-143</td>
                            <td>22</td>
                          </tr>
                        </tbody>
                      </table>
                    <br>
                    <button class="btn btn-success">Proceed to Request</button>


                </div>
        
            </div>
            
            
        </div>
    </div>   
        {{-- End Card Boady --}}                                                            
</div>
</div>
</div>



@endsection