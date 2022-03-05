<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\EmailTemplate;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class SchoolsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $response = [];
        $authUser = $request->user();
        $data = $request->user();
        $p_school_id = 2;
        $school = School::find($p_school_id);
        $lanCode = 'en';
        if (Session::has('locale')) {
            $lanCode = Session::get('locale');
        }
        $currency = Currency::all();  
        $country = Country::all();  
        
        $emailTemplate = EmailTemplate::where([
            ['template_code', 'school'],
            ['language', $lanCode]
        ])->first(); 
        //dd($school);

        if ($emailTemplate) {
            $http_host=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/" ;
            if (!empty($emailTemplate->body_text)) {
                $emailTemplate->body_text = str_replace("[~~ HOSTNAME ~~]",$http_host,$emailTemplate->body_text);
                $emailTemplate->body_text = str_replace("[~~HOSTNAME~~]",$http_host,$emailTemplate->body_text);
            }
        } 
        
        if($school->incorporation_date != null){
            
            $school->incorporation_date = str_replace('-', '/', $school->incorporation_date);
            //$school->incorporation_date = Carbon::createFromFormat('Y/m/d', $school->incorporation_date);
        } 

        //dd($currency);
        
        return view('pages.schools.edit')
        ->with(compact('authUser','currency','school','emailTemplate','country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userUpdate(Request $request, $id)
    {
        //
    }


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
