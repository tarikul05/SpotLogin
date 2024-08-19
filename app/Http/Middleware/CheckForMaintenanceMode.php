<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CheckForMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $maintenance = DB::table('maintenance')->where('active', true)->first();

        if ($maintenance) {
            $user = Auth::user();
            $isSuperAdmin = $user ? $user->isSuperAdmin() : false;
            if ($isSuperAdmin) {
                if ($maintenance->start_date && Carbon::parse($maintenance->start_date)->isFuture()) {
                    $request->session()->flash('maintenance', 'Upcoming maintenance...');
                } else {
                    $request->session()->flash('maintenance', 'Maintenance in progress...');
                }
            } else {
                if ($maintenance->start_date && Carbon::parse($maintenance->start_date)->isFuture()) {
                    $request->session()->flash('maintenance', 'Upcoming maintenance'. Carbon::parse($maintenance->start_date)->format('d/m/Y à H:i') . ' ' . date_default_timezone_get() );
                } else {
                    return response()->view('maintenance', [
                        'message' => $maintenance->message,
                        'date' => $maintenance->start_date,
                        'isSuperAdmin' => $isSuperAdmin,
                        'user' => $user
                    ], 503);
                }
            }
        }

        return $next($request);
    }
}