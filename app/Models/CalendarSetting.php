<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarSetting extends Model
{
    use HasFactory;

    protected $fillable = ['timezone', 'min_time', 'max_time', 'user'];
}
