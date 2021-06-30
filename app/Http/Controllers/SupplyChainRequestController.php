<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



class SupplyChainRequestController extends Controller
{
// Supply Chain

    // Materials Request


        // Project
        public function createMRProject() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.MaterialsRequest.create-sc-mr-project', compact('posts'));
        }

        // Delivery
        public function createMRDelivery() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.MaterialsRequest.create-sc-mr-delivery', compact('posts'));
        }

        // Demo
        public function createMRDemo() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.MaterialsRequest.create-sc-mr-demo', compact('posts'));
        }


        
    // Assets Request
        // Project
        public function createARProject() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-delivery', compact('posts'));
        }

        // Delivery
        public function createARDelivery() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-delivery', compact('posts'));
        }
        // Demo
        public function createARDemo() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-demo', compact('posts'));
        }
        // POC
        public function createARPOC() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-poc', compact('posts'));
        }
        // Internal
        public function createARInternal(Request $request) { 

            $listItems = DB::select("call procurement.llard_load_item_request('%', '".session('LoggedUser_CompanyID')."')");
            Paginator::useBootstrap();
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $itemCollection = collect($listItems);
            $perPage = 12;
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);


            // $plucked = $itemCollection->pluck('item_code');

            // $plucked->all();
        //    dd($itemCollection->pluck('item_code','description'));

            // $filtered = $itemCollection->where('unit_measure', 'pair(s)')->where('item_code', 'G1021744');
            // $filtered = $itemCollection->where('item_code', 'G1021744');


            // $filtered->all();

            // dd($filtered->all());

            // $paginatedItems = $plucked->all();

            // dd($itemCollection);
            // dd($listItems);
            // dd($itemCollection->search('G1028273'));

            // $itemCollection->search('599');


            // $itemCollection->filter(function ($item) use ($productName) {
            //     // replace stristr with your choice of matching function
            //     return false !== stristr($item->name, $productName);
            // });


         
            $paginatedItems->setPath($request->url());
    
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-internal', ['listItems' => $paginatedItems]);

        }


        public function getListitemDetails($id){
            $listItems = DB::select("call procurement.llard_load_item_request('%', '".session('LoggedUser_CompanyID')."')");
            $itemCollection = collect($listItems);
            $filtered = $itemCollection->where('group_detail_id', $id);
            $filteredData = $filtered->first();
            return response()->json($filteredData);
        }

        
        public function mrCart(){
            return view('SupplyChainRequest.AssetsRequest.mr-cart');
        }




   

        public function createARInternaldetails(){
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-internal-fulldetails');
        }



    // Supplies Request
        // Project
        public function createSRProject() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.SuppliesRequest.create-sc-sr-project', compact('posts'));
        }

        // Internal
        public function createSRInternal() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.SuppliesRequest.crate-sc-sr-project', compact('posts'));
        }




    // Release Stocks
    public function createReleaseStocks() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SupplyChainRequest.create-sc-rs', compact('posts'));
    //return $posts;
    }

    // RMA
    public function createRMA() { 
        $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
        return view('SupplyChainRequest.create-sc-rma', compact('posts'));
    //return $posts;
    }

    
}
