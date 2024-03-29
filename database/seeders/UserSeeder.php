<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->withPersonalTeam()->create([
            'name' => 'Administrator',
            'email' => 'admin@localhost',
            'password' => Hash::make('P@ssw0rd'),
            'is_system_admin' => true,
        ]);

        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Team',
            'personal_team' => false,
        ]);

        $user->switchTeam($team);
    }
}
