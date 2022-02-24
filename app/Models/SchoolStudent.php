<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolStudent extends BaseModel
{
  use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
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
    public function school()
    {
      // return $this->hasOne(School::class,'school_id', 'id')
      //           ->whereNull('deleted_at')
      //           ;
      return $this->belongsTo(School::class);
    }
    /**
     * Get the city for the user.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
