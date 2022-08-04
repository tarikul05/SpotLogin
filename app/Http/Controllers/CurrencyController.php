<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Country;

class CurrencyController extends Controller
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
     * Remove the specified resource from storage.
     * @return Response
    */
    public function index()
    {
        $currencyList = Currency::all();
        $countries = Country::active()->get();
        // dd($country);

        return view('pages.currency.add', [
            'allcurrency' => $currencyList,
            'countries' => $countries,
            'title' => 'currency',
            'pageInfo'=>['siteTitle'=>'']
        ]);
       
    } 
    
    public function addUpdate(Request $request, Currency $currency)
    {
        $params = $request->all();

        if ($request->isMethod('post')){
            $lanCode = !empty($params['row_id']) ? $params['currency_code_data'] : $params['currency_code'] ;
            $request->merge(['name'=> $params['currency_title'] ,'currency_code'=> $lanCode ,'flag_class'=>'flag-icon flag-icon-'.$lanCode]);

            $this->validate($request, [
                'country_code' => 'required',
                'currency_code' => 'required',
                'currency_title' => 'required',
                // 'sort_order' => 'required',
            ]);

            $currency = Currency::where('currency_code', $lanCode)->first();
            if (empty($currency)) {
                Currency::create($request->except(['_token']));
                return back()->with('success', __('Currency added successfully!'));
            }else{
                $currency->update($request->except(['_token']));
                return back()->with('success', __('Currency updated successfully!'));
            }
        }

    }
 
}
