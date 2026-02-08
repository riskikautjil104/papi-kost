<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersExtended;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user (update if exists)
        $admin = User::updateOrCreate(
            ['email' => 'admin@kontrakan.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create admin extended profile (update if exists)
        UsersExtended::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'phone' => '081234567890',
                'room_number' => 'ADM',
                'monthly_fee' => 0,
                'join_date' => now(),
                'contract_end_date' => now()->addYears(10),
                'status' => 'active',
                'address' => 'Admin Office',
                'emergency_contact' => '081234567890',
                'notes' => 'Administrator account'
            ]
        );
    }
}
