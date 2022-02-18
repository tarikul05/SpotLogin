<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class PermissionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize the permission lists';

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
     * @return int
     */
    public function handle()
    {
        $allper = config('permission-attr');

        foreach ($allper as $key => $per) {
           $exist = Permission::where(['name'=>$per])->count();
           if (!$exist) {
                Permission::create(['name'=>$per]);
                echo $per ;
                $this->newLine();
           }
        }

        $this->call('permission:show');
        // return 0;
    }
}
