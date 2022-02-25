<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Parameters;

class ParametersController extends Controller
{
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
        return view('pages.parameters.index');
    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function create(Request $request)
    {
        
    }
 
 
}
