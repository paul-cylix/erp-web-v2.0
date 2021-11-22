<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


class CustomerSupportController extends Controller
{
    public function createRmaReceiving(){
        $projects = DB::select("SELECT project_id, project_name FROM general.`setup_project` WHERE project_type <> 'MAIN OFFICE' AND `status` = 'Active' AND title_id = 1 ORDER BY project_name");
        $managers = DB::select("SELECT id as RMID,UserFull_name as RMName FROM general.`users` a WHERE a.`status` = 'ACTIVE' ORDER BY a.`UserFull_name`");
        $uom = DB::select("SELECT id, UoM FROM procurement.`tbl_uom` a");
        $dateTime = $this->getDateTime();
        $dateTime = date_create($dateTime);
        $dateTime = date("m/d/Y h:i A");

        return view('CustomerSupportRequest.create-receiving', compact('projects','managers','dateTime','uom'));
    }

    public function getRmaReceivingList(){
        $rma_receiving_details = DB::select("SELECT *,(SELECT b.`PROJECT_MANAGER` FROM customer_support.`rma_receiving` b WHERE b.`id` = a.`rma_receiving_id`) AS RMName FROM customer_support.`rma_receiving_details` a WHERE a.`STATUS` = 'Active' AND a.`deleted_at` IS NOT TRUE");
        return view('CustomerSupportRequest.rma-receiving-list', compact('rma_receiving_details'));
        
    }

    public function deleteRmaReceivingList(Request $request){
        DB::table('customer_support.rma_receiving_details')
        ->where('id', $request->rcvdID)
        ->update([
            'deleted_at' => $this->getDateTime(),
            'deleted_by' => session('LoggedUser'),
            'STATUS' => 'Inactive',
        ]);

        return back()->with('form_submitted', 'Your RMA Recieving was successfully deleted.');

    }

    public function getRmaByGuid($guid){
        // $rma = DB::select("SELECT * FROM customer_support.`rma_receiving_details` a WHERE a.`STATUS` = 'Active' AND a.`GUID` = $guid AND a.`deleted_at` IS NOT NULL");
        $rma =  DB::table('customer_support.rma_receiving_details')
        ->join('customer_support.rma_receiving', 'customer_support.rma_receiving.id', '=', 'customer_support.rma_receiving_details.rma_receiving_id')
        ->where('STATUS','Active')
        ->where('GUID',$guid)
        ->whereNull('customer_support.rma_receiving_details.deleted_at')
        ->select('customer_support.rma_receiving_details.*','customer_support.rma_receiving.PROJECT_MANAGER')
        ->get();

        // dd($rma);
        return view('CustomerSupportRequest.rma', compact('rma'));

    }






    public function saveRmaReceiving(Request $request){


        $request->validate([
            'rdateTime' => 'required',
            'rmID' => 'required',
            'createdBy' => 'required',  
        ]);
        

            $rma_receiving_id = DB::table('customer_support.rma_receiving')->insertGetId([

                'UID' => session('LoggedUser'),
                'FNAME' => session('LoggedUser_FirstName'),
                'LNAME' => session('LoggedUser_LastName'),
                'DEPARTMENT' => session('LoggedUser_DepartmentName'),
                'POSITION' => session('LoggedUser_PositionName'),
                'PMID' => $request->rmID,
                'PROJECT_MANAGER' => $request->rmName,
                'TITLEID' => session('LoggedUser_CompanyID'),
                
            ]);

            $rmaData = $request->jsonrmaData;
            $rmaData =json_decode($rmaData,true);


            if(!empty($rmaData)){
                // insert to hr.ot main table
                for($i = 0; $i <count($rmaData); $i++) {

                    $dateReceived = date_create($rmaData[$i][9]);
             
                    $setRmaRdata[] = [
                        'rma_receiving_id' => $rma_receiving_id,
                        'PROJECTID' => $rmaData[$i][10],
                        'PROJECT' => $rmaData[$i][1],
                        'CLIENTID' => $rmaData[$i][11],
                        'CLIENT' => $rmaData[$i][2],
                        'ISSUE' => $rmaData[$i][3],
                        'BRAND' => $rmaData[$i][4],
                        'MODEL' => $rmaData[$i][5],
                        'SERIALNUMBER' => $rmaData[$i][6],
                        'DATERECEIVED' => date_format($dateReceived, 'Y-m-d H:i:s'),
                        'QTY' => $rmaData[$i][7],
                        'UOMID' => $rmaData[$i][0],
                        'UOM' => $rmaData[$i][8],
                        'GUID' => $this->getGuid(),
                    ];
                }
                DB::table('customer_support.rma_receiving_details')->insert($setRmaRdata);
            }


            return back()->with('form_submitted', 'Your RMA Recieving was successfully submitted.');
 

     
    }



