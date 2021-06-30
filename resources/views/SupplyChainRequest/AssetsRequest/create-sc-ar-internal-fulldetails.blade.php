@extends('layouts.base')
@section('title', 'Assets Request - Internal Details') 
@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title">@yield('title')</h3>
            </div>

                <div class="card-body py-0" style="background-color:rebeccapurple;" >                             
                         
                </div>   
                                                          
                   
               
        </div>
    </div>
</div>








<div class="row" style="background-color: lavender;">
    {{-- Left --}}
    <div class="col-md-8 px-0 m-2" style="background-color: red">
       
           
            {{-- Card --}}
             <div class="row">

                <div class="container d-flex " >
                    <div class="col-md-3 d-flex px-0" style="background-color: khaki;">
                        <img src="{{ asset('images/bird-thumbnail-11629211231616049145.jpg') }}"  style="width: 100%; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">

                        <div class="row">
                            <div class="col-md-12">
                                <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae dolores sequi est blandit</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div style="background-color: red;">Item Code</div>
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
        
    </div>



    {{-- Right --}}
    <div class="col-md-4 m-2 " style="background-color: indianred;">
   asd

    </div>
    
    
</div>









@endsection