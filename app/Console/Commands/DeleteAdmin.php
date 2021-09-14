<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class DeleteAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:delete {email=admin@admin.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an admin user by email';

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
        $email = $this->argument('email');
        if (Admin::where('email', $email)->exists()) {
            $admin = Admin::where('email', $email)->first();
            $admin->forceDelete();
            return $this->info($email.' has been deleted');
        }
        return $this->info($email.' is not exists');
    }
}
