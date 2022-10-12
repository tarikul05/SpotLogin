<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class InvoiceItem extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'invoice_id',
        'is_locked',
        'caption',
        'unit',
        'item_date',
        'price_unit',
        'event_detail_id',
        'event_id',
        'teacher_id',
        'student_id',
        'participation_id',
        'price_type_id',
        'price',
        'total_item',
        'price_currency',
        'unit_type',
        'event_extra_expenses',
        'is_active',
        'created_at',
        'modified_at',
        'deleted_at',
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

}

