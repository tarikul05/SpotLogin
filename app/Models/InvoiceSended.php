<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSended extends Model
{
    protected $table = 'invoice_sended';

    protected $fillable = [
        'invoice_id',
        'user_id',
        'student_id',
        // Ajoutez d'autres champs remplissables si nécessaire
    ];

    // Relations éventuelles
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
