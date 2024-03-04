<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'currencies_country';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    protected $primaryKey = 'currency_code';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'currency_code',
      'country_code',
      'name',
      'description',
      'sort_order',
      'is_active',
      'created_by',
      'modified_by'
    ];



    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y/m/d H:i',
        'modified_at' => 'date:Y/m/d H:i',
    ];

    /**
     * Get the country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class,'country_code', 'code');
    }

      /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCountry($query, $code)
    {
        return $query->where('country_code', $code);
    }

    public function getCurrencyByCountry($code,$isArry=false)
    {

        $currency = self::active()->where('country_code', $code)->get();

        if (count($currency) == 0) {
            $currency = self::active()->where('country_code', 'US')->get();
        }
        if (!$isArry) {
            $currency = $currency[0];
        }
        return $currency;
    }

}
