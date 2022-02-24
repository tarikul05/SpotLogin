<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;


class Language extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'language_code';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_code',
        'title',
        'abbr_name',
        'is_active',
        'sort_order',
        'flag_class',
        'translation_file',
        'is_active',
        // 'created_by',
        // 'modified_by'
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
