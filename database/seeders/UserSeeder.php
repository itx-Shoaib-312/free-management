<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.debug')) {
            Schema::disableForeignKeyConstraints();
            DB::table('model_has_roles')->truncate();
            User::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $users = [
            [
                'name' => "Administrator",
                'email' => "admin@mail.com",
                'phone' => "+92123456789",
                'password' => "12341234",
                'role' => 'admin',
            ],
            [
                'name' => "Agent One",
                'email' => "agent_one@mail.com",
                'phone' => "+92987654321",
                'password' => "12341234",
                'role' => 'agent',
            ],
        ];

        foreach ($users as $user) {
            $role = $user['role'];
            unset($user['role']);

            $new_user = User::updateOrCreate([
                'email' => $user['email'],
                'phone' => $user['phone'],
            ],$user);

            $new_user->assignRole($role);
        }
    }
}
