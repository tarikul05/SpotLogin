<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgendaImport implements ToArray, WithHeadingRow
{
    protected $data;

    public function array(array $array)
    {
        // Format hours after importing the data
        $this->data = array_map(function ($row) {
            if (isset($row['heure_de_dpart']) || isset($row['heure_de_dpart'])) {
                $row['heure_de_dpart'] = $this->formatHour($row['heure_de_dpart']);
            }
            if (isset($row['heure_de_fin'])) {
                $row['heure_de_fin'] = $this->formatHour($row['heure_de_fin']);
            }
            return $row;
        }, $array);
    }

    public function getData()
    {
        return $this->data;
    }

    private function formatHour($value)
    {
        // Convert Excel decimal hour to H:i format
        if (is_numeric($value)) {
            $hours = floor($value * 24);
            $minutes = ($value * 1440) % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        // If value is already in time format
        try {
            return \Carbon\Carbon::parse($value)->format('H:i');
        } catch (\Exception $e) {
            return $value; // Return the original value if parsing fails
        }
    }
}