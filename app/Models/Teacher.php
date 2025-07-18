<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'employee_number', 'department'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'teacher_id');
    }

    public function awards()
    {
        return $this->hasMany(\App\Models\Award::class, 'teacher_id');
    }

    public function classes()
    {
        return $this->belongsToMany(\App\Models\SchoolClass::class, 'class_teacher', 'teacher_id', 'class_id');
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'teacher_id');
    }

    public function results()
    {
        return $this->hasMany(\App\Models\Result::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(\App\Models\Subject::class, 'teacher_subject')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primarySubject()
    {
        return $this->belongsToMany(\App\Models\Subject::class, 'teacher_subject')
                    ->wherePivot('is_primary', true)
                    ->withTimestamps();
    }
} 