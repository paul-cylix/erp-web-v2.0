<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;

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
        // Pending
        public function createARPending() { 
            $posts = DB::select("call general.Display_Approver_Company_web('%', '" . session('LoggedUser') . "', '1', '2020-01-01', '2020-12-31', 'True')");
            return view('SupplyChainRequest.AssetsRequest.create-sc-ar-pending', compact('posts'));
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
