<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\Subscription;

class EmailReportAdmin extends Command
{
    protected $signature = 'report:admin';
    protected $description = 'Rapport Admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $period_start = Carbon::today()->startOfWeek();
        $period_end = Carbon::today()->endOfWeek();
        

        // Compter les événements créés aujourd'hui, cette semaine et ce mois-ci
        //$todayCount = Event::whereDate('created_at', today())->count();
        $weekCount = Event::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthCount = Event::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        $user_week = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $invoice_week = Invoice::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $user_month = User::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $invoice_month = Invoice::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        // Récupérer tous les abonnements
        try {
            $subscriptions = Subscription::all();
        } catch (ApiErrorException $e) {
            $this->error("Erreur lors de la récupération des abonnements: " . $e->getMessage());
            return;
        }
        
        $subscriptions_count = count($subscriptions->data);
        
        // Filtrer les abonnements créés cette semaine
        $subscriptions_this_week = collect($subscriptions->data)->filter(function ($subscription) {
            $created = Carbon::createFromTimestamp($subscription->created);
            return $created->between(now()->startOfWeek(), now()->endOfWeek());
        })->count();

        $recipients = ['j.steeve@free.fr', 'vanessagusmeroli@gmail.com', 'kimlucine@gmail.com', 'matthieu.jost@gmail.com'];

        // Envoyer l'email de rapport
        Mail::to($recipients)->send(new \App\Mail\ReportAdmin($period_start, $period_end, $weekCount, $monthCount, $subscriptions_count, $subscriptions_this_week, $user_week, $invoice_week, $user_month, $invoice_month));
    }
}