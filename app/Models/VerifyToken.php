<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class VerifyToken extends Model
{
  use SoftDeletes;
  protected $table = 'verify_token';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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

    

    public function setTokenAttribute($token)
    {
        $this->attributes['token'] = Hash::make($token);
    }

     /**
     * Get the user for the user enquiry.
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

}
