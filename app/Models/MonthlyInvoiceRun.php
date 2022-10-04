<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\School;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class MonthlyInvoiceRun extends BaseModel
{
  use SoftDeletes, CreatedUpdatedBy;
  protected $table = 'monthly_invoice_setup_runday';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'day_no',
        'active_flag',
        'language_preference',
        'lastrun_start_time',
        'lastrun_end_time',
        'process_month',
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
        'updated_at' => 'date:Y/m/d H:i',
    ];

  

     /**
     * Get the school.
     */
    public function school()
    {
      return $this->belongsTo(School::class);
    }

}
