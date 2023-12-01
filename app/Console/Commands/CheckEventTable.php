<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;

class CheckEventTable extends Command
{
    protected $signature = 'events:check';
    protected $description = 'Vérifie la table des événements pour la surcharge.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $eventCount = Event::where('created_at', '>=', now()->subHour())
            ->count();

        if ($eventCount >= 1000) {
            Mail::to('j.steeve@free.fr')->send(new \App\Mail\OverloadedEventAlert($eventCount));
        }
    }
}
