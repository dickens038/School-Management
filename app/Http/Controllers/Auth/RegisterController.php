<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $registerAs = $request->input('register_as', 'student');
        if ($registerAs === 'teacher') {
            $teacherRole = \App\Models\Role::where('name', 'teacher')->first();
            if (!$teacherRole) {
                $teacherRole = \App\Models\Role::create(['name' => 'teacher']);
            }
            $user->role_id = $teacherRole->id;
            $user->save();
            // Only create Teacher record if not exists
            if (!$user->teacher) {
                \App\Models\Teacher::create([
                    'user_id' => $user->id,
                    'employee_number' => 'EMP' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'department' => null,
                ]);
            }
        } else {
            $studentRole = \App\Models\Role::where('name', 'student')->first();
            if (!$studentRole) {
                $studentRole = \App\Models\Role::create(['name' => 'student']);
            }
            $user->role_id = $studentRole->id;
            $user->save();
            if (!$user->student) {
                \App\Models\Student::create([
                    'user_id' => $user->id,
                    'admission_number' => 'ADM' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'date_of_birth' => null,
                    'gender' => null,
                    'class_id' => null,
                ]);
            }
        }

        Auth::login($user);

        if ($registerAs === 'teacher') {
            return redirect()->intended('/dashboard/teacher');
        }
        return redirect()->intended('/dashboard/student');
    }
} 