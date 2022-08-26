<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Carbon\Carbon;

class Helper
{
    public function get_local_time(){

        $ip = file_get_contents("http://ipecho.net/plain");
     
        $url = 'http://ip-api.com/json/'.$ip;
     
        $tz = file_get_contents($url);
     
        $tz = json_decode($tz,true)['timezone'];
     
        return $tz;
     
     }
    /**
     * Return the formated date based on timezone selected from sidebar nav | Front-end user | Saved in Cookie | Sent $to
     *
     * @param Carbon $date
     * @param null $type (long | short)
     * @return Carbon date
     */
    public function formatDateTimeZone($date, $type = 'long', $from = null, $to = null)
    {
        if (!$from)
            $from = 'UTC';

        if (!$to){
            $to = $this->get_local_time();
        }
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
        $carbon->setTimezone($to)->format('Y-m-d H:i:s');
        if ($type == 'short')
        {
            $carbon = Carbon::createFromFormat('Y-m-d', $date, $from); // specify UTC otherwise defaults to locale time zone as per ini setting
            $carbon->setTimezone($to)->format('Y-m-d');
        }
        return $carbon->toDateTimeString();
    }
}