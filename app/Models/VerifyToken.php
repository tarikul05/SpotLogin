<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class VerifyToken extends BaseModel
{
  use SoftDeletes;
  protected $table = 'verify_token';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'person_id',
      'school_id',
      'person_type',
      'token_type',
      'user_id',
      'token',
      'expire_date'
    ];

   

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y/m/d H:i',
        'updated_at' => 'date:Y/m/d H:i',
    ];

  

     /**
     * Get the user for the user enquiry.
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

     /**
     * Get the user for the user enquiry.
     */
    public function school()
    {
      return $this->belongsTo(School::class);
    }


    /**
     * Get the personable for the user.
    */
    public function personable()
    {
        return $this->morphTo(__FUNCTION__, 'person_type', 'person_id');
    }

}
