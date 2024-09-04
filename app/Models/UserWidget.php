<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWidget extends Model
{
    protected $table = 'user_widgets';

    protected $fillable = [
        'user_id',
        'widget_id',
        'is_active',
    ];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}