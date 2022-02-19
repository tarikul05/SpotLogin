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
 
    public function addUpdate(Request $request, Language $language)
    {
        
        $result = [];
        $params = $request->all();
        
        if ($request->isMethod('post'))
        {
            try {
                $params['title']=$params['language_title'];
                if (!empty($params['row_id'])) {
                    $language = Language::where([
                        ['language_code', $data['language_code']],
                        ['deleted_at', null],
                    ])->first();
                    $authUser = request()->user();

                    if ($authUser) {
                        $params['modified_by'] = $authUser->id;
                    }
                    if (!$language->update($params)) {
                        return redirect()->back()->withInput()->with('error', __('Internal server error'));
                    }
                } else {
                    $authUser = request()->user();
                    if ($authUser) {
                        $params['created_by'] = $authUser->id;
                    }
                    if (!Language::create($params)) {
                        return redirect()->back()->withInput()->with('error', __('Internal server error'));
                    }
                }
                return back()->with('success', __('Language added successfully!'));
            } catch (\Exception $e) {
                //return error message
                return redirect()->back()->withInput()->with('error', __('Internal server error'));
        
    
            }
            
        }


        
        
        try{
            //All languages
            $languageList = Language::all();
        
            $result = $languageList;
            

            return view('pages.languages.add', [
                'alllanguages' => $result,
                'data'        => $params,
                'title' => 'Languages',
                'pageInfo'=>['siteTitle'=>'']
            ]);
        }
        catch(\Exception $e){
            echo $e->getMessage(); exit;
        }
        
         
        
        
        //return view('pages.languages.add', ['data'=>$params]);        
    }
 
 
}
