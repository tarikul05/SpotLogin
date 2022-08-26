<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends BaseModel
{
    // use SoftDeletes;
    protected $table = 'provinces';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'country_code',
      'province_code',
      'province_name',
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
     * Get the teacher.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'province_id');
    }

}
