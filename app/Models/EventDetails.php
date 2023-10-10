<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class EventDetails extends BaseModel
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;
    protected $table = 'event_details';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'event_id',
      'teacher_id',
      'student_id',
      'visibility_id',
      'participation_id',
      'is_locked',
      'price_type_id',
      'buy_price',
      'buy_duration',
      'buy_total',
      'is_buy_invoiced',
      'buy_invoice_id',
      'sell_price',
      'sell_duration',
      'sell_total',
      'is_sell_invoiced',
      'sell_invoice_id',
      'teacher_comment',
      'student_comment',
      'price_currency',
      'private_comment',
      'costs_1',
      'costs_2',
      'invoice_by_items',
      'source_event_id',
      'billing_method',
      'level_id',
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


    public function event()
    {
        // Relation many-to-one : Un détail d'événement appartient à un événement
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function updateEventDetail($id, $invoice_id, $buy_invoice_id = 'buy_invoice_id', $student_id = null)
    {
        if ($buy_invoice_id == 'buy_invoice_id') {
            $detail_id =  explode(',', $id);
            $eventUpdate = [
                'buy_invoice_id' => $invoice_id,
                'is_buy_invoiced' => 1
            ];
            $eventData = $this->whereIn('id', $detail_id)
                ->update($eventUpdate);
        } else {
            $eventUpdate = [
                'sell_invoice_id' => $invoice_id,
                'is_sell_invoiced' => 1
            ];
            $eventData = EventDetails::where('student_id', $student_id)
                ->where('id', $id)
                ->where('participation_id', '>', 198)
                ->update($eventUpdate);
        }
        return $eventData;
    }

}
