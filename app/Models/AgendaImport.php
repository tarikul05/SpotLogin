<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaImport extends Model
{
    use HasFactory;

    protected $table = 'agenda_imports';

    protected $fillable = ['created_at', 'updated_at', 'date', 'start_time', 'end_time', 'count_students', 'students_names', 'title', 'duration', 'coach', 'teacher_id', 'imported'];

}
