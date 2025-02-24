<?php

namespace Database\Seeders;

use App\Enums\Role;
use Modules\Users\User\App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate([
            'email' => 'yunbira2412@gmail.com',
        ], [
            'name' => 'Bee Bee',
            'password' => 'Password'
        ]);

        $user = User::where('email', 'yunbira2412@gmail.com')->first();
        if (!$user->hasRole(Role::STUDENT)) {
            $user->assignRole(Role::STUDENT);
        }
    }
}
