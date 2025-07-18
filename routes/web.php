<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentResultsController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\TeacherClassAssignmentController;
use App\Http\Controllers\SchoolPerformanceController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    $role = $user->role->name ?? '';
    
    if ($role === 'headmaster') {
        return redirect('/dashboard/headmaster');
    } elseif (in_array($role, ['it', 'it_department', 'it-staff', 'it_department_user'])) {
        return redirect('/dashboard/it');
    } elseif ($role === 'teacher') {
        return redirect('/dashboard/teacher');
    } else {
        return redirect('/dashboard/student');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Route::get('/dashboard/student', [StudentDashboardController::class, 'index'])->name('dashboard.student');
    Route::get('/results/student', [StudentResultsController::class, 'index'])->name('results.student');
    Route::get('/dashboard/teacher', [TeacherDashboardController::class, 'index'])->name('dashboard.teacher');
    Route::resource('teacher/assignments', TeacherAssignmentController::class);
    Route::resource('classes', App\Http\Controllers\TeacherClassController::class);
});

Route::resource('students', StudentController::class);
Route::resource('teachers', TeacherController::class);
Route::resource('classes', ClassController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('results', ResultController::class);
Route::resource('users', UserController::class);
Route::resource('departments', DepartmentController::class);
Route::get('departments/{department}/assign-users', [DepartmentController::class, 'assignUsers'])->name('departments.assign-users');
Route::post('departments/{department}/assign-users', [DepartmentController::class, 'storeAssignedUsers'])->name('departments.store-assigned-users');

Route::get('/dashboard/headmaster', [DashboardController::class, 'headmaster'])->name('dashboard.headmaster');
Route::get('/dashboard/it', [DashboardController::class, 'it'])->name('dashboard.it');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::resource('admin/news', NewsController::class);
Route::resource('admin/events', EventController::class);
Route::resource('admin/gallery-images', GalleryImageController::class);

Route::prefix('admin/settings')->group(function () {
    Route::get('/', [SystemSettingsController::class, 'index'])->name('settings.index');
    Route::post('/', [SystemSettingsController::class, 'update'])->name('settings.update');
    Route::post('/backup', [SystemSettingsController::class, 'backup'])->name('settings.backup');
    Route::get('/logs', [SystemSettingsController::class, 'logs'])->name('settings.logs');
    Route::get('/branding', [SystemSettingsController::class, 'branding'])->name('settings.branding');
});

// User management actions for System Settings
Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
Route::post('users/{user}/toggle-lock', [UserController::class, 'toggleLock'])->name('users.toggle-lock');
Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

// System Settings log viewing
Route::get('admin/settings/logs/view', [SystemSettingsController::class, 'viewLog'])->name('settings.logs.view');

// Role permissions management
Route::post('roles/{role}/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');

Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::post('admin/settings/results/{result}/approve', [App\Http\Controllers\SystemSettingsController::class, 'approveResult'])->name('settings.results.approve');

Route::post('/settings/results/upload', [App\Http\Controllers\SystemSettingsController::class, 'uploadResultsCsv'])->name('settings.results.upload')->middleware(['auth', 'role:it,headmaster']);

// Teacher routes
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('assignments', App\Http\Controllers\TeacherAssignmentController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('results/create', [App\Http\Controllers\TeacherDashboardController::class, 'createResult'])->name('results.create');
    Route::post('results', [App\Http\Controllers\TeacherDashboardController::class, 'storeResult'])->name('results.store');
    Route::get('results/feedback', [App\Http\Controllers\TeacherDashboardController::class, 'resultsFeedback'])->name('results.feedback');
    Route::resource('students', App\Http\Controllers\TeacherStudentController::class);
    Route::resource('classes', App\Http\Controllers\TeacherClassController::class);
    Route::get('attendance', [App\Http\Controllers\TeacherDashboardController::class, 'attendanceIndex'])->name('attendance.index');
    Route::post('attendance', [App\Http\Controllers\TeacherDashboardController::class, 'attendanceStore'])->name('attendance.store');
    Route::get('reports', [App\Http\Controllers\TeacherDashboardController::class, 'reportsIndex'])->name('reports.index');
    
    // Student Admissions (Form 1-6)
    Route::resource('admissions', App\Http\Controllers\TeacherAdmissionController::class);
    Route::post('admissions/{student}/approve', [App\Http\Controllers\TeacherAdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('admissions/{student}/reject', [App\Http\Controllers\TeacherAdmissionController::class, 'reject'])->name('admissions.reject');
    Route::get('admissions/pending', [App\Http\Controllers\TeacherAdmissionController::class, 'pending'])->name('admissions.pending');
});

// Admin and Headmaster Admissions Access
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':headmaster,it'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('admissions', App\Http\Controllers\TeacherAdmissionController::class);
    Route::post('admissions/{student}/approve', [App\Http\Controllers\TeacherAdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('admissions/{student}/reject', [App\Http\Controllers\TeacherAdmissionController::class, 'reject'])->name('admissions.reject');
    Route::get('admissions/pending', [App\Http\Controllers\TeacherAdmissionController::class, 'pending'])->name('admissions.pending');
    Route::get('admissions/export', [App\Http\Controllers\TeacherAdmissionController::class, 'export'])->name('admissions.export');
    
    // Teacher-Class Assignment Routes
    Route::get('teacher-class-assignments', [App\Http\Controllers\TeacherClassAssignmentController::class, 'index'])->name('teacher-class-assignments.index');
    Route::get('teacher-class-assignments/{class}', [App\Http\Controllers\TeacherClassAssignmentController::class, 'show'])->name('teacher-class-assignments.show');
    Route::post('teacher-class-assignments/{class}/assign', [App\Http\Controllers\TeacherClassAssignmentController::class, 'assignTeacher'])->name('teacher-class-assignments.assign');
    Route::post('teacher-class-assignments/{class}/remove', [App\Http\Controllers\TeacherClassAssignmentController::class, 'removeTeacher'])->name('teacher-class-assignments.remove');
    Route::post('teacher-class-assignments/{class}/set-class-teacher', [App\Http\Controllers\TeacherClassAssignmentController::class, 'setClassTeacher'])->name('teacher-class-assignments.set-class-teacher');
    Route::post('teacher-class-assignments/bulk-assign', [App\Http\Controllers\TeacherClassAssignmentController::class, 'bulkAssign'])->name('teacher-class-assignments.bulk-assign');
    Route::get('teacher-class-assignments/teachers-by-department', [App\Http\Controllers\TeacherClassAssignmentController::class, 'getTeachersByDepartment'])->name('teacher-class-assignments.teachers-by-department');
    Route::get('teacher-class-assignments/{class}/details', [App\Http\Controllers\TeacherClassAssignmentController::class, 'getClassDetails'])->name('teacher-class-assignments.class-details');
    Route::get('teacher-class-assignments/export', [App\Http\Controllers\TeacherClassAssignmentController::class, 'exportAssignments'])->name('teacher-class-assignments.export');
    Route::get('school-performance', [SchoolPerformanceController::class, 'index'])->name('school-performance.index');
});

// Teacher-Subject Assignment Routes (Headmaster and IT only)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':headmaster,it'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('teacher-subjects', [App\Http\Controllers\TeacherSubjectController::class, 'index'])->name('teacher-subjects.index');
    Route::post('teacher-subjects/assign', [App\Http\Controllers\TeacherSubjectController::class, 'assignSubjects'])->name('teacher-subjects.assign');
    Route::post('teacher-subjects/remove', [App\Http\Controllers\TeacherSubjectController::class, 'removeSubject'])->name('teacher-subjects.remove');
    Route::post('teacher-subjects/set-primary', [App\Http\Controllers\TeacherSubjectController::class, 'updatePrimarySubject'])->name('teacher-subjects.set-primary');
    Route::get('teacher-subjects/subjects-by-department', [App\Http\Controllers\TeacherSubjectController::class, 'getSubjectsByDepartment'])->name('teacher-subjects.subjects-by-department');
    Route::get('teacher-subjects/teachers-by-department', [App\Http\Controllers\TeacherSubjectController::class, 'getTeachersByDepartment'])->name('teacher-subjects.teachers-by-department');
});

require __DIR__.'/auth.php';
