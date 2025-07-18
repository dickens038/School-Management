<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            'Form 2',
            'Form 3',
            'Form 4', 
            'Form 5',
            'Form 6'
        ];

        foreach ($classes as $className) {
            SchoolClass::firstOrCreate(
                ['name' => $className],
                [
                    'name' => $className
                ]
            );
        }
    }
}
