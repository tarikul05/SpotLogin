<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class ProvincesController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
    * get Province by country code
    */
    public function getProvinceByCountry(Request $request){
        $all_data = $request->all();
        $province_by_country = Province::where([
                            ['country_code','=',$all_data['country_name']],
                            ['is_active','=',1]
                        ])->get();
        // if(count($province_by_country) > 0){
        //     $html = '';
        //     foreach ($province_by_country as $province) {
        //         if( $province->id == $all_data['set_province_id']){
        //             $select = 'selected';
        //         }else{
        //             $select = '';
        //         }
        //         $html .= '<option '.$select.' value="'.$province->id.'">'.$province->province_name.'</option>';
        //     }
        // }else{
        //     $html = '<option value="">Select Province</option>';
        // }
        return response()->json(['data' => $province_by_country]);
    }
}