    public function editRmaReceiving($id){
        
        $projects = DB::select("SELECT IFNULL((SELECT 'True' FROM customer_support.`rma_receiving_details` b WHERE b.`PROJECTID` = a.`project_id` AND b.`id` = '20'), 'False') 
        AS 'option',
        a.`project_id`, a.`project_name` 
        FROM general.`setup_project` a WHERE a.`project_type` <> 'MAIN OFFICE' AND a.`status` = 'Active' AND a.`title_id` = 1 ORDER BY a.`project_name`");
        $rmaStatus = DB::select("SELECT  IFNULL ((SELECT 'True' FROM customer_support.`rma_receiving_details` b WHERE b.`rma_status_id` = a.`id` AND b.`id` = $id), 'False') AS 'option',a.`id`,a.`rma_status` FROM customer_support.`tbl_rma_status` a WHERE a.`status` = 'Active'");

        



        $uom = DB::select("SELECT IFNULL((SELECT 'True' FROM customer_support.`rma_receiving_details` b WHERE b.UOMID = a.id AND b.`id` = '20'), 'False') AS 'option',a.`id`, a.`UoM` FROM procurement.`tbl_uom` a");
        $image = DB::select("SELECT * FROM customer_support.`rma_receiving_attachments` a WHERE a.`rma_receiving_details_id` = $id");
       
        $rma_details = DB::table('customer_support.rma_receiving_details')
        ->join('customer_support.rma_receiving', 'customer_support.rma_receiving.id', '=', 'customer_support.rma_receiving_details.rma_receiving_id')
        // ->join('customer_support.rma_receiving_attachments', 'customer_support.rma_receiving_details.id', '=', 'customer_support.rma_receiving_attachments.rma_receiving_details_id')


        ->where('customer_support.rma_receiving_details.id', $id)
        // ->where('customer_support.rma_receiving_details.STATUS', 'Inactive')
        ->whereNull('customer_support.rma_receiving_details.deleted_at')
        
        ->select('customer_support.rma_receiving_details.*','customer_support.rma_receiving.TS as mainTs',DB::raw("IFNULL(customer_support.rma_receiving_details.LOCATION, FALSE) as 'isLocation' "))
        ->first();




        return view('CustomerSupportRequest.rma-receiving', compact('projects','rmaStatus','uom','rma_details','image'));
    }


    public function updateRmaReceiving(Request $request){

        $request->validate([
            'projectID'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'dateReceived'=>'required',
            'location'=>'required',
            'qty'=>'required|not_in:0',
            'uom'=>'required',
            'status'=>'required',
            'rmaStatus'=>'required',
            'serialNumber'=>'required',
            'issue'=>'required',
        ]);


        $dateReceived = date_create($request->dateReceived);
      

        DB::table('customer_support.rma_receiving_details')
              ->where('id', $request->id)
              ->whereNull('deleted_at')
              ->update([
                'PROJECTID' => $request->projectID, 
                'PROJECT' => $request->projectName, 
                'CLIENTID' => $request->clientID, 
                'CLIENT' => $request->clientName, 
                'ISSUE' => $request->issue, 
                'BRAND' => $request->brand, 
                'MODEL' => $request->model, 
                'SERIALNUMBER' => $request->serialNumber, 
                'DATERECEIVED' => date_format($dateReceived, 'Y-m-d H:i:s'),
                'LOCATION' => $request->location, 
                'QTY' => $request->qty, 
                'UOMID' => $request->uom, 
                'UOM' => $this->getUoMName(intval($request->uom)),
                'STATUS' => $request->status,
                'updated_at' => $this->getDateTime(),
                'updated_by' => session('LoggedUser'),
                'rma_status_id' => $request->rmaStatus,
                'rma_status' => $this->getRmaStatus(intval($request->rmaStatus)), // wala
            ]);


  

            if($request->hasFile('image')){


                    if($request->filepath){
                        unlink(public_path($request->filepath));
                        DB::table('customer_support.rma_receiving_attachments')->where('rma_receiving_details_id', $request->id)->delete();

                    }
               
                    $completeFileName = $request->file('image')->getClientOriginalName();
                    $fileName = pathinfo($completeFileName, PATHINFO_FILENAME);
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $mimeType = $request->file('image')->getMimeType();
                    $filesize = $request->file('image')->getSize();

                    $randomized = rand();
                    $newFileName = str_replace(' ', '', $fileName).'-'.$randomized.''.time().'.'.$extension;
                    // dd($newFileName);


                    // For moving the file
                    $destinationPath = "public/Attachments/".session('LoggedUser_CompanyID')."/RMA/".$request->guid;
                    // For preview
                    $storagePath = "storage/Attachments/".session('LoggedUser_CompanyID')."/RMA/".$request->guid."/".$newFileName;

                    $filepath = $request->file('image')->storeAs($destinationPath, $newFileName);


                    $insert_doc = DB::table('customer_support.rma_receiving_attachments')->insert([
                        'rma_receiving_details_id' => $request->id,
                        'complete_filename' => $completeFileName, 
                        'new_filename' => $newFileName,
                        'filename' => $fileName, 
                        'extension' => $extension,
                        'mime_type' => $mimeType,
                        'filepath'=>$storagePath,
                        'filesize' => $filesize,
                    ]);

                

            } 



            return back()->with('form_submitted', 'RMA Updated Successfully.');


    }



    public function getUoMName($id){
        $uom = DB::table('procurement.tbl_uom')
        ->where('id', $id)
        ->select('UoM')
        ->first();
        return $uom->UoM;
    }

    public function getRmaStatus($id){
        $rma_status = DB::table('customer_support.tbl_rma_status')
        ->where('id', $id)
        ->select('rma_status')
        ->first();
        return $rma_status->rma_status;
    }


    public function getGuid(){
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $GUID = '';
        $GUID = chr(123)
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);
        $GUID = trim($GUID, '{');
        $GUID = trim($GUID, '}');

        return $GUID;
    }

    public function getDateTime(){
        date_default_timezone_set('Asia/Brunei');      
        $date=date("Y/m/d h:i:sa");
        return $date;
    }
}
