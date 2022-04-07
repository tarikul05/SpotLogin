<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;
use App\Models\Teacher;

class Event extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;
    protected $table = 'events';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'school_id',
      'visibility_id',
      'event_type',
      'event_category',
      'date_start',
      'date_end',
      'duration_minutes',
      'teacher_id',
      'is_paying',
      'event_price',
      'title',
      'description',
      'original_event_id',
      'is_locked',
      'price_amount_sell',
      'price_currency',
      'price_amount_buy',
      'fullday_flag',
      'no_of_students',
      'event_mode',
      'extra_charges',
      'location_id',
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
     * Get the city for the user.
     */
    public function teacher()
    {
      return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}
