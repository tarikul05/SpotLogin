<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use \Log;

class SendEmailInvitation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailinvitation:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It sends push notification to app user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info(get_class($this) . ': Start process');
        $from = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $to = now();

        // $users = User::where([['is_active', 1]])->get();

        // if (!empty($users)) {
        //     foreach ($users as $user) { 
        //     }
        // }
        \Log::info(get_class($this) . ': End process');
    }
}
