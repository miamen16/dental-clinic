<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo admin, doctors and a receptionist. Every account uses the password "password".
     */
    public function run(): void
    {
        $address = [
            'address' => '12 Nile Street',
            'city' => 'Cairo',
            'country' => 'Egypt',
            'postal_code' => '11511',
        ];

        User::updateOrCreate(['email' => 'admin@clinic.test'], [
            'first_name' => 'Clinic',
            'last_name' => 'Admin',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        $doctors = [
            ['Sara', 'Hassan', 'female', 'General Dentistry', 8, '1988-04-12'],
            ['Omar', 'Khaled', 'male', 'Orthodontics', 12, '1983-09-30'],
            ['Mona', 'Adel', 'female', 'Endodontics', 5, '1991-01-18'],
            ['Youssef', 'Ali', 'male', 'Oral Surgery', 15, '1979-06-05'],
            ['Nour', 'Ibrahim', 'female', 'Pediatric Dentistry', 3, '1994-11-22'],
        ];

        foreach ($doctors as $i => [$first, $last, $gender, $specialty, $experience, $dob]) {
            User::updateOrCreate(['email' => strtolower("{$first}.{$last}@clinic.test")], [
                'first_name' => $first,
                'last_name' => $last,
                'password' => 'password',
                'role' => User::ROLE_DOCTOR,
                'phone' => '+20 100 000 00' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'gender' => $gender,
                'date_of_birth' => $dob,
                'specialty' => $specialty,
                'experience' => $experience,
                'bio' => "{$specialty} specialist with {$experience} years of experience.",
                'is_active' => true,
            ] + $address);
        }

        User::updateOrCreate(['email' => 'reception@clinic.test'], [
            'first_name' => 'Laila',
            'last_name' => 'Mostafa',
            'password' => 'password',
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '+20 100 000 0100',
            'gender' => 'female',
            'is_active' => true,
        ] + $address);
    }
}
