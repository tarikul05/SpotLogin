<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Invoice;



class InvoiceController extends Controller
{

    public function __construct()
    {
        parent::__construct();    
    }

   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$schoolId = null)
    {

        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? $schoolId : $user->selectedSchoolId() ; 
        $school = School::active()->find($schoolId);
        if (empty($school)) {
            return redirect()->route('schools')->with('error', __('School is not selected'));
        }
        // $school = School::active()->find($schoolId);
        // if (empty($school)) {
        //     $schoolId = 0;
        // }
        $invoice_type_all = config('global.invoice_type');
        $payment_status_all = config('global.payment_status');
        $invoice_status_all = config('global.invoice_status');
        $invoices = Invoice::active()->where('school_id',$schoolId)->get();
        //dd($invoices);
        return view('pages.invoices.list',compact('invoices','schoolId','invoice_type_all','payment_status_all','invoice_status_all'));
    }
}