<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Admin;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email=admin@admin.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

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
        $password = $this->password();
        if (!Admin::where('email', $this->argument('email'))->exists()) {
            $admin = new Admin;
            $admin->email = $this->argument('email');
            $admin->password = bcrypt($password());
            $admin->save();
            return $this->info($password());
        }
        return $this->info($this->argument('email').' exists already');
    }

    protected function password()
    {
        if (app()->environment('local')) {
            return 123456;
        }
        return Str::lower(Str::random(20));
    }
}
