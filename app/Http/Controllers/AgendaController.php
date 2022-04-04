<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class AgendaController extends Controller
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
     * Agenda calendar
     * @return Response
    */
    public function index()
    {
        $language = Language::orderBy('sort_order')->get();
        
        return view('pages.agenda.index', [
            'alllanguages' => $language
        ]);

    }   

}
