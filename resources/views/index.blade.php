@extends('layouts.base')
@section('title', 'Home')
@section('content')
         <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>15</h3>

                <p>For Inputs</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/inputs" class="small-box-footer">View list <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53</h3>

                <p>For Approvals</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="/approvals" class="small-box-footer">View list <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>44</h3>

                <p>Clarifications</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/clarifications" class="small-box-footer">View list <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>

                <p>Rejected</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="rejected" class="small-box-footer">View list <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

{{-- Button sticky create  --}}
<button type="button" class="btn btn-secondary fixed-button btn-circle btn-xl" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i></button>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
                
                
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingOne">
  <h4 class="panel-title">
  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
    <div class="container"></div>
    <i class="fa fa-calculator" aria-hidden="true"></i>
    Accounting & Finance
  </a>
</h4>
</div>
<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-rfp" class="list-group-item list-group-item-action">Payment</a>
      <a href="/create-re" class="list-group-item list-group-item-action">Reimbursement</a>
      <a href="/create-pc" class="list-group-item list-group-item-action">Petty Cash</a>
      <a href="/create-ca" class="list-group-item list-group-item-action">Cash Advance</a>
    </div>
  </div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingTwo">
  <h4 class="panel-title">
  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
    <i class="fa fa-users" aria-hidden="true"></i>
    Human Resource
  </a>
</h4>
</div>
<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-ot" class="list-group-item list-group-item-action">Overtime</a>
      <a href="/create-leave" class="list-group-item list-group-item-action">Leave</a>
      <a href="/create-itinerary" class="list-group-item list-group-item-action">Itinerary</a>
      <a href="/create-ir" class="list-group-item list-group-item-action">Incident Report</a>
    </div>
  </div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingThree">
  <h4 class="panel-title">
  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
    <i class="fa fa-list-alt" aria-hidden="true"></i>
    Master List
  </a>
</h4>
</div>
<div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-ce" class="list-group-item list-group-item-action">Customer Entry</a>
      <a href="/create-ie" class="list-group-item list-group-item-action">Item Entry</a>
      <a href="/create-se" class="list-group-item list-group-item-action">Supplier Entry</a>
    </div>
  </div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingFour">
  <h4 class="panel-title">
  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
    <i class="fa fa-cogs" aria-hidden="true"></i>
    Operations
  </a>
</h4>
</div>
<div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-lr" class="list-group-item list-group-item-action">Labor Resources</a>

    </div>
  </div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingFive">
  <h4 class="panel-title">
  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
    Purchasing
  </a>
</h4>
</div>
<div id="collapseFive" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-pr" class="list-group-item list-group-item-action">Purchase Request</a>
      <a href="/create-po" class="list-group-item list-group-item-action">Purchase Order</a>
      <a href="/create-dpo" class="list-group-item list-group-item-action">Direct Purchase Order</a>
    </div>
  </div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingSix">
  <h4 class="panel-title">
  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
    <i class="fas fa-chart-line"></i>
    Sales Order
  </a>
</h4>
</div>
<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
  <div class="panel-body">
    <div class="list-group">
      <a href="/create-sof-delivery" class="list-group-item list-group-item-action">Delivery</a>
      <a href="/create-sof-project" class="list-group-item list-group-item-action">Project</a>
      <a href="/create-sof-demo" class="list-group-item list-group-item-action">Demo</a>
      <a href="/create-sof-poc" class="list-group-item list-group-item-action">POC</a>
      <a href="/sof-pending" class="list-group-item list-group-item-action">Pending</a>
    </div>
  </div>
</div>
</div>



<div class="panel panel-default">
<div class="panel-heading" role="tab" id="headingSeven">
  <h4 class="panel-title">
  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
    <i class="fa fa-truck" aria-hidden="true"></i>
    Supply Chain
  </a>
</h4>
</div>
<div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
  <div class="panel-body">
    <div class="list-group">

      {{-- accordion 1 --}}
      <div class="panel-subheading " role="tab" id="headingMR">
          <a class="collapsed list-group-item list-group-item-action" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMR" aria-expanded="false" aria-controls="collapseMR">
            Materials Request
          </a>
      </div>
      {{-- accordion 1a --}}
      <div id="collapseMR" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingMR">
        <div class="panel-body">
          <div class="list-group">
            <a href="/create-mr-project" class="list-group-item list-group-item-action indention">Project</a>
            <a href="/create-mr-delivery" class="list-group-item list-group-item-action indention">Delivery</a>
            <a href="/create-mr-demo" class="list-group-item list-group-item-action indention">Demo</a>
          </div>
        </div>
      </div>

      {{-- accordion 2--}}
      <div class="panel-subheading " role="tab" id="headingAR">
        <a class="collapsed list-group-item list-group-item-action" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAR" aria-expanded="false" aria-controls="collapseAR">
          Assets Request
        </a>
    </div>
    {{-- accordion 2a --}}
    <div id="collapseAR" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingAR">
      <div class="panel-body">
        <div class="list-group">
          <a href="/create-ar-project" class="list-group-item list-group-item-action indention">Project</a>
          <a href="/create-ar-delivery" class="list-group-item list-group-item-action indention">Delivery</a>
          <a href="/create-ar-demo" class="list-group-item list-group-item-action indention">Demo</a>
          <a href="/create-ar-poc" class="list-group-item list-group-item-action indention">POC</a>
          <a href="/create-ar-pending" class="list-group-item list-group-item-action indention">Pending</a>
        </div>
      </div>
    </div>

      {{-- accordion 3 --}}
      <div class="panel-subheading " role="tab" id="headingSR">
        <a class="collapsed list-group-item list-group-item-action" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSR" aria-expanded="false" aria-controls="collapseSR">
          Supplies Request
        </a>
    </div>
    {{-- accordion 3a --}}
    <div id="collapseSR" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSR">
      <div class="panel-body">
        <div class="list-group">
          <a href="/create-sr-project" class="list-group-item list-group-item-action indention">Project</a>
          <a href="/create-sr-internal" class="list-group-item list-group-item-action indention">Internal</a>
        </div>
      </div>
    </div>
    
      <a href="/sc-releasestocks" class="list-group-item list-group-item-action">Release Stocks</a>
      <a href="/sc-rma" class="list-group-item list-group-item-action">RMA</a>
    </div>
  </div>
</div>
</div>
</div>
{{-- end accordion --}}
</div>
</div>
</div>
{{-- end modal --}}
</div>

@endsection