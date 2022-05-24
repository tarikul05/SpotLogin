<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class InvoiceController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
      parent::__construct();
    }
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
        $genders = config('global.gender');
        $countries = Country::active()->get();
        return view('pages.invoices.add', [
            'title' => 'Invoice',
            'pageInfo'=>['siteTitle'=>'']
        ])->with(compact('genders','countries'));
       
    } 
 
}
