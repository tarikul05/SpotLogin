<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\School;

class CheckForMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $maintenance = DB::table('maintenance')->where('active', true)->first();

        if ($maintenance) {
            $user = Auth::user();
            $schoolId = $user ? $user->selectedSchoolId() : null;
            $school = School::find($schoolId);
            $timeZone = 'UTC'; // Par défaut, UTC
            if (!empty($school->timezone)) {
                $timeZone = $school->timezone; // Récupérer le timezone de l'école s'il est défini
            }
            $isSuperAdmin = $user ? $user->isSuperAdmin() : false;
            
            // Convertir la date du début de la maintenance dans le timezone de l'école
            $maintenanceStartDate = $maintenance->start_date ? Carbon::parse($maintenance->start_date)->timezone($timeZone) : null;

            if ($isSuperAdmin) {
                if ($maintenanceStartDate && $maintenanceStartDate->isFuture()) {
                    $request->session()->flash('maintenance', 'Upcoming maintenance...');
                } else {
                    $request->session()->flash('maintenance', 'Maintenance in progress...');
                }
            } else {
                if ($maintenanceStartDate && $maintenanceStartDate->isFuture()) {

                    if (strpos($timeZone, 'Europe') !== false) {
                        // Format 24 heures pour les zones Europe
                        $formattedDate = $maintenanceStartDate->format('d/m/Y - H:i');
                    } else {
                        // Format 12 heures (AM/PM) pour les autres zones comme les États-Unis
                        $formattedDate = $maintenanceStartDate->format('d/m/Y - g:i A');
                    }

                    $request->session()->flash('maintenance', 'Upcoming maintenance '. $formattedDate . ' (' . $timeZone .')' );
                } else {
                    return response()->view('maintenance', [
                        'message' => $maintenance->message,
                        'date' => $maintenanceStartDate ? $maintenanceStartDate->format('d/m/Y - H:i') : null,
                        'isSuperAdmin' => $isSuperAdmin,
                        'user' => $user
                    ], 503);
                }
            }
        }

        return $next($request);
    }
}