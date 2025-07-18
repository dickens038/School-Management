<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'admission_number', 'class_id', 'date_of_birth', 'gender',
        'combination', 'admission_status', 'admission_notes', 'admitted_by', 'admitted_at'
    ];

    protected $casts = [
        'admitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    // Get available combinations for Form 5 and Form 6 (Advanced Level)
    public static function getAdvancedLevelCombinations()
    {
        return [
            'CBG' => 'Chemistry, Biology, Geography',
            'PCM' => 'Physics, Chemistry, Mathematics',
            'PCB' => 'Physics, Chemistry, Biology',
            'PGM' => 'Physics, Geography, Mathematics',
            'HKL' => 'History, Kiswahili, Literature',
            'HGK' => 'History, Geography, Kiswahili',
            'HGE' => 'History, Geography, Economics',
            'ECA' => 'Economics, Commerce, Accounts'
        ];
    }

    // Get available combinations for Form 1-4 (Ordinary Level)
    public static function getOrdinaryLevelCombinations()
    {
        return [
            'SCIENCE' => 'Physics, Chemistry, Biology, Mathematics',
            'ARTS' => 'History, Geography, Literature, Kiswahili',
            'COMMERCE' => 'Commerce, Accounts, Economics, Mathematics',
            'GENERAL' => 'General Studies (No specific combination)'
        ];
    }

    // Get all available combinations based on class level
    public static function getAvailableCombinations($classLevel = null)
    {
        if ($classLevel === 'advanced' || in_array($classLevel, ['Form 5', 'Form 6'])) {
            return self::getAdvancedLevelCombinations();
        } elseif ($classLevel === 'ordinary' || in_array($classLevel, ['Form 1', 'Form 2', 'Form 3', 'Form 4'])) {
            return self::getOrdinaryLevelCombinations();
        }
        
        // Return both if no specific level is provided
        return [
            'Advanced Level' => self::getAdvancedLevelCombinations(),
            'Ordinary Level' => self::getOrdinaryLevelCombinations()
        ];
    }

    // Check if student is in Advanced Level (Form 5 or Form 6)
    public function isAdvancedLevel()
    {
        return in_array($this->class->name ?? '', ['Form 5', 'Form 6']);
    }

    // Check if student is in Ordinary Level (Form 1-4)
    public function isOrdinaryLevel()
    {
        return in_array($this->class->name ?? '', ['Form 1', 'Form 2', 'Form 3', 'Form 4']);
    }

    // Get the appropriate combinations for a specific class
    public static function getCombinationsForClass($className)
    {
        if (in_array($className, ['Form 5', 'Form 6'])) {
            return self::getAdvancedLevelCombinations();
        } elseif (in_array($className, ['Form 1', 'Form 2', 'Form 3', 'Form 4'])) {
            return self::getOrdinaryLevelCombinations();
        }
        return [];
    }
} 