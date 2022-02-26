<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermCondition extends Model
{
    use SoftDeletes;
    protected $table = 'tc_template';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'type',
        'effected_at',
        'effected_till',
        'active_flag',
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


    protected $appends = [];

     /**
     * Get the user for the News.
     */
    public function school()
    {
      return $this->belongsTo(School::class);
    }

   

}

