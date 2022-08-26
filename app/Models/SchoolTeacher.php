<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolTeacher extends BaseModel
{
  use SoftDeletes;
  protected $table = 'school_teacher';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'teacher_id',
        'role_type',
        'nickname',
        'bg_color_agenda',
        'comment',
        'is_sent_invite'
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
    protected $appends = ['full_name'];

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
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

        /**
     * Get the user through Teacher.
     */
    public function getUserAttribute()
    {
        return isset($this->teacher->user) && !empty($this->teacher->user) ? $this->teacher->user : null;
    }

    public function getFullNameAttribute()
    {
        return $this->teacher->full_name;
    }

    /**
     * Scope a query to only include Teachers users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeOnlyTeacher($query)
    {
        $query->wherein('role_type',['teachers_admin', 'teachers_all', 'teachers_medium','teachers_minimum']);
    }

}
