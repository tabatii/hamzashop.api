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
        $admin->name = 'OtmaN';
        $admin->email = 'otman@example.com';
        $admin->email_verified_at = now();
        $admin->password = bcrypt('123456');
        $admin->save();
    }
}
