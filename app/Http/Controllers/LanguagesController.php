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
                $params['language_code']=$params['language_code_data'];
                if (!empty($params['row_id'])) {
                    $language = Language::where([
                        ['language_code', $params['language_code']]
                    ])->first();
                    $language->title = $params['title'];
                    $language->abbr_name = $params['abbr_name'];
                    $language->is_active = $params['is_active'];
                    $authUser = request()->user();
                    if ($authUser) {
                        $language->modified_by = $authUser->id;
                    }
                    if (!$language->save()) {
                        return redirect()->back()->withInput()->with('error', __('Internal server error'));
                    }
                    return back()->with('success', __('Language updated successfully!'));
                } else {
                    $authUser = request()->user();
                    if ($authUser) {
                        $params['created_by'] = $authUser->id;
                    }
                    if (!Language::create($params)) {
                        return redirect()->back()->withInput()->with('error', __('Internal server error'));
                    }
                    return back()->with('success', __('Language added successfully!'));
                }
                
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
