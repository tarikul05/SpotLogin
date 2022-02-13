<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $table = 'currencies';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

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

}
