<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckActiveUsers extends Command
{
    protected $signature = 'users:check-active';
    protected $description = 'Check and store active users based on active sessions';

    public function handle()
    {
        $now = Carbon::now();

        // Récupérer les sessions actives (dernière activité dans les 5 dernières minutes)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>=', $now->subMinutes(5)->timestamp)
            ->get();

        // Récupérer les user_ids non nuls
        $userIds = $activeSessions->pluck('user_id')->filter();

        // Récupérer les utilisateurs associés avec les informations de session
        $activeUsers = $userIds->map(function($userId) use ($activeSessions) {
            $session = $activeSessions->firstWhere('user_id', $userId);
            $user = \App\Models\User::find($userId);
            if ($user && $session) {
                $user->last_activity = Carbon::createFromTimestamp($session->last_activity)->format('d-m-Y H:i');
                $user->ip_address = $session->ip_address;
            }
            return $user;
        })->filter(); // Filtrer pour éviter les valeurs nulles

        // Stocker la liste des utilisateurs actifs dans le cache
        Cache::put('active_users', $activeUsers, now()->addMinutes(1));

        $this->info('Active users: ' . $activeUsers->count());
    }
}
