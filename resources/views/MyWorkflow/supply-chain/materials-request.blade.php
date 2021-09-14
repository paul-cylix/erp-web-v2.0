<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Materials Request</title>
</head>
<body>
    



{{-- Nav --}}
<div class=" border container" style="background-color: #212529 !important;">
  <div class="row p-2">
      <div class="col-md-2  m-0 p-0 d-flex justify-content-center align-items-center" style="color: #9dd227">Cylix Technologies Inc.</div>
      <div class="col-md-9"><input class="form-control" type="text" placeholder="Search"></div>
      <div class="col-md-1 text-center  m-0 p-0"><button type="button" class="btn" style="color: #9dd227"><i class="fas fa-shopping-cart"></i></button></div>
  </div>
</div>


{{-- Main --}}
<div class="container " style="background-color: #f4f6f9 !important;">
  <div class="row">

    {{-- Sidebar --}}
    <div class="col-md-2  border">

      <div class=" mt-3 p-1" >
        <span class="" style=""><i class="fas fa-filter"></i> Search Filter</span>
      </div>
      <hr>

      <div class="mb-3">
        <span class="">Brand</span>
      </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customCheck1">
          <label class="custom-control-label" for="customCheck1">Samsung</label>
        </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customCheck2">
          <label class="custom-control-label" for="customCheck2">Sony</label>
        </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customCheck3">
          <label class="custom-control-label" for="customCheck3">Nvidia</label>
        </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customCheck4">
          <label class="custom-control-label" for="customCheck4">Toyota</label>
        </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customCheck5">
          <label class="custom-control-label" for="customCheck5">HP</label>
        </div>

        

      <hr>
      <div class="mt-3 mb-3">
        <span>Price</span>
      </div>

      <div class="mb-3">
        <div class="row">
          <div class="col"><input type="number" class="form-control form-control-sm" id="filtermin" aria-describedby="numberHelp" placeholder="Min"></div>
          <span>-</span>
          <div class="col"><input type="number" class="form-control form-control-sm" id="filtermax" aria-describedby="numberHelp" placeholder="Max"></div>
        </div>
        
      </div>

    
    </div>

    {{-- Main Content --}}
    <div class="col-md-10 border" >
      <div class="mt-3 mb-3 d-flex justify-content-between">
        <div class="d-flex">
          <span class=" mr-1" style="">Sort by:</span>
          <button type="button" class="btn btn-secondary btn-sm mx-1">Top Sales</button>
          <button type="button" class="btn btn-secondary btn-sm mx-1">Relevance</button>
          <button type="button" class="btn btn-secondary btn-sm mx-1">Latest</button>
          <button type="button" class="btn btn-secondary btn-sm mx-1">Price: Low to High</button>
          <button type="button" class="btn btn-secondary btn-sm mx-1">Price: High to Low</button>
        </div>


        <div class="d-flex">
          <span class=" mr-1" style="">View by:</span>
          <button type="button" class="btn btn-secondary btn-sm mx-1"><i class="fab fa-microsoft"></i></button>
          <button type="button" class="btn btn-secondary btn-sm mx-1"><i class="fas fa-list"></i></button>
        </div>

      </div>
  
      <hr>







      {{-- Main Items --}}
      <div class="container ">
        {{-- Card List Style --}}

        <div class="card__post d-flex border">
          <div class="card__img">
            <img src="{{ asset('images/usama-akram-kP6knT7tjn4-unsplash.jpg') }}" alt="" srcset="" style="width: 200px; height:200px; object-fit: cover;">
          </div>
          <div class="card__info">
            <div class="card__title border" style="width:100%;">Lorasdasdadsadsadsasdasdadsem ipsum dolor sit amet consectetur adipisicing elit. A, perspiciatis!</div>
            <div class="card__content"></div>
          </div>
        </div>
      




      </div>



          

          

          

          


          


          


          

      <div style="height: 60px;"></div>
      </div>
      {{-- Main Items End --}}






    </div>
  </div>
  {{-- End Row --}}

</div>
{{-- End Main --}}







    
    
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>