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
                $params['flag_class']='flag-icon flag-icon-'.$params['language_code_data'];
                if (!empty($params['row_id'])) {
                    $language = Language::where([
                        ['language_code', $params['language_code']]
                    ])->first();
                    $language->title = $params['title'];
                    $language->abbr_name = $params['abbr_name'];
                    $language->is_active = $params['is_active'];
                    $language->flag_class = $params['flag_class'];
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

    public function addUpdate2(Request $request, Language $language)
    {
        $params = $request->all();

        if ($request->isMethod('post')){
            $lanCode = !empty($params['row_id']) ? $params['language_code_data'] : $params['language_code'] ;
            $request->merge(['language_code'=> $lanCode ,'flag_class'=>'flag-icon flag-icon-'.$lanCode]);

            $this->validate($request, [
                'language_code' => 'required',
                'title' => 'required',
                'abbr_name' => 'required',
            ]);

            $language = Language::where('language_code', $lanCode)->first();
            if (empty($language)) {
                Language::create($request->except(['_token']));
                $this->generateLanFile($lanCode);
                return back()->with('success', __('Language added successfully!'));
            }else{
                $language->update($request->except(['_token']));
                return back()->with('success', __('Language updated successfully!'));
            }
        }

        $languageList = Language::all();

        return view('pages.languages.add', [
            'alllanguages' => $languageList,
            'title' => 'Languages',
            'pageInfo'=>['siteTitle'=>'']
        ]);

    }


    public function generateLanFile($lanCode){
        $this->openJSONFile('en');
        $ndata = $this->openJSONFile('en');
        $data = [];
        foreach ($ndata as $key => $val) {
            $data[$key] = null;
        }
        $this->saveJSONFile($lanCode, $data);
        // return response()->json(['success'=>'Done!']);
        return response()->json(['success'=>__('Done')]);
    }


     /**
     * Open Translation File
     * @return Response
    */
 
    private function openJSONFile($code){
        $jsonString = [];
        if(File::exists(base_path('resources/lang/'.$code.'.json'))){
            $jsonString = file_get_contents(base_path('resources/lang/'.$code.'.json'));
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }
 
 
    /**
     * Save JSON File
     * @return Response
    */
    private function saveJSONFile($code, $data){
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/'.$code.'.json'), stripslashes($jsonData));
    }
 
 
}
