{{-- Modal Start --}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Overtime Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
      

                    <div class="row">
                        
                        <input type="hidden" name="projectName" id="projectName">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Project Name</label>
                                <select class="form-control select2 select2-default" id="projectID" data-dropdown-css-class="select2-default"  onchange="getProjectName(this)">
                                    @foreach ($project as $prj )
                                        <option value="{{ $prj->project_id }}">{{ $prj->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>                            
                        </div>
                    </div>
                    <div class="row"> 
                        <input type="hidden" name="employeeName" id="employeeName">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Employee Name</label>
                                <select class="form-control select2 select2-default" id="employeeID" data-dropdown-css-class="select2-default" onchange="getEmployeeName(this)">
                                    @foreach ($employee as $emp )
                                        <option value="{{ $emp->SysPK_Empl }}">{{ $emp->Name_Empl }}</option>
                                    @endforeach
                                </select>

                            </div>                            
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Overtime Date</label>
                                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="overtimeDate" data-target="#datetimepicker4"/>
                                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Auth. OT Hrs</label>
                                <input type="text" class="form-control" id="authTotalHrs" readonly placeholder="">
                          
                            </div>
                        </div>
                    </div>
  
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Auth. Time Start</label>
                                <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="authTimeStart" data-target="#datetimepicker7"/>
                                    <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                            </div> 
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Auth. Time End</label>
                                <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="authTimeEnd" data-target="#datetimepicker8"/>
                                    <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Time Start</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Time End</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Total OT Hrs</label>
                                <input type="text" class="form-control" readonly placeholder="">
                            </div>
                        </div>
                    </div> --}}
                    
                    <div class="form-group">
                        <label for="message-text" id="purposeLabel" class="col-form-label">Purpose</label>
                        <textarea class="form-control" id="purposeTwo" rows="3"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="insertOTDetails">Add</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal End --}}





{{-- Overtime Details Row Table --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                <div class="row">
                    <div class="col" style="font-size:18px; padding-top:5px;">Overtime Details</div>                                          
                    <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</a></div>
                </div>                                       
            </div> 
            <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                <table class="table table-bordered " id="overtimeDetailsTable">
                    <thead>
                        <tr class="d-flex text-center" style="font-size: 13px;">
                            <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Employee Name</th>
                            <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Project Name</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Overtime Date</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Auth. OT Hours</th>
                            {{-- <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Actual OT Hours</th> --}}
                            <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                            <th class="col-1" style="position: sticky; top: 0; background: white; ">Action</th>
                        </tr>
                    </thead>
                    <tbody id="overtimeDetailsTbody">
                        <tr class="d-flex" style="font-size: 13px;">
                           <td class="col-2">employee_name</td>
                           <td class="col-2">Project_Name</td>
                           <td class="col-1">overtime_date</td>
                           <td class="col-1">ot_in</td>
                           <td class="col-1">ot_out</td>
                           <td class="col-1">ot_totalhrs</td>
                           <td class="col-1">ot_in_actual</td>
                           <td class="col-1">ot_out_actual</td>
                           <td class="col-1">ot_totalhrs_actual</td>
                           <td class="col-3">purpose</td>
                           <div class="d-none">employee_id</div>
                           <div class="d-none">PRJID</div>
                           <div class="d-none">cust_id</div>
                           <div class="d-none">cust_name</div>
                           <div class="d-none">id</div>
                           <td class="col-1 text-center px-0">
                               <button class="btn btn-success"><i class="fas fa-edit"></i></button>
                               <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                           </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- footer /Pagination part --}}
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>                                    
</div>
{{-- Overtime Details --}}
















{{-- Itinerary Layout --}}


<div class="row">
    <div class="col-md-12">
        <div class="card card-gray">
            <div class="card-header">
                <h3 class="card-title">@yield('title')</h3>
            </div>

            <form action="">
                <div class="card-body">                                
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reference Number</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="" readonly>
                            </div>                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Requested Date</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>         
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Reporting Manager</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    <option selected="selected">Ceballos, Rosevir Jr.</option>
                                    <option>Bonifacio, Andres</option>
                                    <option>Ceballos, Rosevir Jr.</option>
                                    <option>Dela Cruz, Juan</option>  
                                    <option>Mabini, Andres</option>
                                    <option>Rizal, Jose</option>
                                    <option>Washington, George</option>
                                </select>
                            </div>                            
                        </div>
                    </div> 

                    {{-- Itinerary Details Table Start--}}
                    <div class="row  mt-4">
                        <div class="col-md-12">
                            <div class="card card-gray">
                                <div class="card-header" style="padding: 5px 20px 5px 20px; ">
                                    <div class="row">
                                        <div class="col" style="font-size:18px; padding-top:5px;">Itinerary Details</div>                                          
                                        <div class="col"><a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal" data-target=".bd-example-modal-lg">Add Record</a></div>
                                    </div>                                       
                                </div> 
                                <div class="card-body table-responsive  p-0" style="max-height: 300px; overflow: auto; display:inline-block;">
                                    <table class="table table-bordered " id="itineraryDetailsTable">
                                        <thead>
                                            {{-- <tr class="d-flex text-center" style="font-size: 13px;">
                                                <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Client Name</th>
                                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                                <th class="col-2 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                                <th class="col-4 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Action</th>
                                            </tr> --}}

                                            <tr class="d-flex text-center" style="font-size: 13px;">
                                                <th class="col-3 text-left" style="position: sticky; top: 0; background: white; ">Client Name</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time Start</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Auth. Time End</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Actual Time Start</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Actual Time End</th>
                                                <th class="col-4 text-left" style="position: sticky; top: 0; background: white; ">Purpose</th>
                                                <th class="col-1 text-left" style="position: sticky; top: 0; background: white; ">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itineraryDetailsTbody">
                                            {{-- <tr class="d-flex" style="font-size: 13px;">
                                                <td class="col-md-3">Cylix Technologies Inc.</td>
                                                <td class="col-md-2">7/9/2021</td>
                                                <td class="col-md-2">7/12/2021</td>
                                                <td class="col-md-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim sint quam accusantium!</td>
                                                <td class="col-1 text-center px-0">
                                                    <button class="btn btn-success"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr> --}}

                                            <tr class="d-flex" style="font-size: 13px;">
                                                <td class="col-md-3">Cylix Technologies Inc.</td>
                                                <td class="col-md-1">7/9/2021</td>
                                                <td class="col-md-1">7/12/2021</td>
                                                <td class="col-md-1">7/9/2021</td>
                                                <td class="col-md-1">7/12/2021</td>
                                                <td class="col-md-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim sint quam accusantium!</td>
                                                <td class="col-1 text-center px-0">
                                                    <button class="btn btn-success"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{-- footer /Pagination part --}}
                                <div class="card-footer clearfix">
                                </div>
                            </div>
                        </div>                                    
                    </div>
                    {{-- Itinerary Details Table End --}}

                </div>

                
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Itinerary Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Client Name</label>
                                <select class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                    <option selected="selected">OKADA</option>
                                    <option>Charity First</option>
                                    <option>OKADA</option>
                                    <option>Thuderbird</option>  
                                    <option>WFifth</option>
                                </select>
                            </div>                            
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Authorized Time Start</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Authorized Time End</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Time Start</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Time End</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Purpose</label>
                        <textarea class="form-control" rows="3" id="message-text"></textarea>
                    </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>




</div>





