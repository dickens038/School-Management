<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Sciences
            ['name' => 'Chemistry', 'department' => 'Sciences'],
            ['name' => 'Physics', 'department' => 'Sciences'],
            ['name' => 'Biology', 'department' => 'Sciences'],
            
            // Mathematics
            ['name' => 'Basic Applied Mathematics', 'department' => 'Mathematics'],
            ['name' => 'Pure Advanced Mathematics', 'department' => 'Mathematics'],
            
            // Humanities
            ['name' => 'Geography', 'department' => 'Humanities'],
            ['name' => 'General Studies', 'department' => 'Humanities'],
            ['name' => 'History', 'department' => 'Humanities'],
            
            // Languages
            ['name' => 'Language', 'department' => 'Languages'],
            
            // Business
            ['name' => 'Economics', 'department' => 'Business'],
            ['name' => 'Accountancy', 'department' => 'Business'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['name' => $subject['name']],
                [
                    'name' => $subject['name'],
                    'department' => $subject['department'],
                    'class_id' => 1, // Default class, you can adjust this
                ]
            );
        }
    }
}
