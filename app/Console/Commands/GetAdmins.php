<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class GetAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get admins list';

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
        $admins = Admin::all(['email','created_at'])->toArray();
        return $this->table(['email','date'], $admins);
    }
}
