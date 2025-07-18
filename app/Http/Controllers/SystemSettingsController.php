<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$this->isHeadmasterOrIT()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $settings = SystemSetting::all()->groupBy('category');
        $users = \App\Models\User::with('role')->get();
        $roles = \App\Models\Role::with(['permissions', 'users'])->get();
        $permissions = []; // If you have a Permission model, load it here
        $logs = collect(\Storage::disk('logs')->files())->reverse();
        $pendingResults = \App\Models\Result::where('status', 'pending')->with(['student.user', 'subject', 'teacher.user'])->get();
        return view('admin.system-settings.index', compact('settings', 'users', 'roles', 'permissions', 'logs', 'pendingResults'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token']) as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Settings updated successfully.');
    }

    public function backup()
    {
        Artisan::call('backup:run', ['--only-db' => true]);
        return back()->with('success', 'Database backup created.');
    }

    public function logs()
    {
        $logs = collect(Storage::disk('logs')->files())->reverse();
        return view('admin.system-settings.logs', compact('logs'));
    }

    public function branding()
    {
        $settings = SystemSetting::where('category', 'branding')->get();
        return view('admin.system-settings.branding', compact('settings'));
    }

    public function viewLog(Request $request)
    {
        $file = $request->query('file');
        if (!$file) {
            return back()->with('error', 'No log file specified.');
        }
        $path = storage_path('logs/' . $file);
        if (!file_exists($path)) {
            return back()->with('error', 'Log file not found.');
        }
        $logContent = file_get_contents($path);
        $settings = SystemSetting::all()->groupBy('category');
        $users = \App\Models\User::with('role')->get();
        $roles = \App\Models\Role::with(['users'])->get();
        $permissions = [];
        $logs = collect(\Storage::disk('logs')->files())->reverse();
        return view('admin.system-settings.index', compact('settings', 'users', 'roles', 'permissions', 'logs', 'logContent'));
    }

    public function uploadResultsCsv(Request $request)
    {
        $request->validate([
            'results_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('results_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }
        fclose($handle);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            // Required fields
            $admission = $row['admission_number'] ?? null;
            $subjectName = $row['subject_name'] ?? null;
            $score = $row['score'] ?? null;
            $term = $row['term'] ?? null;
            $year = $row['year'] ?? null;
            $status = $row['status'] ?? 'pending';
            $teacherEmp = $row['teacher_employee_number'] ?? null;

            if (!$admission || !$subjectName || $score === null || !$term || !$year) {
                $skipped++;
                $errors[] = "Row ".($i+2).": Missing required fields.";
                continue;
            }

            $student = \App\Models\Student::where('admission_number', $admission)->first();
            if (!$student) {
                $skipped++;
                $errors[] = "Row ".($i+2).": Student not found (admission_number: $admission).";
                continue;
            }
            $subject = \App\Models\Subject::where('name', $subjectName)->first();
            if (!$subject) {
                $skipped++;
                $errors[] = "Row ".($i+2).": Subject not found (name: $subjectName).";
                continue;
            }
            $teacher_id = null;
            if ($teacherEmp) {
                $teacher = \App\Models\Teacher::where('employee_number', $teacherEmp)->first();
                if ($teacher) {
                    $teacher_id = $teacher->id;
                } else {
                    $errors[] = "Row ".($i+2).": Teacher not found (employee_number: $teacherEmp).";
                }
            }
            try {
                \App\Models\Result::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'score' => $score,
                    'term' => $term,
                    'year' => $year,
                    'status' => $status,
                    'teacher_id' => $teacher_id,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row ".($i+2).": ".$e->getMessage();
            }
        }

        $msg = "$imported results imported, $skipped skipped.";
        if ($errors) {
            $msg .= ' Errors: '.implode(' ', $errors);
            return back()->with('error', $msg);
        }
        return back()->with('success', $msg);
    }
} 