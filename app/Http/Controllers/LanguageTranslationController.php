<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use App\Models\Language;

class LanguageTranslationController extends Controller
{
     /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
      // $languages = DB::table('languages')->orderBy('sort_order')->get();
      $languages = Language::orderBy('sort_order')->get();
      $columns = [];
      $columnsCount = $languages->count();
        if($languages->count() > 0){
            foreach ($languages as $key => $language){
                if ($key == 0) {
                    $columns[$key] = $this->openJSONFile($language->language_code);
                }
                $columns[++$key] = ['data'=>$this->openJSONFile($language->language_code), 'lang'=>$language->language_code];
            }
        }
 //  echo "<pre>";
 // print_r($columns); exit;
      return view('languages', compact('languages','columns','columnsCount'));
    }   
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $languages = Language::orderBy('sort_order')->get();
        if($languages->count() > 0){
            foreach ($languages as $language){
                $data = $this->openJSONFile($language->language_code);
                if (isset($data[$request->key])){
                    return redirect()->route('languages')->with('error','Key already exist');;
                }
                $data[$request->key] = ($language->language_code == 'en') ?  $request->value :  null;
                $this->saveJSONFile($language->language_code, $data);
            }
        }
        return redirect()->route('languages');
    }
 
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function destroy($key)
    {
        $languages = Language::orderBy('sort_order')->get();
        if($languages->count() > 0){
            foreach ($languages as $language){
                $data = $this->openJSONFile($language->language_code);
                unset($data[$key]);
                $this->saveJSONFile($language->language_code, $data);
            }
        }
        return response()->json(['success' => $key]);
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
 
 
    /**
     * Save JSON File
     * @return Response
    */
 
    public function transUpdate(Request $request){
        $data = $this->openJSONFile($request->name);
        $data[$request->pk] = $request->value;
        $this->saveJSONFile($request->name, $data);
        // return response()->json(['success'=>'Done!']);
        return response()->json(['success'=>__('Done')]);
    }
 
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
 
    public function transUpdateKey(Request $request){
        $languages = Language::orderBy('sort_order')->get();

        if($languages->count() > 0){
            foreach ($languages as $language){
                $data = $this->openJSONFile($language->language_code);
                if (isset($data[$request->pk])){
                    $data[$request->value] = $data[$request->pk];
                    unset($data[$request->pk]);
                    $this->saveJSONFile($language->language_code, $data);
                }
            }
        }
        return response()->json(['success'=>'Done!']);
    }
}
