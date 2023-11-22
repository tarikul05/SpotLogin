<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Availability extends Model
{
    protected $fillable = [
        'student_id', // Ajoutez le champ student_id ici
        'day_of_week',
        'time_of_day',
        'start_time',
        'end_time',
        // Ajoutez d'autres champs si nécessaire
    ];

    use HasFactory;

    public function student()
    {
    return $this->belongsTo(Student::class);
    }

}
