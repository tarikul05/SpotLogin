<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'name',
        'stripe_id',
        'stripe_status','stripe_price',
        'quantity',
        'trial_ends_at',
        'end_at',
        'created_at',
        'updated_at'
        // Autres colonnes de votre table subscriptions
    ];

    public function mySubscription()
    {
        return $this->where('user_id', $this->user)->first();
    }

}