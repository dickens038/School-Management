<?php

require_once 'vendor/autoload.php';

use App\Models\SchoolClass;

// Add classes from Form 2 to Form 6
$classes = [
    'Form 2',
    'Form 3', 
    'Form 4',
    'Form 5',
    'Form 6'
];

foreach ($classes as $className) {
    $class = SchoolClass::firstOrCreate(['name' => $className], [
        'name' => $className,
        'capacity' => 40
    ]);
    
    if ($class->wasRecentlyCreated) {
        echo "Created: $className\n";
    } else {
        echo "Already exists: $className\n";
    }
}

echo "All classes added successfully!\n"; 