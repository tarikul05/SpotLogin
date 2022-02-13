<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Teacher;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
  use SoftDeletes;
  protected $table = 'email_template';
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_code',
        'template_name',
        'subject_text',
        'body_text',
        'language',
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


    public function getDelatedAtAttribute($value){
      return $value === 0 ? null : $value;
    }

  
}
