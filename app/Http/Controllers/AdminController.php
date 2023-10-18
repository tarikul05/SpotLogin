<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Alert;
use App\Models\User;
use App\Models\ContactForm;
use App\Models\Event;
use App\Models\School;

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
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function index(Request $request)
    {
    try{
        $result = [];

        //Count Schools
        $countSchools = School::count();

        $thirtyDaysAgo = now()->subDays(30);
        $events = Event::where('date_start', '>=', $thirtyDaysAgo)->get();

        $subsriptions = $this->stripe->subscriptions->all();
        $subTrial = 0;
        $subActive = 0;
        $subCanceled = 0;
        $subPastDue = 0;
        $totalAmountActivePlans = 0;
        $totalAmountActivePlans = 0;
        foreach ($subsriptions as $sub) {
            if ($sub->status == 'active') {
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

        return view('pages.admin.dashboard')->with(compact('events', 'stats', 'subsriptions'));
    } catch(\Exception $e){
        echo $e->getMessage(); exit;
    }
}

}
