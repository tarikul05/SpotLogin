<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Parents;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentStudent extends Model
{
  use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'student_id'
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

     /**
     * Get the user for the user enquiry.
     */
    public function parent()
    {
      // return $this->hasOne(School::class,'school_id', 'id')
      //           ->whereNull('deleted_at')
      //           ;
      return $this->belongsTo(Parents::class);
    }
    /**
     * Get the city for the user.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
