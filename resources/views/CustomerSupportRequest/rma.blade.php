<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body style="background-color:#e2e1e0;">

@foreach ( $rma as $a )
  <div class="container" style="background-color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  transition: all 0.3s cubic-bezier(.25,.8,.25,1);
  margin-top: 50px;
  ">
      <div class="row ">
          <div class="col-md-5 m-0 p-0">
              <img src="https://t4.ftcdn.net/jpg/02/07/87/79/360_F_207877921_BtG6ZKAVvtLyc5GWpBNEIlIxsffTtWkv.jpg" class="" style="width: 100%; max-height: 450px;" alt="">
          </div>
          <div class="col-md-7 ">
              <h1 class="p-2 m-0 ">{{ $a->MODEL }}</h1>
              <ul>
                  <li><b>Brand: </b><span>{{ $a->BRAND }}</span></li>
                  <li><b>Serial Number: </b><span>{{ $a->SERIALNUMBER }}</span></li>
                  <li><b>Date Received: </b><span>{{ $a->DATERECEIVED }}</span></li>
                  <li><b>Qty: </b><span>{{ $a->QTY }}</span></li>
                  <li><b>UoM: </b><span>{{ $a->UOM }}</span></li>
                  <li><b>Client: </b><span>{{ $a->CLIENT }}</span></li>
                  <li><b>Project Name: </b><span>{{ $a->PROJECT }}</span></li>
                  <li><b>Project Manager: </b><span>{{ $a->PROJECT_MANAGER }}</span></li>

                  {{-- <li>SOF No.: SOF-001234</li> --}}
              </ul>
          <h6><b>Issue:</b></h6>
          <p>{{ $a->ISSUE }}</p>
          </div>
      </div>
  </div>
@endforeach



    




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</body>
</html>