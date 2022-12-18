<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->withPersonalTeam()->create([
            'name' => 'Administrator',
            'email' => 'admin@localhost',
            'password' => Hash::make('P@ssw0rd'),
        ]);
    }
}
