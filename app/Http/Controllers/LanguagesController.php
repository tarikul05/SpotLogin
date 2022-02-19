<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Language;

class LanguagesController extends Controller
{
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
       
    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function create(Request $request, Language $language)
    {
        
        $result = [];
        $params = $request->all();
        if ($request->isMethod('post'))
        {
            print_r($params);
            exit();
            //$language = Language::find($id);
            //$query = $language->filter($params); 
        }
         
        
        
        return view('pages.languages.add', ['data'=>$params]);        
    }
 
 
}
