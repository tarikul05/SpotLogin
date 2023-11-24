<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Availability extends Model
{
    protected $fillable = [
        'student_id',
        'day_of_week',
        'time_of_day',
        'start_time',
        'end_time',
        'is_special',
        'day_special'
    ];

    use HasFactory;

    public function student()
    {
    return $this->belongsTo(Student::class);
    }

}
