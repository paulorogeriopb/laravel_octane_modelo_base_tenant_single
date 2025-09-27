<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pega o primeiro tenant já criado
        $tenant = Tenant::first();
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Default Tenant',
                'uuid' => Str::uuid(),
            ]);
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'user_status_id' => 1,  // Define status ativo
                'tenant_id' => $tenant->id, // importante!
            ]
        );
        $superAdmin->assignRole('Super Admin');

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'user_status_id' => 1,
                'tenant_id' => $tenant->id,
            ]
        );
        $admin->assignRole('Admin');

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'user_status_id' => 1,
                'tenant_id' => $tenant->id,
            ]
        );
        $user->assignRole('User');
    }
}