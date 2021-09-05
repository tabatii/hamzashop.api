<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin;
        $admin->email = 'admin@admin.com';
        $admin->password = bcrypt('evxkuythjbsdfycn');
        $admin->save();
    }
}
