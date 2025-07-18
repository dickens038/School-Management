<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Discipline',
            'Academic',
            'Administration',
            'Sports',
            'Affairs & Culture',
            'Health',
            'Finance',
            'Library',
            'ICT/Exams',
            'Boarding',
            'Transport',
            'Guidance & Counseling',
            'Procurement',
            'Human Resources',
            'Catering',
        ];
        foreach ($departments as $department) {
            Department::firstOrCreate(['name' => $department]);
        }
    }
} 