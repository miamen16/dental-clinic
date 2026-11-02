<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['Ahmed', 'Samir', 'male', '1990-03-14', 'O+', null],
            ['Fatma', 'Nabil', 'female', '1985-07-02', 'A+', 'Penicillin'],
            ['Karim', 'Fouad', 'male', '2001-12-20', 'B+', null],
            ['Hana', 'Tarek', 'female', '1996-05-09', 'AB+', null],
            ['Mahmoud', 'Gamal', 'male', '1972-10-27', 'O-', 'Latex'],
            ['Rana', 'Sherif', 'female', '2010-02-15', 'A-', null],
            ['Tamer', 'Hosny', 'male', '1980-08-08', 'B-', null],
            ['Dina', 'Wael', 'female', '1993-04-30', 'O+', 'Ibuprofen'],
            ['Mostafa', 'Ezz', 'male', '1968-01-11', 'A+', null],
            ['Salma', 'Reda', 'female', '1999-09-03', 'AB-', null],
        ];

        foreach ($patients as $i => [$first, $last, $gender, $dob, $bloodType, $allergies]) {
            Patient::updateOrCreate(['patient_number' => sprintf('P-%04d', $i + 1)], [
                'first_name' => $first,
                'last_name' => $last,
                'email' => strtolower("{$first}.{$last}@example.com"),
                'phone_number' => '+20 111 000 ' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'date_of_birth' => $dob,
                'address' => ($i + 1) . ' Tahrir Square, Cairo',
                'gender' => $gender,
                'blood_type' => $bloodType,
                'allergies' => $allergies,
            ]);
        }
    }
}
