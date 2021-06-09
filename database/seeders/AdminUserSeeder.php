<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.admin',
            'password' => bcrypt('4dm1n!234'),
            'is_admin' => true
        ]);

        User::factory(3)->create();
    }
}
