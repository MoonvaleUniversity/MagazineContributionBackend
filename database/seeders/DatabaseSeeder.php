<?php

namespace Database\Seeders;

use App\Enums\Role;
use Modules\Users\User\App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\AcademicYear\App\Models\AcademicYear;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\Faculty\App\Models\Faculty;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $faculty = Faculty::firstOrCreate([
            'name' => 'Art'
        ]);

        $academicYear = AcademicYear::firstOrCreate([
            'year_name' => '2025 - 2026'
        ]);

        $closureDate = ClosureDate::firstOrCreate([
            'academic_year_id' => $academicYear->id
        ], [
            'closure_date' => now()->addMonths(6),
            'final_closure_date' => now()->addMonths(8)
        ]);

        $user = User::firstOrCreate([
            'email' => 'yunbira2412@gmail.com',
        ], [
            'name' => 'Bee Bee',
            'password' => 'password',
            'faculty_id' => $faculty->id,
            'academic_year_id' => $academicYear->id,
            'email_verified_at' => now()
        ]);

        $user = User::where('email', 'yunbira2412@gmail.com')->first();
        if (!$user->hasRole(Role::STUDENT->label())) {
            $user->assignRole(Role::STUDENT->label());
        }

        $admin = User::firstOrCreate([
            'email' => 'admin@mv.com',
        ], [
            'name' => 'Moonvale Admin',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $admin = User::where('email', 'admin@mv.com')->first();
        if (!$admin->hasRole(Role::ADMIN->label())) {
            $admin->assignRole(Role::ADMIN->label());
        }

        $manager = User::firstOrCreate([
            'email' => 'manager@mv.com',
        ], [
            'name' => 'Moonvale Marketing Manager',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $manager = User::where('email', 'manager@mv.com')->first();
        if (!$manager->hasRole(Role::MARKETING_MANAGER->label())) {
            $manager->assignRole(Role::MARKETING_MANAGER->label());
        }

        $coordinator = User::firstOrCreate([
            'email' => 'coordinator@mv.com'
        ], [
            'name' => 'Moonvale Marketing Coordinator',
            'password' => 'password',
            'email_verified_at' => now()
        ]);

        $coordinator = User::where('email', 'coordinator@mv.com')->first();
        if (!$coordinator->hasRole(Role::MARKETING_COORDINATOR->label())) {
            $coordinator->assignRole(Role::MARKETING_COORDINATOR->label());
        }
        $guest = User::firstOrCreate([
            'email' => 'guest@mv.com'
        ], [
            'name' => 'Guest 1',
            'password' => 'password',
            'faculty_id' => $faculty->id,
            'email_verified_at' => now()
        ]);

        $guest = User::where('email', 'guest@mv.com')->first();
        if (!$guest->hasRole(Role::GUEST->label())) {
            $guest->assignRole(Role::GUEST->label());
        }
    }
}
