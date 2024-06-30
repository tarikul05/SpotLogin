<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportAdmin extends Mailable
{
    private $todayCount;
    private $weekCount;
    private $monthCount;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($period_start, $period_end, $weekCount, $monthCount, $subscriptions_count, $subscriptions_this_week, $user_week, $invoice_week, $user_month, $invoice_month)
    {
        $this->period_start = $period_start;
        $this->period_end = $period_end;
        $this->weekCount = $weekCount;
        $this->monthCount = $monthCount;
        $this->subscriptions_count = $subscriptions_count;
        $this->subscriptions_this_week = $subscriptions_this_week;
        $this->user_week = $user_week;
        $this->invoice_week = $invoice_week;
        $this->user_month = $user_month;
        $this->invoice_month = $invoice_month;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reportAdmin')
                    ->with('period_start', $this->period_start)
                    ->with('period_end', $this->period_end)
                    ->with('weekCount', $this->weekCount)
                    ->with('monthCount', $this->monthCount)
                    ->with('subscriptions_count', $this->subscriptions_count)
                    ->with('subscriptions_this_week', $this->subscriptions_this_week)
                    ->with('user_week', $this->user_week)
                    ->with('invoice_week', $this->invoice_week)
                    ->with('user_month', $this->user_month)
                    ->with('invoice_month', $this->invoice_month);
    }
}
