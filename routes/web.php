<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Coordinator\RequirementController;
use App\Http\Controllers\Coordinator\PlacementController;
use App\Http\Controllers\Coordinator\MonitoringController;
use App\Http\Controllers\Coordinator\ReportController;
use App\Http\Controllers\Student\RequirementController as StudentRequirementController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\ProgressController as StudentProgressController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Supervisor\AttendanceController as SupervisorAttendanceController;
use App\Http\Controllers\Supervisor\EvaluationController as SupervisorEvaluationController;

Route::get('/',[DashboardController::class, 'index'])
->name('dashboard');

Route::get ('/students', [StudentController::class, 'index'])
->name('students.index');

Route::get('/companies', [CompanyController::class, 'index'])
->name('companies.index');

Route::get('/analytics', [AnalyticsController::class, 'index'])
->name('analytics.index');

Route::get('/evaluations',[EvaluationController::class,'index'])
->name('evaluations.index');

Route::post('evaluations/remind', [EvaluationController::class, 'remind'])
->name('evaluations.remind');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:6,1')->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');

Route::middleware(['auth', 'role:administrator'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::patch('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Programs
        Route::get('programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::post('programs', [ProgramController::class, 'store'])->name('programs.store');
        Route::put('programs/{program}', [ProgramController::class, 'update'])->name('programs.update');
        Route::delete('programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');

        // System settings
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
});
Route::middleware(['auth', 'role:coordinator,administrator'])->group(function () {
    Route::get('requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::get('placements', [PlacementController::class, 'index'])->name('placements.index');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/requirements',  [StudentRequirementController::class,  'index'])->name('requirements.index');
        Route::get('/attendance',    [StudentAttendanceController::class,   'index'])->name('attendance.index');
        Route::get('/progress',      [StudentProgressController::class,     'index'])->name('progress.index');
        Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications.index');
    });
    Route::middleware(['auth', 'role:company_supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/attendance',  [SupervisorAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/evaluations', [SupervisorEvaluationController::class, 'index'])->name('evaluations.index');
    });