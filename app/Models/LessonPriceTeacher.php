<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonPriceTeacher extends BaseModel
{
    use HasFactory,SoftDeletes;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'lesson_price_id',
      'teacher_id',
      'event_category_id',
      'lesson_price_student',
      'price_buy',
      'price_sell',
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
