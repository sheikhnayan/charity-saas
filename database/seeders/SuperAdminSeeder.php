<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'superadmin'], ['label' => 'Super Admin']);

        $email = env('INITIAL_SUPERADMIN_EMAIL', 'superadmin@local.test');
        $password = env('INITIAL_SUPERADMIN_PASSWORD', 'password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'status' => 'active'
            ]);
        }

        // Assign global superadmin role
        $user->assignRoleForWebsite('superadmin', null);
    }
}
