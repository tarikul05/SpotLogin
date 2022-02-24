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
    
    public function addUpdate(Request $request, Language $language)
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
