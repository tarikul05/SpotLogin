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
    
    // Fonction pour valider le mot de passe selon les critères spécifiés
    public function validatePassword($password)
    {
        // Vérifier la longueur du mot de passe (au moins 8 caractères)
        if (strlen($password) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        // Vérifier s'il y a une lettre majuscule
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Le mot de passe doit contenir au moins une lettre majuscule.';
        }

        // Vérifier s'il y a une lettre minuscule
        if (!preg_match('/[a-z]/', $password)) {
            return 'Le mot de passe doit contenir au moins une lettre minuscule.';
        }

        // Vérifier s'il y a un chiffre
        if (!preg_match('/[0-9]/', $password)) {
            return 'Le mot de passe doit contenir au moins un chiffre.';
        }

        // Vérifier s'il y a un caractère spécial (caractère non-alphanumérique)
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return 'Le mot de passe doit contenir au moins un caractère spécial.';
        }

        // Le mot de passe correspond aux critères
        return true;
    }
}