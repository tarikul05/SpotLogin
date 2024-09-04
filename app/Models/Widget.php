<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $fillable = [
        'name',
        'explanation',
        'id_unique'
    ];

    public function userWidgets()
    {
        return $this->hasMany(UserWidget::class);
    }
}