<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Language;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class TermConditionLang extends Model
{
    use SoftDeletes, CreatedUpdatedBy;
    protected $table = 'tc_template_lang';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'tc_template_id',
        'language_id',
        'tc_text',
        'spp_text',
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


    protected $appends = [];

    /**
     * Get the user for the News.
    */
    public function language()
    {
      return $this->belongsTo(Language::class);
    }

   

}

