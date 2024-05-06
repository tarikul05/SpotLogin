<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Alert;
use App\Models\User;
use App\Models\ContactForm;
use App\Models\Event;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{

    protected $stripe;

    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $this->middleware('permission:superadmin');
    }

    public function index(Request $request)
    {
    try{
        $result = [];

        //Count Schools
        $countSchools = School::count();

        $thirtyDaysAgo = now()->subDays(30);
        $events = Event::where('date_start', '>=', $thirtyDaysAgo)->get();
        $events_stripe = $this->stripe->events->all(['limit' => 50]);
        $subsriptions = $this->stripe->subscriptions->all();
        $subTrial = 0;
        $subActive = 0;
        $subCanceled = 0;
        $subPastDue = 0;
        $totalAmountActivePlans = 0;
        $totalAmountActivePlans = 0;
        foreach ($subsriptions as $sub) {
            if ($sub->status == 'active' && $sub->trial_end === null) {
                // Obtenez le montant de l'abonnement actif
                $totalAmountActivePlans += $sub->items->data[0]->plan->amount;
            }
        }
        $totalAmountActivePlans = $totalAmountActivePlans / 100;
        $stats['totalAmountActivePlans'] = $totalAmountActivePlans;

        foreach ($subsriptions as $sub) {
            if ($sub->status == 'trialing') {
                $subTrial++;
            } else if ($sub->status == 'active') {
                $subActive++;
            } else if ($sub->status == 'canceled') {
                $subCanceled++;
            } else if ($sub->status == 'past_due') {
                $subPastDue++;
            }
        }

        $taskCount = Task::count();
        $alertCount = Alert::count();
        $ContactFormCount = ContactForm::count();

        $stats['subTrial'] = $subTrial;
        $stats['subActive'] = $subActive;
        $stats['subCanceled'] = $subCanceled;
        $stats['subPastDue'] = $subPastDue;
        $stats['countSchools'] = $countSchools;
        $stats['subsriptions'] = $subsriptions;
        $stats['totalAmountActivePlans'] = $totalAmountActivePlans;
        $stats['taskCount'] = $taskCount;
        $stats['alertCount'] = $alertCount;
        $stats['ContactFormCount'] = $ContactFormCount;

        return view('pages.admin.dashboard')->with(compact('events', 'stats', 'subsriptions', 'events_stripe'));
    } catch(\Exception $e){
        echo $e->getMessage(); exit;
    }
}

    public function setting(Request $request)
    {
        $users = User::all();

        $maintenance = DB::table('maintenance')->first();

        if($maintenance == null){
            $maintenance = new \stdClass();
            $maintenance->message = '';
            $maintenance->start_date = '';
            $maintenance->active = false;
        }

        return response()->view('pages.admin.setting', compact('users','maintenance'));

    }

    public function update(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'start_date' => 'required|date',
            'active' => 'required|boolean',
        ]);

        $maintenance = DB::table('maintenance')->updateOrInsert([], [
            'message' => $request->input('message'),
            'start_date' => $request->input('start_date'),
            'active' => $request->input('active'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if($request->input('active') == true){
            $request->session()->flash('maintenance', 'Maintenance activée le '. Carbon::now()->format('d/m/Y à H:i') . ' ' . date_default_timezone_get() );
        } else {
            $request->session()->flash('maintenance', 'Maintenance terminée le '. Carbon::now()->format('d/m/Y à H:i') . ' ' . date_default_timezone_get() );
        }

        return redirect()->back()->with('success', 'Les paramètres de maintenance ont été mis à jour avec succès.');
    }

}
