<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Budi Santoso', 'email' => 'budi@devflow.test', 'role' => 'pm'],
            ['name' => 'Andi Wijaya', 'email' => 'andi@devflow.test', 'role' => 'developer'],
            ['name' => 'Sari Dewi', 'email' => 'sari@devflow.test', 'role' => 'developer'],
            ['name' => 'Citra Lestari', 'email' => 'citra@devflow.test', 'role' => 'qa'],
            ['name' => 'Client User', 'email' => 'client@devflow.test', 'role' => 'client'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
