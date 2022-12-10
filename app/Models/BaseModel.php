<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class BaseModel extends Model
{

   /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }


    /**
     * Return the formated date based on timezone selected from sidebar nav | Front-end user | Saved in Cookie | Sent $to
     *
     * @param Carbon $date
     * @param null $type (long | short)
     * @return Carbon date
     */
    public function formatDateTimeZone($date, $type = 'long', $from = null, $to = null,$onlyDate=0)
    {
        if (!$from)
            $from = 'UTC';

        if (!$to){
            $to = $this->get_local_time();
        }
        
        if ($type == 'short')
        {
            $date = str_replace('/', '-', $date);
            $date = str_replace('.', '-', $date);
            $date =  date('Y-m-d', strtotime($date));
            
            $carbon = Carbon::createFromFormat('Y-m-d', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
            if ($onlyDate ==1) {
                return $carbon->setTimezone($to)->format('Y-m-d');
            }
            else {
                $carbon->setTimezone($to)->format('Y-m-d');
            }
            
        } else {

            // $teste= '23:59:59';


            // if (Carbon::createFromFormat('H:i:s', $teste) != false) {
            //     //echo $entrada = Carbon::createFromFormat('H:i:s', $teste);
            //     $carbon = Carbon::createFromFormat('H:i:s', $teste, 'Asia/Dhaka'); // specify UTC otherwise defaults to locale time zone as per ini setting
            //             $carbon->setTimezone('UTC')->format('H:i:s');
            //             echo $carbon->toDateTimeString();
            // exit();
            // }

            $date = str_replace('/', '-', $date);
            $date = str_replace('.', '-', $date);
            $date =  date('Y-m-d H:i:s', strtotime($date));
            
            $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
            $carbon->setTimezone($to)->format('Y-m-d H:i:s');
        }
        return $carbon->toDateTimeString();
    }

}
